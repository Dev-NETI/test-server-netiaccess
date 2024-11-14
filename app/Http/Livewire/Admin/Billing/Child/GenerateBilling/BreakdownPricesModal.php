<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\tblbusmonitoring;
use App\Models\tblforeignrate;
use App\Models\tblmealmonitoring;
use App\Traits\DailyWeeklyReportTraits;
use DateTime;
use Livewire\Component;

use function PHPUnit\Framework\isEmpty;

class BreakdownPricesModal extends Component
{
    use DailyWeeklyReportTraits;
    public $trainees, $scheduleid, $companyid, $foreign;
    public $newData = [];

    public function render()
    {
        if ($this->foreign) {
            foreach ($this->trainees as $key1 => $value) {

                if ($this->companyid != 89) {
                    $rate = tblforeignrate::where('companyid', 262)->where('courseid', $value->courseid)->first();
                } else {
                    $rate = tblforeignrate::where('companyid', 89)->where('courseid', $value->courseid)->first();
                }

                if (!empty($value->dormitory)) {
                    $dorm = $value->dorm->dorm;
                    $checkin = date('M. d, Y h:i a', strtotime($value->dormitory->checkindate . ' ' . $value->dormitory->checkintime));
                    $checkindate = new DateTime($value->dormitory->checkindate);

                    if ($value->dormitory->checkoutdate == NULL) {
                        $checkout = 'No Checkout Date';
                        $checkoutdate = '-';
                        $days = '-';
                    } else {
                        $checkout = date('M. d, Y h:i a', strtotime($value->dormitory->checkoutdate . ' ' . $value->dormitory->checkouttime));
                        $checkoutdate = new DateTime($value->dormitory->checkoutdate);
                        $checkoutdate->modify('+1 day');
                        $days = $checkindate->diff($checkoutdate)->days;
                    }
                    $dormcourserate = $rate->dorm_rate;
                } else {
                    $dormcourserate = 'Not Availed';
                    $checkin = '-';
                    $checkout = '-';
                    $days = "-";
                    $dorm = 'Not Availed';
                }

                $mealcount = tblmealmonitoring::where('enroledid', $value->enroledid)->count();
                $meal = tblmealmonitoring::where('enroledid', $value->enroledid)->get();
                $transpocount = tblbusmonitoring::where('enroledid', $value->enroledid)->count();
                $transpo = tblbusmonitoring::select('divideto')->where('enroledid', $value->enroledid)->get();
                $divideto = [];

                $mealbreakdown = [];
                if (empty($meal)) {
                    $mealbreakdown['breakfast'] = 0;
                    $mealbreakdown['lunch'] = 0;
                    $mealbreakdown['dinner'] = 0;
                } else {
                    $bf = 0;
                    $lh = 0;
                    $dn = 0;
                    foreach ($meal as $valuemeal) {
                        switch ($valuemeal->mealtype) {
                            case 2:
                                $lh++;
                                break;

                            case 3:
                                $dn++;
                                break;

                            default:
                                $bf++;
                                break;
                        }

                        $mealbreakdown['breakfast'] = $bf;
                        $mealbreakdown['lunch'] = $lh;
                        $mealbreakdown['dinner'] = $dn;
                    }
                }

                if (!$transpo->isEmpty()) {
                    foreach ($transpo as $key => $transpos) {
                        $divideto[$key] = $transpos->divideto;
                    }
                    $divideto = implode(',', $divideto);
                } else {
                    $divideto = 0;
                }

                $sd = new DateTime($value->schedule->dateonsitefrom);
                $ed = new DateTime($value->schedule->dateonsiteto);
                $ed->modify('+1 day');
                $trainingdays = $sd->diff($ed);

                $this->newData[$key1] = [
                    'enroledid' => $value->enroledid,
                    'name' => $value->trainee->rank->rankacronym . ' / ' . $value->trainee->rank->rank . ' - ' . $value->trainee->f_name . ' ' . $value->trainee->l_name,
                    'dormrate' => $dormcourserate,
                    'dormtype' => $dorm,
                    'checkinam' => $rate->dorm_am_checkin,
                    'checkinpm' => $rate->dorm_pm_checkin,
                    'checkoutam' => $rate->dorm_am_checkout,
                    'checkoutpm' => $rate->dorm_pm_checkout,
                    'checkin' => $checkin,
                    'checkout' => $checkout,
                    'days' => $days,
                    'trainingdays' => $trainingdays->days,
                    'mealrate' => $rate->meal_rate,
                    'breakfast' => $rate->bf_rate,
                    'lunch' => $rate->lh_rate,
                    'dinner' => $rate->dn_rate,
                    'mealcount' => $mealcount,
                    'meal' => $mealbreakdown,
                    'transporate' => $rate->transpo,
                    'divideto' => $divideto,
                    'transpocount' => $transpocount,
                ];
            }
        } else {
            foreach ($this->trainees as $key => $value) {
                if (!empty($value->dormitory)) {

                    if ($value->dormitory->checkoutdate == null) {
                        $dormcourserate = $this->getRoomPrice($value->courseid, $value->trainee->company_id, $value->dormid);
                        $checkin = date('M. d, Y h:i a', strtotime($value->dormitory->checkindate . ' ' . $value->dormitory->checkintime));
                        $checkindate = new DateTime($value->dormitory->checkindate);
                        $dorm = $value->dorm->dorm;
                        $checkout = 'No check out';
                        $days = "-";
                    } else {
                        $dormcourserate = $this->getRoomPrice($value->courseid, $value->trainee->company_id, $value->dormid);
                        $checkin = date('M. d, Y h:i a', strtotime($value->dormitory->checkindate . ' ' . $value->dormitory->checkintime));
                        $checkout = date('M. d, Y h:i a', strtotime($value->dormitory->checkoutdate . ' ' . $value->dormitory->checkouttime));
                        $checkindate = new DateTime($value->dormitory->checkindate);
                        $checkoutdate = new DateTime($value->dormitory->checkoutdate);
                        $checkoutdate->modify('+1 day');
                        $days = $checkindate->diff($checkoutdate)->days;
                        $dorm = $value->dorm->dorm;
                    }
                } else {
                    $dormcourserate = 'Not Availed';
                    $checkin = '-';
                    $checkout = '-';
                    $days = "-";
                    $dorm = "Not Availed";
                }

                $mealrate = $this->getMealPrice($value->courseid, $value->trainee->company_id);
                $transporate = $this->getTranspoPrice($value->courseid, $value->trainee->company_id);
                $mealcount = tblmealmonitoring::where('enroledid', $value->enroledid)->count();
                $transpocount = tblbusmonitoring::where('enroledid', $value->enroledid)->count();

                $sd = new DateTime($value->schedule->dateonsitefrom);
                $ed = new DateTime($value->schedule->dateonsiteto);
                $ed->modify('+1 day');
                $trainingdays = $sd->diff($ed);

                $this->newData[$key] = [
                    'enroledid' => $value->enroledid,
                    'name' => $value->trainee->rank->rankacronym . ' / ' . $value->trainee->rank->rank . ' - ' . $value->trainee->f_name . ' ' . $value->trainee->l_name,
                    'dormrate' => $dormcourserate,
                    'dormtype' => $dorm,
                    'checkin' => $checkin,
                    'checkout' => $checkout,
                    'days' => $days,
                    'trainingdays' => $trainingdays->days,
                    'mealrate' => $mealrate,
                    'mealcount' => $mealcount,
                    'transporate' => $transporate,
                    'transpocount' => $transpocount,
                ];
            }
        }

        return view('livewire.admin.billing.child.generate-billing.breakdown-prices-modal');
    }
}
