<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\ClientInformation;
use App\Models\tblbillingstatement;
use App\Models\tblcompanycourse;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblexportedbillingstatement;
use App\Models\tblforeignrate;
use App\Models\tblnyksmcompany;
use GuzzleHttp\Client;
use Hamcrest\Arrays\IsArray;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;

use function PHPUnit\Framework\isEmpty;

class GenerateBillingComponent extends Component
{
    use ConsoleLog;
    public $companyid;
    public $companyid2;
    public $scheduleid;
    public $default_recipient;
    public $billingstatusid, $transfercompanyID;
    public $fileUrl;

    public function render()
    {
        if ($this->companyid2 == NULL) {
            $this->companyid2 = session('companyid');
        }
        $client_information = ClientInformation::where('company_id', $this->companyid2)->get();
        // ->orderBy('is_active', 'DESC')
        return view('livewire.admin.billing.child.generate-billing.generate-billing-component', compact('client_information'));
    }

    public function updatedDefaultRecipient($value)
    {
        if ($value) {
            Session::put('client_information', $value);
        }
    }

    public function passSessionData()
    {
        $scheduleinfo = tblcourseschedule::where('scheduleid', $this->scheduleid)->first();
        if ($this->transfercompanyID) {
            $nykComps = tblnyksmcompany::getcompanyid();

            if (in_array($this->companyid, $nykComps)) {
                $companycourseinfo = tblforeignrate::where('companyid', 262)->where('courseid', $scheduleinfo->courseid)->count();
                $company = $this->companyid2;
                $trainees = tblenroled::with('trainee')
                    ->whereIn('scheduleid', $this->scheduleid)
                    ->where('dropid', 0)
                    ->where('IsRemedial', 0)
                    ->where('istransferedbilling', 1)
                    ->where('deletedid', 0)
                    ->where('nabillnaid', 0);
                $traineesCount = $trainees->where('deletedid', 0)
                    ->where('attendance_status', 0)
                    ->where('istransferedbilling', 1)
                    ->where('reservationstatusid', '!=', 4)
                    ->count();
            } else {
                if (!is_array($this->scheduleid)) {
                    $scheduleid = explode(',', $this->scheduleid);
                } else {
                    $scheduleid = $this->scheduleid;
                }

                $companycourseinfo = tblcompanycourse::where('companyid', $this->companyid)->where('courseid', $scheduleinfo->courseid)->count();
                $trainees = tblenroled::with('trainee')
                    ->whereIn('scheduleid', $scheduleid)
                    ->where('dropid', 0)
                    ->where('deletedid', 0)
                    ->where('IsRemedial', 0)
                    ->where('nabillnaid', 0)
                    ->where('istransferedbilling', 1)
                    ->where('attendance_status', 0)
                    ->where('reservationstatusid', '!=', 4)->first();

                $traineesCount = tblenroled::with('trainee')
                    ->whereIn('scheduleid', $scheduleid)
                    ->where('dropid', 0)
                    ->where('deletedid', 0)
                    ->where('attendance_status', 0)
                    ->where('istransferedbilling', 1)
                    ->where('nabillnaid', 0)
                    ->where('reservationstatusid', '!=', 4)
                    ->count();

                $scheduleid = null;
            }

            if ($traineesCount > 0) {

                if ($companycourseinfo == 0) {
                    $this->dispatchBrowserEvent('confirmationbilling', [
                        'position' =>  'center',
                        'icon' => 'error',
                        'title' => 'Meal / Dorm / Tranpo / Training Fee Not Found!',
                        'confirmbtn' => true,
                        'confirmbtntxt' => 'Ok'
                    ]);
                } else {
                    if (in_array($this->companyid, $nykComps)) {
                        Session::put('companyid2', 262);
                    } else {
                        Session::put('companyid2', NULL);
                    }
                    Session::put('companyid', $this->companyid);
                    Session::put('scheduleid', $this->scheduleid);
                    Session::put('billing_status_id', $this->billingstatusid);

                    $check = null;
                    $check1 = null;

                    if (is_array($this->scheduleid)) {
                        $count1 = count($this->scheduleid);
                        if ($count1 == 1) {
                            $scheduleid = implode(',', $this->scheduleid);
                        } else {
                            $scheduleid = implode(',', $this->scheduleid);
                        }
                        $count = 0;
                    } else {

                        if (!is_array($this->scheduleid)) {
                            $scheduleid = explode(',', $this->scheduleid);
                        }

                        $count = count($scheduleid);

                        $scheduleid = null;
                    }

                    $company = Auth::user()->u_type;

                    $route = Auth::user()->billing_statement_route;
                    return redirect()->route($route);
                }
            } else {
                $this->dispatchBrowserEvent('confirmationbilling', [
                    'position' =>  'center',
                    'icon' => 'error',
                    'title' => 'No trainees found!',
                    'confirmbtn' => true,
                    'confirmbtntxt' => 'Ok'
                ]);
            }
        } else {
            $nykComps = tblnyksmcompany::getcompanyid();

            if (in_array($this->companyid, $nykComps)) {
                $companycourseinfo = tblforeignrate::where('companyid', 262)->where('courseid', $scheduleinfo->courseid)->count();
                $company = $this->companyid2;
                $trainees = tblenroled::with('trainee')
                    ->whereIn('scheduleid', $this->scheduleid)
                    ->where('dropid', 0)
                    ->where('IsRemedial', 0)
                    ->where('deletedid', 0)
                    ->where('nabillnaid', 0)
                    ->whereHas('trainee', function ($query) use ($nykComps, $company) {
                        $query->whereIn('company_id', $nykComps);
                        if ($company == 89) {
                            $query->where('nationalityid', 51);
                        } else {
                            $query->where('nationalityid', '!=', 51);
                        }
                    })->get();
                $traineesCount = $trainees->where('deletedid', 0)
                    ->where('attendance_status', 0)
                    ->where('reservationstatusid', '!=', 4)
                    ->count();
            } else {
                if (!is_array($this->scheduleid)) {
                    $scheduleid = explode(',', $this->scheduleid);
                } else {
                    $scheduleid = $this->scheduleid;
                }

                $companycourseinfo = tblcompanycourse::where('companyid', $this->companyid)->where('courseid', $scheduleinfo->courseid)->count();
                $trainees = tblenroled::with('trainee')
                    ->whereIn('scheduleid', $scheduleid)
                    ->where(function ($query) {
                        $query->whereHas('trainee', function ($query) {
                            $query->where('company_id', $this->companyid);
                        })
                            ->orWhere('companyid', $this->companyid);
                    })
                    ->where('dropid', 0)
                    ->where('deletedid', 0)
                    ->where('IsRemedial', 0)
                    ->where('nabillnaid', 0)
                    ->where('attendance_status', 0)
                    ->where('reservationstatusid', '!=', 4)->first();

                $traineesCount = tblenroled::with('trainee')
                    ->whereIn('scheduleid', $scheduleid)
                    ->where(function ($query) {
                        $query->whereHas('trainee', function ($query) {
                            $query->where('company_id', $this->companyid);
                        })
                            ->orWhere('companyid', $this->companyid);
                    })
                    ->where('dropid', 0)
                    ->where('deletedid', 0)
                    ->where('attendance_status', 0)
                    ->where('nabillnaid', 0)
                    ->where('reservationstatusid', '!=', 4)
                    ->count();

                $scheduleid = null;
            }

            if ($traineesCount > 0) {

                if ($companycourseinfo == 0) {
                    $this->dispatchBrowserEvent('confirmationbilling', [
                        'position' =>  'center',
                        'icon' => 'error',
                        'title' => 'Meal / Dorm / Tranpo / Training Fee Not Found!',
                        'confirmbtn' => true,
                        'confirmbtntxt' => 'Ok'
                    ]);
                } else {
                    if (in_array($this->companyid, $nykComps)) {
                        Session::put('companyid2', 262);
                    } else {
                        Session::put('companyid2', NULL);
                    }
                    Session::put('companyid', $this->companyid);
                    Session::put('scheduleid', $this->scheduleid);
                    Session::put('billing_status_id', $this->billingstatusid);

                    $check = null;
                    $check1 = null;

                    if (is_array($this->scheduleid)) {
                        $count1 = count($this->scheduleid);
                        if ($count1 == 1) {
                            $scheduleid = implode(',', $this->scheduleid);
                        } else {
                            $scheduleid = implode(',', $this->scheduleid);
                        }
                        $count = 0;
                    } else {

                        if (!is_array($this->scheduleid)) {
                            $scheduleid = explode(',', $this->scheduleid);
                        }

                        $count = count($scheduleid);

                        $scheduleid = null;
                    }

                    $company = Auth::user()->u_type;

                    $nykComps = tblnyksmcompany::getcompanyid();
                    if (in_array($this->companyid, $nykComps)) {
                        if ($this->companyid == 89) {
                            # code...
                        } else {
                            $this->companyid = 262;
                        }
                    }

                    if ($count > 0) {
                        if ($company == 3) {
                            $check = tblbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledids', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                            $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledid', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                        } else {
                            if ($this->billingstatusid > 5) {
                                $check = tblbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledids', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                                $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledid', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                            }
                        }
                    } else {

                        if ($count >= 2) {
                            foreach ($trainees as $key => $trainees) {
                                if ($company == 3) {
                                    $check = tblbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledids', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                                    $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledid', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                                } else {
                                    if ($this->billingstatusid > 5) {
                                        $check = tblbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledids', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                                        $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledid', 'LIKE', '%' . $trainees->enroledid . '%')->first();
                                    }
                                }
                            }
                        } else {
                            if ($company == 3) {
                                $check = tblbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledids', 'LIKE', '%' . $trainees->first()->enroledid . '%')->first();
                                $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledid', 'LIKE', '%' . $trainees->first()->enroledid . '%')->first();
                            } else {
                                // dd($trainees);
                                if ($traineesCount > 0) {
                                    if ($this->billingstatusid > 5) {
                                        if (is_array($trainees) && count($trainees) > 0) {
                                            // Access the first element in the array
                                            $check = tblbillingstatement::where('companyid', $this->companyid)
                                                ->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')
                                                ->where('enroledids', 'LIKE', '%' . $trainees[0]->enroledid . '%')
                                                ->first();

                                            $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)
                                                ->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')
                                                ->where('enroledid', 'LIKE', '%' . $trainees[0]->enroledid . '%')
                                                ->first();
                                        } else {
                                            // If $trainees is a collection, use first() to get the first element
                                            // $trainee = is_array($trainees) ? $trainees[0] : $trainees->first();
                                        // dd($trainees);
                                        // $trainee = is_array($trainees) ? $trainees[0]->enroledid : $trainees->enroledid;


                                        if ($this->companyid == 262) {
                                            $trainee = $trainees[0]->enroledid;
                                        } else {
                                            $trainee = $trainees->enroledid;
                                        }

                                        $check = tblbillingstatement::where('companyid', $this->companyid)
                                            ->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')
                                            ->where('enroledids', 'LIKE', '%' . $trainee . '%')
                                            ->first();

                                        $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)
                                            ->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')
                                            ->where('enroledid', 'LIKE', '%' . $trainee . '%')
                                            ->first();
                                    }


                                        // $check = tblbillingstatement::where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledids', 'LIKE', '%' . $trainees->first()->enroledid . '%')->first();
                                        // $check1 = tblexportedbillingstatement::where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledid', 'LIKE', '%' . $trainees->first()->enroledid . '%')->first();`
                                    }
                                } else {

                                    if ($this->billingstatusid > 5) {
                                        $check = tblbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledids', 'LIKE', '%' . $trainees[0]->enroledid . '%')->first();

                                        $check1 = tblexportedbillingstatement::where('companyid', $this->companyid)->where('scheduleid', 'LIKE', '%' . $scheduleid . '%')->where('enroledid', 'LIKE', '%' . $trainees[0]->enroledid . '%')->first();
                                    }
                                }
                            }
                        }
                    }

                    //Check if Manual Billing
                    if ($check != null && $check != "") {
                        $this->fileUrl = '/storage/' . $check->billing_attachment_path;
                        $this->dispatchBrowserEvent('d_modal', [
                            'do' => 'show',
                            'id' => '#pdfModal'
                        ]);
                    } elseif ($check1 != null && $check1 != "") {
                        $this->fileUrl = '/storage/uploads/billingSentToClient/' . $check1->filepath;

                        $this->dispatchBrowserEvent('d_modal', [
                            'do' => 'show',
                            'id' => '#pdfModal'
                        ]);
                    } else {
                        if ($this->billingstatusid >= 6) {
                            $this->dispatchBrowserEvent('notif-log', [
                                'title' => "Oops! Your billing statement is not exported. Please request a copy to this email: jonabel.cabrejas@neti.com.ph"
                            ]);
                        } else {
                            $route = Auth::user()->billing_statement_route;
                            return redirect()->route($route);
                        }
                    }
                }
            } else {
                $this->dispatchBrowserEvent('confirmationbilling', [
                    'position' =>  'center',
                    'icon' => 'error',
                    'title' => 'No trainees found!',
                    'confirmbtn' => true,
                    'confirmbtntxt' => 'Ok'
                ]);
            }
        }
    }
}
