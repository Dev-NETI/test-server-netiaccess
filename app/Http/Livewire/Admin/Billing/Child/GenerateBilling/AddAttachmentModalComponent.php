<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use Livewire\WithFileUploads;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\billingattachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\billingattachmenttype;
use App\Models\tbltraineeaccount;
use App\Models\tbltransferbilling;
use App\Models\tbltransferbillingattachment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AddAttachmentModalComponent extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use ConsoleLog;
    public $file;
    public $title;
    public $attachment_type;
    public $is_OR_selected = 0;
    public $companyid;
    public $scheduleid;
    public $OR_Number;
    public $trainees;
    public $billingstatusid;

    protected $rules = [
        'file' => "required|mimes:jpg,png,pdf|max:2048",
        'title' => 'required',
        'attachment_type' => 'required'
    ];

    public function render()
    {
        $attachmenttype_data = billingattachmenttype::when($this->billingstatusid != 7, function ($query) {
            $query->where('id', '!=', '2');
        })
            ->when($this->billingstatusid != 8, function ($query) {
                $query->where('id', '!=', '3');
            })
            ->when(Auth::user()->u_type != 1, function ($query) {
                $query->where('id', '!=', 4)
                    ->where('id', '!=', 5)
                    ->where('id', '!=', 7)
                    ->where('id', '!=', 8);
            })
            ->orderBy('attachmenttype', 'asc')
            ->get();


        return view('livewire.admin.billing.child.generate-billing.add-attachment-modal-component', compact('attachmenttype_data'));
    }

    public function updatedAttachmentType($value)
    {
        if ($value == 3) {
            $this->is_OR_selected = 1;
        } else {
            $this->is_OR_selected = 0;
        }
    }

    public function upload()
    {
        $this->scheduleid = json_encode($this->scheduleid);
        $transferedBilling = session('transferedBilling');

        if ($transferedBilling) {
            $filepath = $this->file->storeAs('public/uploads/billingAttachment', $this->file->hashName());
            if ($filepath) {
                $scheduleid = json_decode($this->scheduleid);
                $enroledid = tbltransferbilling::whereIn('scheduleid', $scheduleid)->get();
                $enroledids = [];

                foreach ($enroledid as $key => $value) {
                    $enroledids[] = $value->enroledid;
                }

                $create = tbltransferbillingattachment::create([
                    'scheduleid' => $this->scheduleid,
                    'enroledid' => implode(',', $enroledids),
                    'title' => $this->title,
                    'attachmenttypeid' => $this->attachment_type,
                    'filepath' => $this->file->hashName(),
                    'posted_by' => Auth::user()->fullname,
                ]);

                if ($create) {
                    $this->dispatchBrowserEvent('save-log', [
                        'title' => 'Uploaded Successfully'
                    ]);

                    $this->redirect(route('a.billing-viewtrainees'));
                }
            }

            return redirect()->route(Auth::user()->upload_attachment_route);
        } else {
            Gate::authorize('authorizeBillingAccess', 60);
            $this->validate();
            if ($this->is_OR_selected == 1) {
                $this->validate(['OR_Number' => 'required']);
            }

            if (in_array($this->companyid, [115, 262, 89, 285, 286, 287, 289, 290])) {
                try {
                    $check = true;
                    $companyids = [];
                    foreach ($this->trainees as $trainee) {
                        $companyids[] = $trainee->trainee->company_id;
                    }

                    $companyids = array_unique($companyids);
                    $filepath = $this->file->storeAs('public/uploads/billingAttachment', $this->file->hashName());

                    foreach ($companyids as $key => $value) {
                        if ($filepath) {
                            $add_attachment = new billingattachment();
                            $add_attachment->scheduleid = $this->scheduleid;
                            $add_attachment->companyid = $value;
                            $add_attachment->title = $this->title;
                            $add_attachment->filepath = $this->file->hashName();
                            $add_attachment->attachmenttypeid = $this->attachment_type;
                            $add_attachment->is_deleted = 0;

                            if ($this->is_OR_selected == 1) {
                                $add_attachment->OR_Number = $this->OR_Number;
                            }

                            $create = $add_attachment->save();

                            if (!$create) {
                                $check = false;
                            }
                        }
                    }

                    if ($check) {
                        $this->dispatchBrowserEvent('save-log', [
                            'title' => 'Uploaded Successfully'
                        ]);

                        $this->redirect(route('a.billing-viewtrainees'));
                    }
                } catch (\Exception $th) {
                    $this->consoleLog($th->getMessage());
                }
            } else {
                try {
                    $filepath = $this->file->storeAs('public/uploads/billingAttachment', $this->file->hashName());
                    if ($filepath) {
                        $add_attachment = new billingattachment();
                        $add_attachment->scheduleid = $this->scheduleid;
                        $add_attachment->companyid = $this->companyid;
                        $add_attachment->title = $this->title;
                        $add_attachment->filepath = $this->file->hashName();
                        $add_attachment->attachmenttypeid = $this->attachment_type;
                        $add_attachment->is_deleted = 0;

                        if ($this->is_OR_selected == 1) {
                            $add_attachment->OR_Number = $this->OR_Number;
                        }

                        $create = $add_attachment->save();

                        if ($create) {
                            $this->dispatchBrowserEvent('save-log', [
                                'title' => 'Uploaded Successfully'
                            ]);

                            $this->redirect(route('a.billing-viewtrainees'));
                        }
                    }

                    return redirect()->route(Auth::user()->upload_attachment_route);
                } catch (\Exception $e) {
                    $this->consoleLog($e->getMessage());
                }
            }
        }
    }
}
