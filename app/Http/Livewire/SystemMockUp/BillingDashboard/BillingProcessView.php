<?php

namespace App\Http\Livewire\SystemMockUp\BillingDashboard;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BillingProcessView extends Component
{
    public $status, $company, $course = "IGF-A", $trainingDate = "Sep. 16, 2024 to Sep. 20, 2024";
    public $traineeListData = [
        [
            'name' => 'GERRY R. CASISON',
            'vessel' => 'IGF-TANKER',
            'rank' => 'C/E',
            'company' => 'NYK-FIL SHIP MANAGEMENT INC.',
            'nationality' => 'Filipino',
            'serialnumber' => '2409-025',
        ],
        [
            'name' => 'CESAR JR. P. COLANGO',
            'vessel' => 'IGF-PCC',
            'rank' => '1/AE',
            'company' => 'NYK-FIL SHIP MANAGEMENT INC.',
            'nationality' => 'Filipino',
            'serialnumber' => '2409-025',
        ],
    ];
    public $attachment = [
        [
            'title' => 'DMT 043',
            'attachmentType' => 'Dormitory , Meal and Transportation',
            'filePath' => 'Mock DMT 1.pdf',
        ],
        [
            'title' => 'DMT 044',
            'attachmentType' => 'Dormitory , Meal and Transportation',
            'filePath' => 'Mock DMT 2.pdf',
        ],
        [
            'title' => 'RF 043',
            'attachmentType' => 'Registration Form',
            'filePath' => 'Mock RF 1.pdf',
        ],
        [
            'title' => 'RF 044',
            'attachmentType' => 'Registration Form',
            'filePath' => 'Mock RF 2.pdf',
        ],
        [
            'title' => 'BUS',
            'attachmentType' => 'Transportation',
            'filePath' => 'Mock Bus.pdf',
        ]
    ];

    public function mount($status = 1)
    {
        switch ($status) {
            case 1:
                $this->status = "Manning Board";
                break;
            case 2:
                $this->status = "MHR Board";
                break;
            case 3:
                $this->status = "NYKSM Board";
                break;
            case 4:
                $this->status = "Proof of Payment Board";
                break;
            case 5:
                $this->status = "A.R. Board";
                break;
            default:
                $this->status = "Closed Transaction Board";
                break;
        }
    }

    public function render()
    {
        return view('livewire.system-mock-up.billing-dashboard.billing-process-view')
            ->layout('layouts.admin.abase');
    }

    public function openDocument($fileName)
    {
        $url = Storage::url('Mock-up-document/' . $fileName);
        return redirect()->to($url);
    }
}
