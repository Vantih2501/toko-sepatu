<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Walkway')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Walkway Logo" class="h-8 w-auto">
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="hidden sm:block flex-1 max-w-lg mx-8">
                    <form action="{{ route('produk.search') }}" method="GET" class="relative group">
                        <input type="text" name="q" placeholder="Cari sneakers, brands, atau koleksi..." 
                               class="w-full bg-gray-50 text-sm border-0 rounded-full pl-5 pr-12 py-3 focus:ring-2 focus:ring-black focus:bg-white transition-all shadow-sm group-hover:shadow-md outline-none"
                               value="{{ request('q') }}">
                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Navigation -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('produk.search') }}" class="text-sm font-semibold hover:text-gray-500 transition">Shop</a>
                    <a href="{{ route('lelang.index') }}" class="text-sm font-semibold hover:text-gray-500 transition">Lelang</a>
                    
                    @auth
                        @php
                            $cartCount = \App\Models\Keranjang::where('user_id', Auth::id())->count();
                        @endphp
                        <a href="{{ route('keranjang.index') }}" class="relative text-gray-800 hover:text-black transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 bg-black text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <div class="relative group">
                            <button class="flex items-center gap-2 focus:outline-none">
                                <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ substr(Auth::user()->nama, 0, 1) }}
                                </div>
                            </button>
                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 py-2">
                                <div class="px-4 py-2 border-b border-gray-50 mb-2">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->nama }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('transaksi.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-black transition">Pesanan Saya</a>
                                @if(Auth::user()->role == '1')
                                    <a href="{{ route('backend.beranda') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-black transition">Dashboard Admin</a>
                                @endif
                                <form action="{{ route('backend.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('backend.login') }}" class="bg-black text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-gray-800 transition shadow-sm hover:shadow">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div id="flash-message" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 bg-green-50 text-green-800 px-6 py-3 rounded-full shadow-lg border border-green-100 flex items-center gap-2 text-sm font-semibold transition-all duration-500 translate-y-0 opacity-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="flash-message" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 bg-red-50 text-red-800 px-6 py-3 rounded-full shadow-lg border border-red-100 flex items-center gap-2 text-sm font-semibold transition-all duration-500 translate-y-0 opacity-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="mb-4 block">
                        <img src="{{ asset('img/logo.png') }}" alt="Walkway Logo" class="h-8 w-auto">
                    </a>
                    <p class="text-sm text-gray-500 max-w-sm mb-6">Platform jual-beli sneaker autentik terpercaya di Indonesia. Garansi 100% asli atau uang kembali.</p>
                </div>
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4">Layanan</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-black transition">Bantuan & FAQ</a></li>
                        <li><a href="#" class="hover:text-black transition">Lacak Pesanan</a></li>
                        <li><a href="#" class="hover:text-black transition">Pengembalian</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4">Perusahaan</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-black transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-black transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-black transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Walkway. All rights reserved.</p>
                <div class="flex items-center gap-4 text-gray-400">
                    <!-- Social icons dummy -->
                    <a href="#" class="hover:text-black transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Flash message auto dismiss
            const flash = document.getElementById('flash-message');
            if(flash) {
                setTimeout(() => {
                    flash.classList.replace('translate-y-0', '-translate-y-4');
                    flash.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => flash.remove(), 500);
                }, 3500);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
