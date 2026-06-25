@extends('layouts.app')
@section('title', $lelang->produk->nama_produk ?? 'Lelang')
@section('content')

<div class="max-w-6xl mx-auto px-4 py-12">
    <a href="{{ route('lelang.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-black transition mb-8">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Lelang
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Left: Product Image --}}
        <div>
            <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl overflow-hidden">
                @if($lelang->produk && $lelang->produk->foto)
                    <img src="{{ asset('storage/img-produk/' . $lelang->produk->foto) }}"
                         alt="{{ $lelang->produk->nama_produk }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Auction Info --}}
        <div>
            <h1 class="text-3xl font-black tracking-tight mb-2">{{ $lelang->produk->nama_produk ?? '-' }}</h1>

            {{-- Status --}}
            @php
                $isActive = $lelang->status === 'Ongoing' && \Carbon\Carbon::now()->lt($lelang->tgl_akhir);
            @endphp

            @if($isActive)
                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Lelang Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-bold px-3 py-1 rounded-full mb-6">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span> Lelang Berakhir
                </span>
            @endif

            {{-- Price Info Cards --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs text-gray-400 mb-1">Harga Awal</p>
                    <p class="text-lg font-black">Rp {{ number_format($lelang->harga_awal, 0, ',', '.') }}</p>
                </div>
                <div class="bg-black text-white rounded-2xl p-5">
                    <p class="text-xs text-gray-400 mb-1">Bid Tertinggi</p>
                    <p class="text-lg font-black">
                        @if($highestBid)
                            Rp {{ number_format($highestBid->jumlah_bid, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            {{-- Countdown --}}
            <div class="bg-gray-50 rounded-2xl p-5 mb-6">
                <p class="text-xs text-gray-400 mb-2">Sisa Waktu</p>
                <div class="text-2xl font-black tracking-wider countdown-main" data-end="{{ $lelang->tgl_akhir->toIso8601String() }}">
                    Loading...
                </div>
                <div class="flex gap-4 mt-2 text-xs text-gray-400">
                    <span>Mulai: {{ $lelang->tgl_mulai->format('d M Y, H:i') }}</span>
                    <span>Berakhir: {{ $lelang->tgl_akhir->format('d M Y, H:i') }}</span>
                </div>
            </div>

            {{-- Bid Form --}}
            @if($isActive)
                @auth
                    <form action="{{ route('lelang.bid', $lelang->id) }}" method="POST" class="mb-8">
                        @csrf
                        <label class="block text-sm font-bold mb-2">Masukkan Tawaran Anda</label>
                        <div class="flex gap-3">
                            <div class="relative flex-1">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-sm">Rp</span>
                                <input type="number" name="jumlah_bid" value="{{ old('jumlah_bid') }}"
                                    class="w-full pl-10 pr-4 py-4 border border-gray-200 rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                                    placeholder="{{ $highestBid ? number_format($highestBid->jumlah_bid + 10000, 0, '', '') : number_format($lelang->harga_awal + 10000, 0, '', '') }}"
                                    min="{{ $highestBid ? $highestBid->jumlah_bid + 1 : $lelang->harga_awal + 1 }}">
                            </div>
                            <button type="submit" class="bg-black text-white px-8 py-4 rounded-xl font-bold text-sm hover:bg-gray-800 transition transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                                Place Bid
                            </button>
                        </div>
                        @error('jumlah_bid')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </form>
                @else
                    <div class="bg-gray-50 rounded-xl p-6 text-center mb-8">
                        <p class="text-sm text-gray-500 mb-3">Silakan login untuk mengikuti lelang</p>
                        <a href="{{ route('backend.login') }}" class="inline-block bg-black text-white px-6 py-3 rounded-full font-bold text-sm hover:bg-gray-800 transition">Masuk</a>
                    </div>
                @endauth
            @else
                @if($lelang->pemenang)
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
                        <p class="font-bold text-green-800">🏆 Pemenang: {{ $lelang->pemenang->nama }}</p>
                        <p class="text-sm text-green-600 mt-1">Dengan tawaran Rp {{ number_format($highestBid->jumlah_bid ?? 0, 0, ',', '.') }}</p>
                    </div>
                @endif
            @endif

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 text-green-800 px-4 py-3 rounded-xl text-sm font-semibold mb-6">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 text-red-800 px-4 py-3 rounded-xl text-sm font-semibold mb-6">❌ {{ session('error') }}</div>
            @endif

            {{-- Deskripsi & Ukuran --}}
            <div class="mb-8 border-t border-gray-100 pt-6">
                <h3 class="font-bold text-sm uppercase text-gray-500 mb-3">Informasi Produk</h3>
                <div class="space-y-4 mb-6">
                    @if($lelang->produk->ukuran)
                        <div>
                            <span class="text-xs text-gray-400 block mb-1.5">Ukuran Sepatu (Lelang)</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach(array_map('trim', explode(',', $lelang->produk->ukuran)) as $size)
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200">
                                        {{ $size }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($lelang->produk->detail)
                        <div>
                            <span class="text-xs text-gray-400 block mb-1.5">Deskripsi</span>
                            <div class="prose prose-sm text-gray-600 text-sm">
                                {!! $lelang->produk->detail !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Bid History --}}
            <div>
                <h3 class="font-bold text-sm uppercase text-gray-500 mb-4">Riwayat Penawaran (Top 10)</h3>
                @if($bids->isEmpty())
                    <p class="text-gray-400 text-sm">Belum ada penawaran.</p>
                @else
                    <div class="space-y-3">
                        @foreach($bids as $index => $bid)
                        <div class="flex items-center justify-between py-3 px-4 rounded-xl {{ $index === 0 ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-{{ $index === 0 ? 'yellow-400' : 'gray-200' }} rounded-full flex items-center justify-center text-xs font-bold {{ $index === 0 ? 'text-white' : 'text-gray-600' }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm">{{ $bid->user->nama ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $bid->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <p class="font-black text-sm">Rp {{ number_format($bid->jumlah_bid, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function updateMainCountdown() {
        const el = document.querySelector('.countdown-main');
        if (!el) return;
        const end = new Date(el.dataset.end);
        const now = new Date();
        const diff = end - now;
        if (diff <= 0) {
            el.textContent = '00 : 00 : 00';
            el.classList.add('text-red-500');
            return;
        }
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const secs = Math.floor((diff % (1000 * 60)) / 1000);
        if (days > 0) {
            el.textContent = `${days}d ${String(hours).padStart(2,'0')} : ${String(mins).padStart(2,'0')} : ${String(secs).padStart(2,'0')}`;
        } else {
            el.textContent = `${String(hours).padStart(2,'0')} : ${String(mins).padStart(2,'0')} : ${String(secs).padStart(2,'0')}`;
        }
    }
    updateMainCountdown();
    setInterval(updateMainCountdown, 1000);
</script>
@endsection
