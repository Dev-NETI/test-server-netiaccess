<?php

namespace Database\Seeders;

use App\Models\tblbillingstatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tblbillingstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tblbillingstatus::truncate();

        // Set the starting ID to 0
        DB::statement('ALTER TABLE tblbillingstatus AUTO_INCREMENT = 0');

        $status = [
            1 => ["Pending Statements Board","Manages all billing statements that are yet to be processed."],
            2 => ["Billing Statement Review Board","Facilitates the generation and review of billing statements by the billing staff."],
            3 => ["BOD Manager Review Board","Manages the review of billing statements by the BOD Manager."],
            4 => ["GM Review Board","Manages the review of billing statements by the General Manager."],
            5 => ["BOD Manager Dispatch Board","Facilitates the dispatch of billing statements to clients by the BOD Manager."],
            6 => ["Client Confirmation Board","Tracks the confirmation of billing statement receipt by clients."],
            7 => ["Proof of Payment Upload Board","Manages the upload of proof of payment by clients."],
            8 => ["Official Receipt Issuance Board","Handles the issuance of Official Receipts by the billing staff."],
            9 => ["Official Receipt Confirmation Board","Tracks the confirmation of official receipt by clients."],
            10 => ["Transaction Close Board","Tracks the confirmation of Official Receipt receipt by clients, officially closing the transaction."]
        ];

        foreach($status as $id => [$billingstatus,$description]){
                tblbillingstatus::create([
                    'billingstatus' => $billingstatus,
                    'description' => $description
                ]);
        }

        // 3 => ["Finance Review Board","Handles the review of billing statements by the finance staff."],
    }
}
