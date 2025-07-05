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
            ['name' => 'Teknik Elektro', 'code' => 'T.Elektro'],
            ['name' => 'Teknik Mesin', 'code' => 'T.Mesin'],
            ['name' => 'Teknik Sipil', 'code' => 'T.Sipil'],
            ['name' => 'Teknik Kimia', 'code' => 'T.Kimia'],
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
