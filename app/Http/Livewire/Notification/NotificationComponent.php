<?php

namespace App\Http\Livewire\Notification;

use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tbllogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;


class LogEventEnum extends Enum {
    const TRAINEE_EVENTS = [
        'enroll_rejected' => 'Rejected your enrollment request for :course',
        'enroll_approved' => 'Approved your enrollment request for :course',
        'enroll_changed_status' => 'Changed your enrollment status for :course',
        'drop_status' => 'Dropped your enrollment for :course',
    ];

    const ADMIN_EVENTS = [
        'generate_enrollment_report' => 'Generated an enrollment report for :course',
        'generate_course_completion_report' => 'Generated a course completion report for :course',
        'generate_certificate_of_completion' => 'Generated a certificate of completion for :course',
        'generate_attendance' => 'Generated a Attendance for :course',
        'generate_training_report' => 'Generated a training report for :course',
        'generate_tper_report' => 'Generated a TPER report for :course',
        'generate_tcroa' => 'Generate a TCROA report for :course',
        'generate_edit_trainee' => 'You edited the name of trainee.',
        'generate_atd_slaf' => 'Generate a ATD/SLAF for :course',
        'delete_enroled' => 'Deleted an enrollment for :course',

    ];
}
class NotificationComponent extends Component
{
    public $logs = [];

    protected $listeners = ['seen', 'add'];
    public $detail;
    public $isAllLogsRead;

    public function getLogs()
    {
        // Untested code: Only works for admin and trainees
        // Get the currently authenticated user
        $user = Auth::user();


        if ($user == null) {
            // if $user is null it's probably a trainee account
            $id =  Auth::guard('trainee')->user()->traineeid;

            $this->logs  =   tbllogs::where('data->trainee_id', 'like', '%' . $id . '%')
                ->orderBy('timestamp', 'desc')
                ->limit(5)
                ->get();

            return $this->generateLogs($this->logs, "trainee");
        }

        // if $user is not null it's an admin or instructor
        $this->logs  =   tbllogs::where('user_id', $user->user_id)
            ->orderBy('timestamp', 'desc')
            ->limit(5)
            ->get();

        return $this->generateLogs($this->logs, "admin");
    }

   
    public function generateLogs($logs, $u_type)
    {
        $updatedLogs = [];
    
        // Loop through logs
        foreach ($logs as $log) {
            $updatedLog = $log; // Create a copy of the log to modify
    
            // Determine the event type based on user type
            $eventTypes = ($u_type === "trainee") ? LogEventEnum::TRAINEE_EVENTS : LogEventEnum::ADMIN_EVENTS;
    
            if (array_key_exists($log['data']['event_type'], $eventTypes)) {
                $course = tblcourses::find($log['data']['course_id']);
                $eventDetail = $eventTypes[$log['data']['event_type']];
                $updatedLog['details'] = "{$log['user']} " . str_replace(':course', $course->coursename, $eventDetail);
            }
    
            $updatedLogs[] = $updatedLog; // Add the modified log
        }
    
        return $updatedLogs;
    }

    public function render()
    {
        $this->getLogs();

        // dd($this->logs);
        // check if route is /login-otp
        if (request()->is('login-otp')) {
            // do not render the logs
            $this->logs = [];
        }

        // Check if logs is not empty or null
        if ($this->logs && !$this->logs->isEmpty()) {
            // Check if all logs are read
            $this->isAllLogsRead = $this->logs->every(function ($log) {
                return $log->is_read === 1;
            });
        }

        return view('livewire.notification.notification-component');
    }

    public function seen($log_id)
    {
        $log = tbllogs::find($log_id);
        $log->is_read = 1;
        $log->save();
    }

    public function add($message, $data)
    {
        $log = new tbllogs();
        $log->user_id = Auth::user()->user_id;
        $log->f_name = Auth::user()->f_name;
        $log->l_name = Auth::user()->l_name;

        $log->details = $message;
        $log->is_read = 0;
        $log->timestamp = Carbon::now()->format('Y-m-d H:i:s');

        $log->data = $data;
        $log->save();
    }
}
