<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToeflScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_date',
        'listening_score',
        'structure_score',
        'reading_score',
        'total_score',
        'dataset_batch',
        'uploaded_by',
    ];

    protected $casts = [
        'test_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function clusteringResults()
    {
        return $this->hasMany(ClusteringResult::class);
    }

    public function getScoreGradeAttribute()
    {
        if ($this->total_score >= 550) return 'Tinggi';
        if ($this->total_score >= 450) return 'Sedang';
        return 'Rendah';
    }
}
