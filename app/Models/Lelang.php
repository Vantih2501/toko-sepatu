<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lelang extends Model
{
    protected $table = 'lelangs';
    protected $guarded = ['id'];

    protected $fillable = [
        'produk_id',
        'harga_awal',
        'tgl_mulai',
        'tgl_akhir',
        'status',
        'pemenang_id',
    ];

    protected $casts = [
        'tgl_mulai' => 'datetime',
        'tgl_akhir' => 'datetime',
        'harga_awal' => 'decimal:2',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pemenang()
    {
        return $this->belongsTo(User::class, 'pemenang_id');
    }

    public function bids()
    {
        return $this->hasMany(LelangBid::class);
    }

    public function highestBid()
    {
        return $this->hasOne(LelangBid::class)->orderBy('jumlah_bid', 'desc');
    }
}
