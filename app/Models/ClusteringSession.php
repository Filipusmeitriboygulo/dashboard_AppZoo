<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusteringSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'session_name',
        'data_scope',
        'scope_id',
        'total_data',
        'num_clusters',
        'algorithm_params',
        'status',
        'processed_by',
    ];

    protected $casts = [
        'algorithm_params' => 'array',
    ];

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function results()
    {
        return $this->hasMany(ClusteringResult::class, 'batch_id', 'batch_id');
    }

    public function scopeEntity()
    {
        switch ($this->data_scope) {
            case 'class':
                return $this->belongsTo(ClassModel::class, 'scope_id');
            case 'study_program':
                return $this->belongsTo(StudyProgram::class, 'scope_id');
            case 'department':
                return $this->belongsTo(Department::class, 'scope_id');
            default:
                return null;
        }
    }
}
