<?php

namespace App\Http\Livewire\Company\Enroll;

use App\Mail\SendConfirmedEnrollment;
use App\Models\tblcompanycourse;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use App\Models\tblenroled;
use App\Models\tbltraineeaccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class CCalendarDetailsComponent extends Component
{
    use WithPagination;

    public $schedule_id;
    public $schedule;
    public $search;

    public $loadtrainee;
    public $selected_schedule;
    public $formatted_registration_num;

    public $start_date;
    public $end_date;
    public $room_start;
    public $room_end;
    public $dateonlinefrom = null;
    public $dateonlineto = null;
    public $dateonsitefrom = null;
    public $dateonsiteto = null;
    public $trainee_id;
    public $numberofenroled;
    public $selectedDorm;
    public $duration;
    public $bus_id;

    public function mount($schedule_id)
    {
        $this->schedule_id = $schedule_id;

        $this->schedule = tblcourseschedule::where('scheduleid', $schedule_id)->first();
        $this->dateonlinefrom = $this->schedule->dateonlinefrom;
        $this->dateonlineto = $this->schedule->dateonlineto;
        $this->dateonsitefrom = $this->schedule->dateonsitefrom;
        $this->dateonsiteto = $this->schedule->dateonsiteto;
    }

    public function generateRegistrationNumber()
    {
        $registration_num = mt_rand(10000, 99999);
        $year = date('Y');
        $start_month = date('m-d', strtotime($this->schedule->startdateformat));
        $formatted_registration_num = $year . $start_month . '-' . $registration_num;

        $this->formatted_registration_num = $formatted_registration_num;
    }

    public function loadtrainee($id)
    {
        $this->loadtrainee = tbltraineeaccount::where('traineeid', $id)->first();
        $this->trainee_id = $id;
        
    }

    private function isEnrolled($trainee_id)
    {
        $schedule = tblcourseschedule::find($this->schedule_id);
        $user = tbltraineeaccount::find($trainee_id);

        // Check if the user is enrolled in the course
        return tblenroled::where('traineeid', $user->traineeid)
            ->where('courseid', $schedule->courseid)
            ->where('deletedid', 0)
            ->exists();
    }


    private function CheckNumberOfTrainees($schedid)
    {
        // Check if the user is enrolled in the course
        return tblenroled::where('scheduleid', $schedid)
            ->where(function ($query) {
                $query->where('tblenroled.pendingid', 0)
                    ->orWhere('tblenroled.pendingid', 1);
            })
            ->where('tblenroled.deletedid', 0)
            ->count();
    }

    public function enroll()
    {
        $selected_schedule = tblcourseschedule::find($this->schedule_id);
        $course_company = tblcompanycourse::where('courseid', $selected_schedule->courseid)->first();
        $isEnrolled = $this->isEnrolled($this->trainee_id);
        $pending_enrolled_trainees = $this->CheckNumberOfTrainees($this->schedule_id);
        $conflictingSchedules = $this->getConflictingScheduleId($this->trainee_id, $this->schedule_id);

        if (count($conflictingSchedules) > 0) {
            $conflictingEnroledIds = $this->getConflictingEnroledIds($conflictingSchedules);
        
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Conflicting with your other courses. The following registration numbers are in conflict: ' . implode(', ', $conflictingEnroledIds),
            ]);
        }

        if ($isEnrolled) {

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'The trainee are already enrolled in this course.'
            ]);

            return;
        }


        if ($pending_enrolled_trainees >= $selected_schedule->course->maximumtrainees) {

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'This training schedule are reached the maximum trainees. Please select another.'
            ]);

            return;
        }

        $this->generateRegistrationNumber();


        $trainee = tbltraineeaccount::find($this->trainee_id);

        $new_enrol = new tblenroled();
        $new_enrol->registrationcode = $this->formatted_registration_num;

        $new_enrol->scheduleid = $this->schedule_id;
        $new_enrol->courseid =  $selected_schedule->courseid;
        $new_enrol->traineeid = $this->trainee_id;

        $new_enrol->t_fee_package =  $course_company->t_fee_package;

        if ($course_company->t_fee_package == 2 || $course_company->t_fee_package == 3) {
            $new_enrol->busid = 1;
        } else {
            $new_enrol->busid = 0;
        }

        if ($this->start_date && $this->bus_id == 1 ) {
            $new_enrol->t_fee_package = 2;
        } else {
            $new_enrol->t_fee_package = 1;
        }

        $dateconfirmed = Carbon::now('Asia/Manila');
        $new_enrol->dateconfirmed = $dateconfirmed;

        $new_enrol->paymentmodeid = 1;
        $new_enrol->pendingid = 0;

        //bus
        if ($this->bus_id) {
            $new_enrol->busid = 1;
            $new_enrol->busmodeid = $this->bus_id;
        }

        //transactions
        $new_enrol->t_fee_price = null;
        $new_enrol->meal_price = null;
        $new_enrol->dorm_price = null;

        //calculate the total price
        $new_enrol->total =  0;
        $new_enrol->enrolledby = Auth::user()->formal_name();
        $new_enrol->fleetid = $this->loadtrainee->fleet_id;
        $new_enrol->dormid = $this->selectedDorm;
        $new_enrol->checkindate = $this->room_start;
        $new_enrol->checkoutdate = $this->room_end;
        $checkin = Carbon::parse($this->room_start);
        $checkout = Carbon::parse($this->room_end);
        $this->duration = $checkin->diffInDays($checkout) + 1;

        $new_enrol->save();

        $latestId = $new_enrol->enroledid;
        Session::put('latest_enrol_id', $latestId);

        $enrol = tblenroled::where('enroledid', $latestId)->first();

        $data = [
            'enrol' => $enrol,
        ];

        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-admission-slip', $data);
        $pdf->setPaper('a4', 'landscape');
        Mail::to($enrol->trainee->email)->send(new SendConfirmedEnrollment($enrol, $trainee, $pdf));


        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Enroll Successfully'
        ]);

    }

    private function getConflictingScheduleId($traineeId, $selected_sched)
    {
        $enrolledCourses = tblenroled::where('traineeid', $traineeId)
            ->where('deletedid', 0)
            ->get();

        $conflictingSchedules = collect();
    
        $selectedSchedule = tblcourseschedule::find($selected_sched);
    
        foreach ($enrolledCourses as $enrolledCourse) {
            $conflictingSchedule = tblcourseschedule::where('scheduleid', $enrolledCourse->scheduleid)
                ->whereBetween('startdateformat', [$selectedSchedule->startdateformat, $selectedSchedule->enddateformat])
                ->first();
    
            if ($conflictingSchedule) {
                $conflictingSchedules->push($enrolledCourse->enroledid);
            }
        }
    
        return $conflictingSchedules->unique()->all();
    }

    private function getConflictingEnroledIds($conflictingSchedules)
    {
        $conflictingEnroledIds = [];

        foreach ($conflictingSchedules as $conflictingScheduleId) {
            $enrolledCourse = tblenroled::find($conflictingScheduleId);
            if ($enrolledCourse) {
                $conflictingEnroledIds[] = $enrolledCourse->enroledid;
            }
        }
        return $conflictingEnroledIds;
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes
    }

    public function render()
    {
        $trainees = tbltraineeaccount::where('company_id', Auth::user()->company_id)->where('l_name', 'LIKE', '%' . $this->search . '%')->paginate(10);
        $e_trainees = tblenroled::where('scheduleid', $this->schedule_id)->get();
        $dorm = tbldorm::all();
        $this->numberofenroled = tblenroled::where('scheduleid', $this->schedule->scheduleid)->where('pendingid', 0)->count();

        if ($this->selectedDorm) {
            $this->room_start =   $this->schedule->dateonsitefrom;
            $this->room_end =   $this->schedule->dateonsiteto;
        } else {
            $this->room_start =  null;
            $this->room_end =  null;
        }
        return view(
            'livewire.company.enroll.c-calendar-details-component',
            [
                'trainees' => $trainees,
                'e_trainees' => $e_trainees,
                'dorm' => $dorm,
            ]
        )->layout('layouts.admin.abase');
    }
}
