@extends('layouts.app')
@section('title', 'Lelang')
@section('content')

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black tracking-tight">AUCTION</h1>
        <p class="text-gray-500 mt-2">Bid & win exclusive sneakers at the best price</p>
    </div>

    @if($lelangs->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-gray-400 text-lg">Belum ada lelang aktif saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($lelangs as $lelang)
            <a href="{{ route('lelang.show', $lelang->id) }}" class="group block">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform group-hover:-translate-y-1">
                    {{-- Image --}}
                    <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 relative overflow-hidden">
                        @if($lelang->produk && $lelang->produk->foto)
                            <img src="{{ asset('storage/img-produk/' . $lelang->produk->foto) }}"
                                 alt="{{ $lelang->produk->nama_produk }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        {{-- Badge live --}}
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1 animate-pulse">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span> LIVE
                        </div>
                    </div>
                    {{-- Info --}}
                    <div class="p-5">
                        <h3 class="font-bold text-sm truncate mb-1">{{ $lelang->produk->nama_produk ?? '-' }}</h3>
                        <p class="text-xs text-gray-400 mb-3">Starting Price</p>
                        <p class="font-black text-lg">Rp {{ number_format($lelang->harga_awal, 0, ',', '.') }}</p>

                        {{-- Countdown --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="countdown-timer" data-end="{{ $lelang->tgl_akhir->toIso8601String() }}">Loading...</span>
                            </div>
                            <span class="font-semibold text-black">{{ $lelang->bids->count() }} bids</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>

<script>
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(el => {
            const end = new Date(el.dataset.end);
            const now = new Date();
            const diff = end - now;
            if (diff <= 0) {
                el.textContent = 'Ended';
                el.classList.add('text-red-500', 'font-semibold');
                return;
            }
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((diff % (1000 * 60)) / 1000);
            if (days > 0) {
                el.textContent = `${days}d ${hours}h ${mins}m`;
            } else {
                el.textContent = `${hours}h ${mins}m ${secs}s`;
            }
        });
    }
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
</script>
@endsection
