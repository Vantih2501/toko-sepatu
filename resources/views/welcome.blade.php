@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-black text-white h-[600px] overflow-hidden">
    <img src="https://images.unsplash.com/photo-1552346154-21d32810baa3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Hero Sneakers" class="absolute inset-0 w-full h-full object-cover opacity-50">
    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center items-start">
        <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tighter mb-4 leading-none">
            Find Your <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-zinc-200 to-zinc-600">Holy Grail</span>
        </h1>
        <p class="text-lg md:text-xl text-zinc-300 max-w-xl mb-8 font-light">
            Discover the most exclusive sneakers and streetwear. Authenticity guaranteed.
        </p>
        <a href="#shop" class="bg-white text-black px-8 py-4 rounded-full font-bold text-sm tracking-wider uppercase hover:bg-zinc-200 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            Shop Now
        </a>
    </div>
</div>

<!-- Brands -->
<div class="border-b border-zinc-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex justify-between items-center overflow-x-auto gap-8 grayscale opacity-60">
        <span class="text-2xl font-black tracking-tighter">NIKE</span>
        <span class="text-2xl font-black tracking-tighter">JORDAN</span>
        <span class="text-2xl font-black tracking-tighter">ADIDAS</span>
        <span class="text-2xl font-black tracking-tighter">YEEZY</span>
        <span class="text-2xl font-black tracking-tighter">NEW BALANCE</span>
    </div>
</div>

<!-- Products Section -->
<div id="shop" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-black uppercase tracking-tight">Trending Now</h2>
            <p class="text-zinc-500 mt-1">The most wanted pairs right now.</p>
        </div>
        <a href="#" class="text-sm font-semibold border-b border-black pb-1 hover:text-zinc-600 transition hidden sm:block">View All</a>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($produk ?? [] as $item)
        <a href="{{ route('produk.show', $item->id) }}" class="group relative bg-white border border-zinc-100 p-4 rounded-xl hover:shadow-2xl transition duration-300">
            <div class="aspect-square bg-zinc-100 rounded-lg mb-4 overflow-hidden relative flex items-center justify-center p-4">
                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="object-contain w-full h-full mix-blend-multiply group-hover:scale-105 transition duration-500">
                <div class="absolute top-3 left-3 bg-white px-2 py-1 text-xs font-bold rounded shadow-sm uppercase tracking-wider">
                    New
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg leading-tight mb-1 group-hover:underline decoration-2 underline-offset-2">{{ $item->nama_produk }}</h3>
                <p class="text-zinc-500 text-sm mb-3 line-clamp-1">{{ $item->detail }}</p>
                <p class="font-black text-xl">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
            </div>
        </a>
        @empty
        <!-- Placeholder Products -->
        @for($i=1; $i<=8; $i++)
        <a href="#" class="group relative bg-white border border-zinc-100 p-4 rounded-xl hover:shadow-2xl transition duration-300">
            <div class="aspect-square bg-zinc-100 rounded-lg mb-4 overflow-hidden relative flex items-center justify-center p-8">
                <!-- Using placeholder shoe image from unsplash -->
                <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Shoe" class="object-contain w-full h-full mix-blend-multiply group-hover:scale-105 transition duration-500 transform rotate-[-15deg]">
            </div>
            <div>
                <h3 class="font-bold text-lg leading-tight mb-1 group-hover:underline decoration-2 underline-offset-2">Nike Air Max (Dummy)</h3>
                <p class="text-zinc-500 text-sm mb-3">Premium quality sneaker</p>
                <p class="font-black text-xl">Rp 2.500.000</p>
            </div>
        </a>
        @endfor
        @endforelse
    </div>
</div>
@endsection

