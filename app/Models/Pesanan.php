<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = ['tanggal', 'jumlah', 'harga'];

    public function pembeli()
    {
        return $this->hasMany(Pembeli::class, 'id_pesanan');
    }
}

