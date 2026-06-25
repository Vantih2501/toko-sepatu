<?php

namespace App\Http\Controllers;

use App\Models\Lelang;
use App\Models\LelangBid;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LelangController extends Controller
{
    // ========================================
    // BACKEND (Admin) Methods
    // ========================================

    /**
     * Admin: Daftar semua lelang (Halaman Order)
     */
    public function index(Request $request)
    {
        $query = Lelang::with(['produk', 'pemenang', 'highestBid']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tgl_mulai', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tgl_akhir', '<=', $request->end_date);
        }

        $lelangs = $query->orderBy('created_at', 'desc')->get();

        return view('backend.v_lelang.index', [
            'judul' => 'Order Lelang',
            'lelangs' => $lelangs,
        ]);
    }

    /**
     * Admin: Form tambah lelang
     */
    public function create()
    {
        $produk = Produk::where('status', 1)->orderBy('nama_produk', 'asc')->get();
        return view('backend.v_lelang.create', [
            'judul' => 'Tambah Lelang',
            'produk' => $produk,
        ]);
    }

    /**
     * Admin: Simpan lelang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'harga_awal' => 'required|numeric|min:1',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after:tgl_mulai',
        ]);

        Lelang::create([
            'produk_id' => $request->produk_id,
            'harga_awal' => $request->harga_awal,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_akhir' => $request->tgl_akhir,
            'status' => 'Ongoing',
        ]);

        return redirect()->route('backend.lelang.index')->with('success', 'Lelang berhasil ditambahkan.');
    }

    /**
     * Admin: Update status lelang
     */
    public function updateStatus(Request $request, $id)
    {
        $lelang = Lelang::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Ongoing,Delivered,Cancelled',
        ]);

        $lelang->update(['status' => $request->status]);

        // Jika delivered, set pemenang dari bid tertinggi
        if ($request->status === 'Delivered') {
            $highestBid = LelangBid::where('lelang_id', $id)->orderBy('jumlah_bid', 'desc')->first();
            if ($highestBid) {
                $lelang->update(['pemenang_id' => $highestBid->user_id]);
            }
        }

        return redirect()->route('backend.lelang.index')->with('success', 'Status lelang berhasil diubah.');
    }

    /**
     * Admin: Hapus lelang
     */
    public function destroy($id)
    {
        $lelang = Lelang::findOrFail($id);
        $lelang->delete();
        return redirect()->route('backend.lelang.index')->with('success', 'Lelang berhasil dihapus.');
    }

    // ========================================
    // FRONTEND (User) Methods
    // ========================================

    /**
     * Frontend: Daftar lelang aktif
     */
    public function frontIndex()
    {
        $lelangs = Lelang::with(['produk', 'bids'])
            ->where('status', 'Ongoing')
            ->where('tgl_akhir', '>', Carbon::now())
            ->orderBy('tgl_akhir', 'asc')
            ->get();

        return view('lelang.index', compact('lelangs'));
    }

    /**
     * Frontend: Detail lelang + form bid
     */
    public function frontShow($id)
    {
        $lelang = Lelang::with(['produk.fotoProduk', 'bids.user', 'pemenang'])->findOrFail($id);
        $bids = LelangBid::where('lelang_id', $id)->with('user')->orderBy('jumlah_bid', 'desc')->take(10)->get();
        $highestBid = $bids->first();

        return view('lelang.show', compact('lelang', 'bids', 'highestBid'));
    }

    /**
     * Frontend: Submit bid
     */
    public function placeBid(Request $request, $id)
    {
        $lelang = Lelang::findOrFail($id);

        // Validasi lelang masih aktif
        if ($lelang->status !== 'Ongoing' || Carbon::now()->gt($lelang->tgl_akhir)) {
            return redirect()->back()->with('error', 'Lelang sudah berakhir.');
        }

        // Cari bid tertinggi saat ini
        $currentHighest = LelangBid::where('lelang_id', $id)->max('jumlah_bid') ?? $lelang->harga_awal;

        $request->validate([
            'jumlah_bid' => 'required|numeric|min:' . ($currentHighest + 1),
        ], [
            'jumlah_bid.min' => 'Tawaran harus lebih tinggi dari Rp ' . number_format($currentHighest, 0, ',', '.'),
        ]);

        LelangBid::create([
            'lelang_id' => $id,
            'user_id' => Auth::id(),
            'jumlah_bid' => $request->jumlah_bid,
        ]);

        return redirect()->back()->with('success', 'Tawaran Anda berhasil dikirim!');
    }
}
