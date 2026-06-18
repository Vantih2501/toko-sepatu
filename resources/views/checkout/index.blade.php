@extends('layouts.app')

@section('title', 'Checkout - SEP-OKAT')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black mb-2 tracking-tight uppercase">Checkout</h1>
    <p class="text-gray-400 text-sm mb-10">Lengkapi informasi pengiriman Anda sebelum melakukan pembayaran.</p>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

        <!-- Left: Form Pengiriman -->
        <div class="lg:col-span-3 space-y-8">

            <!-- Info Penerima -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h2 class="font-black text-lg uppercase tracking-tight mb-6 flex items-center gap-2">
                    <span class="w-7 h-7 bg-black text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                    Informasi Penerima
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap</label>
                        <input type="text" id="nama_penerima" value="{{ auth()->user()->nama }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor HP / WhatsApp</label>
                        <input type="text" id="no_hp" value="{{ auth()->user()->hp }}" placeholder="Contoh: 08123456789"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-gray-50">
                    </div>
                </div>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h2 class="font-black text-lg uppercase tracking-tight mb-6 flex items-center gap-2">
                    <span class="w-7 h-7 bg-black text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                    Alamat Pengiriman
                </h2>

                <!-- Gunakan Alamat Tersimpan -->
                @if(auth()->user()->alamat_lengkap && auth()->user()->provinsi_id && auth()->user()->kota_id)
                <div class="mb-6">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-3">Gunakan Alamat Tersimpan</p>
                    <button type="button" id="btn-use-saved"
                            class="w-full flex items-start gap-4 p-4 border-2 border-dashed border-gray-200 rounded-2xl hover:border-black transition text-left group">
                        <div class="w-9 h-9 bg-gray-50 rounded-full flex items-center justify-center shrink-0 group-hover:bg-black group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ auth()->user()->nama }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ auth()->user()->alamat_lengkap }}</p>
                            <span id="saved-city-label" class="text-xs text-gray-400 font-medium">
                                (Provinsi ID: {{ auth()->user()->provinsi_id }}, Kota ID: {{ auth()->user()->kota_id }})
                            </span>
                            <span class="mt-2 inline-block bg-green-50 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full">Klik untuk pakai alamat ini</span>
                        </div>
                    </button>
                </div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-xs text-gray-400 font-medium">atau isi manual</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                </div>
                @endif

                <!-- Dropdown Provinsi & Kota -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Provinsi</label>
                        <select id="provinsi" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-gray-50 appearance-none cursor-pointer">
                            <option value="">-- Memuat provinsi... --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kota / Kabupaten</label>
                        <select id="kota" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-gray-50 appearance-none cursor-pointer" disabled>
                            <option value="">-- Pilih provinsi dulu --</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Lengkap (Jalan, No. Rumah, RT/RW, dll)</label>
                    <textarea id="alamat_lengkap" rows="3" placeholder="Contoh: Jl. Sudirman No. 10, RT 01/RW 02, Kel. Bendungan Hilir"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-gray-50 resize-none">{{ auth()->user()->alamat_lengkap }}</textarea>
                </div>
            </div>

            <!-- Pilih Kurir & Cek Ongkir -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h2 class="font-black text-lg uppercase tracking-tight mb-6 flex items-center gap-2">
                    <span class="w-7 h-7 bg-black text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                    Pengiriman
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kurir</label>
                        <select id="kurir" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-gray-50 appearance-none cursor-pointer">
                            <option value="">-- Pilih Kurir --</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="btn-cek-ongkir"
                                class="w-full bg-gray-900 text-white py-3 rounded-xl font-bold text-sm hover:bg-black transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Cek Ongkos Kirim
                        </button>
                    </div>
                </div>

                <!-- Hasil Layanan Pengiriman -->
                <div id="shipping-options" class="hidden">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-3">Pilih Layanan</p>
                    <div id="shipping-list" class="space-y-3"></div>
                </div>

                <div id="shipping-loading" class="hidden text-center py-6">
                    <div class="w-8 h-8 border-2 border-black border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-sm text-gray-500">Mengambil data ongkos kirim...</p>
                </div>

                <div id="shipping-error" class="hidden bg-red-50 text-red-600 text-sm px-4 py-3 rounded-xl mt-3"></div>
            </div>

        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm sticky top-24">
                <h2 class="font-black text-lg uppercase tracking-tight mb-6">Ringkasan Pesanan</h2>

                @php $subtotal = 0; @endphp
                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto pr-1">
                    @foreach($keranjang as $item)
                        @php $subtotal += $item->qty * $item->produk->harga; @endphp
                        <div class="flex gap-4 items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center shrink-0 p-2">
                                <img src="{{ asset('storage/img-produk/' . $item->produk->foto) }}" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm leading-tight truncate">{{ $item->produk->nama_produk }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Ukuran: {{ $item->ukuran_sepatu }} &bull; Qty: {{ $item->qty }}</p>
                            </div>
                            <p class="font-bold text-sm shrink-0">Rp {{ number_format($item->produk->harga * $item->qty, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 pt-5 space-y-3 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Produk</span>
                        <span class="font-semibold text-black">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span id="label-ongkir" class="font-semibold text-gray-400">Belum dipilih</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 mt-4 pt-5 flex justify-between items-center">
                    <span class="font-black text-base uppercase">Total</span>
                    <span id="label-total" class="font-black text-2xl">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <button type="button" id="btn-bayar"
                        class="mt-6 w-full bg-black text-white py-4 rounded-full font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition shadow-lg hover:-translate-y-0.5 transform flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:translate-y-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Bayar Sekarang
                </button>

                <p class="text-center text-[11px] text-gray-400 mt-4 flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Pembayaran aman dengan Midtrans
                </p>
            </div>
        </div>

    </div>
</div>

<!-- Hidden fields -->
<input type="hidden" id="selected_ongkir" value="0">
<input type="hidden" id="selected_kurir_service" value="">
<input type="hidden" id="selected_kota_id" value="{{ auth()->user()->kota_id }}">
<input type="hidden" id="subtotal_value" value="{{ $subtotal }}">

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
const subtotal = {{ $subtotal }};
const savedProvinsiId = "{{ auth()->user()->provinsi_id ?? '' }}";
const savedKotaId = "{{ auth()->user()->kota_id ?? '' }}";

// Format currency
function rupiah(n) {
    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
}

// Update total display
function updateTotal() {
    const ongkir = parseInt(document.getElementById('selected_ongkir').value) || 0;
    const total = subtotal + ongkir;
    document.getElementById('label-total').innerText = rupiah(total);
    document.getElementById('label-ongkir').innerText = ongkir > 0 ? rupiah(ongkir) : 'Belum dipilih';
    document.getElementById('label-ongkir').className = ongkir > 0 ? 'font-semibold text-black' : 'font-semibold text-gray-400';
}

// Load Provinces on page load
async function loadProvinces() {
    try {
        const res = await fetch('/api/provinces');
        const data = await res.json();
        const provinces = data?.rajaongkir?.results ?? [];
        const sel = document.getElementById('provinsi');
        sel.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
        provinces.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.province_id;
            opt.text = p.province;
            if (p.province_id == savedProvinsiId) opt.selected = true;
            sel.appendChild(opt);
        });
        if (savedProvinsiId) loadCities(savedProvinsiId);
    } catch(e) {
        document.getElementById('provinsi').innerHTML = '<option value="">Gagal memuat provinsi</option>';
    }
}

// Load Cities by Province
async function loadCities(provinceId) {
    const kotaSel = document.getElementById('kota');
    kotaSel.disabled = true;
    kotaSel.innerHTML = '<option value="">-- Memuat kota... --</option>';
    try {
        const res = await fetch(`/api/cities/${provinceId}`);
        const data = await res.json();
        const cities = data?.rajaongkir?.results ?? [];
        kotaSel.innerHTML = '<option value="">-- Pilih Kota --</option>';
        cities.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.city_id;
            opt.text = c.type + ' ' + c.city_name;
            if (c.city_id == savedKotaId) opt.selected = true;
            kotaSel.appendChild(opt);
        });
        kotaSel.disabled = false;
        if (savedKotaId) document.getElementById('selected_kota_id').value = savedKotaId;
    } catch(e) {
        kotaSel.innerHTML = '<option value="">Gagal memuat kota</option>';
        kotaSel.disabled = false;
    }
}

