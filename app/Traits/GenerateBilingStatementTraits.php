<?php

namespace App\Traits;

use App\Models\billingserialnumber;
use App\Models\ClientInformation;
use App\Models\tblbillingstatement;
use App\Models\tblbillingstatementcomments;
use App\Models\tblbus;
use App\Models\tblbusmonitoring;
use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblcourseschedule;
use App\Models\tbldormitoryreservation;
use App\Models\tblenroled;
use App\Models\tblforeignrate;
use App\Models\tblmealmonitoring;
use App\Models\tblnyksmcompany;
use App\Models\tbltransferbilling;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

trait GenerateBilingStatementTraits
{
    public $DMTTotal;
    public $DMT;
    public $totaldorm;
    public $daysonsite;
    public $foreignrate;


    public $trainees;
    public $counttrainees;
    public $trainees2;
    public $totaltrainingfee;
    public $DMTtotal;
    public $dataDormDMT;
    public $concat;
    public $mealrate;
    public $companycourseinfo;
    public $roomtype;
    public $meal;
    public $bus;
    public $dorm;
    public $tfRateorig;
    public $vatvalue;
    public $totalbus;
    public $totalmeal;
    public $totalwVAT;
    public $totalVAT;
    public $totalwvatall;
    public $totalwoVAT;
    public $totalwovatall;
    public $traineeswVessel;
    public $traineeswVessel2;
    public $total;


    public $scheduledat;
    public $days;

    public function computedormforeign($enroledid, $foreignrateid)
    {
        $enroleddata = tblenroled::find($enroledid);
        $dateonsitefrom = \Carbon\Carbon::parse($enroleddata->schedule->dateonsitefrom);
        $dateonsiteto = \Carbon\Carbon::parse($enroleddata->schedule->dateonsiteto);

        $daysonsite = ($dateonsiteto->diffInDays($dateonsitefrom)) + 1;
        $this->foreignrate = tblforeignrate::find($foreignrateid);

        $dorm = 0;

        // if ($enroledid == 77745) {
        // dd($daysonsite);
        // }

        $dataDorm = tbldormitoryreservation::where('enroledid', $enroledid)
            ->where('is_reserved', '!=', 1)
            ->whereNotNull('dateto')
            ->get();
        foreach ($dataDorm as $key => $dormItem) {
            $checkinTime = date('a', strtotime($dormItem->checkintime));
            $checkoutTime = date('a', strtotime($dormItem->checkouttime));

            $addDormCIRate = $checkinTime == 'am' ? $this->foreignrate->dorm_am_checkin : $this->foreignrate->dorm_pm_checkin;
            $addDormCORate = $checkoutTime == 'am' ? $this->foreignrate->dorm_am_checkout : $this->foreignrate->dorm_pm_checkout;

            if ($dormItem->checkoutdate == $dateonsiteto->format('Y-m-d')) {
                $addDormCORate = 0;
            }

            $dorm += ($daysonsite * $this->foreignrate->dorm_rate) + ($addDormCIRate + $addDormCORate);
        }

        $this->dorm = $dorm;
        $this->DMTTotal += $dorm;
        $this->DMT += $dorm;
        $this->totaldorm += $dorm;
        $this->daysonsite = $daysonsite;
    }

    public function computemealforeign($enroledid, $daysonsite, $DMT, $DMTTotal, $foreignrate)
    {
        try {
            $mealdata = tblmealmonitoring::where('enroledid', $enroledid)->where('deletedid', 0)->get();
            $mealToAdd = 0;
            // dd($mealdata->count());
            foreach ($mealdata as $item) {
                if ($item->mealtype == 1) {
                    $mealToAdd += $foreignrate->bf_rate;
                } elseif ($item->mealtype == 2) {
                    $mealToAdd += $foreignrate->lh_rate;
                } else {
                    $mealToAdd += $foreignrate->dn_rate;
                }
            }

            $mealrate = ($foreignrate->meal_rate * $daysonsite) + $mealToAdd;
            $this->mealrate = $mealrate;
            $this->DMTTotal += $mealrate;
            $this->totalmeal += $mealrate;
            $this->DMT += $mealrate;
        } catch (\Exception $th) {
            $this->consoleLog($th->getMessage());
        }
    }

    public function computetranspoforeign($enroledid, $DMT, $DMTTotal, $foreignrate)
    {
        $busdata = tblbusmonitoring::where('enroledid', $enroledid)->where('deletedid', 0)->get();
        $busToAdd = 0;
        foreach ($busdata as $data) {
            $transpo = $foreignrate->transpo;
            $busToAdd += ($transpo / $data->divideto);
        }

        $this->bus = $busToAdd;
        $this->totalbus += $this->bus;
        $this->DMT += $this->bus;
        $this->DMTTotal += $this->bus;
    }

    //--------------------------------------------//
    //-----------------functionsss----------------//
    //--------------------------------------------//

