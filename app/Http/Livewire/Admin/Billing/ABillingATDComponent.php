<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblatdmealprice;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ABillingATDComponent extends Component
{
    public $selected_month;
    public $selected_batch;
    public $paymentmodeid;
    public $atds;
    public $months = [];
    public $selected_t_fee;
    public $grand_fee_price, $grand_dorm, $grand_meal, $grand_total, $atds_records, $all_trainees, $all_price, $all_dorm, $all_meal, $all_total, $per_t_fee;
    public $input_assessment, $input_dorm, $input_meal;
    public $disabled;

    public $editIndex1, $editIndex2, $editIndex3;
    public $defaultValues = [];

    public $input_address, $input_department, $input_company, $input_location1, $input_location2, $rowstart;

    public $course, $enroledid, $course_title, $batch, $dateonline, $dateonsite, $room_end, $room_start, $selectedDorm, $numberofenroled, $payment_features, $bus_id, $maximumtrainees;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 89);

        for ($month = 1; $month <= 12; $month++) {
            $carbonMonth = Carbon::create(null, $month, 1);
            $this->months[$carbonMonth->format('Y-m')] = $carbonMonth->format('F Y');
        }
    }

    public function enableAssessment($index, $price)
    {
        $this->editIndex1 = $index;
        $this->input_assessment = $price;
    }

    public function enableDorm($index, $price)
    {
        $this->editIndex2 = $index;
        $this->input_dorm = $price;
    }

    public function enableMeal($index, $price)
    {
        $this->editIndex3 = $index;
        $this->input_meal = $price;
    }

    public function save_assessment($enroledid)
    {
        $update_enroled = tblenroled::find($enroledid);

        if (!$this->input_assessment) {
            $this->input_assessment = 0;
        }

        if ($update_enroled) {
            $update_enroled->t_fee_price = $this->input_assessment;
            $update_enroled->total = $update_enroled->t_fee_price + $update_enroled->dorm_price + $update_enroled->meal_price;
            $update_enroled->save();
        }

        $this->editIndex1 = null;
        $this->input_assessment = null;
        $this->reset_field();
        $this->generateData();
    }

    public function save_dorm($enroledid)
    {
        $update_enroled = tblenroled::find($enroledid);

        if (!$this->input_dorm) {
            $this->input_dorm = 0;
        }

        if ($update_enroled) {
            $update_enroled->dorm_price = $this->input_dorm;
            $update_enroled->total = $update_enroled->t_fee_price + $update_enroled->dorm_price + $update_enroled->meal_price;
            $update_enroled->save();
        }

        $this->editIndex2 = null;
        $this->input_dorm = null;
        $this->reset_field();
        $this->generateData();
    }


    public function save_meal($enroledid)
    {
        $update_enroled = tblenroled::find($enroledid);

        if (!$this->input_meal) {
            $this->input_meal = 0;
        }

        if ($update_enroled) {
            $update_enroled->meal_price = $this->input_meal;
            $update_enroled->total = $update_enroled->t_fee_price + $update_enroled->dorm_price + $update_enroled->meal_price;
            $update_enroled->save();
        }

        $this->editIndex3 = null;
        $this->input_meal = null;
        $this->reset_field();
        $this->generateData();
    }

    public function reset_field()
    {
        $this->grand_fee_price = null;
        $this->grand_meal = null;
        $this->grand_total = null;
        $this->all_trainees = null;
        $this->all_price = null;
        $this->all_dorm = null;
        $this->all_meal = null;
        $this->all_total = null;
    }

    public function generateData()
    {
        Session::put('paymentmodeid', $this->paymentmodeid);
        Session::put('selected_batch', $this->selected_batch);
        Session::put('selected_month', $this->selected_month);

        $query = tblenroled::query()
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->where('paymentmodeid', $this->paymentmodeid)
            ->where('pendingid', 0)
            ->where('tblenroled.deletedid', 0);
        if ($this->selected_batch) {
            $query->whereHas('schedule', function ($subQuery) {
                $subQuery->where('batchno', $this->selected_batch);
            });
        }

        if ($this->selected_month) {
            $query->whereHas('schedule', function ($subQuery) {
                $subQuery->where('startdateformat', 'LIKE', '%' . $this->selected_month . '%');
            });
        }

        $atds = $query->orderBy('tblcourseschedule.startdateformat', 'ASC')
            ->orderBy('tblcourses.coursecode', 'ASC')
            ->orderBy('tbltraineeaccount.l_name', 'ASC')
            ->orderBy('tblcourseschedule.enddateformat', 'ASC')
            ->get();

        $atds_chart = tblenroled::query()
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->where('paymentmodeid', $this->paymentmodeid);

        if ($this->selected_batch) {
            $atds_chart->whereHas('schedule', function ($subQuery) {
                $subQuery->where('batchno', $this->selected_batch);
            });
        }

        if ($this->selected_month) {
            $atds_chart->whereHas('schedule', function ($subQuery) {
                $subQuery->where('startdateformat', 'LIKE', '%' . $this->selected_month . '%');
            });
        }

        $this->atds = $atds;

        $this->grand_fee_price = $atds ? $atds->sum('t_fee_price') : 0;
        $this->grand_dorm = $atds ? $atds->sum('dorm_price') : 0;
        $this->grand_meal = $atds ? $atds->sum('meal_price') : 0;
        $this->grand_total = $atds ? $atds->sum('total') : 0;

        $this->disabled = true;

        $this->diff_addressed($this->paymentmodeid);
    }

    public function diff_addressed($paymentmodeid)
    {
        if ($paymentmodeid == 3) {
            $this->input_address = 'MR. ADEL B. SANGALANG / MR. TRISTAN MARCIA';
            $this->input_department = 'Finance Center - Payroll Section';
            $this->input_company = 'NYK-FIL SHIP MANAGEMENT, INC.';
            $this->input_location1 = 'Gen. Luna cor. Sta. Potenciana Sts.';
            $this->input_location2 = 'Intramuros, Manila';
            $this->rowstart = 20;
        } else {
            $this->input_address = 'MS. BERNADETTE NADAYAO / MS. CHARRY UMABOS';
            $this->input_department = 'NTMA SLAF / Finance Center - Disbursment Section';
            $this->input_company = 'NYK-FIL SHIP MANAGEMENT, INC.';
            $this->input_location1 = 'Gen. Luna cor. Sta. Potenciana Sts.';
            $this->input_location2 = 'Intramuros, Manila';
            $this->rowstart = 20;
        }

        Session::put('address', $this->input_address);
        Session::put('department', $this->input_department);
        Session::put('company', $this->input_company);
        Session::put('location1', $this->input_location1);
        Session::put('location2', $this->input_location2);
        Session::put('rowstart', $this->rowstart);
    }

    public function save_settings()
    {
        $input_address =  $this->input_address;
        $input_department = $this->input_department;
        $input_company = $this->input_company;
        $input_location1 = $this->input_location1;
        $input_location2 = $this->input_location2;
        $rowstart = $this->rowstart;

        Session::put('address', $input_address);
        Session::put('department', $input_department);
        Session::put('company', $input_company);
        Session::put('location1', $input_location1);
        Session::put('location2', $input_location2);
        Session::put('rowstart', $rowstart);

        $this->dispatchBrowserEvent('close-model');
    }

    public function reset_button()
    {
        $this->disabled = false;
        $this->atds = null;
        $this->atds_records = null;
        $this->grand_fee_price = null;
        $this->grand_meal = null;
        $this->grand_total = null;
        $this->selected_batch = null;
        $this->selected_month = null;
        $this->paymentmodeid = null;
        $this->all_trainees = null;
        $this->all_price = null;
        $this->all_dorm = null;
        $this->all_meal = null;
        $this->all_total = null;
        $this->input_address = null;
        $this->input_department = null;
        $this->input_company = null;
        $this->input_location1 = null;
        $this->input_location2 = null;
        $this->rowstart = 20;

        Session::forget('paymentmodeid');
        Session::forget('selected_batch');
        Session::forget('selected_month');
        Session::forget('address');
        Session::forget('department');
        Session::forget('company');
        Session::forget('location1');
        Session::forget('location2');
        Session::forget('rowstart');
    }

    public function edit_enroll($enroledid)
    {
        $enroled = tblenroled::find($enroledid);
        $this->enroledid = $enroled->enroledid;
        $this->course = $enroled->course;
        $this->course_title = $enroled->course->coursecode . ' - ' . $enroled->course->coursename;
        $this->batch = $enroled->schedule->batchno;
        $this->dateonline = ($enroled->schedule->dateonlinefrom ? date('d F Y', strtotime($enroled->schedule->dateonlinefrom)) . ' - ' : null)  . ($enroled->schedule->dateonlineto ? date('d F Y', strtotime($enroled->schedule->dateonlineto)) : null);
        $this->dateonsite = ($enroled->schedule->dateonsitefrom ? date('d F Y', strtotime($enroled->schedule->dateonsitefrom)) . ' - ' : null) . ($enroled->schedule->dateonsiteto ? date('d F Y', strtotime($enroled->schedule->dateonsiteto)) : null);
        $this->maximumtrainees = $enroled->course->maximumtrainees;
        $this->numberofenroled = tblenroled::where('scheduleid', $enroled->scheduleid)
            ->whereIn('pendingid', [0, 1])
            ->count();
        $this->payment_features = $enroled->paymentmodeid;
        $this->bus_id = $enroled->busmodeid;
        $this->selectedDorm = $enroled->dormid;
        $this->room_start = $enroled->checkindate;
        $this->room_end = $enroled->checkoutdate;
    }

    public function enroll_save()
    {
        Gate::authorize('authorizeRegistrarEnrollment', Auth::user()->id);
        try {
            $new_enrol = tblenroled::find($this->enroledid);

            $new_enrol->paymentmodeid = $this->payment_features;
            $new_enrol->dormid = $this->selectedDorm;



            if ($this->room_start && $this->room_end) {
                $new_enrol->checkindate = $this->room_start;
                $new_enrol->checkoutdate = $this->room_end;
            } else {
                $new_enrol->checkindate = null;
                $new_enrol->checkoutdate = null;
            }

            //getting duration of date
            $checkin = Carbon::parse($this->room_start);
            $checkout = Carbon::parse($this->room_end);
            $new_enrol->duration = $checkin->diffInDays($checkout) + 1;
            $dorm_price = tbldorm::find($this->selectedDorm);

            // Check if $dorm_price is not null before accessing its properties
            if ($dorm_price) {
                //total price for dorm
                $total_price_dorm = $dorm_price->atddormprice * ($checkin->diffInDays($checkout) + 1);
                $new_enrol->dorm_price = $total_price_dorm;

                $total_meal =  tblatdmealprice::find(1)->atdmealprice * ($checkin->diffInDays($checkout) + 1);
                //gett the meal price
                $new_enrol->meal_price = $total_meal;
                $new_enrol->dorm_price = $total_price_dorm;

                $total = $total_price_dorm + $new_enrol->t_fee_price + $total_meal;
            } else {
                $total =  $new_enrol->t_fee_price;
            }

            if ($this->bus_id) {
                if ($this->bus_id == 1) {
                    $new_enrol->busid = 1;
                    $new_enrol->busmodeid = $this->bus_id;
                } else if ($this->bus_id == 2) {
                    $new_enrol->busid = 1;
                    $new_enrol->busmodeid = $this->bus_id;
                }
            } else {
                $new_enrol->busid = null;
                $new_enrol->busmodeid = null;
            }

            $new_enrol->total = $total;

            $new_enrol->save();
            $this->generateData();

            // $this->selectedCourse = null;
            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Update Successfully'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        $dorm = tbldorm::all();

        $currentYear = Carbon::now()->year;
        $batchWeeks = tblcourseschedule::select('batchno')
            ->where('startdateformat', 'like', '%' . $currentYear . '%')
            ->groupBy('batchno')
            ->orderBy('startdateformat', 'asc')
            ->get();

        return view(
            'livewire.admin.billing.a-billing-a-t-d-component',
            [
                'batchWeeks' => $batchWeeks,
                'dorm' => $dorm
            ]
        )->layout('layouts.admin.abase');
    }
}
