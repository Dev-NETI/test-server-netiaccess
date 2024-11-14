<?php

namespace App\Http\Livewire\Admin\TCROA;

use App\Exports\TCROAExport;
use App\Exports\TCROAExportRemedial;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblremedial;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ADisplayTcroaComponent extends Component
{
    public $enroledid;

    public function export()
    {
        $scheduleid = Session::get('scheduleid');
        $schedule = tblcourseschedule::find($scheduleid);

        $crews = tblenroled::where('pendingid', 0)->where(function ($query) use ($scheduleid) {
            $query->where('scheduleid', $scheduleid)->orWhere('remedial_sched', $scheduleid)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })
            ->where('dropid', 0)
            ->where('deletedid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        $filenameExport = $schedule->course->coursename . '(' . $schedule->startdateformat . ')' . ".xlsx";

        try {
            return Excel::download(new TCROAExport([$crews, $schedule]), $filenameExport);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function remedial_export($enroledid)
    {
        $this->enroledid = $enroledid;
        $schedule = tblremedial::where('enroledid', $enroledid)->first();

        // Check if schedule is not found
        if (!$schedule) {
            abort(404); // Display 404 error
        }

        $crew = tblenroled::where('enroledid', $enroledid)->where('pendingid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->first();

        $filenameExport = $schedule->schedule->course->coursename . '(' . $schedule->schedule->startdateformat . ')' . ".xlsx";

        try {
            return Excel::download(new TCROAExportRemedial([$crew, $schedule->schedule]), $filenameExport);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.admin.t-c-r-o-a.a-display-tcroa-component');
    }
}
