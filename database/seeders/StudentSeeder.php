<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::query()->delete();
        $faker = Faker::create('id_ID');
        $classes = ClassModel::with('studyProgram.department')->get();

        foreach ($classes as $class) {
            // Generate 20-30 students per class
            $studentCount = rand(20, 30);

            for ($i = 1; $i <= $studentCount; $i++) {
                $nim = $class->studyProgram->code .
                    substr($class->academic_year, 2, 2) .
                    str_pad($i, 3, '0', STR_PAD_LEFT);

                Student::updateOrCreate(
                    ['student_id' => $nim], // Cek unik berdasarkan student_id
                    [
                        'name' => $faker->name,
                        'class_id' => $class->id,
                        'study_program_id' => $class->study_program_id,
                        'department_id' => $class->studyProgram->department_id,
                    ]
                );
            }
        }
    }
}
