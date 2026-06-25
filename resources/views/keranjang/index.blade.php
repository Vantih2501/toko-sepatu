@extends('layouts.app')

@section('title', 'Keranjang Saya - Walkway')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Cart Items -->
        <div class="flex-1">
            <h1 class="text-3xl font-black mb-8 tracking-tight">My Cart ({{ $keranjang->count() }})</h1>

            @if($keranjang->count() > 0)
                <div class="space-y-6">
                    @php $total = 0; @endphp
                    @foreach($keranjang as $item)
                        @php $subtotal = $item->qty * $item->produk->harga; $total += $subtotal; @endphp
                        
                        <!-- Cart Card -->
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition">
                            <!-- Header Info -->
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-50">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm uppercase tracking-wider">{{ $item->produk->kategori->nama_kategori ?? 'Sneakers' }}</span>
                                    <span class="text-yellow-400 text-xs">★ 5.0</span>
                                </div>
                                <button class="text-gray-400 hover:text-black transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </button>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-6">
                                <!-- Image -->
                                <div class="w-full sm:w-32 h-32 bg-gray-50 rounded-2xl flex items-center justify-center p-3 shrink-0">
                                    <img src="{{ asset('storage/img-produk/' . $item->produk->foto) }}" alt="{{ $item->produk->nama_produk }}" class="w-full h-full object-contain mix-blend-multiply hover:scale-110 transition duration-300">
                                </div>

                                <!-- Details -->
                                <div class="flex-1 flex flex-col">
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">{{ $item->produk->kategori->nama_kategori ?? 'Sneakers' }}</p>
                                    <h3 class="font-bold text-lg leading-tight mb-2 uppercase">{{ $item->produk->nama_produk }}</h3>
                                    
                                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                        <p>Size: <span class="font-bold text-black">{{ $item->ukuran_sepatu }}</span></p>
                                    </div>

                                    <div class="flex gap-2 mb-4">
                                        <span class="bg-teal-50 text-teal-600 text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 uppercase tracking-wider">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                            Free Shipping
                                        </span>
                                        <span class="bg-orange-50 text-orange-500 text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 uppercase tracking-wider">
                                            Disc 5%
                                        </span>
                                    </div>

                                    <!-- Bottom Row -->
                                    <div class="mt-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                        <!-- Qty -->
                                        <div class="flex items-center gap-4">
                                            <form action="{{ route('keranjang.update', $item->id) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="action" value="minus">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-black hover:bg-gray-50 rounded-full font-bold transition">-</button>
                                            </form>
                                            
                                            <span class="font-bold text-sm w-4 text-center">{{ $item->qty }}</span>
                                            
                                            <form action="{{ route('keranjang.update', $item->id) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="action" value="plus">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-black hover:bg-gray-50 rounded-full font-bold transition">+</button>
                                            </form>
                                        </div>

                                        <!-- Price -->
                                        <div class="text-right w-full sm:w-auto">
                                            <p class="text-xs text-gray-400 line-through mb-0.5">Rp {{ number_format($item->produk->harga * 1.05, 0, ',', '.') }}</p>
                                            <p class="text-2xl sm:text-3xl font-black">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex justify-end items-center gap-4 mt-4 pt-4 border-t border-gray-50">
                                        <button class="text-xs font-semibold text-gray-400 hover:text-black transition">Move to Favourites</button>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('keranjang.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition group flex items-center justify-center p-1">
                                                <svg class="w-4 h-4 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold mb-2">Keranjang Anda Kosong</h2>
                    <p class="text-gray-500 mb-8">Temukan sneakers impian Anda sekarang!</p>
                    <a href="{{ route('produk.search') }}" class="bg-black text-white px-8 py-4 rounded-full font-bold text-sm tracking-wider uppercase hover:bg-gray-800 transition">Mulai Belanja</a>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        @if($keranjang->count() > 0)
        <div class="w-full lg:w-96 shrink-0">
            <div class="bg-white border border-gray-100 rounded-3xl p-8 sticky top-24 shadow-sm">
                <h2 class="text-xl font-black mb-6 uppercase tracking-tight">Order Summary</h2>
                
                <div class="space-y-4 text-sm mb-6 pb-6 border-b border-gray-100">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-bold text-black">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Estimated Shipping</span>
                        <span class="text-teal-600 font-bold">Free</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-8">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-black text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="w-full bg-black text-white flex items-center justify-center py-4 rounded-full font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition shadow-lg hover:-translate-y-1 transform">
                    Checkout Now
                </a>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
