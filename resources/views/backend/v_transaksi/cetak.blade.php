<style>
  table {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #ccc;
  }

  table td, table th {
    padding: 6px;
    font-weight: normal;
    border: 1px solid #ccc;
  }

  table th {
    font-weight: bold;
    background-color: #f4f4f4;
  }
</style>

<table>
  <tr>
    <td align="left" style="border: none;">
      Perihal : {{ $judul }} <br>
      Tanggal Awal : {{ $tanggalAwal }} s.d Tanggal Akhir {{ $tanggalAkhir }}
    </td>
  </tr>
</table>

<p></p>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Order ID</th>
      <th>Pelanggan</th>
      <th>Total Harga</th>
      <th>Ongkir</th>
      <th>Status Pembayaran</th>
      <th>Tanggal Transaksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($cetak as $row)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $row->id }}</td>
        <td>{{ $row->user->nama ?? '-' }}</td>
        <td>Rp{{ number_format($row->total_harga, 0, ',', '.') }}</td>
        <td>Rp{{ number_format($row->ongkir, 0, ',', '.') }}</td>
        <td>{{ ucfirst($row->status_pembayaran) }}</td>
        <td>{{ $row->created_at->format('d M Y H:i') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
