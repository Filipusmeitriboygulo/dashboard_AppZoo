<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UploadLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class ToeflScoreEntry extends Model
{
    //protected $table = 'toefl_score_entries';
    protected $fillable = [
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
}
