<?php

namespace App\Http\Livewire\Admin\Enrollment;

use App\Models\tblatdmealprice;
use App\Models\tblcourses;
use App\Models\tbldorm;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AEnrollmentLogComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $course, $enroledid, $course_title, $batch, $dateonline, $dateonsite, $room_end, $room_start, $selectedDorm, $numberofenroled, $payment_features, $bus_id, $maximumtrainees;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 46);
    }

    public function edit_enroll($enroledid)
    {
        $enroled = tblenroled::find($enroledid);
        $this->enroledid = $enroled->enroledid;
        $this->course = $enroled->course;
        $this->course_title = $enroled->course->coursecode . ' - ' . $enroled->course->coursename;
        $this->batch = $enroled->schedule->batchno;
        $this->dateonline = ($enroled->schedule->dateonlinefrom ? date('d F Y', strtotime($enroled->schedule->dateonlinefrom)) : null) . ' - ' . ($enroled->schedule->dateonlineto ? date('d F Y', strtotime($enroled->schedule->dateonlineto)) : null);
        $this->dateonsite = ($enroled->schedule->dateonsitefrom ? date('d F Y', strtotime($enroled->schedule->dateonsitefrom)) : null) . ' - ' . ($enroled->schedule->dateonsiteto ? date('d F Y', strtotime($enroled->schedule->dateonsiteto)) : null);
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
            if ($this->selectedDorm != 1 || $this->selectedDorm != null) {
                //total price for dorm
                $total_price_dorm = $dorm_price->atddormprice * ($checkin->diffInDays($checkout) + 1);
                $new_enrol->dorm_price = $total_price_dorm;
                if ($this->selectedDorm != 1) {
                    $total_meal =  tblatdmealprice::find(1)->atdmealprice * ($checkin->diffInDays($checkout) + 1);
                } else {
                    $total_meal = 0;
                }

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

        try {
            if ($this->search) {
                $logs = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->where(function ($query) {
                        $query->where('tbltraineeaccount.f_name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('tbltraineeaccount.l_name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('tbltraineeaccount.m_name', 'LIKE', '%' . $this->search . '%');
                    })
                    ->orderBy('tblenroled.enroledid', 'desc')
                    ->paginate(10);
            } else {
                $logs = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->orderBy('tblenroled.enroledid', 'desc')
                    ->paginate(10);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.enrollment.a-enrollment-log-component',
            [
                'logs' => $logs,
                'dorm' => $dorm
            ]
        )->layout('layouts.admin.abase');
    }
}
