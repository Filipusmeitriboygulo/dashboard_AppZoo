<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusteringResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'toefl_score_id',
        'cluster_id',
        'membership_degree',
        'cluster_label',
        'algorithm_params',
        'processed_by',
    ];

    protected $casts = [
        'membership_degree' => 'decimal:8',
        'algorithm_params' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(ClusteringSession::class, 'batch_id', 'batch_id');
    }

    public function toeflScore()
    {
        return $this->belongsTo(ToeflScore::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
