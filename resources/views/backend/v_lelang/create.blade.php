@extends('backend.v_layouts.app')
@section('content')
<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ $judul }}</h5>
        <form action="{{ route('backend.lelang.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <label>Produk</label>
            <select name="produk_id" class="form-control @error('produk_id') is-invalid @enderror">
              <option value="">-- Pilih Produk --</option>
              @foreach ($produk as $p)
                <option value="{{ $p->id }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                  {{ $p->nama_produk }} - Rp{{ number_format($p->harga, 0, ',', '.') }}
                </option>
              @endforeach
            </select>
            @error('produk_id')
              <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label>Harga Awal Lelang (Rp)</label>
            <input type="number" name="harga_awal" value="{{ old('harga_awal') }}"
              class="form-control @error('harga_awal') is-invalid @enderror" placeholder="25000">
            @error('harga_awal')
              <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label>Tanggal & Waktu Mulai</label>
            <input type="datetime-local" name="tgl_mulai" value="{{ old('tgl_mulai') }}"
              class="form-control @error('tgl_mulai') is-invalid @enderror">
            @error('tgl_mulai')
              <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label>Tanggal & Waktu Selesai</label>
            <input type="datetime-local" name="tgl_akhir" value="{{ old('tgl_akhir') }}"
              class="form-control @error('tgl_akhir') is-invalid @enderror">
            @error('tgl_akhir')
              <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="{{ route('backend.lelang.index') }}" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
