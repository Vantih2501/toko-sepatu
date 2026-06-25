@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Walkway')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black mb-2 tracking-tight uppercase">Riwayat Pesanan</h1>
    <p class="text-gray-400 text-sm mb-10">Daftar transaksi dan pesanan Anda.</p>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($transaksi as $trx)
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col md:flex-row gap-6">
                <!-- Details -->
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">#TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-xs text-gray-400">&bull;</span>
                        <span class="text-xs font-medium text-gray-500">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                        
                        @if($trx->status_pembayaran == 'pending')
                            <span class="ml-auto bg-orange-50 text-orange-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Pending</span>
                        @elseif($trx->status_pembayaran == 'sudah dibayar' || $trx->status_pembayaran == 'sukses')
                            <span class="ml-auto bg-green-50 text-green-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Sudah Dibayar</span>
                        @else
                            <span class="ml-auto bg-red-50 text-red-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $trx->status_pembayaran }}</span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @foreach($trx->detail as $item)
                        <div class="flex gap-4 items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center shrink-0 p-2">
                                <img src="{{ asset('storage/img-produk/' . $item->produk->foto) }}" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm leading-tight truncate">{{ $item->produk->nama_produk }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Ukuran: {{ $item->ukuran_sepatu }} &bull; Qty: {{ $item->qty }}</p>
                            </div>
                            <p class="font-bold text-sm shrink-0">Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Summary & Action -->
                <div class="w-full md:w-64 shrink-0 flex flex-col justify-between border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 md:pl-6">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Total Belanja</p>
                        <p class="text-2xl font-black">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                        @if($trx->kurir)
                        <p class="text-xs text-gray-400 mt-2">Kurir: <span class="uppercase font-semibold text-gray-600">{{ $trx->kurir }}</span></p>
                        @endif
                    </div>
                    
                    <div class="mt-6">
                        @if($trx->status_pembayaran == 'pending')
                            <button onclick="payMidtrans('{{ $trx->snap_token_midtrans }}')" class="w-full mb-2 bg-black text-white text-sm font-bold py-3 rounded-xl hover:bg-gray-800 transition">Bayar Sekarang</button>
                            <form action="{{ route('transaksi.check_status', $trx->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-white border border-gray-200 text-gray-700 text-sm font-bold py-3 rounded-xl hover:bg-gray-50 transition">Cek Status</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h3 class="font-black text-lg mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-500 text-sm mb-6">Anda belum pernah melakukan pemesanan.</p>
                <a href="{{ route('produk.search') }}" class="inline-block bg-black text-white px-8 py-3 rounded-full font-bold text-sm hover:bg-gray-800 transition">Mulai Belanja</a>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    function payMidtrans(token) {
        if (!token) return alert('Token pembayaran tidak valid.');
        snap.pay(token, {
            onSuccess: async function(result) { 
                alert('Pembayaran sukses!');
                await fetch('{{ route('transaksi.success_callback') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: result.order_id })
                });
                location.reload(); 
            },
            onPending: function(result) { location.reload(); },
            onError: function(result) { alert('Pembayaran gagal!'); },
            onClose: function() { 
                location.reload(); 
            }
        });
    }
</script>
@endpush
@endsection
