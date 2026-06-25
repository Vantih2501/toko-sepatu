<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LelangBid extends Model
{
    protected $table = 'lelang_bids';
    protected $guarded = ['id'];

    protected $fillable = [
        'lelang_id',
        'user_id',
        'jumlah_bid',
    ];

    protected $casts = [
        'jumlah_bid' => 'decimal:2',
    ];

    public function lelang()
    {
        return $this->belongsTo(Lelang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
