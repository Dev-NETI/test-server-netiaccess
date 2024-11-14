<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Exports\PdeExport;
use App\Models\tblpde;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class GeneratePdeReportExcelComponent extends Component
{

   // GeneratePdeReportExcelComponent.php

   public function exportPdeExcel($datefrom, $dateto)
   {
       // Fetch records
    //    $pde_data = tblpde::whereBetween('certdateprinted', [$datefrom, $dateto])
    //        ->join('tblrank', 'tblpde.position', '=', 'tblrank.rank')
    //        ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblrank.rankdepartmentid')
    //        ->join('tblcompany', 'tblcompany.companyid', '=', 'tblpde.companyid')
    //        ->where('tblpde.deletedid', 0)
    //        ->get();


        $pde_data = tblpde::whereBetween('certdateprinted', [$datefrom, $dateto])
               ->where('deletedid', 0)
               ->get();

       $record_count = $pde_data->count();

       $pde_records = [];
   
       foreach ($pde_data as $record) {
           $pde_records[] = [
               'pdecertificatenumber' => $record->certificatenumber,
            //    'pdecrewname' => $record->rankacronym . ' ' . $record->givenname . ' ' . $record->middlename . ' ' . $record->surname,
               'pdecrewname' => $record->givenname . ' ' . $record->middlename . ' ' . $record->surname,
               'position' => $record->position,
               'certdateprinted' => $record->certdateprinted,
               'passportno' => $record->passportno,
               'pdepassportno' => $record->passportno,
           ];
       }

     
   
       // Pass data directly to the export class
       return Excel::download(new PdeExport($pde_records,$record_count,$datefrom, $dateto), 'generated_pde_annex1.xlsx');
   }
   


    public function render()
    {
        return view('livewire.admin.pde.generate-pde-report-excel-component');
    }
}
