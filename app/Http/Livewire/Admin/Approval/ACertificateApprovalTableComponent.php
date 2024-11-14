<?php

namespace App\Http\Livewire\Admin\Approval;

use App\Mail\SendCertificateApprovalAccept;
use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Traits\CertificateTrait;
use App\Traits\TrainingFormatTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateApprovalTableComponent extends Component
{

    use CertificateTrait;
    use TrainingFormatTrait;

    public $schedule;
    public $editingCertId = null;
    public $cert_num;
    public $reg_num;
    public $cert_id;
    public $comments = [];
    public $selectedItems = [];
    public $selectedAll = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    use WithPagination;

    public function mount($training_id)
    {
        $this->schedule = tblcourseschedule::find($training_id);
    }

    public function sendApproval($scheduleid)
    {
        $schedule = tblcourseschedule::findorfail($scheduleid);
        $crews = tblenroled::where(function ($query) use ($scheduleid) {
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

        $user = Auth::user();

        $schedule->printedid = 1;
        $schedule->status_releasing = 1;
        $schedule->updated_at = now();
        $schedule->save();

        $this->emit('refreshComponent'); // Emit this event after approval is sent

        if ($schedule->printedid != 0) {
            $data = [
                'enrolled' => $crews,
                'schedule' => $schedule,
                'user' => $user->formal_name(),
            ];
            Mail::to('bod@neti.com.ph')->cc([$user->email, 'angelo.peria@neti.com.ph', 'eliseo.clemente@neti.com.ph'])->send(new SendCertificateApprovalAccept($data));
            // Mail::to('angelo.peria@neti.com.ph')->cc('')->send(new SendCertificateApprovalAccept($data));
        }
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully sent'
        ]);
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

        $this->exportCertificate($cert->enrolled->enroledid);
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


    // Method to toggle the state of all checkboxes
    public function toggleSelectAll($scheduleid)
    {
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
            ->get();

        $this->selectedAll = !$this->selectedAll;

        if (!$this->selectedAll) {
            $this->selectedItems = [];
            return;
        }

        foreach ($enrolled as $enrol) {
            $certificateHistoryId = optional($enrol->certificate_history)->certificatehistoryid;
            if ($certificateHistoryId) {
                $this->selectedItems[] = $certificateHistoryId;
            }
        }
        $this->selectedAll = false;
    }


    public function performAction()
    {
        try {
            foreach ($this->selectedItems as $item) {
                $cert = tblcertificatehistory::find($item);
                $cert->approved_by = Auth::user()->formal_name();
                $cert->is_approve = 1; //valid
                $cert->save();

                $this->exportCertificate($cert->enrolled->enroledid);
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

        $schedule = $this->schedule;

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
            ->paginate(15);

        return view(
            'livewire.admin.approval.a-certificate-approval-table-component',
            [
                'enrolled' => $enrolled,
                'scheduleid' => $scheduleid,
                'schedule' => $schedule
            ]
        );
    }
}
