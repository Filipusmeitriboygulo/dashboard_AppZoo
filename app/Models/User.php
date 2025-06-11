<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'study_program_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function uploadedToeflScores()
    {
        return $this->hasMany(ToeflScore::class, 'uploaded_by');
    }

    public function clusteringSessions()
    {
        return $this->hasMany(ClusteringSession::class, 'processed_by');
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKepalaUPA()
    {
        return $this->role === 'kepala_upa';
    }

    public function isKetuaJurusan()
    {
        return $this->role === 'ketua_jurusan';
    }

    public function isKetuaProdi()
    {
        return $this->role === 'ketua_prodi';
    }

    public function isWakilDirektur()
    {
        return $this->role === 'wakil_direktur';
    }

    // Get accessible scope based on role
    public function getAccessibleScope()
    {
        switch ($this->role) {
            case 'admin':
            case 'kepala_upa':
            case 'wakil_direktur':
                return 'campus';
            case 'ketua_jurusan':
                return 'department';
            case 'ketua_prodi':
                return 'study_program';
            default:
                return null;
        }
    }
}
