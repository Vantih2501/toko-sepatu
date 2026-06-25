<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'detail.produk']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();

        return view('backend.v_transaksi.index', [
            'judul' => 'Order Checkout',
            'transaksi' => $transaksi,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $request->validate([
            'status_pembayaran' => 'required|in:pending,sukses',
        ]);
        $transaksi->update(['status_pembayaran' => $request->status_pembayaran]);
        return redirect()->route('backend.transaksi.index')->with('success', 'Status pembayaran berhasil diubah.');
    }

    public function updateResi(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $request->validate([
            'resi' => 'required|string|max:255',
        ]);
        $transaksi->update(['resi' => $request->resi]);
        return redirect()->route('backend.transaksi.index')->with('success', 'Nomor resi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('backend.transaksi.index')->with('success', 'Order berhasil dihapus.');
    }

    public function formTransaksi()
    {
        return view('backend.v_transaksi.form', [
            'judul' => 'Laporan Data Transaksi'
        ]);
    }

    public function cetakTransaksi(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal'
        ]);

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        // Adding 23:59:59 to tanggal_akhir so it includes transactions on that day
        $query = Transaksi::with(['user', 'detail.produk'])
            ->whereBetween('created_at', [$tanggalAwal . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $transaksi = $query->get();

        $data = [
            'judul' => 'Laporan Transaksi',
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'cetak' => $transaksi
        ];

        $pdf = Pdf::loadView('backend.v_transaksi.cetak', $data);
        return $pdf->stream('Laporan Transaksi.pdf');
    }
}
