<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\tblcompanycourse;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblforeignrate;
use App\Models\tblnyksmcompany;
use App\Models\tbltransferbilling;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ChangeSerialNumberModal extends Component
{
    public $billingserialnumber = null;
    use ConsoleLog;
    use AuthorizesRequests;
    public $scheduleid;
    public $error = false;
    public $companyid;

    public function changeserial()
    {
        $transferedBilling = session('transferedBilling');
        $companyid = session('companyid');
        if ($transferedBilling) {

            if ($this->billingserialnumber) {
                $concat = date('ym');
                $finalserialnumber = $concat . '-' . $this->billingserialnumber;

                $dattoupdate = tbltransferbilling::where('scheduleid', $this->scheduleid)->where('payeecompanyid', $companyid)->get();
                foreach ($dattoupdate as $key => $value) {
                    $value->update([
                        'serialnumber' => $finalserialnumber
                    ]);

                    tblenroled::find($value->enroledid)->update(['billingserialnumber' => $finalserialnumber]);
                }

                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Serial number changed'
                ]);

                session()->put('billingserialnumber', $finalserialnumber);

                $this->redirect(route('a.billing-viewtrainees'));
            }
        } else {
            $nykComps = tblnyksmcompany::getcompanyid();
            $nykCompswoNYKLINE = tblnyksmcompany::getcompanywoNYKLINE();
            $scheddata = tblcourseschedule::find($this->scheduleid)->first();
            if (in_array($this->companyid, $nykComps)) {
                try {
                    if ($this->companyid == 89) {
                        $data = tblforeignrate::where('companyid', 89)->where('courseid', $scheddata->courseid)->first();
                    } else {
                        $data = tblforeignrate::where('companyid', 262)->where('courseid', $scheddata->courseid)->first();
                    }
                    $format = $data->format;
                } catch (\Exception $th) {
                    $this->error = true;
                }
            } else {
                $data = tblcompanycourse::where('companyid', $this->companyid)->where('courseid', $scheddata->courseid)->first();
                $format = $data->company->billing_statement_format;
            }

            if (!$this->error) {
                if ($format == 3) {
                    $check = 0;
                    $finalserialnumber = date('ym') . '-' . $this->billingserialnumber;
                    $finalserialnumberreal = date('ym') . '-' . $this->billingserialnumber;

                    //NYKSM
                    if (in_array($this->companyid, $nykCompswoNYKLINE)) {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query, $nykComps) {
                                $query->whereIn('company_id', $nykComps)
                                    ->where('nationalityid', '!=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('nabillnaid', 0)
                            ->where('deletedid', 0)
                            ->where('isRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('isRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query, $nykComps) {
                                $query->whereIn('company_id', $nykComps)
                                    ->where('nationalityid', '!=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('nabillnaid', 0)
                            ->where('deletedid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    } elseif ($this->companyid == 89) {
                        //NYKLINE
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', 89)
                                    ->where('nationalityid', '=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('nabillnaid', 0)
                            ->where('isRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('isRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', 89)
                                    ->where('nationalityid', '=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('nabillnaid', 0)
                            ->where('deletedid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();
                        $enroleddata = $enroleddata->merge($enroleddata2);
                    } else {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', $this->companyid);
                            })
                            ->where('dropid', 0)
                            ->where('nabillnaid', 0)
                            ->where('deletedid', 0)
                            ->where('isRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('isRemedial', 1)
                            ->where('nabillnaid', 0)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', $this->companyid);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    }

                    $uniqueVessels = array();
                    foreach ($enroleddata as $vessel) {
                        $vessel = $vessel->trainee->vessel;
                        if (!in_array($vessel, $uniqueVessels)) {
                            $uniqueVessels[] = $vessel;
                        }
                    }
                    $myarray = array();

                    $vesselcount = count($uniqueVessels);
                    for ($i = 0; $i < $vesselcount; $i++) {

                        $checkIfSerialNumberUsed = 0;
                        $checkIfSerialNumberUsed = tblenroled::where('billingserialnumber', $finalserialnumber)->count();
                        if ($checkIfSerialNumberUsed == 0) {
                        } else {
                            $check = 1;
                        }

                        $parts = explode('-', $finalserialnumber);
                        $billingserialnumber = $parts[1];
                        $billingserialnumber++;
                        $billingserialnumber = str_pad($billingserialnumber, 3, '0', STR_PAD_LEFT);
                        $finalserialnumber = date('ym') . '-' . $billingserialnumber;
                    }

                    if ($check == 0) {
                        foreach ($uniqueVessels as $vesselid) {
                            $parts = explode('-', $finalserialnumberreal);
                            $serialno = $parts[1];
                            foreach ($enroleddata as $data) {
                                if ($data->trainee->vessel == $vesselid) {
                                    $myarray[] = $finalserialnumberreal . '-' . $data->enroledid . '-' . $data->trainee->vessel . '-' . 'done';
                                    $data->update([
                                        'billingserialnumber' => date('ym') . '-' . str_pad($serialno, 3, "0", STR_PAD_LEFT)
                                    ]);
                                }
                            }
                            $serialno++;
                            $finalserialnumberreal = date('ym') . '-' . str_pad($serialno, 3, "0", STR_PAD_LEFT);
                        }
                        $this->dispatchBrowserEvent('save-log', [
                            'title' =>  'Successfully Changed Serial Number!',
                        ]);

                        $this->redirect(route('a.billing-viewtrainees'));
                    } else {
                        $this->dispatchBrowserEvent('error-log', [
                            'title' =>  'Serial Number is Used  !',
                        ]);
                    }
                } elseif ($format == 2) {
                    $check = 0;
                    $finalserialnumber = date('ym') . '-' . $this->billingserialnumber;
                    $finalserialnumberreal = date('ym') . '-' . $this->billingserialnumber;

                    if (in_array($this->companyid, $nykCompswoNYKLINE)) {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query, $nykComps) {
                                $query->whereIn('company_id', $nykComps);
                                $query->where('nationalityid', '!=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('IsRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('IsRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query, $nykComps) {
                                $query->whereIn('company_id', $nykComps);
                                $query->where('nationalityid', '!=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    } elseif ($this->companyid == 89) {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', 89);
                                $query->where('nationalityid', '=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('IsRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('IsRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', 89);
                                $query->where('nationalityid', '=', 51);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    } else {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', $this->companyid);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('IsRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('IsRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', $this->companyid);
                            })
                            ->where('dropid', 0)
                            ->where('deletedid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    }

                    if ($check == 0) {
                        $parts = explode('-', $finalserialnumberreal);
                        $serialno = $parts[1];

                        foreach ($enroleddata as $data) {

                            $myarray[] = $finalserialnumberreal . '-' . $data->enroledid . '-' . $data->trainee->vessel . '-' . 'done';
                            $data->update([
                                'billingserialnumber' => date('ym') . '-' . str_pad($serialno, 3, "0", STR_PAD_LEFT)
                            ]);
                            $serialno++;
                            $finalserialnumberreal = date('ym') . '-' . str_pad($serialno, 3, "0", STR_PAD_LEFT);
                        }

                        $this->dispatchBrowserEvent('save-log', [
                            'title' =>  'Successfully Changed Serial Number!',
                        ]);

                        $this->redirect(route('a.billing-viewtrainees'));
                    } else {
                        $this->dispatchBrowserEvent('error-log', [
                            'title' =>  'Serial Number is Used  !',
                        ]);
                    }
                } else {
                    $finalserialnumber = date('ym') . '-' . $this->billingserialnumber;

                    if (in_array($this->companyid, $nykCompswoNYKLINE)) {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query) use ($nykComps) {
                                $query->whereIn('company_id', $nykComps);
                                $query->where('nationalityid', '!=', 51);
                            })
                            ->where('deletedid', 0)
                            ->where('dropid', 0)
                            ->where('IsRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('IsRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query) use ($nykComps) {
                                $query->whereIn('company_id', $nykComps);
                                $query->where('nationalityid', '!=', 51);
                            })
                            ->where('deletedid', 0)
                            ->where('dropid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    } elseif ($this->companyid == 89) {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', 89);
                                $query->where('nationalityid', '=', 51);
                            })
                            ->where('deletedid', 0)
                            ->where('dropid', 0)
                            ->where('IsRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('IsRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', 89);
                                $query->where('nationalityid', '=', 51);
                            })
                            ->where('deletedid', 0)
                            ->where('dropid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    } else {
                        $enroleddata = tblenroled::with('trainee')
                            ->whereIn('scheduleid', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', $this->companyid);
                            })
                            ->where('deletedid', 0)
                            ->where('dropid', 0)
                            ->where('IsRemedial', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata2 = tblenroled::with('trainee')
                            ->where('IsRemedial', 1)
                            ->whereIn('remedial_sched', $this->scheduleid)
                            ->whereHas('trainee', function ($query) {
                                $query->where('company_id', $this->companyid);
                            })
                            ->where('deletedid', 0)
                            ->where('dropid', 0)
                            ->where('attendance_status', '!=', 4)
                            ->get();

                        $enroleddata = $enroleddata->merge($enroleddata2);
                    }


                    $checkIfSerialNumberUsed = tblenroled::where('billingserialnumber', $finalserialnumber)->count();

                    if ($checkIfSerialNumberUsed > 0) {
                        $this->dispatchBrowserEvent('error-log', [
                            'title' =>  'Serial Number is Used  !',
                        ]);
                    } else {
                        foreach ($enroleddata as $data) {
                            $data->update([
                                'billingserialnumber' => $finalserialnumber
                            ]);
                        }

                        $this->dispatchBrowserEvent('save-log', [
                            'title' =>  'Successfully Changed Serial Number!',
                        ]);

                        $this->redirect(route('a.billing-viewtrainees'));
                    }
                }
            } else {
                $this->dispatchBrowserEvent('notif-log', [
                    'title' => 'No data in price matrix'
                ]);
            }
        }
    }

    public function render()
    {
        try {
            $enroleddata = tblenroled::with('trainee')
                ->whereIn('scheduleid', $this->scheduleid)
                ->whereHas('trainee', function ($query) {
                    $query->where('company_id', $this->companyid);
                })
                ->where('dropid', 0)
                ->where('attendance_status', 0)
                ->orderBy('billingserialnumber', 'DESC')
                ->first();

            $parts = explode('-', $enroleddata->billingserialnumber);
            $this->billingserialnumber = $parts[1];
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.billing.child.generate-billing.change-serial-number-modal');
    }
}