    public function computedorm($enroledid)
    {
        try {
            $data = tbldormitoryreservation::where('enroledid', $enroledid)
                ->where('dateto', '!=', '0000-00-00')
                ->where('is_reserved', '!=', 1)
                ->whereNotNull('dateto')
                ->get();

            $enroleddata = tblenroled::where('enroledid', $enroledid)->first();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        switch ($enroleddata->dormid) {
            case 3:
                $dorm_col_name = "dorm_2s_price_" . $this->concat;

                break;

            case 4:
                $dorm_col_name = "dorm_4s_price_" . $this->concat;

                break;

            default:
                $dorm_col_name = "dorm_price_" . $this->concat;
                break;
        }

        $days = 0;
        $countdata = count($data);

        if ($countdata > 0) {

            $companyid = $enroleddata->trainee->company->companyid;

            if ($companyid == 1) {
                foreach ($data as $reservation) {
                    $this->roomtype = $reservation->room->roomtype->roomtype;
                    $checkinDate = \Carbon\Carbon::parse($enroleddata->schedule->dateonsitefrom);
                    $checkoutDate = \Carbon\Carbon::parse($enroleddata->schedule->dateonsiteto);

                    // Initialize the days counter
                    $days = 0;
                    $currentDate = $checkinDate->copy(); // Clone checkinDate to avoid modifying the original

                    // Loop through each day from checkinDate to checkoutDate
                    while ($currentDate <= $checkoutDate) {
                        // Check if the current day is not Saturday (6) or Sunday (0)
                        if ($currentDate->dayOfWeek != \Carbon\Carbon::SATURDAY && $currentDate->dayOfWeek != \Carbon\Carbon::SUNDAY) {
                            $days++;  // Increment the day counter
                        }
                        // Move to the next day
                        $currentDate->addDay();
                    }
                }
            } else {
                foreach ($data as $reservation) {
                    $this->roomtype = $reservation->room->roomtype->roomtype;
                    $checkinDate = \Carbon\Carbon::parse($reservation->checkindate);
                    $checkoutDate = \Carbon\Carbon::parse($reservation->dateto);

                    $days += $checkoutDate->diffInDays($checkinDate);
                }
                $days++;
            }

            $this->dorm = $days * $this->companycourseinfo->$dorm_col_name;
            $this->DMTtotal += $this->dorm;
            $this->DMT += $this->dorm;
            $this->totaldorm += $this->dorm;
            $this->days = $days;
        } else {
            $enroledinfo = tblenroled::where('deletedid', 0)->where('dropid', 0)->find($enroledid);
            $companyid = $enroledinfo->trainee->company->companyid;

            $this->roomtype = NULL;

            if ($companyid == 1) {
                $checkindate = $enroledinfo->schedule->dateonsitefrom;
                $checkoutdate = $enroledinfo->schedule->dateonsiteto;
                $checkinDate = \Carbon\Carbon::parse($checkindate);
                $checkoutDate = \Carbon\Carbon::parse($checkoutdate);

                $days += $checkoutDate->diffInDays($checkinDate);

                $days++;
                if ($enroledinfo->dormid != 1 && $enroledinfo->dormid != 0) {

                    if ($enroledinfo->reservationstatusid == 1 || $enroledinfo->reservationstatusid == 0) {
                        $this->dorm = $days * $this->companycourseinfo->$dorm_col_name;
                    } else {
                        $this->dorm = 0 * $this->companycourseinfo->$dorm_col_name;
                    }
                } else {
                    $this->dorm = 0 * $this->companycourseinfo->$dorm_col_name;
                }
            } else {

                if (!empty($enroledinfo->dormitory->checkindate)) {
                    $checkindate = $enroledinfo->dormitory->checkindate;
                } else {
                    $checkindate = $enroledinfo->schedule->dateonsitefrom;
                }
                $checkoutdate = $enroledinfo->schedule->dateonsiteto;
                $checkinDate = \Carbon\Carbon::parse($checkindate);
                $checkoutDate = \Carbon\Carbon::parse($checkoutdate);
                $days += $checkoutDate->diffInDays($checkinDate);
                $days++;
                if ($enroledinfo->dormid != 1 && $enroledinfo->dormid != 0) {

                    if ($enroledinfo->reservationstatusid == 1 || $enroledinfo->reservationstatusid == 0) {
                        $this->dorm = $days * $this->companycourseinfo->$dorm_col_name;
                    } else {
                        $this->dorm = 0 * $this->companycourseinfo->$dorm_col_name;
                    }
                } else {
                    $this->dorm = 0 * $this->companycourseinfo->$dorm_col_name;
                }
            }

            $this->DMTtotal += $this->dorm;
            $this->DMT += $this->dorm;
            $this->totaldorm += $this->dorm;
            $this->days = $days;
        }
    }

    public function computetotal($tfRate, $meal, $dorm, $transpo)
    {
        $tfRate = $tfRate - $this->vatvalue;
        $this->totalwoVAT = $tfRate + $meal + $dorm + $transpo;
        $this->totalwVAT = $this->totalwoVAT + $this->vatvalue;


        $this->totalwoVAT = round($this->totalwVAT, 2) / 1.12;
        $this->totalwVAT = $this->totalwVAT;

        // $this->totalVAT += round($vatValue, 0);
        $this->totalVAT += $this->vatvalue;
        $this->totalwvatall  += $this->totalwVAT;
        $this->totalwovatall  += $this->totalwoVAT;
    }

    public function getbuscount($enroledid)
    {
        try {

            $enroleddata = tblenroled::where('enroledid', $enroledid)->first();
            $busData = tblbusmonitoring::where('enroledid', $enroledid)->count();
            $bus_col_name = 'transpo_fee_' . $this->concat;

            if ($enroleddata->IsRemedial == 1) {
                $scheduleid = $enroleddata->remedial_sched;
            } else {
                $scheduleid = $enroleddata->scheduleid;
            }

            if ($enroleddata->trainee->company->companyid == 1) {
                if ($enroleddata->busid == 1 && $enroleddata->dormid != 0 && $enroleddata->dormid != 1) {
                    $this->bus = $this->companycourseinfo->$bus_col_name * $busData;
                    $this->DMTtotal += $this->bus;
                    $this->DMT += $this->bus;
                    $this->totalbus += $this->bus;
                } else {
                    if ($enroleddata->busid != 0) {
                        $this->bus = $this->companycourseinfo->$bus_col_name * $this->days;
                        $this->DMTtotal += $this->bus;
                        $this->DMT += $this->bus;
                        $this->totalbus += $this->bus;
                    } else {
                        $this->bus = 0;
                        $this->DMTtotal += $this->bus;
                        $this->DMT += $this->bus;
                        $this->totalbus += $this->bus;
                    }
                }
            } else {
                $data = tblbusmonitoring::where('enroledid', $enroledid)->get();
                $this->bus = $this->companycourseinfo->$bus_col_name * count($data);
                $this->DMTtotal += $this->bus;
                $this->DMT += $this->bus;
                $this->totalbus += $this->bus;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function getmealcount($enroledid)
    {
        try {
            $enroleddata = tblenroled::where('enroledid', $enroledid)->first();
            $meal_col_name = 'meal_price_' . $this->concat;

            if ($this->days > 0) {
                $days = $this->days;
            } else {
                $days = 1;
            }

            if ($enroleddata->trainee->company->companyid == 1) {
                // $datefrom = \Carbon\Carbon::parse($enroleddata->schedule->dateonsitefrom);
                // $dateto = \Carbon\Carbon::parse($enroleddata->schedule->dateonsiteto);

                // $days += $dateto->diffInDays($datefrom);
                $this->meal = $this->companycourseinfo->$meal_col_name * $days;
                $this->DMTtotal += $this->meal;
                $this->DMT += $this->meal;
                $this->totalmeal += $this->meal;
            } else {
                $this->meal = $this->companycourseinfo->$meal_col_name * $this->days;
                $this->DMTtotal += $this->meal;
                $this->DMT += $this->meal;
                $this->totalmeal += $this->meal;
            }
        } catch (\Exception $th) {
            $this->consoleLog($th->getMessage());
        }
    }

    public function loadTransferBillingTrainees($schedid, $compid)
    {
        return $this->trainees = DB::table('tbltransferbilling as a')
            ->join(
                'tbltraineeaccount as b',
                'a.traineeid',
                '=',
                'b.traineeid'
            )
            ->select([
                'b.l_name',
                'b.f_name',
                'b.m_name',
                'b.suffix',
                'b.vessel',
                'b.company_id',
                'b.nationalityid',
                'c.nationality',
                'x.scheduleid',
                'x.istransferedbilling',
                'x.reservationstatusid',
                'v.vesselname',
                'x.is_SignatureAttached',
                'x.datebilled',
                'x.is_Bs_Signed_BOD_Mgr',
                'x.is_GmSignatureAttached',
                'x.enroledid',
                'x.discount',
                'v.vesselname',
                'x.billingserialnumber',
                'z.serialnumber',
                'b.rank_id',
                'r.rankacronym',
                'r.rank',
                'b.traineeid'
            ])
            ->join('tblnationality as c', 'c.nationalityid', '=', 'b.nationalityid')
            ->leftJoin('tblvessels as v', 'v.id', '=', 'b.vessel')
            ->join('tblenroled as x', 'x.traineeid', '=', 'b.traineeid')
            ->leftJoin('tbltransferbilling as z', 'x.enroledid', '=', 'z.enroledid')
            ->rightJoin('tblrank as r', 'r.rankid', '=', 'b.rank_id')
            ->where('x.reservationstatusid', '!=', 4)
            ->where('x.dropid', 0)
            ->where('x.nabillnaid', 0)
            ->where('x.deletedid', 0)
            ->where('x.istransferedbilling', 1)
            ->where('a.scheduleid', $schedid)
            ->where('a.payeecompanyid', $compid)
            ->get();
    }

    public function loadTrainees($schedid, $compid)
    {
        $istransfered = session('transferedBilling');
        $serialnumber = session('billingserialnumber');
        $nykComps = tblnyksmcompany::getcompanyid();
        try {
            $query = DB::table('tbltraineeaccount as a')
                ->select([
                    'a.l_name',
                    'a.f_name',
                    'a.m_name',
                    'a.suffix',
                    'a.vessel',
                    'a.company_id',
                    'a.nationalityid',
                    'c.nationality',
                    'x.scheduleid',
                    'x.istransferedbilling',
                    'x.reservationstatusid',
                    'v.vesselname',
                    'x.is_SignatureAttached',
                    'x.datebilled',
                    'x.is_Bs_Signed_BOD_Mgr',
                    'x.is_GmSignatureAttached',
                    'x.enroledid',
                    'x.discount',
                    'v.vesselname',
                    'x.billingserialnumber',
                    'z.serialnumber',
                    'b.rankacronym'
                ])
                ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
                ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
                ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
                ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
                ->leftJoin('tbltransferbilling as z', 'x.enroledid', '=', 'z.enroledid')
                ->where('x.reservationstatusid', '!=', 4)
                ->where('x.dropid', 0)
                ->where('x.nabillnaid', 0)
                ->where('x.deletedid', 0);

            // Condition for transferred billing
            $query->where('x.istransferedbilling', $istransfered ? 1 : 0);

            if ($serialnumber != null) {
                $query->where('x.billingserialnumber', $serialnumber);
            }

            // Handle NYKLINE company condition (company_id 89)
            if ($compid == 89) {
                $query->where('a.company_id', 89)
                    ->where('a.nationalityid', 51)
                    ->whereIn('x.scheduleid', $schedid)
                    ->where('x.IsRemedial', 0);
            }
            // Handle other NYKLINE associated companies
            elseif (in_array($compid, $nykComps)) {
                $query->whereIn('a.company_id', $nykComps)
                    ->where('a.nationalityid', '!=', 51)
                    ->whereIn('x.scheduleid', $schedid)
                    ->where('x.IsRemedial', 0)
                    ->where('x.attendance_status', 0);
            }
            // Handle other companies
            else {
                array_push($nykComps, 1);

                if ($istransfered) {
                    $query->whereIn('x.scheduleid', $schedid)
                        ->where('x.IsRemedial', 0);
                } else {
                    if (in_array($compid, $nykComps)) {
                        $query->where('x.attendance_status', 0);
                    }
                    $query->where('a.company_id', '=', $compid)
                        ->whereIn('x.scheduleid', $schedid)
                        ->where('x.IsRemedial', 0);
                }
            }

            // Get non-remedial trainees
            $trainees = $query->orderBy('x.billingserialnumber', 'ASC')->get();
            // Query for remedial trainees
            $queryRemedial = clone $query;
            $queryRemedial->where('x.IsRemedial', 1)
                ->whereIn('x.remedial_sched', $schedid);

            $trainees2 = $queryRemedial->orderBy('x.billingserialnumber', 'ASC')->get();

            // Merge both trainee sets and sort by last name
            $this->trainees = $trainees->merge($trainees2)->sortBy('l_name');

            $this->counttrainees = $this->trainees->count();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function loadTraineeswVessel($schedid, $compid, $vesselid)
    {
        $nykComps = tblnyksmcompany::getcompanyid();
        // try {
        //     //For NYKLINE
        //     if ($compid == 89) {
        //         $this->traineeswVessel = DB::table('tbltraineeaccount as a')
        //             ->select([
        //                 'a.l_name',
        //                 'a.f_name',
        //                 'a.m_name',
        //                 'a.suffix',
        //                 'a.vessel',
        //                 'a.company_id',
        //                 'a.nationalityid',
        //                 'c.nationality',
        //                 'v.vesselname',
        //                 'x.datebilled',
        //                 'x.is_SignatureAttached',
        //                 'x.is_Bs_Signed_BOD_Mgr',
        //                 'x.is_GmSignatureAttached',
        //                 'x.enroledid',
        //                 'x.discount',
        //                 'v.vesselname',
        //                 'x.billingserialnumber',
        //                 'b.rankacronym'
        //             ])
        //             ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
        //             ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
        //             ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
        //             ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
        //             ->where('a.company_id', 89)
        //             ->where('a.nationalityid', 51)
        //             ->where('a.vessel', $vesselid)
        //             ->where('x.dropid', 0)
        //             ->where('x.attendance_status', 0)
        //             ->where('x.reservationstatusid', '!=', 4)
        //             ->where('x.nabillnaid', 0)
        //             ->where('x.istransferedbilling', 0)
        //             ->where('x.deletedid', 0)
        //             ->whereIn('x.scheduleid', $schedid)
        //             ->where('x.IsRemedial', 0)
        //             ->orderBy('x.billingserialnumber', 'ASC')
        //             ->get();

        //         //Get Remedial Trainees
        //         $this->traineeswVessel2 = DB::table('tbltraineeaccount as a')
        //             ->select([
        //                 'a.l_name',
        //                 'a.f_name',
        //                 'a.m_name',
        //                 'a.suffix',
        //                 'a.vessel',
        //                 'a.company_id',
        //                 'a.nationalityid',
        //                 'c.nationality',
        //                 'v.vesselname',
        //                 'x.datebilled',
        //                 'x.is_SignatureAttached',
        //                 'x.is_Bs_Signed_BOD_Mgr',
        //                 'x.is_GmSignatureAttached',
        //                 'x.enroledid',
        //                 'v.vesselname',
        //                 'x.discount',
        //                 'x.billingserialnumber',
        //                 'b.rankacronym'
        //             ])
        //             ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
        //             ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
        //             ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
        //             ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
        //             ->whereIn('x.remedial_sched', $schedid)
        //             ->where('x.dropid', 0)
        //             ->where('x.attendance_status', 0)
        //             ->where('x.IsRemedial', 1)
        //             ->where('x.reservationstatusid', '!=', 4)
        //             ->where('x.deletedid', 0)
        //             ->where('x.nabillnaid', 0)
        //             ->where('x.istransferedbilling', 0)
        //             ->where('a.company_id', 89)
        //             ->where('a.nationalityid', 51)
        //             ->where('a.vessel', $vesselid)
        //             ->orderBy('x.billingserialnumber', 'ASC')
        //             ->get();

        //         $this->traineeswVessel = $this->traineeswVessel->merge($this->traineeswVessel2);
        //         $this->traineeswVessel = $this->traineeswVessel->sortBy('l_name');

        //         $this->counttrainees = count($this->traineeswVessel);
        //     } elseif (in_array($compid, $nykComps)) {
        //         $this->traineeswVessel = DB::table('tbltraineeaccount as a')
        //             ->select([
        //                 'a.l_name',
        //                 'a.f_name',
        //                 'a.m_name',
        //                 'a.suffix',
        //                 'a.vessel',
        //                 'a.company_id',
        //                 'a.nationalityid',
        //                 'c.nationality',
        //                 'v.vesselname',
        //                 'x.datebilled',
        //                 'x.is_SignatureAttached',
        //                 'x.is_Bs_Signed_BOD_Mgr',
        //                 'x.is_GmSignatureAttached',
        //                 'x.enroledid',
        //                 'v.vesselname',
        //                 'x.billingserialnumber',
        //                 'x.discount',
        //                 'b.rankacronym'
        //             ])
        //             ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
        //             ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
        //             ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
        //             ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
        //             ->whereIn('a.company_id', $nykComps)
        //             ->where('a.nationalityid', '!=', 51)
        //             ->where('a.vessel', $vesselid)
        //             ->where('x.dropid', 0)
        //             ->where('x.attendance_status', 0)
        //             ->where('x.reservationstatusid', '!=', 4)
        //             ->where('x.nabillnaid', 0)
        //             ->where('x.istransferedbilling', 0)
        //             ->where('x.deletedid', 0)
        //             ->whereIn('x.scheduleid', $schedid)
        //             ->where('x.IsRemedial', 0)
        //             ->orderBy('x.billingserialnumber', 'ASC')
        //             ->get();

        //         //Get Remedial Trainees
        //         $this->traineeswVessel2 = DB::table('tbltraineeaccount as a')
        //             ->select([
        //                 'a.l_name',
        //                 'a.f_name',
        //                 'a.m_name',
        //                 'a.suffix',
        //                 'a.vessel',
        //                 'a.company_id',
        //                 'a.nationalityid',
        //                 'c.nationality',
        //                 'v.vesselname',
        //                 'x.datebilled',
        //                 'x.is_SignatureAttached',
        //                 'x.is_Bs_Signed_BOD_Mgr',
        //                 'x.is_GmSignatureAttached',
        //                 'x.enroledid',
        //                 'v.vesselname',
        //                 'x.billingserialnumber',
        //                 'x.discount',
        //                 'b.rankacronym'
        //             ])
        //             ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
        //             ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
        //             ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
        //             ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
        //             ->whereIn('x.remedial_sched',  $schedid)
        //             ->where('x.dropid', 0)
        //             ->where('x.attendance_status', 0)
        //             ->where('x.IsRemedial', 1)
        //             ->where('x.reservationstatusid', '!=', 4)
        //             ->where('x.deletedid', 0)
        //             ->where('x.istransferedbilling', 0)
        //             ->where('x.nabillnaid', 0)
        //             ->whereIn('a.company_id', $nykComps)
        //             ->where('a.nationalityid', '!=', 51)
        //             ->where('a.vessel', $vesselid)
        //             ->orderBy('x.billingserialnumber', 'ASC')
        //             ->get();

        //         $this->traineeswVessel = $this->traineeswVessel->merge($this->traineeswVessel2);
        //         $this->traineeswVessel = $this->traineeswVessel->sortBy('l_name');
        //         $this->counttrainees = count($this->traineeswVessel);
        //     } else {
        //         $this->traineeswVessel = DB::table('tbltraineeaccount as a')
        //             ->select([
        //                 'a.l_name',
        //                 'a.f_name',
        //                 'a.m_name',
        //                 'a.suffix',
        //                 'a.vessel',
        //                 'a.company_id',
        //                 'a.nationalityid',
        //                 'c.nationality',
        //                 'v.vesselname',
        //                 'x.datebilled',
        //                 'x.is_SignatureAttached',
        //                 'x.is_Bs_Signed_BOD_Mgr',
        //                 'x.is_GmSignatureAttached',
        //                 'x.enroledid',
        //                 'v.vesselname',
        //                 'x.billingserialnumber',
        //                 'x.discount',
        //                 'b.rankacronym'
        //             ])
        //             ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
        //             ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
        //             ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
        //             ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
        //             ->where('a.company_id', '=', $compid)
        //             ->where('a.vessel', $vesselid)
        //             ->where('x.dropid', 0)
        //             ->where('x.attendance_status', 0)
        //             ->where('x.reservationstatusid', '!=', 4)
        //             ->where('x.nabillnaid', 0)
        //             ->where('x.deletedid', 0)
        //             ->whereIn('x.scheduleid',  $schedid)
        //             ->where('x.IsRemedial', 0)
        //             ->orderBy('x.billingserialnumber', 'ASC')
        //             ->get();

        //         //Get Remedial Trainees
        //         $this->traineeswVessel2 = DB::table('tbltraineeaccount as a')
        //             ->select([
        //                 'a.l_name',
        //                 'a.f_name',
        //                 'a.m_name',
        //                 'a.suffix',
        //                 'a.vessel',
        //                 'a.company_id',
        //                 'a.nationalityid',
        //                 'c.nationality',
        //                 'v.vesselname',
        //                 'x.datebilled',
        //                 'x.is_SignatureAttached',
        //                 'x.is_Bs_Signed_BOD_Mgr',
        //                 'x.is_GmSignatureAttached',
        //                 'x.enroledid',
        //                 'v.vesselname',
        //                 'x.billingserialnumber',
        //                 'x.discount',
        //                 'b.rankacronym'
        //             ])
        //             ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
        //             ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
        //             ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
        //             ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
        //             ->whereIn('x.remedial_sched',  $schedid)
        //             ->where('x.dropid', 0)
        //             ->where('x.attendance_status', 0)
        //             ->where('x.IsRemedial', 1)
        //             ->where('x.reservationstatusid', '!=', 4)
        //             ->where('x.deletedid', 0)
        //             ->where('x.istransferedbilling', 0)
        //             ->where('x.nabillnaid', 0)
        //             ->where('a.company_id', '=', $compid)
        //             ->where('a.vessel', $vesselid)
        //             ->orderBy('x.billingserialnumber', 'ASC')
        //             ->get();

        //         $this->traineeswVessel = $this->traineeswVessel->merge($this->traineeswVessel2);
        //         $this->traineeswVessel = $this->traineeswVessel->sortBy('l_name');
        //         $this->counttrainees = count($this->traineeswVessel);
        //     }
        // } catch (\Exception $e) {
        //     $this->consoleLog($e->getMessage());
        // }

        try {
            // Base Query to avoid repetition
            $query = DB::table('tbltraineeaccount as a')
                ->select([
                    'a.l_name',
                    'a.f_name',
                    'a.m_name',
                    'a.suffix',
                    'a.vessel',
                    'a.company_id',
                    'a.nationalityid',
                    'c.nationality',
                    'v.vesselname',
                    'x.datebilled',
                    'x.is_SignatureAttached',
                    'x.is_Bs_Signed_BOD_Mgr',
                    'x.is_GmSignatureAttached',
                    'x.enroledid',
                    'x.discount',
                    'x.billingserialnumber',
                    'b.rankacronym'
                ])
                ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
                ->leftJoin('tblvessels as v', 'v.id', '=', 'a.vessel')
                ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
                ->join('tblnationality as c', 'c.nationalityid', '=', 'a.nationalityid')
                ->where('a.vessel', $vesselid)
                ->where('x.dropid', 0)
                ->where('x.reservationstatusid', '!=', 4)
                ->where('x.nabillnaid', 0)
                ->where('x.istransferedbilling', 0)
                ->where('x.deletedid', 0);

            // Condition for NYKLINE (compid = 89)
            if ($compid == 89) {
                $this->traineeswVessel = $query
                    ->where('a.company_id', 89)
                    ->where('a.nationalityid', 51)
                    ->whereIn('x.scheduleid', $schedid)
                    ->where('x.IsRemedial', 0)
                    ->orderBy('x.billingserialnumber', 'ASC')
                    ->get();

                $this->traineeswVessel2 = $query
                    ->whereIn('x.remedial_sched', $schedid)
                    ->where('x.IsRemedial', 1)
                    ->get();

                // Condition for companies in $nykComps
            } elseif (in_array($compid, $nykComps)) {
                $this->traineeswVessel = $query
                    ->whereIn('a.company_id', $nykComps)
                    ->where('a.nationalityid', '!=', 51)
                    ->whereIn('x.scheduleid', $schedid)
                    ->where('x.IsRemedial', 0)
                    ->where('x.attendance_status', 0)
                    ->orderBy('x.billingserialnumber', 'ASC')
                    ->get();

                $this->traineeswVessel2 = $query
                    ->whereIn('x.remedial_sched', $schedid)
                    ->where('x.IsRemedial', 1)
                    ->get();

                // General case for other companies
            } else {
                $query->where('a.company_id', '=', $compid)
                    ->whereIn('x.scheduleid', $schedid)
                    ->where('x.IsRemedial', 0)
                    ->orderBy('x.billingserialnumber', 'ASC');
                if ($compid == 1) {
                    $query->where('x.attendance_status', 0);
                }

                $this->traineeswVessel = $query->get();

                $this->traineeswVessel2 = $query
                    ->whereIn('x.remedial_sched', $schedid)
                    ->where('x.IsRemedial', 1)
                    ->get();
            }

            // Merge, sort and count trainees
            $this->traineeswVessel = $this->traineeswVessel->merge($this->traineeswVessel2);
            $this->traineeswVessel = $this->traineeswVessel->sortBy('l_name');
            $this->counttrainees = $this->traineeswVessel->count();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function generateserialnumber($scheduleid, $enroledid, $companyid)
    {
        $nykComps = tblnyksmcompany::getcompanyid();
        $enroledid = tblenroled::where('enroledid', $enroledid)->first();

        if (in_array($companyid, $nykComps)) {
            $companyid = 262;
            $billingstatementtype = tblforeignrate::where('companyid', $companyid)->where('courseid', $enroledid->courseid)->first();
            $billing_statement_format = 'format';
            $billing_statement_template = 'template';
        } else {
            $billingstatementtype = tblcompanycourse::where('companyid', $companyid)->where('courseid', $enroledid->courseid)->first();
            $billing_statement_format = 'billing_statement_format';
            $billing_statement_template = 'billing_statement_template';
        }


        $last_serialnumber = billingserialnumber::find(1);

        if ($billingstatementtype->$billing_statement_template == 3 && $enroledid->trainee->vessel != "" && $enroledid->trainee->vessel != NULL) {
            $this->loadTrainees($scheduleid, $companyid, $enroledid->trainee->vessel);

            $uniqueVessels = array();
            foreach ($this->trainees as $vessel) {
                $vessel = $vessel->vessel;
                if (!in_array($vessel, $uniqueVessels)) {
                    $uniqueVessels[] = $vessel;
                }
            }

            $year = substr(date('Y'), 2);
            $month = date("m");

            foreach ($uniqueVessels as $vesselid) {
                $this->loadTraineeswVessel($scheduleid, $companyid, $vesselid);
                if ($last_serialnumber->dateformat != $year . $month) {

                    //reset the serial number to start again from 1
                    $last_serialnumber->update([
                        "dateformat" => $year . $month
                    ]);

                    $lastnumber = 0;
                } else {
                    $lastnumber = $last_serialnumber->serialnumber;
                }

                $lastnumber += 1;

                foreach ($this->traineeswVessel as $data) {

                    $last_serialnumber->update([
                        'serialnumber' => $lastnumber
                    ]);

                    $countdigits = strlen($lastnumber);

                    switch ($countdigits) {
                        case 1:
                            $lastnumber = "00" . $lastnumber;
                            break;

                        case 2:
                            $lastnumber = "0" . $lastnumber;
                            break;

                        default:
                            $lastnumber = $lastnumber;
                            break;
                    }



                    $serialfinal = $year . $month . "-" . $lastnumber;
                    if ($data->company_id == $companyid) {
                        $traineinfo = tblenroled::find($data->enroledid);
                        $traineinfo->update([
                            'billingserialnumber' => $serialfinal
                        ]);
                    }
                }

                $lastnumber++;
            }
        } elseif ($billingstatementtype->$billing_statement_format == 2) {
            $this->loadTrainees($scheduleid, $companyid);
            $enroledata = tblenroled::where('scheduleid', $scheduleid)->where('deletedid', 0)->get();
            $getcompany = $companyid;

            $year = substr(date('Y'), 2);
            $month = date("m");

            if ($last_serialnumber->dateformat != $year . $month) {

                //reset the serial number to start again from 1
                $last_serialnumber->update([
                    "dateformat" => $year . $month
                ]);

                $lastnumber = 0;
            } else {
                $lastnumber = $last_serialnumber->serialnumber;
            }

            foreach ($this->trainees as $data) {
                if ($data->company_id == $getcompany) {
                    $lastnumber += 1;

                    $last_serialnumber->update([
                        'serialnumber' => $lastnumber
                    ]);

                    $countdigits = strlen($lastnumber);

                    switch ($countdigits) {
                        case 1:
                            $lastnumber = "00" . $lastnumber;
                            break;

                        case 2:
                            $lastnumber = "0" . $lastnumber;
                            break;

                        default:
                            $lastnumber = $lastnumber;
                            break;
                    }

                    $serialfinal = $year . $month . "-" . $lastnumber;
                    $trainee = tblenroled::find($data->enroledid);
                    $trainee->update([
                        'billingserialnumber' => $serialfinal
                    ]);
                }
            }
        } else {

            $this->loadTrainees($scheduleid, $companyid);
            $enroledata = tblenroled::where('scheduleid', $scheduleid)->where('deletedid', 0)->get();

            $getcompany = $companyid;

            $year = substr(date('Y'), 2);
            $month = date("m");

            if ($last_serialnumber->dateformat != $year . $month) {

                //reset the serial number to start again from 1
                $last_serialnumber->update([
                    "dateformat" => $year . $month
                ]);

                $lastnumber = 0;
            } else {
                $lastnumber = $last_serialnumber->serialnumber;
            }

            $lastnumber += 1;

            $last_serialnumber->update([
                'serialnumber' => $lastnumber
            ]);

            $countdigits = strlen($lastnumber);

            switch ($countdigits) {
                case 1:
                    $lastnumber = "00" . $lastnumber;
                    break;

                case 2:
                    $lastnumber = "0" . $lastnumber;
                    break;

                default:
                    $lastnumber = $lastnumber;
                    break;
            }



            $serialfinal = $year . $month . "-" . $lastnumber;



            foreach ($this->trainees as $data) {
                if ($data->company_id == $getcompany) {
                    $trainee = tblenroled::find($data->enroledid);
                    $trainee->update([
                        'billingserialnumber' => $serialfinal
                    ]);
                } elseif (in_array($companyid, $nykComps)) {
                    if ($data->nationalityid != 51) {
                        $trainee = tblenroled::find($data->enroledid);
                        $trainee->update([
                            'billingserialnumber' => $serialfinal
                        ]);
                    }
                }
            }
        }
    }

    public function getCommentsNotes($scheduleid)
    {
        try {
            if (is_array($scheduleid)) {
                $scheduleid = implode(',', $scheduleid);
            }
            $comment = tblbillingstatementcomments::where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('isactive', 1)->first();
            return $comment->comment;
        } catch (\Exception $th) {
        }
    }

    public function numberformat($toconvert)
    {
        return number_format($toconvert, 2, '.', ',');
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.generate-billing-statement2');
    }

    public function checkTraineesForDiscount($traineeID)
    {
        $courseid = 91;

        $check = tblenroled::where('traineeid', $traineeID)
            ->where('courseid', $courseid)
            ->where('deletedid', 0)
            ->where('dropid', 0)->where('attendance_status', '!=', 0)->first();

        if ($check->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function standardBilling($istransferred, $traininginfonumchrs, $schedid, $companyid)
    {
        $this->scheduleid = $schedid;
        $this->companyid = $companyid;
        if ($istransferred) {
            $this->loadTransferBillingTrainees($this->scheduleid, $this->companyid);
        } else {
            $this->loadTrainees($this->scheduleid, $this->companyid);
        }

        $this->scheduledata = tblcourseschedule::whereIn('scheduleid', $this->scheduleid)->first();

        if ($this->trainees[0]->billingserialnumber == NULL || $this->trainees[0]->billingserialnumber == "") {
            $this->generateserialnumber(session('scheduleid'), $this->trainees[0]->enroledid, $this->companyid);
        }


        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $gothicI = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHICBI.TTF', 'TrueTypeUnicode', '', 96);
        $pdf->SetCreator('NETI');
        $pdf->SetAuthor('NETI');
        $pdf->SetTitle('Billing Statement');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));


        $pageWidth = 210; // A4 width in points
        $pageHeight = 297; // A4 height in points
        $pdf->SetFont($gothic, '', 8);
        $billingstatementtype = tblcompany::where('companyid', $this->companyid)->first();
        $status_id = Session::get('billing_status_id');

        if ($istransferred) {
            $companyid = $this->trainees[0]->company_id;
        } else {
            $companyid = $this->companyid;
        }

        if ($status_id == 2) {
            //save client info to database
            try {
                $client_info_id = Session::get('client_information');
                $clientinfo = ClientInformation::where('id', $client_info_id)->first();
                $client_information = $clientinfo->client_information;
                $update = DB::table('tblenroled as a')
                    ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                    ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid')
                    ->where(function ($query) use ($istransferred, $companyid) {
                        if (is_array($this->scheduleid)) {
                            $query->whereIn('b.scheduleid', $this->scheduleid);
                        } else {
                            $query->where('b.scheduleid', $this->scheduleid);
                        }

                        $query->where('c.company_id', $companyid);
                    })
                    ->where('b.scheduleid', '=', $this->scheduleid)
                    ->update(['a.client_information_id' => $client_info_id]);
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }
        } else {
            //retrieve client info to database
            try {
                $clientinfo = ClientInformation::select('client_information.*')
                    ->join('tblenroled as b', 'client_information.id', '=', 'b.client_information_id')
                    ->join('tblcourseschedule as c', 'c.scheduleid', '=', 'b.scheduleid')
                    ->join('tbltraineeaccount as d', 'd.traineeid', '=', 'b.traineeid')
                    ->where(function ($query) use ($istransferred) {
                        if (is_array($this->scheduleid)) {
                            $query->whereIn('b.scheduleid', $this->scheduleid);
                        } else {
                            $query->where('b.scheduleid', $this->scheduleid);
                        }

                        if ($istransferred) {
                            $query->where('b.istransferedbilling', 1);
                        }
                    })
                    ->where('d.company_id', $companyid)
                    ->limit(1)
                    ->first();
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }

            $client_information = $clientinfo->client_information;
        }

        // $client_information = implode("\n", $textArray);
        if ($clientinfo->label == 0) {
            $client_information = str_replace('</p>', ':', $client_information);
            $client_information = strip_tags($client_information);
            $client_information = str_replace(':', '<br/>', $client_information);
        } else {
            $cliendata = json_decode($clientinfo->client_information);
            $client_information = '<strong>' . $cliendata->recipient . '</strong><br/>' . $cliendata->designation . '<br/><strong>' . $cliendata->companyname . '</strong><br/>' . $cliendata->addressline1 . '<br/>' . $cliendata->addressline2;
        }

        $scheduleinfo = tblcourseschedule::whereIn('scheduleid', $this->scheduleid)->first();
        $nykComps = tblnyksmcompany::getcompanyid();
        $nykCompswoNYKLINE = tblnyksmcompany::getcompanywoNYKLINE();
        if (in_array($this->companyid, $nykComps)) {
            if (in_array($this->companyid, $nykCompswoNYKLINE)) {
                $this->companyid = 262;
            }

            $billingdata = tblforeignrate::where('companyid', $this->companyid)->where('courseid', $this->scheduledata->course->courseid)->first();
            $this->foreignrate = $billingdata;
            $traininginfo = $scheduleinfo->course->coursecode . " / " . $scheduleinfo->course->coursename . "\n (" . date('M. d, Y', strtotime($scheduleinfo->startdateformat)) . " to " . date('M. d, Y', strtotime($scheduleinfo->enddateformat)) . ")";
            if ($scheduleinfo->dateonsitefrom != '0000-00-00' && $scheduleinfo->dateonsiteto != '0000-00-00' && $scheduleinfo->dateonsitefrom != null && $scheduleinfo->dateonsiteto != null &&  $scheduleinfo->dateonsitefrom != '' && $scheduleinfo->dateonsiteto != '') {
                $trainingdate = "(" . date('M. d, Y', strtotime($scheduleinfo->dateonsitefrom)) . " - " . date('M. d, Y', strtotime($scheduleinfo->dateonsiteto)) . ")";
            } else {
                $trainingdate = "( - )";
            }

            if ($billingdata->template == 1) {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_PESO.pdf");
            } elseif ($billingdata->template == 2) {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_USD_Foreign.pdf");
            } else {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_USD_Local_wvat.pdf");
            }

            $pageCount = $pdf->setSourceFile($templatePath);

            // dd($billingdata->format, $billingdata->template);
            if ($billingdata->format == 1 && $billingdata->template == 1 || $billingdata->format == 1 && $billingdata->template == 3) {
                for ($i = 1; $i <= $pageCount; $i++) {
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                    $templateId = $pdf->importPage($i);

                    $pdf->setXY(30, 72.5);
                    $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);
                    // $pdf->MultiCell(0,0,$clientinfo->client_information,0,"L",false,0,0,0,true,0,true);

                    $comment = $this->getCommentsNotes($this->scheduleid);
                    if ($comment == NULL || $comment == "") {
                        $comment = "";
                    } else {
                        $comment = '(' . $comment . ')';
                    }

                    $pdf->SetFont($gothicI, '', 7);
                    $pdf->setXY(29, 123.5);
                    $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                    $pdf->SetFont($gothic, '', 8);
                    $pdf->setXY(144, 72.5);
                    $pdf->Cell(0, 0, date('M. d, Y', strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                    $pdf->setXY(51, 109.5);
                    $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    $pdf->setXY(158, 18.7);
                    $pdf->Cell(0, 0, $istransferred ? $this->trainees[0]->serialnumber  : $this->trainees[0]->billingserialnumber, 0, 0, "C");


                    // check if trainees is checkedin
                    $checkInData = null;
                    $pdf->setXY(100, 119);
                    $pdf->Cell(
                        0,
                        0,
                        $checkInData != null ?
                            Carbon::parse($scheduleinfo->dateonsitefrom)->format('M d, Y') . " - " . Carbon::parse($scheduleinfo->dateonsiteto)->format('M d, Y')
                            : $trainingdate,
                        0,
                        0,
                        "L"
                    );

                    if ($istransferred) {
                        $this->loadTransferBillingTrainees($this->scheduleid, $this->companyid);
                    } else {
                        $this->loadTrainees($this->scheduleid, $this->companyid);
                    }

                    $y = 131;
                    $count = 1;

                    $traineenum = 1;
                    foreach ($this->trainees as $data) {
                        $this->DMT = 0;
                        if ($traineenum == 1) {
                            $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $y);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            //training fee
                            // $pdf->SetXY(0, $y);
                            // $pdf->Cell(0, 0, $currency . "    " . number_format($tfRate,2,'.',','), 0, 0, 'C', false);
                            $pdf->SetXY(0, $y);
                            $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->course_rate, 2, '.', ','), 0, 0, 'C', false);

                            //DMT
                            $this->computedormforeign($data->enroledid, $billingdata->id);
                            $this->computemealforeign($data->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetranspoforeign($data->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                            $pdf->SetXY(57, $y);
                            $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                            //total w/ vat
                            $pdf->SetXY(103, $y);
                            $pdf->Cell(0, 0, 'USD' . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            //total wo/ vat
                            $pdf->SetXY(150, $y);
                            $pdf->Cell(0, 0, 'USD' . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                        } else {
                            $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $y);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            //training fee
                            $pdf->SetXY(9, $y);
                            $pdf->Cell(0, 0, number_format($billingdata->course_rate, 2, '.', ','), 0, 0, 'C', false);

                            //DMT
                            $this->computedormforeign($data->enroledid, $billingdata->id);
                            $this->computemealforeign($data->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetranspoforeign($data->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                            $pdf->SetXY(66, $y);
                            $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                            //total w/ vat
                            $pdf->SetXY(111, $y);
                            $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            //total wo/ vat
                            $pdf->SetXY(158, $y);
                            $pdf->Cell(0, 0, number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                        }
                        $y = $y + 3.5;
                        $this->totaltrainingfee += $billingdata->course_rate;
                        $traineenum++;

                        $this->totaltrainingfee = $this->totaltrainingfee;
                    }

                    $pdf->SetXY(0, 178.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format(round($this->totaltrainingfee, 0), 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(57, 178.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMTTotal, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(103, 178.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(150, 178.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(158, 182.5);
                    // $pdf->Cell(0, 0, $currency . "    " . $this->totalVAT, 0, 0, 'C', false);
                    $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(162, 187.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 'C', false);

                    //Dorm Info
                    if ($this->roomtype == "CDC") {
                        $roomtype = "NGH";
                    } else {
                        $roomtype = $this->roomtype;
                    }
                    $pdf->SetXY(60, 192.2);
                    $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                    $pdf->SetFont($gothic, '', 6.5);

                    //company courseinfo
                    $pdf->SetXY(74, 201.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->foreignrate->dorm_rate, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 206);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->foreignrate->meal_rate, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 210.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->foreignrate->transpo, 2, '.', ','), 0, 0, 'L', false);




                    //Total Dorm / Meal / Transpo
                    $pdf->SetXY(10, 201.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(10, 206);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(10, 210.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetFont($gothic, '', 8);

                    $check = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->is_SignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 1) {
                        $imagePath = public_path('storage/uploads/esign/JCsig.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath, 20, 260, 30, 30);
                    }

                    $check = 2;
                    foreach ($this->trainees as $data) {

                        if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 2) {
                        $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath1, 83, 260, 30, 30);
                    }

                    $check = 3;
                    foreach ($this->trainees as $data) {

                        if ($data->is_GmSignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 3) {
                        $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath2, 142, 260, 30, 30);
                    }
                }
            } elseif ($billingdata->format == 2 && $billingdata->template == 1 || $billingdata->format == 2 && $billingdata->template == 3) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $traineenum = 1;
                foreach ($this->trainees as $data) {

                    $this->totaltrainingfee = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                        $comment = $this->getCommentsNotes($this->scheduleid);
                        if ($comment == NULL || $comment == "") {
                            $comment = "";
                        } else {
                            $comment = '(' . $comment . ')';
                        }

                        $pdf->SetFont($gothicI, '', 7);
                        $pdf->setXY(29, 123.5);
                        $pdf->MultiCell(0, 0, $comment, 0, "L", false);
                        //client info

                        $pdf->SetFont($gothic, '', 8);
                        $pdf->setXY(30, 72.5);
                        $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);
                        // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);


                        $pdf->setXY(144, 72.5);
                        $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                        $templateId = $pdf->importPage($i);
                        $pdf->useTemplate($templateId);

                        $pdf->setXY(51, 109.5);
                        $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                        $pdf->setXY(100, 119);
                        $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                        if ($i == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");
                        }

                        $this->DMT = 0;
                        $this->DMTTotal = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, 131);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        if ($i == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $data->billingserialnumber, 0, 0, "C");
                        }

                        //training fee
                        $pdf->SetXY(0, 131);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->course_rate, 2, '.', ','), 0, 0, 'C', false);


                        //DMT
                        $this->computedormforeign($data->enroledid, $billingdata->id);
                        $this->computemealforeign($data->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                        $this->computetranspoforeign($data->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                        $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                        $pdf->SetXY(57, 131);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                        //total w/ vat
                        $pdf->SetXY(103, 131);
                        $pdf->Cell(0, 0, 'USD' . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                        //total wo/ vat
                        $pdf->SetXY(150, 131);
                        $pdf->Cell(0, 0, 'USD' . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                        $pdf->SetXY(0, 178.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->course_rate, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(57, 178.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMTTotal, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(103, 178.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(150, 178.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(158, 182.5);
                        // $pdf->Cell(0, 0, $currency . "    " . $this->totalVAT, 0, 0, 'C', false);
                        // $pdf->Cell(0, 0, number_format($this->totalVAT,2,'.',','), 0, 0, 'C', false);
                        $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);


                        $pdf->SetXY(150, 187.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                        //Roomtype
                        if ($this->roomtype == "CDC") {
                            $roomtype = "NGH";
                        } else {
                            $roomtype = $this->roomtype;
                        }

                        $pdf->SetXY(60, 192.2);
                        $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                        $pdf->SetFont($gothic, '', 6.5);

                        //company courseinfo
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->dorm_rate, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->meal_rate, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->transpo, 2, '.', ','), 0, 0, 'L', false);


                        //Total Dorm / Meal / Transpo

                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetFont($gothic, '', 8);
                        $check = 1;
                        foreach ($this->trainees as $data) {

                            if ($data->is_SignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 1) {
                            $imagePath = public_path('storage/uploads/esign/JCsig.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath, 20, 260, 30, 30);
                        }

                        $check = 2;
                        foreach ($this->trainees as $data) {

                            if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 2) {
                            $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath1, 83, 260, 30, 30);
                        }

                        $check = 3;
                        foreach ($this->trainees as $data) {

                            if ($data->is_GmSignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 3) {
                            $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath2, 142, 260, 30, 30);
                        }
                    }

                    $traineenum++;
                }
            } elseif ($billingdata->format == 3 && $billingdata->template == 1 || $billingdata->format == 3 && $billingdata->template == 3) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $y = 0;

                $uniqueVessels = array();
                foreach ($this->trainees as $vessel) {
                    $vessel = $vessel->vessel;
                    if (!in_array($vessel, $uniqueVessels)) {
                        $uniqueVessels[] = $vessel;
                    }
                }

                foreach ($uniqueVessels as $vessel => $vesselid) {
                    $traineenum = 1;
                    $this->loadTraineeswVessel($this->scheduleid, $this->companyid, $vesselid);
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                    $axisy = 134;
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;
                    foreach ($this->traineeswVessel as $datawVessel) {
                        $lastnumber = count($this->traineeswVessel);
                        if ($datawVessel->billingserialnumber == NULL || $datawVessel->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $datawVessel->enroledid, $this->companyid);
                        }

                        for ($i = 1; $i <= $pageCount; $i++) {

                            // client info

                            if ($traineenum == 1) {
                                $pdf->setXY(30, 72.5);
                                $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                                // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);


                                $pdf->setXY(144, 72.5);
                                $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->traineeswVessel[0]->datebilled)), 0, 0, "C");

                                $templateId = $pdf->importPage($i);
                                $pdf->useTemplate($templateId);

                                $pdf->setXY(158, 18.7);
                                $pdf->Cell(0, 0, $datawVessel->billingserialnumber, 0, 0, "C");


                                $pdf->setXY(51, 109.5);
                                $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                                $pdf->setXY(100, 119);
                                $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                                $pdf->setXY(29, 122);
                                $pdf->Cell(0, 0, "Vessel Name: (" . $datawVessel->vesselname . ")", 0, 0, "L");
                            }
                            $comment = $this->getCommentsNotes($this->scheduleid);
                            if ($comment == NULL || $comment == "") {
                                $comment = "";
                            } else {
                                $comment = '(' . $comment . ')';
                            }

                            $pdf->SetFont($gothicI, '', 7);
                            $pdf->setXY(29, 123.5);
                            $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                            $pdf->SetFont($gothic, '', 8);
                            $this->DMT = 0;
                            $fullname = $datawVessel->l_name . ", " . $datawVessel->f_name . " " . substr($datawVessel->m_name, 0, 1) . '.' . " " . $datawVessel->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $axisy);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($datawVessel->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            if ($traineenum == 1) {
                                if ($datawVessel->discount == 1) {
                                    dd('check');

                                    $tfrate = $billingdata->course_rate * (1 - 7.5 / 100);
                                } else {
                                    $tfrate = $billingdata->course_rate;
                                }
                                $pdf->SetXY(0, $axisy);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedormforeign($datawVessel->enroledid, $billingdata->id);
                                $this->computemealforeign($datawVessel->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetranspoforeign($datawVessel->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                                $pdf->SetXY(50, $axisy);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(104, $axisy);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(150, $axisy);
                                $pdf->Cell(0, 0, 'USD' . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $billingdata->course_rate;
                            } else {
                                if ($datawVessel->discount == 1) {
                                    $tfrate = $billingdata->course_rate * (1 - 7.5 / 100);
                                } else {
                                    $tfrate = $billingdata->course_rate;
                                }
                                $pdf->SetXY(8, $axisy);
                                $pdf->Cell(0, 0, number_format($billingdata->course_rate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedormforeign($datawVessel->enroledid, $billingdata->id);
                                $this->computemealforeign($datawVessel->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetranspoforeign($datawVessel->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                                $pdf->SetXY(58, $axisy);
                                $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(112.5, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(158, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $billingdata->course_rate;
                            }

                            if ($traineenum == $lastnumber) {

                                if ($this->counttrainees > 1) {
                                    $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                                }

                                $pdf->SetXY(0, 178.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(50, 178.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(104, 178.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(150, 178.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(155, 182.5);
                                $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);


                                $pdf->SetXY(150, 187.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                //Roomtype
                                if ($this->roomtype == "CDC") {
                                    $roomtype = "NGH";
                                } else {
                                    $roomtype = $this->roomtype;
                                }
                                $pdf->SetXY(60, 192.2);
                                $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                                $pdf->SetFont($gothic, '', 6.5);

                                $pdf->SetXY(74, 201.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->dorm_rate, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 206);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->meal_rate, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 210.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->transpo, 2, '.', ','), 0, 0, 'L', false);


                                //Total Dorm / Meal / Transpo

                                $pdf->SetXY(10, 201.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 206);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 210.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetFont($gothic, '', 8);
                            }

                            $check = 1;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_SignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 1) {
                                $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath, 20, 260, 30, 30);
                            }

                            $check = 2;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 2) {
                                $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath1, 83, 260, 30, 30);
                            }

                            $check = 3;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_GmSignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 3) {
                                $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath2, 142, 260, 30, 30);
                            }
                        }

                        $axisy += 3;
                        $traineenum++;
                    }
                }
            } elseif ($billingdata->format == 2 && $billingdata->template == 2) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $traineenum = 1;
                foreach ($this->trainees as $data) {

                    if ($data->billingserialnumber == NULL || $data->billingserialnumber == "") {
                        $this->generateserialnumber(session('scheduleid'), $data->enroledid, $this->companyid);
                    }
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                        //client info

                        $comment = $this->getCommentsNotes($this->scheduleid);
                        if ($comment == NULL || $comment == "") {
                            $comment = "";
                        } else {
                            $comment = '(' . $comment . ')';
                        }

                        $pdf->SetFont($gothicI, '', 7);
                        $pdf->setXY(29, 123.5);
                        $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                        $pdf->SetFont($gothic, '', 8);
                        $pdf->setXY(30, 72.5);
                        // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);
                        $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);



                        $pdf->setXY(144, 72.5);
                        $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                        $templateId = $pdf->importPage($i);
                        $pdf->useTemplate($templateId);

                        $pdf->setXY(51, 109.5);
                        $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                        $pdf->setXY(100, 119);
                        $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                        $pdf->setXY(158, 18.7);
                        $pdf->Cell(0, 0, $istransferred ? $this->trainees[0]->serialnumber : $this->trainees[0]->billingserialnumber, 0, 0, "C");

                        $this->DMT = 0;
                        $this->DMTTotal = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, 131);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        $pdf->SetXY(4, 131);
                        $pdf->Cell(0, 0, $data->nationality, 0, 0, 'C', false);

                        $pdf->SetXY(57, 131);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat(round($billingdata->course_rate)), 0, 0, 'C', false);

                        //DMT
                        $this->computedormforeign($data->enroledid, $billingdata->id);
                        $this->computemealforeign($data->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                        $this->computetranspoforeign($data->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                        $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                        $pdf->SetXY(103, 131);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->DMT), 0, 0, 'C', false);

                        //total wo/ vat
                        $pdf->SetXY(150, 131);
                        $pdf->Cell(0, 0, 'USD' . "   " . $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        $this->totaltrainingfee += $billingdata->course_rate;

                        if ($this->counttrainees > 1) {
                            $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                        }

                        $pdf->SetXY(57, 178.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(103, 178.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->DMTTotal), 0, 0, 'C', false);

                        $pdf->SetXY(150, 178.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totalwvatall), 0, 0, 'C', false);

                        $pdf->SetXY(155, 182.5);
                        $pdf->Cell(0, 0, $this->numberformat($billingdata->bank_charge), 0, 0, 'C', false);

                        $totalwovatallwBC = $billingdata->bank_charge + $this->totalwvatall;
                        $pdf->SetXY(150, 187.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($totalwovatallwBC), 0, 0, 'C', false);

                        //Roomtype
                        if ($this->roomtype == "CDC") {
                            $roomtype = "NGH";
                        } else {
                            $roomtype = $this->roomtype;
                        }
                        $pdf->SetXY(60, 192.2);
                        $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                        $pdf->SetFont($gothic, '', 6.5);

                        //company courseinfo
                        $ratedorm = 'dorm_price_' . $this->concat;
                        $ratemeal = 'meal_price_' . $this->concat;
                        $ratetranspo = 'transpo_fee_' . $this->concat;
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->dorm_rate, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->meal_rate, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->transpo, 2, '.', ','), 0, 0, 'L', false);

                        //Total Dorm / Meal / Transpo

                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totaldorm), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totalmeal), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totalbus), 0, 0, 'C', false);

                        $pdf->SetFont($gothic, '', 8);

                        $check = 1;
                        foreach ($this->trainees as $data) {

                            if ($data->is_SignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 1) {
                            $imagePath = public_path('storage/uploads/esign/JCsig.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath, 20, 260, 30, 30);
                        }

                        $check = 2;
                        foreach ($this->trainees as $data) {

                            if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 2) {
                            $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath1, 83, 260, 30, 30);
                        }

                        $check = 3;
                        foreach ($this->trainees as $data) {

                            if ($data->is_GmSignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 3) {
                            $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath2, 142, 260, 30, 30);
                        }
                    }

                    $traineenum++;
                }
            } elseif ($billingdata->format == 1 && $billingdata->template == 2) {
                if ($istransferred) {
                    $this->loadTransferBillingTrainees($this->scheduleid, $this->companyid);
                } else {
                    $this->loadTrainees($this->scheduleid, $this->companyid);
                }
                //Single page
                for ($i = 1; $i <= $pageCount; $i++) {
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                    $comment = $this->getCommentsNotes($this->scheduleid);
                    if ($comment == NULL || $comment == "") {
                        $comment = "";
                    } else {
                        $comment = '(' . $comment . ')';
                    }

                    $pdf->SetFont($gothicI, '', 7);
                    $pdf->setXY(29, 123.5);
                    $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                    $pdf->SetFont($gothic, '', 8);
                    //client info
                    $pdf->setXY(30, 72.5);
                    $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 0, '', '', true, 0, true);

                    $pdf->setXY(144, 72.5);
                    $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                    $pdf->setXY(51, 109.5);
                    $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    $pdf->setXY(100, 119);
                    $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                    $y = 131;
                    $traineenum = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->billingserialnumber == NULL || $data->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $data->enroledid, $this->companyid);
                        }

                        //name
                        $this->DMT = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $countstr = strlen($fullname);
                        if ($countstr > 32) {
                            $fullname = substr($fullname, 0, 32);
                        }
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, $y);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        $pdf->SetXY(4, $y);
                        $pdf->Cell(0, 0, $data->nationality, 0, 0, 'C', false);

                        if ($traineenum == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");

                            //training fee
                            $pdf->SetXY(57, $y);
                            $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($billingdata->course_rate), 0, 0, 'C', false);

                            //DMT
                            $this->computedormforeign($data->enroledid, $billingdata->id);
                            $this->computemealforeign($data->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetranspoforeign($data->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                            $pdf->SetXY(103, $y);
                            $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->DMT), 0, 0, 'C', false);


                            //total wo/ vat
                            $pdf->SetXY(150, $y);
                            $pdf->Cell(0, 0, 'USD' . "   " . $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        } else {

                            //training fee
                            $pdf->SetXY(65, $y);
                            $pdf->Cell(0, 0, $this->numberformat($billingdata->course_rate), 0, 0, 'C', false);

                            //DMT
                            $this->computedormforeign($data->enroledid, $billingdata->id);
                            $this->computemealforeign($data->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetranspoforeign($data->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                            $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                            $pdf->SetXY(111.5, $y);
                            $pdf->Cell(0, 0, $this->numberformat($this->DMT), 0, 0, 'C', false);
                            //total wo/ vat
                            $pdf->SetXY(158, $y);
                            $pdf->Cell(0, 0, $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        }



                        $y = $y + 3.5;
                        $this->totaltrainingfee += $billingdata->course_rate;
                        $traineenum++;
                    }
                    if ($this->counttrainees > 1) {
                        $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                    }

                    $DMTTotal = $this->numberformat(round($this->DMTTotal, 2));

                    $pdf->SetXY(57, 178.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(103, 178.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . $DMTTotal, 0, 0, 'C', false);


                    // dd($this->totalwvatall);zz

                    $pdf->SetXY(150, 178.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totalwvatall), 0, 0, 'C', false);

                    $pdf->SetXY(158, 182.5);
                    $pdf->Cell(0, 0, $this->numberformat($billingdata->bank_charge), 0, 0, 'C', false);

                    $totalwovatallwBC = $billingdata->bank_charge + $this->totalwvatall;
                    $pdf->SetXY(150, 187.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($totalwovatallwBC), 0, 0, 'C', false);

                    //Roomtype
                    if ($this->roomtype == "CDC") {
                        $roomtype = "NGH";
                    } else {
                        $roomtype = $this->roomtype;
                    }
                    $pdf->SetXY(60, 192.2);
                    $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                    $pdf->SetFont($gothic, '', 6.5);

                    $pdf->SetXY(74, 201.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->dorm_rate, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 206);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->meal_rate, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 210.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->transpo, 2, '.', ','), 0, 0, 'L', false);

                    //Total Dorm / Meal / Transpo

                    $pdf->SetXY(10, 201.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totaldorm), 0, 0, 'C', false);

                    $pdf->SetXY(10, 206);
                    $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totalmeal), 0, 0, 'C', false);

                    $pdf->SetXY(10, 210.5);
                    $pdf->Cell(0, 0, 'USD' . "    " . $this->numberformat($this->totalbus), 0, 0, 'C', false);

                    $pdf->SetFont($gothic, '', 8);


                    $check = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->is_SignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 1) {
                        $imagePath = public_path('storage/uploads/esign/JCsig.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath, 20, 260, 30, 30);
                    }

                    $check = 2;
                    foreach ($this->trainees as $data) {

                        if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 2) {
                        $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath1, 83, 260, 30, 30);
                    }

                    $check = 3;
                    foreach ($this->trainees as $data) {

                        if ($data->is_GmSignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 3) {
                        $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath2, 142, 260, 30, 30);
                    }

                    $templateId = $pdf->importPage($i);
                    $pdf->useTemplate($templateId);
                }
            } elseif ($billingdata->format == 3 && $billingdata->template == 2) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $y = 0;

