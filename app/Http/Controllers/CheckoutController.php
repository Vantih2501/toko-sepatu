<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Setup Midtrans Configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.api_key')
        ])->get('https://api.rajaongkir.com/starter/province');
        
        return response()->json($response->json());
    }

    public function getCities($province_id)
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.api_key')
        ])->get('https://api.rajaongkir.com/starter/city?province=' . $province_id);
        
        return response()->json($response->json());
    }

    public function checkOngkir(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.api_key')
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin' => 153, // Kota asal contoh: Jakarta Selatan (153)
            'destination' => $request->destination_city,
            'weight' => $request->weight,
            'courier' => $request->courier // jne, pos, tiki
        ]);

        return response()->json($response->json());
    }

    public function process(Request $request)
    {
        $user = Auth::user();

        // Pastikan user sudah mengisi alamat dan no hp
        if (!$user->hp || !$user->alamat_lengkap) {
            return response()->json([
                'success' => false, 
                'message' => 'Silakan lengkapi nomor HP dan alamat Anda terlebih dahulu di profil.'
            ], 400);
        }

        $isBuyNow = $request->has('produk_id') && $request->produk_id != null;
        $totalHarga = 0;
        
        if ($isBuyNow) {
            $produk = Produk::find($request->produk_id);
            if (!$produk) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
            }
            $qty = $request->qty ?? 1;
            $totalHarga = $produk->harga * $qty;
        } else {
            $keranjang = Keranjang::where('user_id', $user->id)->with('produk')->get();
            if ($keranjang->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Keranjang Anda kosong.'], 400);
            }
            foreach ($keranjang as $item) {
                $totalHarga += ($item->qty * $item->produk->harga);
            }
        }

        $ongkir = $request->ongkir ?? 0;
        $totalBayar = $totalHarga + $ongkir;

        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'total_harga' => $totalBayar,
            'ongkir' => $ongkir,
            'kurir' => $request->kurir,
            'status_pembayaran' => 'pending'
        ]);

        if ($isBuyNow) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $produk->id,
                'qty' => $qty,
                'harga' => $produk->harga,
                'ukuran_sepatu' => $request->ukuran_sepatu,
            ]);
        } else {
            foreach ($keranjang as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->produk_id,
                    'qty' => $item->qty,
                    'harga' => $item->produk->harga,
                    'ukuran_sepatu' => $item->ukuran_sepatu,
                ]);
            }
            Keranjang::where('user_id', $user->id)->delete();
        }

        // Generate Midtrans Snap Token
        $params = array(
            'transaction_details' => array(
                'order_id' => $transaksi->id . '-' . time(),
                'gross_amount' => $totalBayar,
            ),
            'customer_details' => array(
                'first_name' => $user->nama,
                'email' => $user->email,
                'phone' => $user->hp,
            ),
        );

        try {
            $snapToken = Snap::getSnapToken($params);
            $transaksi->update(['snap_token_midtrans' => $snapToken]);
            return response()->json(['success' => true, 'snapToken' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
