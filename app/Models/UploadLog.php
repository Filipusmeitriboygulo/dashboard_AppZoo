<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ToeflScoreEntry;
use App\Models\ClusterResult;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UploadLog extends Model
{
    //
    protected $table = 'upload_log';
    protected $fillable = [
        'user_id',
        'file_name',
        'cakupan',
        'unit_nama',
        'waktu_upload',
        'status_klasterisasi',
        'jumlah_data'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toeflScores(): HasMany
    {
        return $this->hasMany(ToeflScoreEntry::class, 'upload_id');
    }

    public function clusterResults(): HasMany
    {
        return $this->hasMany(ClusterResult::class, 'upload_id');
    }
}
