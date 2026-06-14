<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['user_id', 'total_harga', 'ongkir', 'kurir', 'resi', 'status_pembayaran', 'snap_token_midtrans'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function detail() {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
