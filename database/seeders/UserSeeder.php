<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    
    {
        User::query()->delete();
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@politeknik.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Kepala UPA Bahasa
        User::create([
            'name' => 'Dr. Kepala UPA Bahasa',
            'email' => 'kepala.upa@politeknik.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'kepala_upa',
            'is_active' => true,
        ]);

        // Ketua Jurusan
        $departments = Department::all();
        foreach ($departments as $department) {
            User::create([
                'name' => 'Ketua Jurusan ' . $department->name,
                'email' => 'ketua.' . strtolower(str_replace(' ', '', $department->code)) . '@politeknik.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'ketua_jurusan',
                'department_id' => $department->id,
                'is_active' => true,
            ]);
        }

        // Ketua Program Studi
        $studyPrograms = StudyProgram::all();
        foreach ($studyPrograms->take(5) as $program) { // Ambil 5 saja untuk contoh
            User::create([
                'name' => 'Ketua Prodi ' . $program->name,
                'email' => 'kaprodi.' . strtolower(str_replace(' ', '', $program->code)) . '@politeknik.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'ketua_prodi',
                'department_id' => $program->department_id,
                'study_program_id' => $program->id,
                'is_active' => true,
            ]);
        }

        // Wakil Direktur
        User::create([
            'name' => 'Dr. Wakil Direktur Bidang Akademik',
            'email' => 'wadir.akademik@politeknik.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'wakil_direktur',
            'is_active' => true,
        ]);
    }
}
