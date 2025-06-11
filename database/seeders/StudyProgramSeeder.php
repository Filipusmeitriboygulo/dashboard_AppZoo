<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use App\Models\Department;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void

    {
       
        $te = Department::where('code', 'TE')->first();
        $tm = Department::where('code', 'TM')->first();
        $ts = Department::where('code', 'TS')->first();
        $tk = Department::where('code', 'TK')->first();
        $bs = Department::where('code', 'BS')->first();
        $tik = Department::where('code', 'TIK')->first();

        $studyPrograms = [
            // Teknik Elektro
            
            ['department_id' => $te->id, 'name' => 'Teknologi Elektronika', 'code' => 'TEL'],
            ['department_id' => $te->id, 'name' => 'Teknologi Listrik', 'code' => 'TL'],

            // Teknik Mesin
            ['department_id' => $tm->id, 'name' => 'Teknologi Mesin', 'code' => 'TM'],
            ['department_id' => $tm->id, 'name' => 'Teknologi Rekayas Manufaktur', 'code' => 'TRMM'],

            // Teknik Sipil
            ['department_id' => $ts->id, 'name' => 'Teknologi Rekayasa Konstruksi Jalan dan Jembatan', 'code' => 'TRKJJ'],
            ['department_id' => $ts->id, 'name' => 'Teknologi Rekayasa Konstursi Bangunan dan Gedung', 'code' => 'TRBG'],

            // Teknik Kimia
            ['department_id' => $tk->id, 'name' => 'Teknologi Pengolahan Minyak dan Gas', 'code' => 'TPMG'],
          

            //  Bisnis
            ['department_id' => $bs->id, 'name' => 'Administrasi Bisnis', 'code' => 'ADB'],
    

            // TIK
            ['department_id' => $tik->id, 'name' => 'Teknik Informatika', 'code' => 'TI'],
        ];

        foreach ($studyPrograms as $program) {
            StudyProgram::create($program);
        }
    }
}
