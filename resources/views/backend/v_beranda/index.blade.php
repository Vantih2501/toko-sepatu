@extends('backend.v_layouts.app')
@section('content')
  <div class="row">
    <!-- Info Cards -->
    <div class="col-md-3">
      <div class="card card-hover">
        <div class="box bg-cyan text-center">
          <h1 class="font-light text-white"><i class="mdi mdi-account-multiple"></i></h1>
          <h6 class="text-white">Total User: {{ $totalUser }}</h6>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-hover">
        <div class="box bg-success text-center">
          <h1 class="font-light text-white"><i class="mdi mdi-shopping"></i></h1>
          <h6 class="text-white">Total Produk: {{ $totalProduk }}</h6>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-hover">
        <div class="box bg-warning text-center">
          <h1 class="font-light text-white"><i class="mdi mdi-cart"></i></h1>
          <h6 class="text-white">Transaksi: {{ $totalTransaksi }}</h6>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-hover">
        <div class="box bg-danger text-center">
          <h1 class="font-light text-white"><i class="mdi mdi-cash-multiple"></i></h1>
          <h6 class="text-white">Pendapatan: Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h6>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Grafik Pendapatan (7 Hari Terakhir)</h5>
          <div style="position: relative; height: 220px; width: 100%;">
            <canvas id="pendapatanChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body border-top">
          <h5 class="card-title">{{ $judul }}</h5>
          <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Selamat Datang {{ Auth::user()->nama }}</h4>

            Aplikasi Toko Online dengan hak akses yang anda miliki sebagai
            <b>
              @if (Auth::user()->role == 1)
                Super Admin
              @elseif(Auth::user()->role == 0)
                Admin
              @endif
            </b>

            Ini adalah halaman utama dari aplikasi Web Programming. Studi Kasus Toko Online.

            <hr>
            <p class="mb-0">Kuliah...? BSI Aja !!!</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const ctx = document.getElementById('pendapatanChart').getContext('2d');
      const chartDates = {!! json_encode($chartDates) !!};
      const chartData = {!! json_encode($chartData) !!};

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: chartDates,
          datasets: [{
            label: 'Pendapatan (Rp)',
            data: chartData,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
  </script>
@endsection
