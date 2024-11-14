<?php

namespace Database\Seeders;

use App\Models\tblinstructorovertime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstructorOvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tblinstructorovertime::truncate();
        tblinstructorovertime::create([
            'userid' => 103,
            'workdate' => '2024-05-11',
            'datefiled' => '2024-05-11',
            'status' => 1,
            'is_approved' => 0,
            'approver' => 103,
            'overtime_start' => '15:35:34',
            'overtime_end' => '19:35:34'
        ]);

        
    }
}
