@extends('layouts.app')

@section('title', 'Shopping Cart - Kick Avenue')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black uppercase tracking-tight mb-8">Your Cart</h1>
    
    @if(session('error'))
    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-sm font-semibold border border-red-200">
        {{ session('error') }}
    </div>
    @endif
    
    @if(session('success'))
    <div class="bg-green-50 text-green-600 p-4 rounded-lg mb-6 text-sm font-semibold border border-green-200">
        {{ session('success') }}
    </div>
    @endif

    @if(isset($keranjang) && $keranjang->count() > 0)
    <div class="flex flex-col lg:flex-row gap-12">
        <div class="lg:w-2/3">
            <!-- Cart Items -->
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                @php $totalHarga = 0; @endphp
                @foreach($keranjang as $item)
                @php $totalHarga += ($item->qty * $item->produk->harga); @endphp
                <div class="flex flex-col sm:flex-row p-6 border-b border-zinc-100 gap-6 relative">
                    <div class="w-32 h-32 bg-zinc-100 rounded-lg shrink-0 flex items-center justify-center p-2">
                        <img src="{{ $item->produk->foto ? asset('storage/' . $item->produk->foto) : 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' }}" alt="Product" class="w-full h-full object-contain mix-blend-multiply">
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg mb-1">{{ $item->produk->nama_produk }}</h3>
                        <p class="text-zinc-500 text-sm mb-3">Size: {{ $item->ukuran_sepatu ?? 'N/A' }}</p>
                        <p class="font-black text-lg mb-4">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                        
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-zinc-500">Qty: {{ $item->qty }}</span>
                        </div>
                    </div>
                    <!-- Delete Button -->
                    <form action="{{ route('keranjang.delete', $item->id) }}" method="POST" class="absolute top-6 right-6">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-zinc-400 hover:text-red-600 transition" onclick="return confirm('Remove item from cart?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Summary -->
        <div class="lg:w-1/3">
            <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-6 sticky top-24">
                <h2 class="text-lg font-bold mb-6">Order Summary</h2>
                <div class="flex justify-between items-center mb-4 text-sm">
                    <span class="text-zinc-500">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center mb-6 text-sm">
                    <span class="text-zinc-500">Estimated Shipping</span>
                    <span class="text-zinc-400 italic">Calculated at checkout</span>
                </div>
                <div class="border-t border-zinc-200 pt-4 mb-8 flex justify-between items-center">
                    <span class="font-bold">Total</span>
                    <span class="font-black text-xl">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="w-full bg-black text-white px-8 py-4 rounded-full font-bold uppercase tracking-wider hover:bg-zinc-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1 block text-center">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-20 bg-white border border-zinc-200 rounded-2xl">
        <svg class="w-16 h-16 text-zinc-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <h2 class="text-xl font-bold mb-2">Your cart is empty</h2>
        <p class="text-zinc-500 mb-6">Looks like you haven't made your choice yet.</p>
        <a href="/" class="bg-black text-white px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider hover:bg-zinc-800 transition inline-block">Start Shopping</a>
    </div>
    @endif
</div>
@endsection
