<?php

namespace App\Traits;

use App\Models\tblcompanycourse;
use DateTime;
use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\IOFactory;

trait DailyWeeklyReportTraits
{
    //LOCAL
    public function getTranspoPrice($courseid, $companyid)
    {

        $transpoRate = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();

        if (!empty($transpoRate)) {
            // dd($transpoRate);
            $txtcurrency = $transpoRate->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            $columnname = 'transpo_fee_' . $txt;
            $data = [
                'currency' => $currency,
                'rate' => $transpoRate->$columnname
            ];

            return $data['currency'] . ' ' . $data['rate'];
        } else {
            $data = [
                'currency' => 'No Price Indicated',
                'rate' => '0'
            ];
            return $data['currency'];
        }
    }

    public function getRoomPrice($courseid, $companyid, $dormType)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();
        if (!empty($roomPrice)) {
            // dd($roomPrice);
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            switch ($dormType) {
                case 3:
                    $concat = '2s_price_';
                    break;

                case 4:
                    $concat = '4s_price_';
                    break;

                default:
                    $concat = 'price_';
                    break;
            }

            $columnname = 'dorm_' . $concat . '' . $txt;
            return $currency . ' ' . $roomPrice->$columnname;
        } else {
            return 'No price indicated';
        }
    }

    public function totalDormPrice($courseid, $companyid, $dormid, $counteddays)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();
        if (!empty($roomPrice)) {
            // dd($roomPrice);
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            switch ($dormid) {
                case 3:
                    $concat = '2s_price_';
                    break;

                case 4:
                    $concat = '4s_price_';
                    break;

                default:
                    $concat = 'price_';
                    break;
            }

            $columnname = 'dorm_' . $concat . '' . $txt;
            return $currency . ' ' . $roomPrice->$columnname * $counteddays;
        } else {
            return 'No price indicated';
        }
    }

    public function getoverallMealPHP($courseid, $companyid, $counteddays)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();

        if (!empty($roomPrice)) {
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            $columnname = 'meal_price_' . $txt;

            if ($txtcurrency == 0) {
                return $roomPrice->$columnname * $counteddays;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    //WEEKLY EXCEL EXPORT
    public function exportExcel()
    {
        $datefrom = new DateTime(session('datefrom'));
        $dateto = new DateTime(session('dateto'));
        // dd($datefrom, $dateto);
        $filePath = public_path('dormitorytemplates/DailyWeeklyReport.xlsx');
        $spreadsheet = IOFactory::load($filePath);

        $sheet = $spreadsheet->getActiveSheet();
        $index = 2;

        foreach ($this->arrayWeekly as $key => $value) {
            $sheet->setCellValue('A' . $index, $value['id']);
            $sheet->setCellValue('B' . $index, $value['roomtype']);
            $sheet->setCellValue('C' . $index, $value['roomname']);
            $sheet->setCellValue('D' . $index, $value['trainee']);
            $sheet->setCellValue('E' . $index, $value['company']);
            $sheet->setCellValue('F' . $index, $value['paymentmode']);
            $sheet->setCellValue('G' . $index, $value['rank']);
            $sheet->setCellValue('H' . $index, $value['course']);
            $sheet->setCellValue('I' . $index, $value['schedule']);
            $sheet->setCellValue('J' . $index, $value['days']);
            $sheet->setCellValue('K' . $index, $value['roomprice']);
            $sheet->setCellValue('L' . $index, $value['mealprice']);
            $sheet->setCellValue('M' . $index, $value['checkindate']);
            $sheet->setCellValue('N' . $index, $value['checkoutdate']);
            $sheet->setCellValue('O' . $index, $value['dormstatus']);
            $sheet->setCellValue('P' . $index, $value['totaldorm']);
            $sheet->setCellValue('Q' . $index, $value['totalmeal']);
            $index++;
        }

        $sheet->setCellValue('U7', $this->overallTotalMealUSD);
        $sheet->setCellValue('U8', $this->overallTotalDormUSD);
        $sheet->setCellValue('U9', $this->overallTotalMealUSD + $this->overallTotalDormUSD);
        $sheet->setCellValue('U13', $this->overallTotalMealPHP);
        $sheet->setCellValue('U14', $this->overallTotalDormPHP);
        $sheet->setCellValue('U15', $this->overallTotalMealPHP + $this->overallTotalDormPHP);

        $tempFilePath = storage_path('app/public/Dormitory-Weekly-Report(' . $datefrom->format('F d') . ' - ' . $dateto->format('F d, Y') . ').xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFilePath);

        return response()->download($tempFilePath)->deleteFileAfterSend(true);
    }

    public function getoverallMealUSD($courseid, $companyid, $counteddays)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();

        if (!empty($roomPrice)) {
            // dd($roomPrice);
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            $columnname = 'meal_price_' . $txt;

            if ($txtcurrency == 1) {
                return $roomPrice->$columnname * $counteddays;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getoverallDormUSD($courseid, $companyid, $dormid, $counteddays)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();
        if (!empty($roomPrice)) {
            // dd($roomPrice);
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            switch ($dormid) {
                case 3:
                    $concat = '2s_price_';
                    break;

                case 4:
                    $concat = '4s_price_';
                    break;

                default:
                    $concat = 'price_';
                    break;
            }

            $columnname = 'dorm_' . $concat . '' . $txt;
            if ($txtcurrency == 1) {
                return $roomPrice->$columnname * $counteddays;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getoverallDormPHP($courseid, $companyid, $dormid, $counteddays)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();
        if (!empty($roomPrice)) {
            // dd($roomPrice);
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            switch ($dormid) {
                case 3:
                    $concat = '2s_price_';
                    break;

                case 4:
                    $concat = '4s_price_';
                    break;

                default:
                    $concat = 'price_';
                    break;
            }

            $columnname = 'dorm_' . $concat . '' . $txt;
            if ($txtcurrency == 0) {
                return $roomPrice->$columnname * $counteddays;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function extractweeklydata($data)
    {
        $weeklydata = [];
        $start = 1;
        try {
            foreach ($data as $key => $value) {
                if ($value->enroled->deletedid == 0) {
                    $counteddays = $this->weeklyDaysCounter($value->checkindate, $value->checkoutdate ==
                        NULL ?
                        $value->enroled->schedule->enddateformat : $value->checkoutdate);

                    $this->overallTotalDormUSD += $this->getoverallDormUSD(
                        $value->enroled->courseid,
                        $value->enroled->trainee->company_id,
                        $value->enroled->dormid,
                        $counteddays
                    );

                    $this->overallTotalDormPHP += $this->getoverallDormPHP(
                        $value->enroled->courseid,
                        $value->enroled->trainee->company_id,
                        $value->enroled->dormid,
                        $counteddays
                    );

                    $this->overallTotalMealUSD += $this->getoverallMealUSD(
                        $value->enroled->courseid,
                        $value->enroled->trainee->company_id,
                        $counteddays
                    );

                    $this->overallTotalMealPHP += $this->getoverallMealPHP(
                        $value->enroled->courseid,
                        $value->enroled->trainee->company_id,
                        $counteddays
                    );

                    $weeklydata[$key] = [
                        'deletedid' => $value->enroled->deletedid,
                        'x' => $start,
                        'id' => $value->id,
                        'roomtype' => $value->room->roomtype->roomtype,
                        'roomname' => $value->room->roomname,
                        'trainee' => $value->enroled->trainee->l_name . " " . $value->enroled->trainee->f_name,
                        'company' => $value->enroled->trainee->company->company,
                        'paymentmode' => $value->paymentmode->paymentmode,
                        'rank' => $value->enroled->trainee->rank->rankacronym . ' - ' . $value->enroled->trainee->rank->rank,
                        'course' => $value->enroled->course->coursecode . ' ' . $value->enroled->course->coursename,
                        'schedule' => $value->enroled->schedule->startdateformat . ' ' . $value->enroled->schedule->enddateformat,
                        'days' => $counteddays,
                        'roomprice' => $this->getRoomPrice(
                            $value->enroled->courseid,
                            $value->enroled->trainee->company_id,
                            $value->enroled->dormid
                        ),
                        'mealprice' => $this->getMealPrice(
                            $value->enroled->courseid,
                            $value->enroled->trainee->company_id,
                            $counteddays
                        ),
                        'checkindate' => $value->checkindate,
                        'checkoutdate' => $value->checkoutdate,
                        'dormstatus' => $value->enroled->reservationstatus->status,
                        'totaldorm' => $this->totalDormPrice(
                            $value->enroled->courseid,
                            $value->enroled->trainee->company_id,
                            $value->enroled->dormid,
                            $counteddays
                        ),
                        'totalmeal' => $this->totalMealPrice(
                            $value->enroled->courseid,
                            $value->enroled->trainee->company_id,
                            $counteddays
                        ),
                    ];
                }

                $start++;
            }
            return $weeklydata;
        } catch (\Exception $th) {
            $this->consoleLog($th->getMessage());
        }
    }

    public function totalMealPrice($courseid, $companyid, $counteddays)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();

        if (!empty($roomPrice)) {
            // dd($roomPrice);
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            $columnname = 'meal_price_' . $txt;

            return $currency . ' ' . $roomPrice->$columnname * $counteddays;
        } else {
            $data = [
                'currency' => 'No price indicated',
                'rate' => '0'
            ];
            return $data['currency'];
        }
    }

    public function getMealPrice($courseid, $companyid)
    {
        $roomPrice = tblcompanycourse::where('courseid', $courseid)->where('companyid', $companyid)->first();

        if (!empty($roomPrice)) {
            // dd($roomPrice);
            $txtcurrency = $roomPrice->default_currency;
            $currency = $txtcurrency == 1 ? 'USD' : 'PHP';
            $txt = $txtcurrency == 1 ? 'dollar' : 'peso';

            $columnname = 'meal_price_' . $txt;
            $data = [
                'currency' => $currency,
                'rate' => $roomPrice->$columnname
            ];

            return $data['currency'] . ' ' . $data['rate'];
        } else {
            $data = [
                'currency' => 'No Price Indicated',
                'rate' => '0'
            ];
            return $data['currency'];
        }
    }

    public function weeklyDaysCounter($checkindate, $checkoutdate)
    {
        $datefromweekly = new DateTimeImmutable($checkindate);
        $datetoweekly = new DateTimeImmutable($checkoutdate);
        $counteddays = 0;

        while ($datefromweekly <= $datetoweekly) {
            $datefromweekly = $datefromweekly->modify('+1 day');
            $counteddays++;
        }

        return $counteddays;
    }
}
