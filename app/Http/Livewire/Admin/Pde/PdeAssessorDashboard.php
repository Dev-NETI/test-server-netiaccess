<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use App\Models\tblrank;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PdeAssessorDashboard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pdeid;


    public function getStatusLabel($statusId)
    {
        switch ($statusId) {
            case 1:
                return 'Pending';
            case 2:
                return 'For Assessment';
            case 3:
                return 'Assessing';
            case 4:
                return 'Certificate is for Signature';
            case 5:
                return 'For Delivery';
            default:
                return 'Unknown Status';
        }
    }
    
    public function render()
    {

        try 
        {

            $user = Auth::user();
            //SELECT ALL PDE 
            $query = tblpde::query();

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('surname', 'like', '%' . $this->search . '%');
                    $q->orWhere('givenname', 'like', '%' . $this->search . '%');
                    $q->orWhere('middlename', 'like', '%' . $this->search . '%');
                    $q->orWhere('suffix', 'like', '%' . $this->search . '%');
                    $q->orWhere('certificatenumber', 'like', '%' . $this->search . '%');
                });
            }

            $query->where('deletedid', 0);
            $query->orderBy('created_at', 'desc');
            $query->where('PDECertAssessorID', $user->user_id);
            $count_pde = tblpde::where('PDECertAssessorID', $user->user_id)->count();

            $AssessorPdeRecord = $query->paginate(10);

            //Download Document File 
            $timestamp = now()->format('YmdHis');
            $desiredFilename = 'document_' . $timestamp . '.zip';


            //RETRIEVE RANK LEVEL
            $retrieverank = tblrank::where('IsPDECert', 1)
                ->orderBy('rank', 'asc')
                ->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }


        return view('livewire.admin.pde.pde-assessor-dashboard',[
            "AssessorPdeRecord" => $AssessorPdeRecord,
            'retrieverank' => $retrieverank,
            'desiredFilename' => $desiredFilename,
            'count_pde' => $count_pde
        ])->layout('layouts.admin.abase');
    }
}
