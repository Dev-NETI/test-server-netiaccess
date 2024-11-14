<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::truncate();

        $statuses = [
            "1" => "On Board",
            "2" => "On Vacation",
            "3" => "Earmark",
            "4" => "Inactive",
            "5" => "No Record",
            "6" => "Reserve Crew",
        ];

        foreach($statuses as $id => $status){
                Status::create([
                    'id' => $id,
                    'status' => $status
                ]);
        }

    }
}
