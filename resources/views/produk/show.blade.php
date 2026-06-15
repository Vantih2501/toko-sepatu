@extends('layouts.app')

@section('title', $produk->nama_produk . ' - Kick Avenue')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="text-sm font-medium text-zinc-500 mb-8">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="/" class="hover:text-black">Home</a>
                <svg class="w-3 h-3 mx-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
            </li>
            <li class="flex items-center">
                <a href="#" class="hover:text-black">Sneakers</a>
                <svg class="w-3 h-3 mx-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
            </li>
            <li>
                <span class="text-zinc-800" aria-current="page">{{ $produk->nama_produk }}</span>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row gap-12">
        <!-- Image Gallery -->
        <div class="md:w-1/2">
            <div class="bg-zinc-100 rounded-2xl p-8 flex items-center justify-center mb-4 aspect-square">
                <!-- Fallback image if $produk->foto is empty, else use the real one -->
                <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-contain mix-blend-multiply transform -rotate-12 hover:rotate-0 transition duration-500 cursor-zoom-in">
            </div>
            <!-- Thumbnails -->
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-zinc-100 rounded-lg p-2 aspect-square flex items-center justify-center border-2 border-black cursor-pointer">
                    <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3' }}" class="w-full h-full object-contain mix-blend-multiply">
                </div>
                <!-- Additional thumbnails loop goes here if foto_produk relation is used -->
            </div>
        </div>

        <!-- Product Info -->
        <div class="md:w-1/2 flex flex-col">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-2">{{ $produk->nama_produk }}</h1>
            <p class="text-zinc-500 mb-6">{{ $produk->detail }}</p>
            
            <div class="mb-8">
                <span class="text-sm font-semibold uppercase tracking-widest text-zinc-400">Authentic Price</span>
                <p class="text-3xl font-black mt-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            </div>

            <!-- Size Selector -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold uppercase text-sm">Select Size (EU)</h3>
                    <a href="#" class="text-sm text-zinc-500 underline decoration-1 underline-offset-2">Size Guide</a>
                </div>
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                    @foreach(['39', '40', '41', '42', '42.5', '43', '44', '44.5', '45', '46'] as $size)
                    <button class="size-btn border border-zinc-300 rounded-lg py-3 text-center font-bold hover:border-black hover:bg-black hover:text-white transition focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        {{ $size }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" id="selected-size" name="ukuran_sepatu">
            </div>

            <!-- Authenticity Guarantee -->
            <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-4 mb-8 flex items-start gap-4">
                <svg class="w-8 h-8 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="font-bold text-sm">100% Authentic Guarantee</h4>
                    <p class="text-xs text-zinc-500 mt-1">Every item sold goes through our rigorous authentication process by our team of expert authenticators.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                <form action="{{ route('keranjang.add') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <input type="hidden" name="ukuran_sepatu" id="cart-size">
                    <button type="submit" class="w-full bg-white border-2 border-black text-black px-8 py-4 rounded-full font-bold uppercase tracking-wider hover:bg-zinc-100 transition flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Add to Cart
                    </button>
                </form>

                <form action="{{ route('checkout.index') }}" method="GET" class="flex-1">
                    <input type="hidden" name="buy_now" value="true">
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <input type="hidden" name="ukuran_sepatu" id="checkout-size">
                    <button type="submit" class="w-full bg-black text-white px-8 py-4 rounded-full font-bold uppercase tracking-wider hover:bg-zinc-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Checkout Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('bg-black', 'text-white', 'border-black');
            });
            // Add active to clicked
            this.classList.add('bg-black', 'text-white', 'border-black');
            
            // Set hidden inputs
            const size = this.innerText.trim();
            document.getElementById('cart-size').value = size;
            document.getElementById('checkout-size').value = size;
        });
    });
</script>
@endsection
