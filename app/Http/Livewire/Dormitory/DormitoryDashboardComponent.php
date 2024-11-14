<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tbldormitoryreservation;
use App\Models\tblenroled;
use App\Models\tblroomtype;
use DateInterval;
use DatePeriod;
use DateTime;
use Livewire\Component;
use Livewire\WithPagination;

class DormitoryDashboardComponent extends Component
{
    use WithPagination;
    public $selectedBuilding = 1;
    public $loadCheckIn = [];
    public $check = 0;

    public function checkIfReserved($date, $id)
    {
        $this->check = 0;
        $checkdata = tbldormitoryreservation::where('is_reserved', 1)
            ->where('roomid', $id)
            ->where(function ($query) use ($date) {
                $query->where('checkindate', '<=', $date)
                    ->where('dateto', '>=', $date);
            })
            ->get();


        if ($checkdata->count() > 0) {
            $this->check = 1;
        } else {
            $this->check = 0;
        }
    }

    public function checkIfCheckIn($date, $id)
    {
        // dd($date);
        $this->check = 0;
        $checkdata = tbldormitoryreservation::where('is_reserved', 0)
            ->where('roomid', $id)
            ->where('checkoutdate', '=', NULL)
            ->where('checkintime', '!=', "00:00:00")
            ->where(function ($query) use ($date) {
                $query->where('checkindate', '<=', $date)
                    ->orWhere('dateto', '>=', $date);
            })
            ->get();


        if ($checkdata->count() > 0) {
            foreach ($checkdata as $key => $data) {
                if ($date >= $data->checkindate && $data->dateto >= $date) {
                    $this->check = 1;
                }
            }
        } else {
            $this->check = 0;
        }
    }

    public function countReserve($id)
    {
        $thisday = new DateTime();
        return $reserved = tbldormitoryreservation::where('datefrom', 'LIKE', $thisday->format('Y-m-') . '%')->where('is_reserved', 1)->where('roomid', $id)->count();
    }

    public function countCheckIn($id)
    {
        $thisday = new DateTime();
        $checkedin = tbldormitoryreservation::where('checkindate', "LIKE", $thisday->format('Y-m-') . '%')->where('is_reserved', 0)
            ->where('checkoutdate', NULL)
            ->where('roomid', $id)
            ->count();

        return $checkedin;
    }

    public function showCheckInList()
    {

        $this->loadCheckIn = tbldormitoryreservation::where('is_reserved', 0)->where('checkoutdate', NULL)->get();
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#checkinmodal',
            'do' => 'show'
        ]);
    }

    public function updatingSelectedBuilding()
    {
        $this->resetPage();
    }

    public function collectNames($id)
    {
        $thisday = new DateTime();
        // $thisweekMonday = (clone $thisday)->modify('this week monday');
        // $thisweekSunday = (clone $thisday)->modify('this week sunday');

        // $dates = [];
        // $dateInterval = new DateInterval('P1D');
        // $dateRange = new DatePeriod($thisweekMonday, $dateInterval, $thisweekSunday->modify('+1 day')); // Add +1 day to include Sunday

        // foreach ($dateRange as $date) {
        //     $dates[] = $date->format('Y-m-d');
        // }

        $data = tbldormitoryreservation::where('checkindate', 'LIKE', $thisday->format('Y-m-') . '%')->where('checkoutdate', NULL)->where('checkouttime', '00:00:00')->where('roomid', $id)->get();

        // dd($data);
        return $data;
    }

    public function collectTrainingDate($id)
    {
        $thisday = new DateTime();
        $data = tbldormitoryreservation::where('checkindate', 'LIKE', $thisday->format('Y-m-') . '%')->where('checkoutdate', NULL)->where(
            'checkouttime',
            '00:00:00'
        )->where('roomid', $id)->get();

        return $data;
    }

    public function generateWeek()
    {
        $datenow = new DateTime();

        $firstDay = clone $datenow;
        $firstDay->modify('last monday');

        $lastDay = clone $datenow;
        $lastDay->modify('next week sunday');

        // Format the dates
        $startOfWeek = $firstDay->format('Y-m-d');
        $endOfWeek = $lastDay->format('Y-m-d');

        return ['startOfWeek' => $startOfWeek, 'endOfWeek' => $endOfWeek];
    }

    public function render()
    {

        $dates = $this->generateWeek();
        $startOfWeek = $dates['startOfWeek'];
        $endOfWeek = $dates['endOfWeek'];

        $countReserve = tbldormitoryreservation::where('is_reserved', 1)->where('checkindate', '>=', $startOfWeek)->count();
        $countCheckIn = tbldormitoryreservation::where('is_reserved', 0)->where('checkindate', '>=', $startOfWeek)->where('checkoutdate', NULL)->count();
        $countCheckOut = tbldormitoryreservation::where('is_reserved', 0)->where('checkoutdate', '!=', NULL)->count();
        $countForReserve = tblenroled::where('deletedid', 0)->where('created_at', '>=', $startOfWeek)->where('dropid', 0)->where('attendance_status', 0)->where('reservationstatusid', 0)->where('created_at', '>', '04-03-2025')->whereNotIn('dormid', [0, 1])->count();
        $buildings = tblroomtype::where('deleteid', 0)->orderBy('roomtype', 'ASC')->where('id', $this->selectedBuilding)->get();
        $loadbuildings = tblroomtype::where('deleteid', 0)->orderBy('roomtype', 'ASC')->get();

        return view('livewire.dormitory.dormitory-dashboard-component', compact(
            'countReserve',
            'countForReserve',
            'countCheckIn',
            'countCheckOut',
            'buildings',
            'loadbuildings',
            'startOfWeek',
            'endOfWeek'
        ))->layout('layouts.admin.abase');
    }
}
