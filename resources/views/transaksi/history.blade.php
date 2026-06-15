@extends('layouts.app')

@section('title', 'Purchase History - Kick Avenue')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black uppercase tracking-tight mb-8">Purchase History</h1>
    
    <div class="space-y-6">
        @forelse($transaksi as $tx)
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
            <div class="bg-zinc-50 border-b border-zinc-200 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <p class="text-sm text-zinc-500 mb-1">Order #{{ $tx->id }}-{{ strtotime($tx->created_at) }}</p>
                    <p class="font-semibold text-sm">{{ $tx->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <div class="text-right">
                        <p class="text-zinc-500 mb-1">Total</p>
                        <p class="font-bold">Rp {{ number_format($tx->total_harga, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        @if($tx->status_pembayaran == 'success')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold uppercase">Success</span>
                        @elseif($tx->status_pembayaran == 'pending')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold uppercase">Pending</span>
                            <button onclick="snap.pay('{{ $tx->snap_token_midtrans }}')" class="ml-2 text-blue-600 hover:underline text-xs">Pay Now</button>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold uppercase">Failed</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                @foreach($tx->detail as $dt)
                <div class="flex items-center gap-6 py-4 border-b border-zinc-100 last:border-0 last:pb-0">
                    <div class="w-20 h-20 bg-zinc-100 rounded-lg shrink-0 flex items-center justify-center p-2">
                        <img src="{{ $dt->produk->foto ? asset('storage/' . $dt->produk->foto) : 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' }}" class="w-full h-full object-contain mix-blend-multiply">
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold mb-1">{{ $dt->produk->nama_produk }}</h3>
                        <p class="text-sm text-zinc-500 mb-1">Size: {{ $dt->ukuran_sepatu }} | Qty: {{ $dt->qty }}</p>
                        <p class="font-semibold text-sm">Rp {{ number_format($dt->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-zinc-50 border border-zinc-200 rounded-2xl">
            <h2 class="text-xl font-bold mb-2">No purchases yet</h2>
            <p class="text-zinc-500 mb-6">You haven't bought any items.</p>
            <a href="/" class="bg-black text-white px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider hover:bg-zinc-800 transition inline-block">Start Shopping</a>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endsection
