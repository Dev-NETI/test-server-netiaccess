<?php

namespace App\Http\Livewire\Components\InstructorTimeLog;

use App\Models\InstructorTimeLog;
use App\Models\tblcourseschedule;
use App\Models\User;
use App\Traits\CreateTimeLogTrait;
use App\Traits\ResourcesTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateInstructorTimeLogComponent extends Component
{
    use CreateTimeLogTrait;
    use ResourcesTrait;
    public $qrCode;
    protected $rules = [
        'qrCode' => 'required',
    ];
    public $regularHours = null;
    public $undertimeHours = null;
    public $overtimeHours = null;

    public $instructor_id, $input_date, $input_timein;

    public function submit_attendance()
    {
        try {
            $userData = User::where('user_id', $this->instructor_id)->where('u_type', 2)->first();
            $this->calculateTimeLogs($this->input_date, $userData, $this->input_timein);
            $this->emit('onCreated');
            $this->dispatchBrowserEvent('close-model');
            $this->reset_input();
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }


    public function reset_input()
    {
        $this->instructor_id = null;
        $this->input_date = null;
        $this->input_timein = null;
        $this->regularHours = null;
        $this->undertimeHours = null;
        $this->overtimeHours = null;
    }

    public function render()
    {
        $instructor_data = User::where('u_type', 2)->where('is_active', 1)->orderBy('l_name', 'ASC')->get();
        return view(
            'livewire.components.instructor-time-log.create-instructor-time-log-component',
            [
                'instructor_data' => $instructor_data
            ]
        );
    }

    public function updatedQrCode($value)
    {
        $this->store();
    }

    public function store()
    {
        $this->validate();

        //codes are put inside transaction because there are multiple queries and you already know why
        try {
            DB::transaction(function () {
                // BEGIN TRANSACTION CODE

                //current time with samples for late, undertime and overtime
                $currentTime = Carbon::now()->format('H:i:s');
                $currentDate = Carbon::now()->format('Y-m-d');

                //identify if time in or time out

                //check if instructor data exist
                $userData = User::where('user_id', $this->qrCode)->where('u_type', 2)->where('is_active', 1)->first();
                if (!$userData) {
                    $this->reset('qrCode');
                    return session()->flash('error', 'Data not found');
                }

                $this->calculateTimeLogs($currentDate, $userData, $currentTime);

                $this->emit('onCreated');
                $this->reset(['qrCode', 'regularHours', 'undertimeHours', 'overtimeHours']);
            });
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
