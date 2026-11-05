<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        Semester::create([
            'semester_name' => 'الفصل الأول 2026-2027',
            'start_date' => '2026-10-01',
            'end_date' => '2027-02-01',
        ]);

        Semester::create([
            'semester_name' => 'الفصل الثاني 2026-2027',
            'start_date' => '2027-02-15',
            'end_date' => '2027-06-15',
        ]);
    }
}