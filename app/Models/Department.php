<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'head_id',
    ];

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
