<?php

namespace App\Http\Livewire\Admin\Approval\Invalid;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateInvalidTableComponent extends Component
{
    public $schedule;
    public $editingCertId = null;
    public $cert_num;
    public $reg_num;
    public $cert_id;
    public $comments = [];
    public $adjustment = [];

    use WithPagination;

    public function mount($training_id)
    {
        $this->schedule = tblcourseschedule::find($training_id);
    }

    public function approval_cert($certificate_id)
    {
        $cert = tblcertificatehistory::where('certificatehistoryid', $certificate_id)->first();
        $cert->is_released = null; //valid
        $cert->is_approve = null;
        $comment = $this->adjustment[$certificate_id] ?? '';
        $cert->adjustment_comment = $comment;
        $cert->save();

        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Back to Approval Certificate'
        ]);
    }

    public function render()
    {
        $enrolled = tblenroled::where('scheduleid', $this->schedule->scheduleid)->where('deletedid', 0)->where('pendingid', 0)->where('dropid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->join('tblcertificatehistory', 'tblcertificatehistory.enroledid', '=', 'tblenroled.enroledid')
            ->where('tblcertificatehistory.is_approve', 2)
            ->orderBy('tbltraineeaccount.l_name', 'ASC')
            ->paginate(10);
        return view(
            'livewire.admin.approval.invalid.a-certificate-invalid-table-component',
            [
                'enrolled' => $enrolled
            ]
        );
    }
}
