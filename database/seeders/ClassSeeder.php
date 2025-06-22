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
        $semesters = [1, 4];
        $hurufs = ['A', 'B', 'C', 'D', 'E'];

        foreach ($studyPrograms as $program) {

            foreach ($semesters as $semester) {
                foreach ($hurufs as $huruf) {
                    $className = $program->code . '-' . $semester . $huruf;

                    ClassModel::create([
                        'study_program_id' => $program->id,
                        'name' => $className,

                        'semester' => $semester,
                    ]);
                }
            }
        }
    }
}
