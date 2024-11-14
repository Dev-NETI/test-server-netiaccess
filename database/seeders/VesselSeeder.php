<?php

namespace Database\Seeders;

use App\Models\tblvessels;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VesselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tblvessels::truncate();

        $data = [
            'SG OCEAN',
            'GAS CAPRICORN',
            'TAKAROA SUN',
            'PACIFIC ENLIGHTEN',
            'LIBRA LEADER',
            'IKIGAI',
            'TOYA',
            'TAITAR NO. 4',
            'NBA MILLET',
            'GRACE EMILIA',
            'GRACE COSMOS',
            'CHALLENGE PRIME',
            'SHINSHU MARU',
            'NORTH STAR',
            'HESTIA LEADER',
            'NYK ROMULUS'
        ];

        foreach($data as $data){
            tblvessels::create([
                'vesselname' => $data
            ]);
        }
    }
}
