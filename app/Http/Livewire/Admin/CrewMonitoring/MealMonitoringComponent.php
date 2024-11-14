<?php

namespace App\Http\Livewire\Admin\CrewMonitoring;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\tblenroled;
use Livewire\WithPagination;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblmealmonitoring;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;

class MealMonitoringComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $enroledid, $enroledidForeign, $local = true, $foreign = false, $foreignDate, $mealtype;
    public $search;

    public function changeForm($txt)
    {
        switch ($txt) {
            case 'foreign':
                $this->local = false;
                $this->foreign = true;
                break;

            default:
                $this->local = true;
                $this->foreign = false;
                break;
        }
    }

    public function saveForeignMeal()
    {
        $this->validate([
            'enroledidForeign' => 'required|integer',
            'mealtype' => 'required',
            'foreignDate' => 'required|date'
        ], [
            'enroledidForeign.required' => 'Foreign ID is required.',
            'enroledidForeign.integer' => 'Foreign ID must be a number, not letters.',
            'mealtype.required' => 'Meal type is required.',
            'foreignDate.required' => 'Date is required.',
            'foreignDate.date' => 'Date must be a valid date.'
        ]);

        try {
            $check = tblmealmonitoring::create([
                'enroledid' => $this->enroledidForeign,
                'mealtype' => $this->mealtype,
                'created_date' => $this->foreignDate
            ]);

            if ($check) {
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Meal Added.'
                ]);
            }
        } catch (QueryException $e) {
            $errorMessage = $e->errorInfo[2] ?? 'An unexpected error occurred.';
            $this->dispatchBrowserEvent('error-log', [
                'title' => $errorMessage
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('error-log', [
                'title' => $e->getMessage()
            ]);
        }
    }

    public function updatedEnroledid($value)
    {
        $check = tblenroled::find($value);

        if ($check === null) {
            $this->requestMessage('null', 'Cannot find ID!');
        } else {
            // Carbon::setTestNow('2024-02-21 06:00:00');
            $now = Carbon::now('Asia/Manila');
            $hour = $now->hour;

            if ($hour > 5 && $hour <= 10) {
                $this->savemeal($this->enroledid, 1);
            } elseif ($hour >= 11 && $hour <= 15) {
                $this->savemeal($this->enroledid, 2);
            } elseif ($hour >= 16 && $hour <= 20) {
                $this->savemeal($this->enroledid, 3);
            } else {
                $this->requestMessage('error', 'Meal Monitoring is closed');
            }
        }
        $this->reset();
    }

    public function requestMessage($type, $msg)
    {
        session()->flash($type, $msg);
    }

    public function savemeal($enroledid, $mealtype)
    {
        try {
            $enroleddata = tblenroled::find($enroledid);
            $yeartoday = date("Y");
            $yearoftraining = date("Y", strtotime($enroleddata->schedule->startdateformat));

            if ($yearoftraining == $yeartoday) {
                $store = tblmealmonitoring::create([
                    'enroledid' => $enroledid,
                    'mealtype' => $mealtype
                ]);

                if (!$store) {
                    $this->requestMessage('error', 'error');
                }

                Session::put('enroled_id', $enroledid);
                $this->requestMessage('success', 'Meal recorded successfully!');
            } else {
                $this->requestMessage('error', 'Expired Training Date');
            }
        } catch (\Exception $e) {
            $this->requestMessage('error', 'Record already exist');
        }
    }

    public function render()
    {
        $mealrecords = tblmealmonitoring::where('deletedid', 0)
            ->whereHas('enrolinfo', function ($query) {
                $query->whereHas('trainee', function ($query2) {
                    $query2->where('l_name', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('f_name', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('m_name', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'Desc')
            ->paginate(10);
        return view('livewire.admin.crew-monitoring.meal-monitoring-component', [
            'mealrecords' => $mealrecords
        ])->layout('layouts.admin.abase');
    }
}