// Province change
document.getElementById('provinsi').addEventListener('change', function() {
    if (this.value) {
        loadCities(this.value);
        document.getElementById('kota').value = '';
        document.getElementById('selected_kota_id').value = '';
    } else {
        document.getElementById('kota').innerHTML = '<option value="">-- Pilih provinsi dulu --</option>';
        document.getElementById('kota').disabled = true;
    }
    // Reset ongkir
    document.getElementById('shipping-options').classList.add('hidden');
    document.getElementById('selected_ongkir').value = 0;
    updateTotal();
});

// Kota change
document.getElementById('kota').addEventListener('change', function() {
    document.getElementById('selected_kota_id').value = this.value;
    // Reset ongkir
    document.getElementById('shipping-options').classList.add('hidden');
    document.getElementById('selected_ongkir').value = 0;
    updateTotal();
});

// Cek Ongkir
document.getElementById('btn-cek-ongkir').addEventListener('click', async function() {
    const kotaId = document.getElementById('selected_kota_id').value || document.getElementById('kota').value;
    const kurir = document.getElementById('kurir').value;

    if (!kotaId) return alert('Silakan pilih kota tujuan pengiriman terlebih dahulu.');
    if (!kurir) return alert('Silakan pilih kurir pengiriman.');

    document.getElementById('shipping-loading').classList.remove('hidden');
    document.getElementById('shipping-options').classList.add('hidden');
    document.getElementById('shipping-error').classList.add('hidden');

    try {
        const res = await fetch('/api/check-ongkir', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                destination_city: kotaId,
                weight: {{ $keranjang->sum('qty') * 500 }}, // 500g per item
                courier: kurir
            })
        });
        const data = await res.json();
        const services = data?.rajaongkir?.results?.[0]?.costs ?? [];

        document.getElementById('shipping-loading').classList.add('hidden');

        if (services.length === 0) {
            document.getElementById('shipping-error').innerText = 'Tidak ada layanan pengiriman tersedia untuk rute ini.';
            document.getElementById('shipping-error').classList.remove('hidden');
            return;
        }

        const list = document.getElementById('shipping-list');
        list.innerHTML = '';
        services.forEach(s => {
            const cost = s.cost[0].value;
            const etd = s.cost[0].etd;
            const div = document.createElement('label');
            div.className = 'flex items-center gap-4 p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-black transition has-[:checked]:border-black has-[:checked]:bg-gray-50';
            div.innerHTML = `
                <input type="radio" name="shipping_service" value="${cost}" data-service="${kurir.toUpperCase()} ${s.service}" class="w-4 h-4 accent-black">
                <div class="flex-1">
                    <p class="font-bold text-sm">${kurir.toUpperCase()} ${s.service}</p>
                    <p class="text-xs text-gray-400">${s.description} &bull; Estimasi ${etd} hari</p>
                </div>
                <p class="font-black text-sm">${rupiah(cost)}</p>
            `;
            list.appendChild(div);
        });

        // Listen for service selection
        list.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('selected_ongkir').value = this.value;
                document.getElementById('selected_kurir_service').value = this.dataset.service;
                updateTotal();
            });
        });

        document.getElementById('shipping-options').classList.remove('hidden');

    } catch(e) {
        document.getElementById('shipping-loading').classList.add('hidden');
        document.getElementById('shipping-error').innerText = 'Gagal memuat ongkos kirim. Coba lagi.';
        document.getElementById('shipping-error').classList.remove('hidden');
    }
});

