@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left: Content -->
            <div>
                <h1 class="text-5xl md:text-6xl font-black text-gray-900 leading-tight tracking-tight mb-6">
                    Explore the <br>
                    latest in sneaker <br>
                    styles
                </h1>
                <p class="text-gray-500 text-base md:text-lg max-w-md mb-8">
                    From timeless classics to modern designs. Find your perfect pair and step up your look today!
                </p>
                <a href="#shop" class="inline-block bg-[#4B746E] text-white px-8 py-3.5 rounded-full font-semibold hover:bg-[#3d5d58] transition shadow-md hover:shadow-lg">
                    Shop Now
                </a>

                <!-- Stats -->
                <div class="flex items-center gap-10 mt-16 pt-8">
                    <div>
                        <p class="text-3xl font-black text-gray-900 mb-1">10,000+</p>
                        <p class="text-xs text-gray-400 font-medium">Items Sold Across Our Platform</p>
                    </div>
                    <div class="w-px h-12 bg-gray-200"></div>
                    <div>
                        <p class="text-3xl font-black text-gray-900 mb-1">50+</p>
                        <p class="text-xs text-gray-400 font-medium">International Brands</p>
                    </div>
                </div>
            </div>

            <!-- Right: Image -->
            <div class="relative flex justify-center lg:justify-end mt-10 lg:mt-0">
                <img src="{{ asset('storage/img-hero/hero-image.png') }}" alt="Sneaker Collection" class="w-full max-w-xl object-contain drop-shadow-xl">
            </div>
        </div>
    </div>
</div>

<!-- Brands -->
<div class="bg-[#29362C] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex justify-between items-center overflow-x-auto gap-8 grayscale">
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
        <a href="http://127.0.0.1:8000/shop" class="text-sm font-semibold border-b border-black pb-1 hover:text-zinc-600 transition hidden sm:block">View All</a>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($produk ?? [] as $item)
        <a href="{{ route('produk.show', $item->id) }}" class="group relative bg-white border border-zinc-100 p-4 rounded-xl hover:shadow-2xl transition duration-300">
            <div class="aspect-square bg-zinc-100 rounded-lg mb-4 overflow-hidden relative flex items-center justify-center p-4">
                <img src="{{ asset('storage/img-produk/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="object-contain w-full h-full mix-blend-multiply group-hover:scale-105 transition duration-500">
                <div class="absolute top-3 left-3 bg-white px-2 py-1 text-xs font-bold rounded shadow-sm uppercase tracking-wider">
                    New
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg leading-tight mb-1 group-hover:underline decoration-2 underline-offset-2">{{ $item->nama_produk }}</h3>
                <p class="text-zinc-500 text-sm mb-3 line-clamp-1">{!! Str::limit(strip_tags($item->detail), 50) !!}</p>
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

<!-- Features Section -->
<div class="bg-gray-50 py-16 border-t border-gray-100 mt-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center mx-auto mb-6 transform rotate-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="font-black text-lg mb-2 uppercase tracking-tight">100% Authentic</h3>
                <p class="text-gray-500 text-sm">Setiap pasang sepatu telah melewati proses verifikasi ketat oleh para ahli kami. Garansi keaslian atau uang kembali.</p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center mx-auto mb-6 transform -rotate-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="font-black text-lg mb-2 uppercase tracking-tight">Fast Delivery</h3>
                <p class="text-gray-500 text-sm">Pengiriman instan untuk wilayah tertentu dan reguler cepat ke seluruh Indonesia. Anda pesan, kami langsung kirim.</p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center mx-auto mb-6 transform rotate-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="font-black text-lg mb-2 uppercase tracking-tight">Secure Payment</h3>
                <p class="text-gray-500 text-sm">Transaksi Anda dijamin aman dengan perlindungan enkripsi terkini dan dukungan berbagai metode pembayaran.</p>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="bg-black text-white py-20 mt-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-black mb-4 tracking-tighter uppercase">Never Miss a Drop</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto text-sm md:text-base">Berlangganan newsletter kami untuk mendapatkan informasi rilisan terbaru, penawaran eksklusif, dan promo spesial khusus untuk Anda.</p>
        <form class="flex flex-col sm:flex-row gap-3 justify-center max-w-lg mx-auto">
            <input type="email" placeholder="Masukkan email Anda" class="flex-1 bg-white/10 border border-white/20 text-white placeholder-gray-400 px-6 py-4 rounded-full focus:outline-none focus:border-white focus:bg-white/20 transition">
            <button type="button" onclick="alert('Terima kasih telah berlangganan!')" class="bg-white text-black px-8 py-4 rounded-full font-bold uppercase tracking-wide hover:bg-gray-200 transition whitespace-nowrap">Subscribe</button>
        </form>
    </div>
</div>
@endsection

