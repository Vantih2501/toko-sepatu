<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Produk;
use App\Models\Transaksi;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        // Info Cards Data
        $totalUser = User::count();
        $totalProduk = Produk::count();
        $totalTransaksi = Transaksi::count();
        $totalPendapatan = Transaksi::where('status_pembayaran', 'lunas')->sum('total_harga');

        // Chart Data (Last 7 Days)
        $chartDates = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartDates[] = Carbon::now()->subDays($i)->format('d M');
            $dailyTotal = Transaksi::whereDate('created_at', $date)
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga');
            $chartData[] = $dailyTotal;
        }

        return view('backend.v_beranda.index', [
            'judul' => 'Halaman Beranda',
            'totalUser' => $totalUser,
            'totalProduk' => $totalProduk,
            'totalTransaksi' => $totalTransaksi,
            'totalPendapatan' => $totalPendapatan,
            'chartDates' => $chartDates,
            'chartData' => $chartData,
        ]);
    }
}
