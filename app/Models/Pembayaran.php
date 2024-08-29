<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = ['status_pembayaran','total_harga','metode_pembayaran', 'id_pesanan' ];

    public function pesanan() {
        return $this->belongsTo(Pesanan::class);
    }
}