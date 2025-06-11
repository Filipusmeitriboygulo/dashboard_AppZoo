<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'study_program_id',
        'name',
        'academic_year',
        'semester',
    ];

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' - ' . $this->academic_year . ' Semester ' . $this->semester;
    }
}
