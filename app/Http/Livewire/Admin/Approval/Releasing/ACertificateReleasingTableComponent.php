<?php

namespace App\Http\Livewire\Admin\Approval\Releasing;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateReleasingTableComponent extends Component
{
    public $schedule;
    public $editingCertId = null;
    public $cert_num;
    public $reg_num;
    public $cert_id;
    public $comments = [];
    public $selectedItems = [];

    use WithPagination;

    public function mount($training_id)
    {
        $this->schedule = tblcourseschedule::find($training_id);
    }

    public function released_cert($certificate_id)
    {
        $cert = tblcertificatehistory::where('certificatehistoryid', $certificate_id)->first();
        $cert->is_released = 1; //valid
        $cert->is_released_date = Carbon::now();
        $cert->save();

        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Released Certificate'
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
                $cert->is_released = 1; //valid
                $cert->is_released_date = Carbon::now();
                $cert->save();
            }
            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Released Certificates'
            ]);

            $this->selectedItems = [];
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        $enrolled = tblenroled::where('scheduleid', $this->schedule->scheduleid)->where('deletedid', 0)->where('pendingid', 0)->where('dropid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->join('tblcertificatehistory', 'tblcertificatehistory.enroledid', '=', 'tblenroled.enroledid')
            ->where('tblcertificatehistory.is_approve', 1)
            ->orderBy('tbltraineeaccount.l_name', 'ASC')
            ->paginate(10);

        return view(
            'livewire.admin.approval.releasing.a-certificate-releasing-table-component',
            [
                'enrolled' => $enrolled
            ]
        )->layout('layouts.admin.abase');
    }
}
