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
        'membership_cluster1',
        'membership_cluster2',
        'membership_cluster3',
        'insight'
    ];

    protected $casts = [
        'membership_cluster1' => 'float',
        'membership_cluster2' => 'float',
        'membership_cluster3' => 'float',
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
