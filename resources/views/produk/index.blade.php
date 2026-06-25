@extends('layouts.app')

@section('title', 'Katalog Produk - Walkway')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Filter -->
        <div class="w-full md:w-64 shrink-0">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sticky top-24 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <h2 class="font-bold text-lg uppercase tracking-tight">Filter</h2>
                    @if(request()->anyFilled(['kategori_id', 'harga_min', 'harga_max', 'q']))
                        <a href="{{ route('produk.search') }}" class="text-xs text-red-500 font-semibold hover:text-red-700">Reset</a>
                    @endif
                </div>

                <form action="{{ route('produk.search') }}" method="GET" id="filter-form">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- Kategori -->
                    <div class="mb-8">
                        <h3 class="font-semibold text-sm mb-4">Kategori</h3>
                        <div class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="kategori_id" value="" onchange="this.form.submit()"
                                       class="w-4 h-4 text-black border-gray-300 focus:ring-black"
                                       {{ !request('kategori_id') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600 group-hover:text-black font-medium transition">Semua Kategori</span>
                            </label>
                            @foreach($kategori as $kat)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="kategori_id" value="{{ $kat->id }}" onchange="this.form.submit()"
                                       class="w-4 h-4 text-black border-gray-300 focus:ring-black"
                                       {{ request('kategori_id') == $kat->id ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600 group-hover:text-black font-medium transition">{{ $kat->nama_kategori }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Harga -->
                    <div class="mb-8">
                        <h3 class="font-semibold text-sm mb-4">Rentang Harga</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Min (Rp)</label>
                                <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="0" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Max (Rp)</label>
                                <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="10000000" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition">
                            </div>
                            <button type="submit" class="w-full bg-gray-900 text-white rounded-lg py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-black transition">Terapkan Harga</button>
                        </div>
                    </div>

                    <!-- Urutkan -->
                    <div>
                        <h3 class="font-semibold text-sm mb-4">Urutkan</h3>
                        <select name="sort" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition appearance-none bg-white cursor-pointer">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                            <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                            <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama: A - Z</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tight">Koleksi Sneakers</h1>
                    <p class="text-sm text-gray-500 mt-1">Menampilkan {{ $produk->count() }} produk</p>
                </div>
            </div>

            @if($produk->count() > 0)
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($produk as $item)
                    <a href="{{ route('produk.show', $item->id) }}"
                       class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                        
                        <div class="relative bg-gray-50 aspect-square flex items-center justify-center p-5 overflow-hidden">
                            <img src="{{ asset('storage/img-produk/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">
                            <span class="absolute top-3 left-3 bg-black text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Baru</span>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <p class="text-xs text-gray-400 mb-0.5">{{ $item->kategori->nama_kategori ?? 'Sneakers' }}</p>
                            <h3 class="font-bold text-sm leading-tight mb-1.5 line-clamp-2 group-hover:text-blue-600 transition">{{ $item->nama_produk }}</h3>
                            <div class="flex items-center justify-between mt-auto pt-2">
                                <p class="font-black text-base text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                <div class="w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-2xl p-12 text-center border border-gray-100">
                    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">👟</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 text-sm max-w-sm mx-auto mb-6">Maaf, kami tidak dapat menemukan produk yang sesuai dengan filter pencarian Anda.</p>
                    <a href="{{ route('produk.search') }}" class="inline-block bg-black text-white px-6 py-3 rounded-full text-sm font-bold hover:bg-gray-800 transition">Reset Pencarian</a>
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    /* Custom Scrollbar for filter */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>
@endsection
