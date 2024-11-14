<?php

namespace App\Http\Livewire\Admin\Reports\EnrollmentStatistics;

use App\Models\tblcompany;
use App\Models\tblenroled;
use Livewire\Component;

class EnrollmentStatsViewComponent extends Component
{
    public $dateFrom;
    public $dateTo;
    public $company;
    public $statistics_data;

    protected $rules = [
        'dateFrom' => 'required',
        'dateTo' => 'required',
        'company' => 'required'
    ];
    
    public function render()
    {
        $company_data = tblcompany::where('deletedid',0)->orderBy('company', 'asc')->get();
        return view('livewire.admin.reports.enrollment-statistics.enrollment-stats-view-component', 
        compact('company_data')
        )->layout('layouts.admin.abase');
    }

    public function show()
    {
        $this->validate();

        $enrollment_data = tblenroled::where('pendingid',0)
                                     ->where('deletedid', 0)
                                     ->whereHas('trainee', function($query){
                                            $query->where('company_id', $this->company);
                                     })
                                     ->whereHas('schedule', function($query){
                                            $query->where('startdateformat', '>=' , $this->dateFrom)
                                                ->where('enddateformat', '<=' , $this->dateTo);
                                        })
                                     ->get();
        $this->statistics_data = $enrollment_data;
        // dd($enrollment_data);
    }
}
