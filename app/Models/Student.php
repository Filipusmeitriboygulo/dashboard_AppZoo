<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'class_id',
        'study_program_id',
        'department_id',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function toeflScores()
    {
        return $this->hasMany(ToeflScore::class);
    }

    public function latestToeflScore()
    {
        return $this->hasOne(ToeflScore::class)->latestOfMany();
    }
}
