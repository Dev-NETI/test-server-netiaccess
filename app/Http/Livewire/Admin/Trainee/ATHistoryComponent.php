<?php

namespace App\Http\Livewire\Admin\Trainee;

use App\Models\tblbillingdrop;
use App\Models\tblbusmode;
use App\Models\tblcourses;
use App\Models\tbldorm;
use App\Models\tbldormitoryreservation;
use App\Models\tblenroled;
use App\Models\tblroomname;
use App\Models\tbltraineeaccount;
use App\Traits\CertificateTrait;
use App\Models\UpdateTraineeInfoLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ATHistoryComponent extends Component
{
    use CertificateTrait;
    use ConsoleLog;
    public $trainee_id;
    public $reason;
    public $enroledid;
    public $updateID;
    public $roomType;
    public $roomTypes = [];
    public $busTypes = [];
    public $busType;

    public $listeners = ['delete_enroled'];


    public function reservationActive($id)
    {
        $this->updateID = $id;

        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#reservationActiveModal',
        ]);
    }

    public function busActive($id)
    {
        $this->updateID = $id;

        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#busActiveModal',
        ]);
    }

    public function updateBus()
    {
        $id = $this->updateID;
        $update = tblenroled::find($id);
        $update->update([
            'busid' => $this->busType,
        ]);

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Successfully Updated',
            'confirmbtn' => false
        ]);

        $id = null;
        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'hide',
            'id' => '#busActiveModal',
        ]);
    }

    public function updateDorm()
    {
        $id = $this->updateID;

        $update = tblenroled::find($id);

        if ($this->roomType == 1) {
            $statusid = 0;
            $logs = 'Updated dorm reservation status from' . $update->dorm->dorm . ' to None. EnroledID: ' . $id;
            UpdateTraineeInfoLogs::StoreLogs($logs);

            $check = $update->update([
                'reservationstatusid' => 0,
                'dormid' => $this->roomType,
            ]);


            $dorm = tbldormitoryreservation::where('enroledid', $id)->orderBy('created_at', 'DESC')->first();

            if (!empty($dorm)) {
                $room = tblroomname::find($dorm->roomid);
                $capacity = $room->capacity + 1;
                $room->update([
                    'capacity' => $capacity
                ]);

                $dorm->delete();
            }
        } else {
            $dormdata = tbldorm::find($this->roomType);

            if (!empty($update->dorm)) {
                $logs = 'Updated dorm reservation status from ' . $update->dorm->dorm . ' to ' . $dormdata->dorm . " EnroledID: " . $id;
            } else {
                $logs = 'Updated dorm reservation status from NULL to ' . $dormdata->dorm . ". EnroledID: " . $id;
            }

            try {
                UpdateTraineeInfoLogs::StoreLogs($logs);
            } catch (\Exception $th) {
                $this->consoleLog($th->getMessage());
            }

            $check = $update->update([
                'dormid' => $this->roomType,
            ]);
        }



        if ($check) {
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Successfully updated',
                'confirmbtn' => false
            ]);

            $id = null;
            $this->dispatchBrowserEvent('d_modal', [
                'do' => 'hide',
                'id' => '#reservationActiveModal',
            ]);
        } else {
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'error',
                'title' => 'Failed to update',
                'confirmbtn' => false
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'do' => 'hide',
                'id' => '#reservationActiveModal',
            ]);
            $id = null;
        }
    }

    public function mount($traineeid)
    {
        $this->trainee_id = $traineeid;
        $this->roomTypes = tbldorm::all();
        $this->busTypes = tblbusmode::all();
    }
    public function render()
    {
        $trainee = tbltraineeaccount::find($this->trainee_id);
        $view_enroled = tblenroled::where('traineeid', $trainee->traineeid)->where('deletedid', 0)->orderBy('enroledid', 'DESC')->get();
        $total_pending = tblenroled::where('traineeid', $trainee->traineeid)->where('pendingid', 1)->where('deletedid', 0)->count();
        $total_enroll = tblenroled::where('traineeid', $trainee->traineeid)->where('pendingid', 0)->where('deletedid', 0)->count();

        return view(
            'livewire.admin.trainee.a-t-history-component',
            [
                'trainee' => $trainee,
                'view_enroled' => $view_enroled,
                'total_pending' => $total_pending,
                'total_enroll' => $total_enroll,
            ]
        )->layout('layouts.admin.abase');
    }

    public function certificate($enroledid)
    {
        Session::put('enroled_id', $enroledid);
        Session::put('second_copy_status', false);

        $enroled = tblenroled::find($enroledid);

        if (Auth::user()->u_type == 3) {
            if (optional($enroled->certificate_history)->certificate_path) {
                $url = Storage::url('trainee-certificate/' . $enroled->certificate_history->certificate_path);
                return redirect($url);
            } else {
                $this->exportCertificate($enroledid);
                return redirect()->route('c.solocertificates');
            }
        } elseif (Auth::user()->dep_type == 1 || Auth::user()->dep_type == 3 || Auth::user()->dep_type == 7) {
            if (optional($enroled->certificate_history)->certificate_path) {
                $url = Storage::url('trainee-certificate/' . $enroled->certificate_history->certificate_path);
                return redirect($url);
            } else {
                $this->exportCertificate($enroledid);
                return redirect()->route('a.solocertificates');
            }
        }
    }

    public function secondc_certificate($enroledid)
    {
        Session::put('enroled_id', $enroledid);
        Session::put('second_copy_status', true);

        $enroled = tblenroled::find($enroledid);

        if (Auth::user()->u_type == 3) {
            if (optional($enroled->certificate_history)->certificate_path) {
                $url = Storage::url('trainee-certificate/' . $enroled->certificate_history->certificate_path);
                return redirect($url);
            } else {
                $this->exportCertificate($enroledid);
                return redirect()->route('c.solocertificates');
            }
        } elseif (Auth::user()->dep_type == 1 || Auth::user()->dep_type == 3 || Auth::user()->dep_type == 7) {
            if (optional($enroled->certificate_history)->certificate_path) {
                $url = Storage::url('trainee-certificate/' . $enroled->certificate_history->certificate_path);
                return redirect($url);
            } else {
                $this->exportCertificate($enroledid);
                return redirect()->route('a.solocertificates');
            }
        }
    }

    public function confirmdelete($enroledid)
    {


        $this->dispatchBrowserEvent('confirmation1', [
            'funct' => 'delete_enroled',
            'id' => $enroledid
        ]);
    }

    public function delete_enroled($enroledid)
    {
        $enroll = tblenroled::find($enroledid);
        $enroll->delete();

        $data = [
            'event_type' => 'delete_enroled',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo(
            'notification.notification-component',
            'add',
            'delete the enrollment',
            $data
        );


        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Deleted',
            'confirmbtn' => false
        ]);
    }



    public function confirmdrop($enroledid)
    {

        $enrol = tblenroled::find($enroledid);
        $this->enroledid = $enrol->enroledid;
    }

    public function drop()
    {
        $this->validate([
            'reason' => ['required', 'string', 'min:10'],
        ]);

        $enroll = tblenroled::find($this->enroledid);
        $enroll->pendingid = 2;
        $enroll->dropid = 1;
        $datedrop = Carbon::now('Asia/Manila');
        $enroll->datedrop = $datedrop;
        $enroll->save();

        $course = tblcourses::find($enroll->courseid);
        $trainee = tbltraineeaccount::find($enroll->traineeid);

        $billdrop = new tblbillingdrop();
        $billdrop->enroledid = $this->enroledid;

        $billdrop->courseid = $enroll->courseid;
        $billdrop->coursename = $course->coursename;
        $billdrop->price = $enroll->t_fee_price;

        $billdrop->dateconfirmed = Carbon::parse($enroll->dateconfirmed)->toDateTimeString();
        $billdrop->datedrop =  $datedrop;
        $billdrop->reason = $this->reason;
        $billdrop->droppedby = Auth::user()->formal_name();
        $billdrop->save();


        $data = [
            'event_type' => 'drop_status',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo(
            'notification.notification-component',
            'add',
            'changed status "DROP" of enrollment application',
            $data
        );

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Successfully dropped',
            'confirmbtn' => false
        ]);
    }
}
