<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToeflFile extends Model
{
    use HasFactory;

    protected $table = 'toefl_files';

    protected $fillable = [
        'file_name',
        'file_path',
        'uploaded_at',
    ];

    // Relasi jika ingin ambil semua scores dari file ini:
    public function scores()
    {
        return $this->hasMany(Score::class, 'toefl_file_id');
    }
}
