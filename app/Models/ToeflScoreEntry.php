<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UploadLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ToeflScoreEntry extends Model
{
    protected $table = 'toefl_score_entries';
    protected $fillable = [
        'upload_id',
        'nim',
        'nama',
        'kelas',
        'prodi',
        'jurusan',
        'listening',
        'structure',
        'reading',
        'total_score',
        'upload_id'
    ];

    public function upload(): BelongsTo
    {
        return $this->belongsTo(UploadLog::class, 'upload_id');
    }

    public function clusterResult(): HasOne
    {
        return $this->hasOne(ClusterResult::class);
    }
}