// Use Saved Address
const btnSaved = document.getElementById('btn-use-saved');
if (btnSaved) {
    btnSaved.addEventListener('click', function() {
        // Set provinsi & reload kota
        document.getElementById('provinsi').value = savedProvinsiId;
        loadCities(savedProvinsiId).then(() => {
            setTimeout(() => {
                document.getElementById('kota').value = savedKotaId;
                document.getElementById('selected_kota_id').value = savedKotaId;
            }, 600);
        });
        // Reset ongkir
        document.getElementById('shipping-options').classList.add('hidden');
        document.getElementById('selected_ongkir').value = 0;
        updateTotal();
        this.classList.add('border-black', 'bg-gray-50');
    });
}

// Bayar Sekarang
document.getElementById('btn-bayar').addEventListener('click', async function() {
    const kotaId = document.getElementById('selected_kota_id').value || document.getElementById('kota').value;
    const ongkir = parseInt(document.getElementById('selected_ongkir').value) || 0;
    const kurir = document.getElementById('selected_kurir_service').value;
    const alamat = document.getElementById('alamat_lengkap').value.trim();
    const hp = document.getElementById('no_hp').value.trim();

    if (!kotaId) return alert('Pilih kota tujuan terlebih dahulu.');
    if (!alamat) return alert('Isi alamat lengkap terlebih dahulu.');
    if (!hp) return alert('Isi nomor HP terlebih dahulu.');
    if (ongkir === 0) return alert('Pilih layanan pengiriman dan ongkos kirim terlebih dahulu.');

    this.disabled = true;
    this.innerHTML = '<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';

    try {
        const res = await fetch('{{ route('checkout.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ongkir, kurir, kota_id: kotaId, alamat_lengkap: alamat, hp })
        });
        const data = await res.json();
        if (data.success && data.snapToken) {
            snap.pay(data.snapToken, {
                onSuccess: function(result) { window.location.href = '{{ route('transaksi.history') }}'; },
                onPending: function(result) { window.location.href = '{{ route('transaksi.history') }}'; },
                onError: function(result) { alert('Pembayaran gagal. Silakan coba lagi.'); },
                onClose: function() { /* user closed */ }
            });
        } else {
            alert(data.message || 'Terjadi kesalahan. Coba lagi.');
        }
    } catch(e) {
        alert('Koneksi bermasalah. Coba lagi.');
    }

    this.disabled = false;
    this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang';
});

// Init
loadProvinces();
updateTotal();
</script>
@endpush
@endsection
