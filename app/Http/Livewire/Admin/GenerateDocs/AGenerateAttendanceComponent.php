<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblnyksmcompany;
use App\Models\tblscheduleattendance;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Livewire\Component;

class AGenerateAttendanceComponent extends Component
{
    public $scheduleid;
    public $att_trainees;
    public $dateRange = [];
    public $attendanceData;
    public $companyid;
    public $enroledid;

    public function generatePdf($scheduleid, $companyid = null)
    {
        $scheduleid = explode(',', $scheduleid);

        $nyksmComps = tblnyksmcompany::getcompanyid();
        $checkIfNYKSMclient = false;
        $dataN = [];
        if (in_array($companyid, $nyksmComps)) {
            $dataN = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                ->whereIn('scheduleid', $scheduleid)
                ->whereHas('trainee', function ($query) use ($nyksmComps) {
                    $query->whereIn('company_id', $nyksmComps)
                        ->where('nationalityid', '!=', 51);
                })
                ->where('deletedid', 0)
                ->pluck('tbltraineeaccount.company_id')
                ->unique()
                ->toArray();
        }

        if (!empty($dataN)) {
            $checkIfNYKSMclient = true;
            $this->att_trainees = tblenroled::where(function ($query) use ($scheduleid) {
                $query->whereIn('scheduleid', $scheduleid)->orWhere('remedial_sched', $scheduleid)
                    ->where(function ($subquery) {
                        $subquery->where('pendingid', 0)
                            ->orWhere('pendingid', 3);
                    });
            });
        } else {
            $this->att_trainees = tblenroled::where(function ($query) use ($scheduleid) {
                $query->where('scheduleid', $scheduleid)->orWhere('remedial_sched', $scheduleid)
                    ->where(function ($subquery) {
                        $subquery->where('pendingid', 0)
                            ->orWhere('pendingid', 3);
                    });
            });
        }

        $this->att_trainees->where('dropid', 0)
            ->where('deletedid', 0);

        if (!$checkIfNYKSMclient) {
            $this->att_trainees->when($companyid, function ($query, $companyid) {
                return $query->where('tbltraineeaccount.company_id', $companyid);
            });
        } else {
            $this->att_trainees->when(!empty($dataN), function ($query) use ($dataN) {
                return $query->whereIn('tbltraineeaccount.company_id', $dataN);
            });
        }
        $this->att_trainees->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        if (empty($this->att_trainees)) {
            abort(404);
        }

        $schedule = tblcourseschedule::whereIn('scheduleid', $scheduleid)->first();


        $startDate = Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $endDate = Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        while ($startDate <= $endDate) {
            // Check if the current day is not a Sunday (day of week = 0)
            if ($startDate->dayOfWeek !== 0 || $schedule->specialclassid == 1) {
                $this->dateRange[$startDate->format('Y-m-d')] = $startDate->format('l');
            }
            $startDate->addDay();
        }

        // $this->attendanceData = tblscheduleattendance::whereIn('traineeid',  $this->att_trainees->pluck('traineeid'))
        $this->attendanceData = tblscheduleattendance::whereIn('traineeid', $this->att_trainees->select('tbltraineeaccount.traineeid')->pluck('traineeid'))
            ->whereIn('date', array_keys($this->dateRange))
            ->whereIn('scheduleid', $scheduleid)
            ->get();

        $traineesPerPage = 10;
        $totalPages = ceil($this->att_trainees->count() / $traineesPerPage);

        $data = [
            'schedule' => $schedule,
            'att_trainees' => $this->att_trainees->get(),
            'dateRange' => $this->dateRange,
            'attendanceData' => $this->attendanceData,
            'traineesPerPage' => $traineesPerPage,
            'totalPages' => $totalPages,
        ];

        $pdf = FacadePdf::loadView('livewire.admin.generate-docs.a-generate-attendance-component', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function soloPdf($scheduleid, $enroledid = null)
    {
        $scheduleid = explode(',', $scheduleid);

        $this->att_trainees = tblenroled::where(function ($query) use ($scheduleid, $enroledid) {
            $query->where('remedial_sched', $scheduleid)->where('enroledid', $enroledid)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })

            ->where('dropid', 0)
            ->where('deletedid', 0);


        $this->att_trainees->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();


        if (empty($this->att_trainees)) {
            abort(404);
        }

        $schedule = tblcourseschedule::whereIn('scheduleid', $scheduleid)->first();

        $startDate = Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $endDate = Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        while ($startDate <= $endDate) {
            // Check if the current day is not a Sunday (day of week = 0)
            if ($startDate->dayOfWeek !== 0 || $schedule->specialclassid == 1) {
                $this->dateRange[$startDate->format('Y-m-d')] = $startDate->format('l');
            }
            $startDate->addDay();
        }

        // $this->attendanceData = tblscheduleattendance::whereIn('traineeid',  $this->att_trainees->pluck('traineeid'))
        $this->attendanceData = tblscheduleattendance::whereIn('traineeid', $this->att_trainees->select('tbltraineeaccount.traineeid')->pluck('traineeid'))
            ->whereIn('date', array_keys($this->dateRange))
            ->whereIn('scheduleid', $scheduleid)
            ->get();

        $traineesPerPage = 10;
        $totalPages = ceil($this->att_trainees->count() / $traineesPerPage);

        $data = [
            'schedule' => $schedule,
            'att_trainees' => $this->att_trainees->get(),
            'dateRange' => $this->dateRange,
            'attendanceData' => $this->attendanceData,
            'traineesPerPage' => $traineesPerPage,
            'totalPages' => $totalPages,
        ];

        $pdf = FacadePdf::loadView('livewire.admin.generate-docs.a-generate-attendance-component', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }



    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-attendance-component');
    }
}
