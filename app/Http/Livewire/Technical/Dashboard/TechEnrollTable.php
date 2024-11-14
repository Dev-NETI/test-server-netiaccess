<?php

namespace App\Http\Livewire\Technical\Dashboard;

use App\Mail\SendConfirmedEnrollment;
use App\Models\tblcourses;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Tblenroled;
use App\Models\tbltraineeaccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class TechEnrollTable extends DataTableComponent
{
    // protected $model = Tblenroled::class;

    public function builder(): Builder
    {
        return Tblenroled::query()
            ->with('trainee')
            ->where('tblenroled.deletedid', '=', 0)
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid');
    }

    public function configure(): void
    {
        // $this->setDebugEnabled();
        $this->setUseHeaderAsFooterEnabled();
        $this->setEagerLoadAllRelationsEnabled();
        $this->setPrimaryKey('enroledid');
        $this->setRefreshKeepAlive();
        $this->setRefreshTime(10000);
        $this->setFiltersEnabled();
        $this->setTableAttributes([
            'id' => 'my-id',
            'class' => '',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Enroled ID", "enroledid")
                ->sortable(),
            Column::make("Last Name", "trainee.l_name")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    return strtoupper($value); // Convert the last name to uppercase
                }),
            Column::make("First Name", "trainee.f_name")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    return strtoupper($value); // Convert the last name to uppercase
                }),
            Column::make("Middle Name", "trainee.m_name")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    return strtoupper($value); // Convert the last name to uppercase
                }),
            Column::make("Fleet", "trainee.fleet.fleet")
                ->sortable(),

                Column::make("Enrolled In", "course.coursename")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    return strtoupper($value);
                }),

                Column::make("Start", "schedule.startdateformat")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    return date('d, F, Y', strtotime($value)); 
                }),

                Column::make("End", "schedule.enddateformat")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    return date('d, F, Y', strtotime($value)); 
                }),

                Column::make("Date Applied", "created_at")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    return date('d, F, Y', strtotime($value)); 
                }),

                Column::make("Status", "pendingid")
                ->sortable()->searchable()->collapseOnTablet()->format(function ($value, $column, $row) {
                    if($value == 1) {
                        return 'Pending';
                    } else {
                        return 'Enroled';
                    }
                }),

            Column::make('Actions')
                ->label(
                    function ($row, Column $column) {
                        if($row->pendingid == 1) {
                            $Accept = '<button class="btn btn-sm btn-success m-1" wire:click="approved_enroll(' . $row->enroledid . ')">Accept</button>';
                            $Reject = '<button class="btn btn-sm btn-danger ms-1" wire:click="reject_enroll(' . $row->enroledid . ')">Reject</button>';
                            return $Accept . $Reject;
                        } 
                    }
                )->html(),
        ];
    }

    public function approved_enroll($id)
    {
        // check if Auth is null or not logged in

        $enroll = tblenroled::find($id);
        $enroll->pendingid = 0;
        $dateconfirmed = Carbon::now('Asia/Manila');
        $enroll->dateconfirmed = $dateconfirmed;
        $enroll->save();
        $this->generatePdf($id);
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Approved enrollment application'
        ]);

        $data = [
            'event_type' => 'enroll_approved',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo('notification.notification-component', 'add',   
        'approved an enrollment application', $data);

    }

    
    public function reject_enroll($id)
    {
        $enroll = tblenroled::find($id);
        $enroll->deletedid = 1;
        $enroll->save();

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Reject enrollment application'
        ]);


        $data = [
            'event_type' => 'enroll_rejected',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo('notification.notification-component', 'add',   
        'rejected an enrollment application', $data);
    }

    public function generatePdf($enrol_id)
    {
        $enrol = tblenroled::findOrFail($enrol_id);
        $trainee = tbltraineeaccount::findOrFail($enrol->trainee->traineeid);

        Mail::to($enrol->trainee->email)->send(new SendConfirmedEnrollment($enrol, $trainee));
    }

    public function filters(): array
    {
        $fleetFilter = SelectFilter::make('FLEET')
            ->setFilterPillTitle('FLEET CATEGORY')
            ->setFilterPillValues([
                '10' => 'NTMA',
                '17' => 'NTMA-NETI',
                '18' => 'Technical (DRY)',
                '19' => 'Technical (LIQUID)',
            ])
            ->options([
                '' => 'All',
                '10' => 'NTMA',
                '17' => 'NTMA-NETI',
                '18' => 'Technical (DRY)',
                '19' => 'Technical (LIQUID)',
            ])
            ->filter(function (Builder $builder, $value) {
                if ($value === '18') {
                    $builder->where('tbltraineeaccount.fleet_id', $value);
                } elseif ($value === '19') {
                    $builder->where('tbltraineeaccount.fleet_id', $value);
                } elseif ($value === '17') {
                    $builder->where('tbltraineeaccount.fleet_id', $value);
                } elseif ($value === '10') {
                    $builder->where('tbltraineeaccount.fleet_id', $value);
                }
            });
    
        $courses = tblcourses::all()->pluck('coursename', 'courseid')->toArray();
        $courses[''] = 'All';
    
        $courseFilter = SelectFilter::make('COURSE')
            ->setFilterPillTitle('COURSE CATEGORY')
            ->setFilterPillValues($courses)
            ->options($courses)
            ->filter(function (Builder $builder, $value) {
                    $builder->where('tblcourses.courseid', $value);
            });

        $statusFilter = SelectFilter::make('STATUS')
            ->setFilterPillTitle('STATUS')
            ->setFilterPillValues([
                '1' => 'PENDING',
                '0' => 'ENROLED',
            ])
            ->options([
                '1' => 'PENDING',
                '0' => 'ENROLED',
            ])
            ->filter(function (Builder $builder, $value) {
                $builder->where('tblenroled.pendingid', $value);
            });
    
    
        return [
            $fleetFilter,
            $courseFilter,
            $statusFilter,
        ];
    }
}