                $uniqueVessels = array();
                foreach ($this->trainees as $vessel) {
                    $vessel = $vessel->vessel;
                    if (!in_array($vessel, $uniqueVessels)) {
                        $uniqueVessels[] = $vessel;
                    }
                }

                foreach ($uniqueVessels as $vessel => $vesselid) {
                    $traineenum = 1;
                    $this->loadTraineeswVessel($this->scheduleid, $this->companyid, $vesselid);
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                    $axisy = 134;
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;

                    foreach ($this->traineeswVessel as $datawVessel) {
                        $lastnumber = count($this->traineeswVessel);
                        if ($datawVessel->billingserialnumber == NULL || $datawVessel->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $datawVessel->enroledid, $this->companyid);
                        }

                        for ($i = 1; $i <= $pageCount; $i++) {

                            // client info

                            $comment = $this->getCommentsNotes($this->scheduleid);
                            if ($comment == NULL || $comment == "") {
                                $comment = "";
                            } else {
                                $comment = '(' . $comment . ')';
                            }

                            $pdf->SetFont($gothicI, '', 7);
                            $pdf->setXY(29, 123.5);
                            $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                            $pdf->SetFont($gothic, '', 8);

                            if ($traineenum == 1) {
                                $pdf->setXY(30, 72.5);
                                $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                                // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);


                                $pdf->setXY(144, 72.5);
                                $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->traineeswVessel[0]->datebilled)), 0, 0, "C");

