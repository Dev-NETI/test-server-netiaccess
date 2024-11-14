<?php

namespace App\Imports;

use App\Models\tblcourseschedule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TrainingSchedImport implements ToModel, WithHeadingRow
{
    
    public function model(array $row)
    {
        // Parse and format the dates using Carbon
        $batchno = $row['batchno'];
        $start = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['start']));
        $end = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['end']));
        $dateOnlineFrom = !empty($row['online_date_from']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['online_date_from'])) : null;
        $dateOnlineTo = !empty($row['online_date_to']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['online_date_to'])) : null;
        $dateOnSiteFrom = !empty($row['practical_date_from']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['practical_date_from'])) : null;
        $dateOnSiteTo = !empty($row['practical_date_to']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['practical_date_to'])) : null;


        try {
            return new tblcourseschedule([
                'batchno' => $batchno,
                'courseid' => Session::get('courseid'),
                'startdateformat' => $start,
                'enddateformat' => $end,
                'dateonlinefrom' => $dateOnlineFrom,
                'dateonlineto' => $dateOnlineTo,
                'dateonsitefrom' => $dateOnSiteFrom,
                'dateonsiteto' => $dateOnSiteTo,
            ]);
        } catch (\Exception $e) {
            // Handle the error, log, or notify the user
            // For example: report($e);
            return null; // Skip the row and continue importing
        }
    }
}
