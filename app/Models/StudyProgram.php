<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name',
        'code',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // public function head()
    // {
    //     return $this->belongsTo(User::class, 'head_id');
    // }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    // public function students()
    // {
    //     return $this->hasMany(Student::class);
    // }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
