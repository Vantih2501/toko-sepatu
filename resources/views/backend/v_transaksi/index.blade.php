@extends('backend.v_layouts.app')
@section('content')
<style>
  .order-container { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
  .filter-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f0f0f0; }
  .filter-btn { padding: 8px 18px; border-radius: 20px; border: 1px solid #e5e7eb; background: #fff; font-size: 13px; font-weight: 500; color: #374151; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all .2s; text-decoration: none; }
  .filter-btn:hover { border-color: #29362C; color: #29362C; }
  .filter-btn.active { background: #29362C; color: #fff; border-color: #29362C; font-weight: 600; }
  .btn-export { margin-left: auto; padding: 8px 18px; border-radius: 20px; border: 1px solid #e5e7eb; background: #fff; font-size: 13px; font-weight: 500; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 6px; text-decoration: none; transition: all .2s; }
  .btn-export:hover { border-color: #29362C; color: #29362C; }

  .order-table { width: 100%; border-collapse: separate; border-spacing: 0; }
  .order-table thead th { font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; padding: 12px 16px; border-bottom: 1px solid #f0f0f0; text-align: left; white-space: nowrap; }
  .order-table thead th .sort-icon { display: inline-block; vertical-align: middle; margin-left: 4px; color: #d1d5db; }
  .order-table tbody tr { transition: background .15s; }
  .order-table tbody tr:hover { background: #f9fafb; }
  .order-table tbody td { padding: 14px 16px; border-bottom: 1px solid #f5f5f5; vertical-align: middle; font-size: 14px; color: #374151; }

  .product-cell { display: flex; align-items: center; gap: 12px; }
  .product-cell img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; background: #f3f4f6; }
  .product-cell .product-info { display: flex; flex-direction: column; }
  .product-cell .product-name { font-weight: 600; font-size: 14px; color: #111827; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .product-cell .product-qty { font-size: 12px; color: #9ca3af; }

  .badge-status { padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; text-transform: capitalize; }
  .badge-sukses { background: #dcfce7; color: #16a34a; }
  .badge-pending { background: #fef9c3; color: #ca8a04; }

  .action-btn { background: none; border: none; cursor: pointer; padding: 4px; color: #9ca3af; transition: color .15s; }
  .action-btn:hover { color: #374151; }
  .action-dropdown { position: relative; display: inline-block; }
  .action-dropdown-menu { display: none; position: absolute; right: 0; top: 100%; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,.1); min-width: 150px; z-index: 10; overflow: hidden; }
  .action-dropdown-menu.show { display: block; }
  .action-dropdown-menu button, .action-dropdown-menu a { display: block; width: 100%; padding: 8px 16px; text-align: left; font-size: 13px; color: #374151; background: none; border: none; cursor: pointer; text-decoration: none; }
  .action-dropdown-menu button:hover, .action-dropdown-menu a:hover { background: #f3f4f6; }
  .action-dropdown-menu .text-danger { color: #ef4444; }
</style>

<div class="row mt-3">
  <div class="col-12">
    <div class="order-container">
      <form method="GET" action="{{ route('backend.transaksi.index') }}" class="filter-bar">
        <!-- Date filter start -->
        <div class="position-relative">
          <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="d-none" onchange="this.form.submit()">
          <button type="button" class="filter-btn" onclick="document.getElementById('start_date').showPicker()">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Starting Date' }}
          </button>
        </div>

        <a href="{{ route('backend.transaksi.index') }}" class="filter-btn {{ !request('status') && !request('start_date') && !request('end_date') ? 'active' : '' }}">
          All
        </a>

        <!-- Date filter end -->
        <div class="position-relative">
          <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="d-none" onchange="this.form.submit()">
          <button type="button" class="filter-btn" onclick="document.getElementById('end_date').showPicker()">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Ending Date' }}
          </button>
        </div>

        <!-- Status filter -->
        <div class="position-relative">
          <select name="status" class="filter-btn text-start appearance-none pr-6" onchange="this.form.submit()" style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 4 5\'%3E%3Cpath fill=\'%23374151\' d=\'M2 0L0 2h4zm0 5L0 3h4z\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 8px 10px;">
            <option value="">Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="sukses" {{ request('status') === 'sukses' ? 'selected' : '' }}>Sukses</option>
          </select>
        </div>

        <!-- Export (using printing PDF) -->
        <button type="submit" form="cetak-transaksi-form" class="btn-export">
          <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          Export
        </button>
      </form>

      <!-- Separate form for PDF print -->
      <form id="cetak-transaksi-form" method="POST" action="{{ route('backend.laporan.cetaktransaksi') }}" target="_blank" class="d-none">
        @csrf
        <input type="hidden" name="tanggal_awal" value="{{ request('start_date') ?? now()->subMonths(3)->format('Y-m-d') }}">
        <input type="hidden" name="tanggal_akhir" value="{{ request('end_date') ?? now()->format('Y-m-d') }}">
      </form>

      <table class="order-table">
        <thead>
          <tr>
            <th>Product <span class="sort-icon">⇅</span></th>
            <th>Customer <span class="sort-icon">⇅</span></th>
            <th>Courier <span class="sort-icon">⇅</span></th>
            <th>Shipping Cost <span class="sort-icon">⇅</span></th>
            <th>Total Price <span class="sort-icon">⇅</span></th>
            <th>Date <span class="sort-icon">⇅</span></th>
            <th>Status <span class="sort-icon">⇅</span></th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($transaksi as $t)
          @php
            $firstDetail = $t->detail->first();
            $product = $firstDetail->produk ?? null;
            $itemsCount = $t->detail->count();
          @endphp
          <tr>
            <td>
              <div class="product-cell">
                @if($product && $product->foto)
                  <img src="{{ asset('storage/img-produk/' . $product->foto) }}" alt="">
                @else
                  <img src="{{ asset('storage/img-produk/img-default.jpg') }}" alt="">
                @endif
                <div class="product-info">
                  <span class="product-name">
                    {{ $product->nama_produk ?? '-' }}
                    @if($itemsCount > 1)
                      <span class="text-xs text-[#29362C] font-black">({{ $itemsCount }} items)</span>
                    @endif
                  </span>
                  <span class="product-qty">Qty: {{ $firstDetail->qty ?? 0 }} @if($firstDetail && $firstDetail->ukuran_sepatu) (Size: {{ $firstDetail->ukuran_sepatu }}) @endif</span>
                </div>
              </div>
            </td>
            <td>{{ $t->user->nama ?? '-' }}</td>
            <td><span class="uppercase font-bold">{{ $t->kurir ?? '-' }}</span> @if($t->resi) <br><span class="text-xs text-gray-400">Resi: {{ $t->resi }}</span> @endif</td>
            <td>Rp. {{ number_format($t->ongkir, 0, '.', '.') }}</td>
            <td>Rp. {{ number_format($t->total_harga, 0, '.', '.') }}</td>
            <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
            <td>
              <span class="badge-status badge-{{ strtolower($t->status_pembayaran) }}">{{ $t->status_pembayaran }}</span>
            </td>
            <td>
              <div class="action-dropdown">
                <button class="action-btn" onclick="toggleDropdown(this)">
                  <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                </button>
                <div class="action-dropdown-menu">
                  @if($t->status_pembayaran === 'pending')
                  <form action="{{ route('backend.transaksi.updateStatus', $t->id) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="status_pembayaran" value="sukses">
                    <button type="submit">✅ Set Lunas</button>
                  </form>
                  @endif
                  <button type="button" onclick="inputResi({{ $t->id }}, '{{ $t->resi }}')">📦 Input Resi</button>
                  <form action="{{ route('backend.transaksi.destroy', $t->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-danger" onclick="return confirm('Hapus transaksi ini?')">🗑 Hapus</button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 40px; color: #9ca3af;">Belum ada data transaksi.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function toggleDropdown(btn) {
    document.querySelectorAll('.action-dropdown-menu.show').forEach(el => {
      if (el !== btn.nextElementSibling) el.classList.remove('show');
    });
    btn.nextElementSibling.classList.toggle('show');
  }
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-dropdown')) {
      document.querySelectorAll('.action-dropdown-menu.show').forEach(el => el.classList.remove('show'));
    }
  });

  function inputResi(id, currentResi) {
    const resi = prompt("Masukkan nomor resi pengiriman:", currentResi || "");
    if (resi !== null) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/backend/transaksi/${id}/resi`;
      
      const csrf = document.createElement('input');
      csrf.type = 'hidden';
      csrf.name = '_token';
      csrf.value = '{{ csrf_token() }}';
      form.appendChild(csrf);

      const method = document.createElement('input');
      method.type = 'hidden';
      method.name = '_method';
      method.value = 'PUT';
      form.appendChild(method);

      const resiInput = document.createElement('input');
      resiInput.type = 'hidden';
      resiInput.name = 'resi';
      resiInput.value = resi;
      form.appendChild(resiInput);

      document.body.appendChild(form);
      form.submit();
    }
  }
</script>
@endsection
