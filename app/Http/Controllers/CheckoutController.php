<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Log;
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

    private function baseUrl()
    {
        return rtrim(config('services.rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1/'), '/');
    }

    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.api_key')
        ])->get($this->baseUrl() . '/destination/province');

        $data = $response->json();
        // Komerce API: { data: [ {id, name} ] }
        $provinces = $data['data'] ?? [];
        $mapped = array_map(fn($p) => [
            'province_id' => $p['id'],
            'province'    => $p['name'],
        ], $provinces);

        return response()->json(['rajaongkir' => ['results' => $mapped]]);
    }

    public function getCities($province_id)
    {
        // Komerce API uses path parameter for province ID
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.api_key')
        ])->get($this->baseUrl() . '/destination/city/' . $province_id);

        $data = $response->json();
        // Expected { data: [ {id, name, type? } ] }
        $cities = $data['data'] ?? [];
        $mapped = array_map(fn($c) => [
            'city_id'   => $c['id'],
            'city_name' => $c['name'],
            'type'      => $c['type'] ?? '',
        ], $cities);

        return response()->json(['rajaongkir' => ['results' => $mapped]]);
    }

    public function checkOngkir(Request $request)
    {
        // Use configurable origin city ID
        $originCityId = config('services.rajaongkir.origin_city_id', 54); // default origin store city (54 = Kota Bekasi, 55 = Kab. Bekasi)
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.api_key')
        ])->asForm()->post($this->baseUrl() . '/calculate/domestic-cost', [
            'origin' => $originCityId,
            'destination' => $request->destination_city,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ]);

        $data = $response->json();
        Log::info('RajaOngkir cost response', $data);
        // Komerce API returns { data: [ {service, description, cost: [{value, etd}] } ] }
        $services = $data['data'] ?? [];
        $mapped = [[
            'costs' => array_map(function($s) {
                return [
                    'service' => $s['service'] ?? '',
                    'description' => $s['description'] ?? '',
                    'cost' => [[
                        'value' => $s['cost'] ?? 0,
                        'etd' => $s['etd'] ?? '-',
                    ]],
                ];
            }, $services)
        ]];

        return response()->json(['rajaongkir' => ['results' => $mapped]]);
    }

    public function process(Request $request)
    {
        $user = Auth::user();

        // Update hp dan alamat dari form checkout jika dikirim
        if ($request->hp || $request->alamat_lengkap) {
            $user->update(array_filter([
                'hp'             => $request->hp ?? $user->hp,
                'alamat_lengkap' => $request->alamat_lengkap ?? $user->alamat_lengkap,
                'kota_id'        => $request->kota_id ?? $user->kota_id,
            ]));
            $user->refresh();
        }

        // Pastikan user sudah mengisi alamat dan no hp
        if (!$user->hp || !$user->alamat_lengkap) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan lengkapi nomor HP dan alamat pengiriman Anda.'
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
