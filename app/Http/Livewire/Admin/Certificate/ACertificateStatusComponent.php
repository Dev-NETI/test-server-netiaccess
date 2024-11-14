<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateStatusComponent extends Component
{
    public $schedule;
    public $editingCertId = null;
    public $cert_num;
    public $reg_num;
    public $cert_id;
    public $comments = [];
    public $selectedItems = [];
    public $enroledid, $remarks;

    use WithPagination;

    public function mount($training_id)
    {
        $this->schedule = tblcourseschedule::find($training_id);
    }


    public function approved_cert($certificate_id)
    {
        $cert = tblcertificatehistory::where('certificatehistoryid', $certificate_id)->first();
        $cert->approved_by = Auth::user()->formal_name();
        $cert->is_approve = 1; //valid
        $cert->save();

        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Approve Certificate'
        ]);
    }

    public function dis_cert($certificate_id)
    {
        $cert = tblcertificatehistory::where('certificatehistoryid', $certificate_id)->first();
        $cert->approved_by = Auth::user()->formal_name();
        $cert->is_approve = 2;
        $comment = $this->comments[$certificate_id] ?? '';
        $cert->invalid_comment = $comment;
        $cert->save();

        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Invalid Certificate'
        ]);
    }

    public function edit_cert($cert_id)
    {
        $this->cert_id = $cert_id;
        $this->editingCertId = $cert_id;

        $certificate = tblcertificatehistory::find($cert_id);
        $this->cert_num = $certificate->certificatenumber;
        $this->reg_num = $certificate->registrationnumber;
    }

    public function edit_remarks($enroledid)
    {
        $this->enroledid = $enroledid;
        $enroled = tblenroled::find($enroledid);
        $this->remarks = $enroled->remedial_remarks;
    }

    public function save_remarks()
    {
        $enroled = tblenroled::find($this->enroledid);
        $enroled->remedial_remarks = $this->remarks;
        $enroled->save();
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Edit Remarks'
        ]);
        $this->dispatchBrowserEvent('close-model');
    }

    public function save_edit_cert($cert_id)
    {
        $certificate = tblcertificatehistory::find($cert_id);
        $certificate->registrationnumber = $this->reg_num;
        $certificate->certificatenumber = $this->cert_num;
        $certificate->save();

        $this->editingCertId = null;
    }

    public function selectItem($itemId)
    {
        if (in_array($itemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
        } else {
            $this->selectedItems[] = $itemId;
        }
    }


    public function performAction()
    {
        try {
            foreach ($this->selectedItems as $item) {
                $cert = tblcertificatehistory::find($item);
                $cert->approved_by = Auth::user()->formal_name();
                $cert->is_approve = 1; //valid
                $cert->save();
            }
            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Approve Certificates'
            ]);

            $this->selectedItems = [];
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        $scheduleid = $this->schedule->scheduleid;

        $enrolled = tblenroled::where(function ($query) use ($scheduleid) {
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
            ->paginate(10);


        return view(
            'livewire.admin.certificate.a-certificate-status-component',
            [
                'enrolled' => $enrolled,
            ]
        );
    }
}
