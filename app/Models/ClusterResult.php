<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UploadLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ClusterResult extends Model
{
    protected $table = 'cluster_result';
    protected $fillable = [
        'toefl_score_entry_id', // Tambahkan relasi ke tabel utama
        'upload_id', // Tetap pertahankan untuk query langsung
        'cluster',
        'membership',
        'insight'
    ];

    protected $casts = [
        'membership' => 'array',
    ];


    public function toeflScoreEntry(): BelongsTo
    {
        return $this->belongsTo(ToeflScoreEntry::class);
    }


    public function upload(): BelongsTo
    {
        return $this->belongsTo(UploadLog::class, 'upload_id');
    }


}
