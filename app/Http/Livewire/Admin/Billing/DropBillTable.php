<?php

namespace App\Http\Livewire\Admin\Billing;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\tblbillingdrop;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Lean\ConsoleLog\ConsoleLog;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

class DropBillTable extends DataTableComponent
{
    use ConsoleLog;
    protected $model = tblbillingdrop::class;

    // public function builder(): Builder
    // {
    //     return Tblbillingdrop::query()
    //         ->with([]) // Eager load anything
    //         ->join('tblenroled', 'tblbillingdrop.enroledid', '=', 'tblenroled.enroledid')
    //         ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
    //         ->select('tblbillingdrop.*', 'tbltraineeaccount.f_name', 'tbltraineeaccount.l_name', 'tbltraineeaccount.m_name', 'tbltraineeaccount.suffix');
    // }


    public function configure(): void
    {
        //$this->setDebugStatus(true);
        $this->setPrimaryKey('dropid')
            ->setTableAttributes([
                'class' => 'table-bordered ',
            ])
            ->setTheadAttributes([
                'class' => 'table-dark',
            ])
            ->setSearchEnabled();
      
    }


    public function columns(): array
    {
        return [

            Column::make("Course Name", "coursename")
                ->sortable()
                ->searchable(),
            Column::make('Name', "enroled.trainee.f_name")
                ->searchable(),
            Column::make('Last Name', "enroled.trainee.l_name")
                ->searchable(),
            Column::make("Date Confirmed", "dateconfirmed")
                ->sortable(),
            Column::make("Date Dropped", "datedrop")
                ->sortable(),
            Column::make("Training Start", "enroled.schedule.startdateformat"),
            Column::make("Remarks", "enroled.schedule.scheduleid")
                ->format(function ($value, $row, $column) {
                    $trainingStartDate = Carbon::parse($row['enroled.schedule.startdateformat']);
                    $datedrop = Carbon::parse($row->datedrop);
                    $daysUntilTraining = $datedrop->diffForHumans($trainingStartDate);
                    return "<p class='text-danger'> dropped " . $daysUntilTraining . " training date" . "</p>";
                })
                ->html(),
            Column::make("Fee", "price ")
                ->format(function ($value, $row, $column) {
                    $status = $this->calculateFee($row->datedrop, $row['enroled.schedule.startdateformat']);
                    return $status . "%";
                }),

        ];
    }

    private function calculateFee($datedrop, $trainingStartDate)
    {
        $trainingStartDate = Carbon::parse($trainingStartDate);
        $datedrop = Carbon::parse($datedrop);

        // Calculate the difference in days
        $differenceInDays = $datedrop->diffInDays($trainingStartDate, false);

        if ($differenceInDays < 0) {
            return 100; // If after training start date
        } else if ($differenceInDays >= 7) {
            return 0;
        } else if ($differenceInDays >= 4) {
            return 25;
        } else if ($differenceInDays >= 2) {
            return 50;
        } else {
            return 100;
        }

        return $differenceInDays;
    }
}
