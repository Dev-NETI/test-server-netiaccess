<?php

namespace Database\Seeders;

use App\Models\tblfleet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tblfleet::truncate();

        $data = [
            "1"=>['FLEET 1','F1',1,0],
            "2"=>['FLEET 2','F2',1,0],
            "3"=>['FLEET 3','F3',1,0],
            "4"=>['FLEET 4','F4',1,0],
            "5"=>['FLEET 5','F5',1,0],
            "6"=>['FLEET 6','F6',1,0],
            "7"=>['FLEET 7','F7',1,0],
            "8"=>['FLEET 8','F8',1,0],
            "9"=>['FLEET 8','F8',1,0],
            "10"=>['NTMA','NTMA',0,0],
            "11"=>['CRUISE','CRUISE',1,0],
            "12"=>['DOLSHIP','DOLSHIP',1,0],
            "13"=>['TECHNICAL','TECHNICAL',1,0],
            "14"=>['OJT MARITIME OIC',NULL,1,0],
            "15"=>['OJT GALLEY OIC',NULL,1,0],
            "16"=>['N/A',NULL,0,205],
            "17"=>['NTMA-NETI',NULL,0,0],
            "18"=>['Technical (DRY)',NULL,0,0],
            "19"=>['Technical (LIQUID)',NULL,0,0],
            "20"=>['FLEET A1','FA1',1,0],
            "21"=>['FLEET A2','FA2',1,0],
            "22"=>['FLEET A3','FA3',1,0],
            "23"=>['FLEET B1','FB1',1,0],
            "24"=>['FLEET B2','FB2',1,0],
            "25"=>['FLEET B3','FB3',1,0],
            "26"=>['FLEET C1','FC1',1,0],
            "27"=>['FLEET C2','FC2',1,0],
            "28"=>['FLEET C3','FC3',1,0],
            "29"=>['FLEET D1','FD1',1,0],
            "30"=>['FLEET D2','FD2',1,0],
            "31"=>['FLEET D3','FD3',1,0],
            "32"=>['FLEET E1','FE1',1,0],
            "33"=>['FLEET E2','FE2',1,0],
            "34"=>['FLEET E3','FE3',1,0],
            "35"=>['FLEET F1','FD3',1,0],
           
        ];

        foreach ($data as $index=>[$fleet,$fleetcode,$deletedid,$pdecertnumber] )
         {
            tblfleet::create([
                'fleetid'=>$index,
                'fleet'=> $fleet,
                'fleetcode'=> $fleetcode,
                'deletedid' =>$deletedid,
                'pdecertnumber' => $pdecertnumber
            ]);
        }
    }
}
