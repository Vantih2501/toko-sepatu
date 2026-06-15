@extends('layouts.app')

@section('title', 'Checkout - Kick Avenue')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black uppercase tracking-tight mb-8">Checkout</h1>
    
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Shipping Form -->
        <div class="lg:w-2/3">
            <div class="bg-white border border-zinc-200 rounded-xl p-8 mb-8">
                <h2 class="text-xl font-bold mb-6 border-b border-zinc-100 pb-4">Shipping Address</h2>
                <form id="checkout-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-zinc-700 mb-2">First Name</label>
                            <input type="text" value="{{ Auth::user()->nama }}" readonly class="w-full border-zinc-300 bg-zinc-50 rounded-lg p-3 text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-700 mb-2">Phone Number</label>
                            <input type="text" value="{{ Auth::user()->hp }}" {{ Auth::user()->hp ? 'readonly' : '' }} class="w-full border-zinc-300 {{ Auth::user()->hp ? 'bg-zinc-50' : 'bg-white border focus:ring-2 focus:ring-black' }} rounded-lg p-3 text-sm outline-none" id="phone" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Province</label>
                        <select id="province" class="w-full border border-zinc-300 rounded-lg p-3 text-sm outline-none focus:ring-2 focus:ring-black">
                            <option value="">Select Province</option>
                            <!-- AJAX populated -->
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">City</label>
                        <select id="city" class="w-full border border-zinc-300 rounded-lg p-3 text-sm outline-none focus:ring-2 focus:ring-black" disabled>
                            <option value="">Select City</option>
                            <!-- AJAX populated -->
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Full Address</label>
                        <textarea rows="3" class="w-full border border-zinc-300 rounded-lg p-3 text-sm outline-none focus:ring-2 focus:ring-black" id="address" required>{{ Auth::user()->alamat_lengkap }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Courier</label>
                        <select id="courier" class="w-full border border-zinc-300 rounded-lg p-3 text-sm outline-none focus:ring-2 focus:ring-black" disabled>
                            <option value="">Select Courier</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Shipping Service</label>
                        <select id="service" class="w-full border border-zinc-300 rounded-lg p-3 text-sm outline-none focus:ring-2 focus:ring-black" disabled>
                            <option value="">Select Service</option>
                            <!-- AJAX populated -->
                        </select>
                    </div>

                    @if(request('buy_now') && request('produk_id'))
                        <input type="hidden" id="is_buy_now" value="1">
                        <input type="hidden" id="produk_id" value="{{ request('produk_id') }}">
                        <input type="hidden" id="ukuran_sepatu" value="{{ request('ukuran_sepatu') }}">
                        <input type="hidden" id="total_weight" value="1000"> <!-- Default 1kg -->
                    @else
                        <input type="hidden" id="is_buy_now" value="0">
                        <input type="hidden" id="total_weight" value="1000"> <!-- Default 1kg -->
                    @endif
                </form>
            </div>
        </div>

        <!-- Summary -->
        <div class="lg:w-1/3">
            <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-6 sticky top-24">
                <h2 class="text-lg font-bold mb-6 border-b border-zinc-200 pb-4">Order Summary</h2>
                
                @php $totalHarga = 0; @endphp
                @if(request('buy_now'))
                    @php 
                        $produk = \App\Models\Produk::find(request('produk_id'));
                        $totalHarga = $produk ? $produk->harga : 0;
                    @endphp
                    <div class="flex justify-between items-start mb-4 text-sm">
                        <div class="pr-4">
                            <p class="font-bold">{{ $produk->nama_produk }}</p>
                            <p class="text-zinc-500">Size: {{ request('ukuran_sepatu') }}</p>
                        </div>
                        <span class="font-semibold shrink-0">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                @else
                    @foreach($keranjang as $item)
                    @php $totalHarga += ($item->qty * $item->produk->harga); @endphp
                    <div class="flex justify-between items-start mb-4 text-sm">
                        <div class="pr-4">
                            <p class="font-bold">{{ $item->produk->nama_produk }} <span class="text-zinc-400">x{{ $item->qty }}</span></p>
                            <p class="text-zinc-500">Size: {{ $item->ukuran_sepatu }}</p>
                        </div>
                        <span class="font-semibold shrink-0">Rp {{ number_format($item->qty * $item->produk->harga, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                @endif
                
                <input type="hidden" id="subtotal_value" value="{{ $totalHarga }}">

                <div class="border-t border-zinc-200 pt-4 mb-4 mt-4 text-sm">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-zinc-500">Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-zinc-500">Shipping</span>
                        <span class="font-semibold" id="shipping_cost_display">Rp 0</span>
                        <input type="hidden" id="shipping_cost" value="0">
                    </div>
                </div>

                <div class="border-t border-black pt-4 mb-8 flex justify-between items-center">
                    <span class="font-bold">Total</span>
                    <span class="font-black text-xl" id="total_payment_display">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>

                <button id="pay-button" class="w-full bg-black text-white px-8 py-4 rounded-full font-bold uppercase tracking-wider hover:bg-zinc-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1 block text-center disabled:opacity-50 disabled:cursor-not-allowed">
                    Pay Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    // Format Rupiah
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // RajaOngkir Logic
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch Provinces
        fetch('/api/provinces')
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Select Province</option>';
                data.rajaongkir.results.forEach(prov => {
                    options += `<option value="${prov.province_id}">${prov.province}</option>`;
                });
                document.getElementById('province').innerHTML = options;
            });

        // Fetch Cities
        document.getElementById('province').addEventListener('change', function() {
            let provId = this.value;
            if(provId) {
                document.getElementById('city').disabled = false;
                fetch(`/api/cities/${provId}`)
                    .then(res => res.json())
                    .then(data => {
                        let options = '<option value="">Select City</option>';
                        data.rajaongkir.results.forEach(city => {
                            options += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                        });
                        document.getElementById('city').innerHTML = options;
                    });
            } else {
                document.getElementById('city').disabled = true;
                document.getElementById('courier').disabled = true;
            }
        });

        // Enable courier after city
        document.getElementById('city').addEventListener('change', function() {
            if(this.value) {
                document.getElementById('courier').disabled = false;
            } else {
                document.getElementById('courier').disabled = true;
            }
        });

        // Check Ongkir
        document.getElementById('courier').addEventListener('change', function() {
            let cityId = document.getElementById('city').value;
            let courier = this.value;
            let weight = document.getElementById('total_weight').value;
            
            if(courier && cityId) {
                fetch('/api/check-ongkir', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        destination_city: cityId,
                        weight: weight,
                        courier: courier
                    })
                })
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Select Service</option>';
                    data.rajaongkir.results[0].costs.forEach(cost => {
                        options += `<option value="${cost.cost[0].value}">${cost.service} - Rp ${formatRupiah(cost.cost[0].value)} (${cost.cost[0].etd} Hari)</option>`;
                    });
                    document.getElementById('service').innerHTML = options;
                    document.getElementById('service').disabled = false;
                });
            }
        });

        // Update Total when service selected
        document.getElementById('service').addEventListener('change', function() {
            let ongkir = parseInt(this.value) || 0;
            let subtotal = parseInt(document.getElementById('subtotal_value').value);
            let total = subtotal + ongkir;
            
            document.getElementById('shipping_cost').value = ongkir;
            document.getElementById('shipping_cost_display').innerText = `Rp ${formatRupiah(ongkir)}`;
            document.getElementById('total_payment_display').innerText = `Rp ${formatRupiah(total)}`;
        });

        // Midtrans Payment
        document.getElementById('pay-button').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validation
            if(!document.getElementById('phone').value || !document.getElementById('address').value || !document.getElementById('service').value) {
                alert('Please fill out all address and shipping details.');
                return;
            }

            // Post to Checkout Process
            let payload = {
                kurir: document.getElementById('courier').value,
                ongkir: document.getElementById('shipping_cost').value,
                alamat_lengkap: document.getElementById('address').value,
                hp: document.getElementById('phone').value,
                _token: '{{ csrf_token() }}'
            };

            if(document.getElementById('is_buy_now').value === "1") {
                payload.buy_now = true;
                payload.produk_id = document.getElementById('produk_id').value;
                payload.ukuran_sepatu = document.getElementById('ukuran_sepatu').value;
            }

            fetch('{{ route('checkout.process') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    snap.pay(data.snapToken, {
                        onSuccess: function(result) {
                            window.location.href = '/transaksi/history';
                        },
                        onPending: function(result) {
                            window.location.href = '/transaksi/history';
                        },
                        onError: function(result) {
                            alert('Payment failed');
                        },
                        onClose: function() {
                            alert('You closed the popup without finishing the payment');
                        }
                    });
                } else {
                    alert(data.message || 'Error processing checkout');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan pada sistem.');
            });
        });
    });
</script>
@endsection
