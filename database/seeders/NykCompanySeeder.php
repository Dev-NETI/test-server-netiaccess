<?php

namespace Database\Seeders;

use App\Models\tblnyksmcompany;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NykCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tblnyksmcompany::truncate();

        $companyid = [262, 115, 285, 286, 287, 289, 290, 89];

        foreach ($companyid as $key => $value) {
            tblnyksmcompany::create([
                'companyid' => $value
            ]);
        }
    }
}
