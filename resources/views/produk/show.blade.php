@extends('layouts.app')

@section('title', $produk->nama_produk . ' - SEP-OKAT')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-8">
        <a href="/" class="hover:text-black">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('produk.search') }}" class="hover:text-black">Shop</a>
        <span class="mx-2">/</span>
        <a href="{{ route('produk.search', ['kategori_id' => $produk->kategori_id]) }}" class="hover:text-black">{{ $produk->kategori->nama_kategori ?? 'Kategori' }}</a>
        <span class="mx-2">/</span>
        <span class="text-black font-semibold">{{ $produk->nama_produk }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Left: Image Gallery -->
        <div>
            <div class="bg-gray-50 rounded-2xl p-8 mb-4 border border-gray-100 flex items-center justify-center aspect-square relative overflow-hidden">
                <img id="main-image" src="{{ asset('storage/img-produk/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-contain mix-blend-multiply transition-transform duration-500 hover:scale-110">
            </div>
            
            <div class="flex gap-4 overflow-x-auto pb-2">
                <!-- Thumbnail 1 (Utama) -->
                <button onclick="changeImage('{{ asset('storage/img-produk/' . $produk->foto) }}', this)" class="thumbnail-btn border-2 border-black rounded-xl p-2 w-20 h-20 flex-shrink-0 bg-gray-50 transition">
                    <img src="{{ asset('storage/img-produk/' . $produk->foto) }}" class="w-full h-full object-contain mix-blend-multiply">
                </button>
                
                @if(isset($produk->fotoProduk) && $produk->fotoProduk->count() > 0)
                    @foreach($produk->fotoProduk as $fp)
                    <button onclick="changeImage('{{ asset('storage/img-produk/' . $fp->foto) }}', this)" class="thumbnail-btn border-2 border-transparent hover:border-gray-300 rounded-xl p-2 w-20 h-20 flex-shrink-0 bg-gray-50 transition">
                        <img src="{{ asset('storage/img-produk/' . $fp->foto) }}" class="w-full h-full object-contain mix-blend-multiply">
                    </button>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right: Product Info -->
        <div class="flex flex-col">
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-2 uppercase tracking-widest font-semibold">{{ $produk->kategori->nama_kategori ?? 'Sneakers' }}</p>
                <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tight mb-4 leading-tight">{{ $produk->nama_produk }}</h1>
                <div class="flex items-center gap-4 mb-4">
                    <p class="text-2xl font-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">Tersedia {{ $produk->stok }} pasang</span>
                </div>
            </div>

            <!-- Description -->
            <div class="prose prose-sm text-gray-600 mb-8 border-y border-gray-100 py-6">
                {!! $produk->detail !!}
            </div>

            <form action="{{ route('keranjang.add') }}" method="POST" id="add-to-cart-form" class="mt-auto">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                <input type="hidden" name="ukuran_sepatu" id="input_ukuran" value="">
                <input type="hidden" name="qty" id="input_qty" value="1">

                <!-- Size Selection -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-bold text-sm uppercase">Pilih Ukuran</h3>
                        
                        <!-- Size Chart Tabs -->
                        <div class="flex gap-2 text-xs font-semibold bg-gray-100 p-1 rounded-lg">
                            <button type="button" class="size-tab active bg-white shadow-sm px-3 py-1 rounded text-black transition" data-target="EU">EU</button>
                            <button type="button" class="size-tab px-3 py-1 rounded text-gray-500 hover:text-black transition" data-target="US">US</button>
                            <button type="button" class="size-tab px-3 py-1 rounded text-gray-500 hover:text-black transition" data-target="CM">CM</button>
                        </div>
                    </div>

                    <!-- Size Grid EU -->
                    <div id="size-grid-EU" class="size-grid grid grid-cols-4 md:grid-cols-5 gap-3">
                        @foreach(['38', '39', '40', '41', '42', '43', '44', '45'] as $size)
                        <button type="button" class="size-btn border border-gray-200 rounded-xl py-3 text-sm font-semibold hover:border-black transition focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2" data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                    <!-- Size Grid US -->
                    <div id="size-grid-US" class="size-grid grid grid-cols-4 md:grid-cols-5 gap-3 hidden">
                        @foreach(['5.5', '6.5', '7', '8', '8.5', '9.5', '10', '11'] as $size)
                        <button type="button" class="size-btn border border-gray-200 rounded-xl py-3 text-sm font-semibold hover:border-black transition focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2" data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                    <!-- Size Grid CM -->
                    <div id="size-grid-CM" class="size-grid grid grid-cols-4 md:grid-cols-5 gap-3 hidden">
                        @foreach(['24', '24.5', '25', '26', '26.5', '27.5', '28', '29'] as $size)
                        <button type="button" class="size-btn border border-gray-200 rounded-xl py-3 text-sm font-semibold hover:border-black transition focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2" data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                    <p id="size-error" class="text-red-500 text-xs mt-2 hidden">Pilih ukuran sepatu terlebih dahulu.</p>
                </div>

                <!-- Quantity & Actions -->
                <div class="flex gap-4">
                    <!-- Qty -->
                    <div class="flex items-center border border-gray-200 rounded-full h-14 px-2 w-32 shrink-0">
                        <button type="button" onclick="updateQty(-1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-black hover:bg-gray-50 rounded-full transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg></button>
                        <span id="qty-display" class="flex-1 text-center font-bold text-sm">1</span>
                        <button type="button" onclick="updateQty(1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-black hover:bg-gray-50 rounded-full transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></button>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="flex-1 bg-black text-white rounded-full font-bold text-sm uppercase tracking-wider h-14 hover:bg-gray-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>
            
            <!-- Perks -->
            <div class="grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0">🛡️</div>
                    <div>
                        <p class="font-bold text-xs">100% Autentik</p>
                        <p class="text-xs text-gray-500">Garansi uang kembali</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0">📦</div>
                    <div>
                        <p class="font-bold text-xs">Pengiriman Aman</p>
                        <p class="text-xs text-gray-500">Double box packaging</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image Switcher
    function changeImage(src, btn) {
        document.getElementById('main-image').src = src;
        document.querySelectorAll('.thumbnail-btn').forEach(b => {
            b.classList.remove('border-black');
            b.classList.add('border-transparent');
        });
        btn.classList.remove('border-transparent');
        btn.classList.add('border-black');
    }

    // Size Selection
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('border-black', 'bg-black', 'text-white');
            });
            this.classList.add('border-black', 'bg-black', 'text-white');
            document.getElementById('input_ukuran').value = this.dataset.size;
            document.getElementById('size-error').classList.add('hidden');
        });
    });

    // Size Chart Tabs
    document.querySelectorAll('.size-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Reset active tab styling
            document.querySelectorAll('.size-tab').forEach(t => {
                t.classList.remove('bg-white', 'shadow-sm', 'text-black');
                t.classList.add('text-gray-500');
            });
            this.classList.remove('text-gray-500');
            this.classList.add('bg-white', 'shadow-sm', 'text-black');

            // Hide all grids
            document.querySelectorAll('.size-grid').forEach(g => g.classList.add('hidden'));
            
            // Show target grid
            const target = this.dataset.target;
            document.getElementById('size-grid-' + target).classList.remove('hidden');

            // Reset selection
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('border-black', 'bg-black', 'text-white');
            });
            document.getElementById('input_ukuran').value = '';
        });
    });

    // Quantity Logic
    let qty = 1;
    const maxQty = {{ $produk->stok }};
    function updateQty(change) {
        let newQty = qty + change;
        if(newQty >= 1 && newQty <= maxQty) {
            qty = newQty;
            document.getElementById('qty-display').innerText = qty;
            document.getElementById('input_qty').value = qty;
        }
    }

    // Form Validation
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        if(!document.getElementById('input_ukuran').value) {
            e.preventDefault();
            document.getElementById('size-error').classList.remove('hidden');
        }
    });
</script>
@endpush
@endsection
