<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UploadLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ClusterResult extends Model
{
    //protected $table = 'cluster_result';
    protected $fillable = [
        'upload_id',
        'no',
        'nama',
        'nim',
        'prodi',
        'kelas',
        'listening',
        'structure',
        'reading',
        'total',
        'cluster',
        'membership_cluster1',
        'membership_cluster2',
        'membership_cluster3',
        'insight'
    ];

    public function upload(): BelongsTo
    {
        return $this->belongsTo(UploadLog::class, 'upload_id');
    }
}
