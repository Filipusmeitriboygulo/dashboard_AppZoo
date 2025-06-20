<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    // Jika ingin mass-assignment kolom, tambahkan fillable:
   // app/Models/Score.php
protected $fillable = [
    'toefl_file_id',
    'nama',
    'nim_mahasiswa',
    'jurusan',
    'prodi',
    'kelas',
    'listening_score',
    'structure_score',
    'reading_score',
    'listening',
    'structure',
    'reading',
    'total'
];


    // Jika nama tabel bukan 'scores', tambahkan:
    protected $table = 'score'; // karena kamu pakai 'score' (bukan jamak)
}