                                $templateId = $pdf->importPage($i);
                                $pdf->useTemplate($templateId);

                                $pdf->setXY(158, 18.7);
                                $pdf->Cell(0, 0, $datawVessel->billingserialnumber, 0, 0, "C");

                                $pdf->setXY(51, 109.5);
                                $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                                $pdf->setXY(100, 119);
                                $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                                $pdf->setXY(29, 122);
                                $pdf->Cell(0, 0, "Vessel Name: (" . $datawVessel->vesselname . ")", 0, 0, "L");
                            }

                            $this->DMT = 0;
                            $this->DMTTotal = 0;
                            $fullname = $datawVessel->l_name . ", " . $datawVessel->f_name . " " . substr($datawVessel->m_name, 1) . '.' . " " . $datawVessel->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $axisy);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($datawVessel->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            $pdf->SetXY(4, $axisy);
                            $pdf->Cell(0, 0, $datawVessel->nationality, 0, 0, 'C', false);

                            if ($traineenum == 1) {
                                $pdf->SetXY(57, $axisy);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->course_rate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedormforeign($datawVessel->enroledid, $billingdata->id);
                                $this->computemealforeign($datawVessel->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetranspoforeign($datawVessel->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                                $pdf->SetXY(103, $axisy);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(150, $axisy);
                                $pdf->Cell(0, 0, 'USD' . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $billingdata->course_rate;
                            } else {
                                $pdf->SetXY(65.5, $axisy);
                                $pdf->Cell(0, 0, number_format($billingdata->course_rate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedormforeign($datawVessel->enroledid, $billingdata->id);
                                $this->computemealforeign($datawVessel->enroledid, $this->daysonsite, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetranspoforeign($datawVessel->enroledid, $this->DMT, $this->DMTTotal, $this->foreignrate);
                                $this->computetotal($billingdata->course_rate, $this->mealrate, $this->dorm, $this->bus);

                                $pdf->SetXY(111.3, $axisy);
                                $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(158, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $billingdata->course_rate;
                            }

                            if ($traineenum == $lastnumber) {

                                if ($this->counttrainees > 1) {
                                    $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                                }

                                $pdf->SetXY(57, 178.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(103, 178.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->DMTTotal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(150, 178.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);


                                $pdf->SetXY(155, 182.5);
                                $pdf->Cell(0, 0, number_format($billingdata->bank_charge, 2, '.', ','), 0, 0, 'C', false);


                                $pdf->SetXY(150, 187.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalwvatall + $billingdata->bank_charge, 2, '.', ','), 0, 0, 'C', false);

                                //Roomtype
                                if ($this->roomtype == "CDC") {
                                    $roomtype = "NGH";
                                } else {
                                    $roomtype = $this->roomtype;
                                }
                                $pdf->SetXY(60, 192.2);
                                $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                                $pdf->SetFont($gothic, '', 6.5);

                                $pdf->SetXY(74, 201.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->dorm_rate, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 206);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->meal_rate, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 210.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($billingdata->transpo, 2, '.', ','), 0, 0, 'L', false);


                                //Total Dorm / Meal / Transpo

                                $pdf->SetXY(10, 201.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 206);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 210.5);
                                $pdf->Cell(0, 0, 'USD' . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetFont($gothic, '', 8);
                            }

                            $check = 1;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_SignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 1) {
                                $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath, 20, 260, 30, 30);
                            }

                            $check = 2;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 2) {
                                $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath1, 83, 260, 30, 30);
                            }

                            $check = 3;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_GmSignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 3) {
                                $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath2, 142, 260, 30, 30);
                            }
                        }

                        $axisy += 3;
                        $traineenum++;
                    }
                }
            }

            $pdf->useTemplate($templateId);


            ////////////////
            ///Foreign/////
            ///////////////

        } else {

            ////////////////
            ///Local/////
            ///////////////
            $billingstatementtype2 = tblcompanycourse::where('companyid', $this->companyid)->where('courseid', $this->scheduledata->course->courseid)->first();

            $this->companycourseinfo = tblcompanycourse::where('companyid', $this->companyid)->where('courseid', $scheduleinfo->courseid)->first();

            if ($billingstatementtype2->default_currency == 0) {
                $currency = "PHP";
                $this->concat = "peso";
                $tfRate = $this->companycourseinfo->ratepeso;
                $this->tfRateorig = $tfRate;
                $this->vatvalue = $tfRate * .12;
            } else {
                $currency = "USD";
                $this->concat = "dollar";
                $tfRate = $this->companycourseinfo->rateusd;
                $this->tfRateorig = $tfRate;
                $this->vatvalue = $tfRate * .12;
            }

            $traininginfo = $scheduleinfo->course->coursecode . " / " . $scheduleinfo->course->coursename . "\n (" . date('M. d, Y', strtotime($scheduleinfo->startdateformat)) . " to " . date('M. d, Y', strtotime($scheduleinfo->enddateformat)) . ")";

            if ($scheduleinfo->dateonsitefrom != '0000-00-00' && $scheduleinfo->dateonsiteto != '0000-00-00' && $scheduleinfo->dateonsitefrom != null && $scheduleinfo->dateonsiteto != null &&  $scheduleinfo->dateonsitefrom != '' && $scheduleinfo->dateonsiteto != '') {
                $trainingdate = "(" . date('M. d, Y', strtotime($scheduleinfo->dateonsitefrom)) . " - " . date('M. d, Y', strtotime($scheduleinfo->dateonsiteto)) . ")";
            } else {
                $trainingdate = "( - )";
            }

            if ($billingstatementtype2->billing_statement_template == 1) {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_PESO.pdf");
            } elseif ($billingstatementtype2->billing_statement_template == 2) {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_USD_Foreign.pdf");
            } else {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_USD_Local_wvat.pdf");
            }

            /////////
            /////////
            $pageCount = $pdf->setSourceFile($templatePath);
            // dd($billingstatementtype2->billing_statement_format, $billingstatementtype2->billing_statement_template);
            if ($billingstatementtype2->billing_statement_format == 1 && $billingstatementtype2->billing_statement_template == 1 || $billingstatementtype2->billing_statement_format == 1 && $billingstatementtype2->billing_statement_template == 3) {
                $this->loadTrainees($this->scheduleid, $this->companyid);

                //Single page
                for ($i = 1; $i <= $pageCount; $i++) {
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                    $pdf->setXY(30, 72.5);
                    $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);
                    // $pdf->MultiCell(0,0,$clientinfo->client_information,0,"L",false,0,0,0,true,0,true);

                    $comment = $this->getCommentsNotes($this->scheduleid);
                    if ($comment == NULL || $comment == "") {
                        $comment = "";
                    } else {
                        $comment = '(' . $comment . ')';
                    }

                    $pdf->SetFont($gothicI, '', 7);
                    $pdf->setXY(29, 123.5);
                    $pdf->MultiCell(0, 0, $comment, 0, "L", false);


                    $pdf->SetFont($gothic, '', 8);
                    $pdf->setXY(144, 72.5);
                    $pdf->Cell(0, 0, date('M. d, Y', strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                    $pdf->setXY(51, 109.5);
                    $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    // $pdf->setXY(51, 111.5);
                    // $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    // check if trainees is checkedin
                    $checkInData = null;
                    $pdf->setXY(100, 119);
                    $pdf->Cell(
                        0,
                        0,
                        $checkInData != null ?
                            Carbon::parse($scheduleinfo->dateonsitefrom)->format('M. d, Y') . " - " . Carbon::parse($scheduleinfo->dateonsiteto)->format('M. d, Y')
                            : $trainingdate,
                        0,
                        0,
                        "L"
                    );

                    $pdf->setXY(158, 18.7);
                    $pdf->Cell(0, 0, $istransferred ? $this->trainees[0]->serialnumber : $this->trainees[0]->billingserialnumber, 0, 0, "C");
                    //PO number
                    // $pdf->setXY(158, 24.7);
                    // $pdf->Cell(0, 0, $this->trainees[0]->billingserialnumber, 0, 0, "C");
                    $y = 131;
                    $count = 1;

                    $templateId = $pdf->importPage($i);
                    $pdf->useTemplate($templateId);
                    $traineenum = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->discount == 1) {
                            $tfrate = $tfRate * (1 - 7.5 / 100);
                        } else {
                            $tfrate = $tfRate;
                        }
                        //name
                        $this->DMT = 0;
                        if ($traineenum == 1) {

                            $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $y);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);


                            //training fee
                            // $pdf->SetXY(0, $y);
                            // $pdf->Cell(0, 0, $currency . "    " . number_format($tfRate,2,'.',','), 0, 0, 'C', false);
                            $pdf->SetXY(0, $y);
                            $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            $pdf->SetXY(57, $y);
                            $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                            //total w/ vat
                            $pdf->SetXY(103, $y);
                            $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            //total wo/ vat
                            $pdf->SetXY(150, $y);
                            $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                        } else {
                            $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $y);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            //training fee
                            $pdf->SetXY(9, $y);
                            $pdf->Cell(0, 0, number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            $pdf->SetXY(66, $y);
                            $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                            //total w/ vat
                            $pdf->SetXY(111, $y);
                            $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            //total wo/ vat
                            $pdf->SetXY(158, $y);
                            $pdf->Cell(0, 0, number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            if ($count == 12) {
                                $pdf->SetXY(100, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(125, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(150, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(173, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(173, 183);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(173, 188);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(80, 201.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(80, 206);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);


                                $pdf->SetXY(80, 210.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(103, 201.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(103, 206);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(103, 210.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $check = 1;
                                foreach ($this->trainees as $data) {

                                    if ($data->is_SignatureAttached != 1) {
                                        $check = 0;
                                    }
                                }

                                if ($check == 1) {
                                    $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                    // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                    $pdf->Image($imagePath, 20, 260, 30, 30);
                                }

                                $check = 2;
                                foreach ($this->trainees as $data) {

                                    if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                        $check = 0;
                                    }
                                }

                                if ($check == 2) {
                                    $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                    // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                    $pdf->Image($imagePath1, 83, 260, 30, 30);
                                }

                                $check = 3;
                                foreach ($this->trainees as $data) {

                                    if ($data->is_GmSignatureAttached != 1) {
                                        $check = 0;
                                    }
                                }

                                if ($check == 3) {
                                    $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                    // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                    $pdf->Image($imagePath2, 142, 260, 30, 30);
                                }

                                if (count($this->trainees) > 12) {
                                    $pdf->SetXY(1.5, 290.5);
                                    $pdf->Cell(0, 0, "Page 1 of 2", 0, 0, 'C', false);
                                }
                            }
                        }



                        $y = $y + 3.5;
                        $this->totaltrainingfee += $tfrate;


                        if ($count >= 13) {
                            $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                            $pdf->setXY(30, 72.5);
                            $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                            $pdf->setXY(150, 72.5);
                            $pdf->Cell(0, 0, date("M. d, Y", strtotime(now('Asia/Manila'))), 0, 0, "C");

                            $pdf->setXY(52, 109.5);
                            $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false);

                            $pdf->setXY(100, 119);
                            $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");
                            $y = 131;
                            $count = 1;

                            $pdf->SetXY(1.5, 290.5);
                            $pdf->Cell(0, 0, "Page 2 of 2", 0, 0, 'C', false);
                        }

                        $count++;
                        $traineenum++;
                    }

                    $this->totaltrainingfee = $this->totaltrainingfee;

                    $pdf->SetXY(0, 178.5);

                    if ($this->counttrainees > 1) {
                        $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                    }

                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(57, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(103, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(150, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(158, 182.5);
                    // $pdf->Cell(0, 0, $currency . "    " . $this->totalVAT, 0, 0, 'C', false);
                    $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(162, 187.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 'C', false);

                    //Dorm Info
                    if ($this->roomtype == "CDC") {
                        $roomtype = "NGH";
                    } else {
                        $roomtype = $this->roomtype;
                    }
                    $pdf->SetXY(60, 192.2);
                    $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                    $pdf->SetFont($gothic, '', 6.5);

                    //company courseinfo
                    $ratedorm = 'dorm_price_' . $this->concat;
                    $ratemeal = 'meal_price_' . $this->concat;
                    $ratetranspo = 'transpo_fee_' . $this->concat;
                    $pdf->SetXY(74, 201.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 206);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 210.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);




                    //Total Dorm / Meal / Transpo
                    $pdf->SetXY(10, 201.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(10, 206);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(10, 210.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetFont($gothic, '', 8);

                    $check = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->is_SignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 1) {
                        $imagePath = public_path('storage/uploads/esign/JCsig.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath, 20, 260, 30, 30);
                    }

                    $check = 2;
                    foreach ($this->trainees as $data) {

                        if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 2) {
                        $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath1, 83, 260, 30, 30);
                    }

                    $check = 3;
                    foreach ($this->trainees as $data) {

                        if ($data->is_GmSignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 3) {
                        $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath2, 142, 260, 30, 30);
                    }

                    $templateId = $pdf->importPage($i);
                    $pdf->useTemplate($templateId);
                }
            } elseif ($billingstatementtype2->billing_statement_format == 2 && $billingstatementtype2->billing_statement_template == 1 || $billingstatementtype2->billing_statement_format == 2 && $billingstatementtype2->billing_statement_template == 3) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $traineenum = 1;
                foreach ($this->trainees as $data) {

                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                        //client info

                        $pdf->setXY(30, 72.5);
                        $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);
                        // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);

                        $comment = $this->getCommentsNotes($this->scheduleid);
                        if ($comment == NULL || $comment == "") {
                            $comment = "";
                        } else {
                            $comment = '(' . $comment . ')';
                        }

                        $pdf->SetFont($gothicI, '', 7);
                        $pdf->setXY(29, 123.5);
                        $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                        $pdf->SetFont($gothic, '', 8);

                        $pdf->setXY(144, 72.5);
                        $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");



                        $templateId = $pdf->importPage($i);
                        $pdf->useTemplate($templateId);

                        $pdf->setXY(51, 109.5);
                        $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                        $pdf->setXY(100, 119);
                        $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                        if ($i == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");
                        }

                        $this->DMT = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, 131);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        if ($i == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");
                        }

                        if ($data->discount == 1) {
                            $tfrate = $tfRate * (1 - 7.5 / 100);
                        } else {
                            $tfrate = $tfRate;
                        }

                        //training fee
                        $pdf->SetXY(0, 131);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);


                        //DMT
                        $this->computedorm($data->enroledid);
                        $this->getbuscount($data->enroledid);
                        $this->getmealcount($data->enroledid);
                        $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                        $pdf->SetXY(57, 131);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                        //total w/ vat
                        $pdf->SetXY(103, 131);
                        $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                        //total wo/ vat
                        $pdf->SetXY(150, 131);
                        $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                        $this->totaltrainingfee += $tfrate;

                        if ($this->counttrainees > 1) {
                            $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                        }

                        $pdf->SetXY(0, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(57, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(103, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(150, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(158, 182.5);
                        // $pdf->Cell(0, 0, $currency . "    " . $this->totalVAT, 0, 0, 'C', false);
                        // $pdf->Cell(0, 0, number_format($this->totalVAT,2,'.',','), 0, 0, 'C', false);
                        $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);


                        $pdf->SetXY(150, 187.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                        //Roomtype
                        if ($this->roomtype == "CDC") {
                            $roomtype = "NGH";
                        } else {
                            $roomtype = $this->roomtype;
                        }

                        $pdf->SetXY(60, 192.2);
                        $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                        $pdf->SetFont($gothic, '', 6.5);

                        //company courseinfo
                        $ratedorm = 'dorm_price_' . $this->concat;
                        $ratemeal = 'meal_price_' . $this->concat;
                        $ratetranspo = 'transpo_fee_' . $this->concat;
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);


                        //Total Dorm / Meal / Transpo

                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetFont($gothic, '', 8);
                        $check = 1;
                        foreach ($this->trainees as $data) {

                            if ($data->is_SignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 1) {
                            $imagePath = public_path('storage/uploads/esign/JCsig.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath, 20, 260, 30, 30);
                        }

                        $check = 2;
                        foreach ($this->trainees as $data) {

                            if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 2) {
                            $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath1, 83, 260, 30, 30);
                        }

                        $check = 3;
                        foreach ($this->trainees as $data) {

                            if ($data->is_GmSignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 3) {
                            $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath2, 142, 260, 30, 30);
                        }
                    }

                    $traineenum++;
                }
            } elseif ($billingstatementtype2->billing_statement_format == 3 && $billingstatementtype2->billing_statement_template == 1 || $billingstatementtype2->billing_statement_format == 3 && $billingstatementtype2->billing_statement_template == 3) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $y = 0;
                $uniqueVessels = array();
                foreach ($this->trainees as $vessel) {
                    $vessel = $vessel->vessel;
                    if (!in_array($vessel, $uniqueVessels)) {
                        $uniqueVessels[] = $vessel;
                    }
                }

                foreach ($uniqueVessels as $vessel => $vesselid) {
                    $traineenum = 1;
                    $this->loadTraineeswVessel($this->scheduleid, $this->companyid, $vesselid);
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                    $axisy = 134;
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;

                    foreach ($this->traineeswVessel as $datawVessel) {
                        $lastnumber = count($this->traineeswVessel);
                        if ($datawVessel->billingserialnumber == NULL || $datawVessel->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $datawVessel->enroledid, $this->companyid);
                        }
                        for ($i = 1; $i <= $pageCount; $i++) {
                            // client info
                            if ($traineenum == 1) {
                                $pdf->setXY(30, 72.5);
                                $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                                $comment = $this->getCommentsNotes($this->scheduleid);
                                if ($comment == NULL || $comment == "") {
                                    $comment = "";
                                } else {
                                    $comment = '(' . $comment . ')';
                                }

                                $pdf->SetFont($gothicI, '', 7);
                                $pdf->setXY(29, 125.5);
                                $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                                $pdf->SetFont($gothic, '', 8);

                                // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);

                                $pdf->setXY(144, 72.5);
                                $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->traineeswVessel[0]->datebilled)), 0, 0, "C");

                                $templateId = $pdf->importPage($i);
                                $pdf->useTemplate($templateId);

                                $pdf->setXY(158, 18.7);
                                $pdf->Cell(0, 0, $datawVessel->billingserialnumber, 0, 0, "C");


                                $pdf->setXY(51, 109.5);
                                $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                                $pdf->setXY(100, 119);
                                $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                                $pdf->setXY(29, 122);
                                $pdf->Cell(0, 0, "Vessel Name: (" . $datawVessel->vesselname . ")", 0, 0, "L");
                            }

                            $this->DMT = 0;
                            $fullname = $datawVessel->l_name . ", " . $datawVessel->f_name . " " . substr($datawVessel->m_name, 0, 1) . '.' . " " . $datawVessel->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $axisy);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($datawVessel->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            if ($traineenum == 1) {
                                if ($datawVessel->discount == 1) {
                                    $tfrate = $tfRate * (1 - 7.5 / 100);
                                } else {
                                    $tfrate = $tfRate;
                                }
                                $pdf->SetXY(0, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(50, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(104, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(150, $axisy);
                                $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            } else {
                                if ($datawVessel->discount == 1) {
                                    $tfrate = $tfRate * (1 - 7.5 / 100);
                                } else {
                                    $tfrate = $tfRate;
                                }
                                $pdf->SetXY(8, $axisy);
                                $pdf->Cell(0, 0, number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(58, $axisy);
                                $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(112.5, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(158, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            }

                            if ($traineenum == $lastnumber) {

                                if ($this->counttrainees > 1) {
                                    $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                                }

                                $pdf->SetXY(0, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(50, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(104, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(150, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                                // $pdf->SetXY(155, 182.5);
                                // $pdf->Cell(0, 0, number_format($billingstatementtype->bank_charge,2,'.',','), 0, 0, 'C', false);

                                // $totalwovatallwBC = $billingstatementtype->bank_charge + $this->totalwovatall;
                                // $pdf->SetXY(150, 187.5);
                                // $pdf->Cell(0, 0, $currency . "    " . number_format($totalwovatallwBC,2,'.',','), 0, 0, 'C', false);

                                $pdf->SetXY(155, 182.5);
                                $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);


                                $pdf->SetXY(150, 187.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                //Roomtype
                                if ($this->roomtype == "CDC") {
                                    $roomtype = "NGH";
                                } else {
                                    $roomtype = $this->roomtype;
                                }
                                $pdf->SetXY(60, 192.2);
                                $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                                $pdf->SetFont($gothic, '', 6.5);

                                //company courseinfo
                                $ratedorm = 'dorm_price_' . $this->concat;
                                $ratemeal = 'meal_price_' . $this->concat;
                                $ratetranspo = 'transpo_fee_' . $this->concat;
                                $pdf->SetXY(74, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);


                                //Total Dorm / Meal / Transpo

                                $pdf->SetXY(10, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetFont($gothic, '', 8);
                            }

                            $check = 1;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_SignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 1) {
                                $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath, 20, 260, 30, 30);
                            }

                            $check = 2;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 2) {
                                $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath1, 83, 260, 30, 30);
                            }

                            $check = 3;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_GmSignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 3) {
                                $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath2, 142, 260, 30, 30);
                            }
                        }

                        $axisy += 3;
                        $traineenum++;
                    }
                }
            } elseif ($billingstatementtype2->billing_statement_format == 1 && $billingstatementtype2->billing_statement_template == 2) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                //Single page
                for ($i = 1; $i <= $pageCount; $i++) {
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                    //client info
                    $pdf->setXY(30, 72.5);
                    $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 0, '', '', true, 0, true);

                    $comment = $this->getCommentsNotes($this->scheduleid);
                    if ($comment == NULL || $comment == "") {
                        $comment = "";
                    } else {
                        $comment = '(' . $comment . ')';
                    }

                    $pdf->SetFont($gothicI, '', 7);
                    $pdf->setXY(29, 123.5);
                    $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                    $pdf->SetFont($gothic, '', 8);

                    $pdf->setXY(144, 72.5);
                    $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                    $pdf->setXY(51, 109.5);
                    $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    $pdf->setXY(100, 119);
                    $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                    $y = 131;
                    $traineenum = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->billingserialnumber == NULL || $data->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $data->enroledid, $this->companyid);
                        }

                        if ($data->discount == 1) {
                            $tfrate = $tfRate * (1 - 7.5 / 100);
                        } else {
                            $tfrate = $tfRate;
                        }
                        //name
                        $this->DMT = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, $y);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        if ($traineenum == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");

                            //training fee
                            $pdf->SetXY(57, $y);
                            $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($tfrate), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            $pdf->SetXY(4, 131);
                            $pdf->Cell(0, 0, $data->nationality, 0, 0, 'C', false);

                            $pdf->SetXY(103, $y);
                            $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMT), 0, 0, 'C', false);


                            //total wo/ vat
                            $pdf->SetXY(150, $y);
                            $pdf->Cell(0, 0, $currency . "   " . $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        } else {
                            //training fee
                            $pdf->SetXY(65, $y);
                            $pdf->Cell(0, 0, $this->numberformat($tfrate), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            $pdf->SetXY(4, $y);
                            $pdf->Cell(0, 0, $data->nationality, 0, 0, 'C', false);

                            $pdf->SetXY(111.5, $y);
                            $pdf->Cell(0, 0, $this->numberformat($this->DMT), 0, 0, 'C', false);

                            //total wo/ vat
                            $pdf->SetXY(158, $y);
                            $pdf->Cell(0, 0, $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        }



                        $y = $y + 3.5;
                        $this->totaltrainingfee += $tfrate;
                        $traineenum++;
                    }

                    if ($this->counttrainees > 1) {
                        $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                    }

                    $pdf->SetXY(57, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(103, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMTtotal), 0, 0, 'C', false);

                    $pdf->SetXY(150, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalwvatall), 0, 0, 'C', false);

                    $pdf->SetXY(158, 182.5);
                    $pdf->Cell(0, 0, $this->numberformat($billingstatementtype2->bank_charge), 0, 0, 'C', false);

                    $totalwovatallwBC = $billingstatementtype2->bank_charge + $this->totalwvatall;
                    $pdf->SetXY(150, 187.5);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($totalwovatallwBC), 0, 0, 'C', false);

                    //Roomtype
                    if ($this->roomtype == "CDC") {
                        $roomtype = "NGH";
                    } else {
                        $roomtype = $this->roomtype;
                    }
                    $pdf->SetXY(60, 192.2);
                    $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                    $pdf->SetFont($gothic, '', 6.5);

                    //company courseinfo
                    $ratedorm = 'dorm_price_' . $this->concat;
                    $ratemeal = 'meal_price_' . $this->concat;
                    $ratetranspo = 'transpo_fee_' . $this->concat;
                    $pdf->SetXY(74, 201.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 206);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                    $pdf->SetXY(74, 210.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);

                    //Total Dorm / Meal / Transpo

                    $pdf->SetXY(10, 201.5);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totaldorm), 0, 0, 'C', false);

                    $pdf->SetXY(10, 206);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalmeal), 0, 0, 'C', false);

                    $pdf->SetXY(10, 210.5);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalbus), 0, 0, 'C', false);

                    $pdf->SetFont($gothic, '', 8);


                    $check = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->is_SignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 1) {
                        $imagePath = public_path('storage/uploads/esign/JCsig.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath, 20, 260, 30, 30);
                    }

                    $check = 2;
                    foreach ($this->trainees as $data) {

                        if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 2) {
                        $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath1, 83, 260, 30, 30);
                    }

                    $check = 3;
                    foreach ($this->trainees as $data) {

                        if ($data->is_GmSignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 3) {
                        $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath2, 142, 260, 30, 30);
                    }

                    $templateId = $pdf->importPage($i);
                    $pdf->useTemplate($templateId);
                }
            } elseif ($billingstatementtype2->billing_statement_format == 2 && $billingstatementtype2->billing_statement_template == 2) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $traineenum = 1;
                foreach ($this->trainees as $data) {

                    if ($data->discount == 1) {
                        $tfrate = $tfRate * (1 - 7.5 / 100);
                    } else {
                        $tfrate = $tfRate;
                    }

                    if ($data->billingserialnumber == NULL || $data->billingserialnumber == "") {
                        $this->generateserialnumber(session('scheduleid'), $data->enroledid, $this->companyid);
                    }
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                        //client info

                        $pdf->setXY(30, 72.5);
                        // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);
                        $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);



                        $pdf->setXY(144, 72.5);
                        $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                        $templateId = $pdf->importPage($i);
                        $pdf->useTemplate($templateId);

                        $pdf->setXY(51, 109.5);
                        $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                        $pdf->setXY(100, 119);
                        $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                        $pdf->setXY(158, 18.7);
                        $pdf->Cell(0, 0, $istransferred ? $this->trainees[0]->serialnumber : $this->trainees[0]->billingserialnumber, 0, 0, "C");


                        $comment = $this->getCommentsNotes($this->scheduleid);
                        if ($comment == NULL || $comment == "") {
                            $comment = "";
                        } else {
                            $comment = '(' . $comment . ')';
                        }

                        $pdf->SetFont($gothicI, '', 7);
                        $pdf->setXY(29, 123.5);
                        $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                        $pdf->SetFont($gothic, '', 8);

                        $this->DMT = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, 131);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        $pdf->SetXY(57, 131);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat(round($tfrate)), 0, 0, 'C', false);

                        //DMT
                        $this->computedorm($data->enroledid);
                        $this->getbuscount($data->enroledid);
                        $this->getmealcount($data->enroledid);
                        $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                        $pdf->SetXY(103, 131);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMT), 0, 0, 'C', false);

                        //total wo/ vat
                        $pdf->SetXY(150, 131);
                        $pdf->Cell(0, 0, $currency . "   " . $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        $this->totaltrainingfee += $tfrate;

                        if ($this->counttrainees > 1) {
                            $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                        }

                        $pdf->SetXY(57, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(103, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMTtotal), 0, 0, 'C', false);

                        $pdf->SetXY(150, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalwvatall), 0, 0, 'C', false);

                        $pdf->SetXY(155, 182.5);
                        $pdf->Cell(0, 0, $this->numberformat($billingstatementtype2->bank_charge), 0, 0, 'C', false);

                        $totalwovatallwBC = $billingstatementtype2->bank_charge + $this->totalwvatall;
                        $pdf->SetXY(150, 187.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($totalwovatallwBC), 0, 0, 'C', false);

                        //Roomtype
                        if ($this->roomtype == "CDC") {
                            $roomtype = "NGH";
                        } else {
                            $roomtype = $this->roomtype;
                        }
                        $pdf->SetXY(60, 192.2);
                        $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                        $pdf->SetFont($gothic, '', 6.5);

                        //company courseinfo
                        $ratedorm = 'dorm_price_' . $this->concat;
                        $ratemeal = 'meal_price_' . $this->concat;
                        $ratetranspo = 'transpo_fee_' . $this->concat;
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);

                        //Total Dorm / Meal / Transpo

                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totaldorm), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalmeal), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalbus), 0, 0, 'C', false);

                        $pdf->SetFont($gothic, '', 8);

                        $check = 1;
                        foreach ($this->trainees as $data) {

                            if ($data->is_SignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 1) {
                            $imagePath = public_path('storage/uploads/esign/JCsig.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath, 20, 260, 30, 30);
                        }

                        $check = 2;
                        foreach ($this->trainees as $data) {

                            if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 2) {
                            $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath1, 83, 260, 30, 30);
                        }

                        $check = 3;
                        foreach ($this->trainees as $data) {

                            if ($data->is_GmSignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 3) {
                            $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath2, 142, 260, 30, 30);
                        }
                    }

                    $traineenum++;
                }
            } elseif ($billingstatementtype2->billing_statement_format == 3 && $billingstatementtype2->billing_statement_template == 2) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $y = 0;

                $uniqueVessels = array();
                foreach ($this->trainees as $vessel) {
                    $vessel = $vessel->vessel;
                    if (!in_array($vessel, $uniqueVessels)) {
                        $uniqueVessels[] = $vessel;
                    }
                }

                foreach ($uniqueVessels as $vessel => $vesselid) {
                    $traineenum = 1;
                    $this->loadTraineeswVessel($this->scheduleid, $this->companyid, $vesselid);
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                    $axisy = 134;
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;

                    foreach ($this->traineeswVessel as $datawVessel) {
                        $lastnumber = count($this->traineeswVessel);
                        if ($datawVessel->billingserialnumber == NULL || $datawVessel->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $datawVessel->enroledid, $this->companyid);
                        }

                        for ($i = 1; $i <= $pageCount; $i++) {

                            // client info
                            if ($datawVessel->discount == 1) {
                                $tfrate = $tfRate * (1 - 7.5 / 100);
                            } else {
                                $tfrate = $tfRate;
                            }

                            if ($traineenum == 1) {
                                $pdf->setXY(30, 72.5);
                                $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                                // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);


                                $pdf->setXY(144, 72.5);
                                $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->traineeswVessel[0]->datebilled)), 0, 0, "C");

                                $templateId = $pdf->importPage($i);
                                $pdf->useTemplate($templateId);

                                $pdf->setXY(158, 18.7);
                                $pdf->Cell(0, 0, $datawVessel->billingserialnumber, 0, 0, "C");

                                $comment = $this->getCommentsNotes($this->scheduleid);
                                if ($comment == NULL || $comment == "") {
                                    $comment = "";
                                } else {
                                    $comment = '(' . $comment . ')';
                                }

                                $pdf->SetFont($gothicI, '', 7);
                                $pdf->setXY(29, 123.5);
                                $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                                $pdf->SetFont($gothic, '', 8);


                                $pdf->setXY(51, 109.5);
                                $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                                $pdf->setXY(100, 119);
                                $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                                $pdf->setXY(29, 122);
                                $pdf->Cell(0, 0, "Vessel Name: (" . $datawVessel->vesselname . ")", 0, 0, "L");
                            }

                            $this->DMT = 0;
                            $fullname = $datawVessel->l_name . ", " . $datawVessel->f_name . " " . substr($datawVessel->m_name, 1) . '.' . " " . $datawVessel->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $axisy);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($datawVessel->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            if ($traineenum == 1) {
                                $pdf->SetXY(57, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(103, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(150, $axisy);
                                $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            } else {
                                $pdf->SetXY(65.5, $axisy);
                                $pdf->Cell(0, 0, number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(111.3, $axisy);
                                $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(158, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            }

                            if ($traineenum == $lastnumber) {

                                if ($this->counttrainees > 1) {
                                    $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                                }

                                $pdf->SetXY(57, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(103, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(150, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(155, 182.5);
                                $pdf->Cell(0, 0, number_format($billingstatementtype2->bank_charge, 2, '.', ','), 0, 0, 'C', false);


                                $pdf->SetXY(150, 187.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall + $billingstatementtype2->bank_charge, 2, '.', ','), 0, 0, 'C', false);

                                //Roomtype
                                if ($this->roomtype == "CDC") {
                                    $roomtype = "NGH";
                                } else {
                                    $roomtype = $this->roomtype;
                                }
                                $pdf->SetXY(60, 192.2);
                                $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                                $pdf->SetFont($gothic, '', 6.5);

                                //company courseinfo
                                $ratedorm = 'dorm_price_' . $this->concat;
                                $ratemeal = 'meal_price_' . $this->concat;
                                $ratetranspo = 'transpo_fee_' . $this->concat;
                                $pdf->SetXY(74, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);


                                //Total Dorm / Meal / Transpo

                                $pdf->SetXY(10, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetFont($gothic, '', 8);
                            }

                            $check = 1;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_SignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 1) {
                                $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath, 20, 260, 30, 30);
                            }

                            $check = 2;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 2) {
                                $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath1, 83, 260, 30, 30);
                            }

                            $check = 3;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_GmSignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 3) {
                                $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath2, 142, 260, 30, 30);
                            }
                        }

                        $axisy += 3;
                        $traineenum++;
                    }
                }
            }
        }



        $pdfContents = $pdf->Output('', 'S');

        if (session('generatestatus') == null) {
            // Set the response headers for preview
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Billing Statement - ' . date("M. d, Y", strtotime(now())) . '.pdf"');
            echo $pdfContents;
        } else {
            $newFileName = "billing_statement_schedid(" . json_encode($this->scheduleid) . ")_compid(" . $this->companyid . ").pdf";
            $pdfFilePath = storage_path('app/public/uploads/billingSentToClient/') . $newFileName;
            // Set the response headers for preview
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Billing Statement - ' . date("M. d, Y", strtotime(now())) . '.pdf"');

            // Save the PDF to the file
            file_put_contents($pdfFilePath, $pdfContents);
        }
    }
}
