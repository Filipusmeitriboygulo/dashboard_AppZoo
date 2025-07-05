<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use App\Models\Department;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void

    {
       
        $te = Department::where('code', 'T.Elektro')->first();
        $tm = Department::where('code', 'T.Mesin')->first();
        $ts = Department::where('code', 'T.Sipil')->first();
        $tk = Department::where('code', 'T.Kimia')->first();
        $bs = Department::where('code', 'BS')->first();
        $tik = Department::where('code', 'TIK')->first();

        $studyPrograms = [
            // Teknik Elektro
            
            ['department_id' => $te->id, 'name' => 'Teknologi Elektronika', 'code' => 'TEL'],
            ['department_id' => $te->id, 'name' => 'Teknologi Listrik', 'code' => 'TL'],
            ['department_id' => $te->id, 'name' => 'Teknologi Telekomunikasi', 'code' => 'T.Telkom'],
            ['department_id' => $te->id, 'name' => 'Teknologi Rekayasa Jaringan Telekomunikasi', 'code' => 'TRJT'],
            ['department_id' => $te->id, 'name' => 'Teknologi Rekayasa Pembangkit Energi', 'code' => 'TRPE'],
            ['department_id' => $te->id, 'name' => 'Teknologi Rekayasa Instrumentasi da Kontrol', 'code' => 'TRIK'],
            ['department_id' => $te->id, 'name' => 'Teknologi Rekayasa Manufaktur', 'code' => 'TRMT'],

            // Teknik Mesin
            ['department_id' => $tm->id, 'name' => 'Teknologi Mesin', 'code' => 'TM'],
            ['department_id' => $tm->id, 'name' => 'Teknologi Rekayasa Manufaktur', 'code' => 'TRM'],
            ['department_id' => $tm->id, 'name' => 'Teknologi Industri', 'code' => 'T.Industri'],
            ['department_id' => $tm->id, 'name' => 'Teknologi Rekayasa Pengelasan Dan Fabrikasi', 'code' => 'TRPF'],

            // Teknik Sipil
            ['department_id' => $ts->id, 'name' => 'Teknologi Rekayasa Konstruksi Jalan dan Jembatan', 'code' => 'TRKJJ'],
            ['department_id' => $ts->id, 'name' => 'Teknologi Konstursi Bangunan Gedung', 'code' => 'TKBG'],
            ['department_id' => $ts->id, 'name' => 'Teknologi Konstursi Bangunan Air', 'code' => 'TKBA'],
            ['department_id' => $ts->id, 'name' => 'Teknologi Konstruksi Jalan dan Jembatan', 'code' => 'TKJJ'],

            // Teknik Kimia
            ['department_id' => $tk->id, 'name' => 'Teknologi Pengolahan Minyak dan Gas', 'code' => 'MIGAS'],
            ['department_id' => $tk->id, 'name' => 'Teknologi Rekayasa Kimia Industri', 'code' => 'TRKI'],
            ['department_id' => $tk->id, 'name' => 'Teknologi Kimia', 'code' => 'TK'],


            //  Bisnis
            ['department_id' => $bs->id, 'name' => 'Adm.Bisnis', 'code' => 'ADB'],
            ['department_id' => $bs->id, 'name' => 'Akuntasi Lembaga Keuangan Syariah', 'code' => 'ALKS'],
            ['department_id' => $bs->id, 'name' => 'Akuntasi Sektor Publik', 'code' => 'ASP'],
            ['department_id' => $bs->id, 'name' => 'Akuntasi', 'code' => 'AK'],
            ['department_id' => $bs->id, 'name' => 'Manajemen Keuangan Sektor Publik', 'code' => 'MKSP'],

            // TIK
            ['department_id' => $tik->id, 'name' => 'Teknik Informatika', 'code' => 'TI'],
            ['department_id' => $tik->id, 'name' => 'Teknologi Rekayasa Komputer dan Jaringan ', 'code' => 'TRKJ'],
            ['department_id' => $tik->id, 'name' => 'Teknologi Rekayasa Multimedia', 'code' => 'TRMM'],
        ];

        foreach ($studyPrograms as $program) {
            StudyProgram::create($program);
        }
    }
}
