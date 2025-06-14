<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $studyPrograms = StudyProgram::all();
        $academicYears = ['2022/2023', '2023/2024'];
        $semesters= [1, 2, 3, 4];

        foreach ($studyPrograms as $program) {
            foreach ($academicYears as $year) {
                foreach ($semesters as $semester) {
                    $className = $program->code . '-' . $semester . 'A' ;

                    ClassModel::create([
                        'study_program_id' => $program->id,
                        'name' => $className,
                        'academic_year' => $year,
                        'semester' => $semester,
                    ]);
                }
            }
        }
    }
}
