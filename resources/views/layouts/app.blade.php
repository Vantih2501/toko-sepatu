<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kick Avenue - Authentic Sneakers')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
    @yield('styles')
</head>
<body class="text-zinc-900 antialiased flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-zinc-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-8">
                    <a href="/" class="text-2xl font-black tracking-tighter uppercase">
                        KICK<span class="text-zinc-500">AVENUE</span>
                    </a>
                    <div class="hidden md:flex gap-6 font-medium text-sm">
                        <a href="/" class="hover:text-zinc-500 transition">SNEAKERS</a>
                        <a href="/" class="hover:text-zinc-500 transition">STREETWEAR</a>
                        <a href="/" class="hover:text-zinc-500 transition">ACCESSORIES</a>
                    </div>
                </div>

                <div class="flex items-center gap-5">
                    <!-- Search -->
                    <div class="relative hidden sm:block">
                        <input type="text" placeholder="Search products..." class="bg-zinc-100 border-none rounded-full py-2 px-5 text-sm focus:ring-2 focus:ring-black outline-none w-64">
                    </div>
                    
                    @auth
                        <a href="{{ route('keranjang.index') }}" class="relative hover:text-zinc-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </a>
                        <div class="relative group">
                            <button class="flex items-center gap-2 hover:text-zinc-600 transition font-medium text-sm">
                                <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&color=fff&background=000' }}" alt="Profile" class="w-8 h-8 rounded-full border border-zinc-200">
                            </button>
                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-2 w-48 bg-white border border-zinc-200 shadow-xl rounded-lg py-2 hidden group-hover:block transition z-50">
                                <a href="/profil" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">My Profile</a>
                                <a href="{{ route('transaksi.history') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">Purchase History</a>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-zinc-100">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('google.login') }}" class="bg-black text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-zinc-800 transition flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white mt-20 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h2 class="text-2xl font-black tracking-tighter uppercase mb-4">KICKAVENUE</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">The safest and most trusted platform to buy and sell authentic sneakers & streetwear in Indonesia.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-sm uppercase tracking-wider">About Us</h3>
                <ul class="text-zinc-400 text-sm space-y-2">
                    <li><a href="#" class="hover:text-white transition">Our Story</a></li>
                    <li><a href="#" class="hover:text-white transition">Authenticity</a></li>
                    <li><a href="#" class="hover:text-white transition">Careers</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-sm uppercase tracking-wider">Support</h3>
                <ul class="text-zinc-400 text-sm space-y-2">
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition">Shipping Info</a></li>
                    <li><a href="#" class="hover:text-white transition">Returns</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-sm uppercase tracking-wider">Connect</h3>
                <ul class="text-zinc-400 text-sm space-y-2">
                    <li><a href="#" class="hover:text-white transition">Instagram</a></li>
                    <li><a href="#" class="hover:text-white transition">Twitter</a></li>
                    <li><a href="#" class="hover:text-white transition">Facebook</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-zinc-800 text-zinc-500 text-sm text-center">
            &copy; {{ date('Y') }} Kick Avenue Clone. All rights reserved.
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
