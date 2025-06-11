<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        Department::query()->delete();

        $departments = [
            ['name' => 'Teknik Elektro', 'code' => 'TE'],
            ['name' => 'Teknik Mesin', 'code' => 'TM'],
            ['name' => 'Teknik Sipil', 'code' => 'TS'],
            ['name' => 'Teknik Kimia', 'code' => 'TK'],
            ['name' => 'Bisnis', 'code' => 'BS'],
            ['name' => 'Teknologi Informasi dan Komputer', 'code' => 'TIK'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                ['name' => $department['name']]
            );
        }
    }
}
