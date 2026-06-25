<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
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
        // Set paper size if needed, e.g. $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Laporan Transaksi.pdf');
    }
}
