<?php

namespace App\Http\Livewire\Admin\Instructor\EditInstructor;

use App\Models\tblinstructorattachment;
use App\Models\tblinstructorattachmenttype;
use App\Models\User;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCertificatesLicensesComponent extends Component
{
    use WithFileUploads;
    use ConsoleLog;
    public $user;
    public $attachmentscoc;
    public $attachmentscop;
    public $attachmentsimo;
    public $attachmentssrib;
    public $attachmentsssc;
    public $attachmentstc;
    public $certitype;
    public $certiname;
    public $certifile = [];
    public $certiexpdate;

    public $updcertiexpdate;
    public $upcertiid;

    public $listeners = ['deletecerti'];


    public function mount($hashid)
    {
        try {
            $this->user = User::where('hash_id', $hashid)->first();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function confirmdelete($certiid)
    {

        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'You want to delete this certicate?',
            'funct' => 'deletecerti',
            'id' => $certiid
        ]);
    }




    public function render()
    {
        try {
            $copid = tblinstructorattachmenttype::where('attachmenttype', '=', 'COP')->first();
            $cocid = tblinstructorattachmenttype::where('attachmenttype', '=', 'COC')->first();
            $imoid = tblinstructorattachmenttype::where('attachmenttype', '=', 'IMO Licenses')->first();
            $srib = tblinstructorattachmenttype::where('attachmenttype', '=', 'SIRB Entries')->first();
            $sscid = tblinstructorattachmenttype::where('attachmenttype', '=', 'Sea Service Card')->first();
            $tcid = tblinstructorattachmenttype::where('attachmenttype', '=', 'Training Certificates')->first();

            $this->attachmentscoc = tblinstructorattachment::where('userid', $this->user->user_id)->where('attachmenttypeid', $cocid->id)->where('is_Deleted', 0)->get();
            $this->attachmentscop = tblinstructorattachment::where('userid', $this->user->user_id)->where('attachmenttypeid', $copid->id)->where('is_Deleted', 0)->get();
            $this->attachmentsimo = tblinstructorattachment::where('userid', $this->user->user_id)->where('attachmenttypeid', $imoid->id)->where('is_Deleted', 0)->get();
            $this->attachmentssrib = tblinstructorattachment::where('userid', $this->user->user_id)->where('attachmenttypeid', $srib->id)->where('is_Deleted', 0)->get();
            $this->attachmentsssc = tblinstructorattachment::where('userid', $this->user->user_id)->where('attachmenttypeid', $sscid->id)->where('is_Deleted', 0)->get();
            $this->attachmentstc = tblinstructorattachment::where('userid', $this->user->user_id)->where('attachmenttypeid', $tcid->id)->where('is_Deleted', 0)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }



        return view('livewire.admin.instructor.edit-instructor.edit-certificates-licenses-component')->layout('layouts.admin.abase');
    }

    public function updatecerti($certiid)
    {
        try {
            $tblattachment = tblinstructorattachment::find($certiid);
            $this->updcertiexpdate = $tblattachment->expirationdate;
            $this->upcertiid = $tblattachment->id;

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#modalupdatecertificate',
                'do' => 'show'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function formcertiupdate()
    {
        try {
            $tblattachment = tblinstructorattachment::find($this->upcertiid);

            $tblattachment->expirationdate = $this->updcertiexpdate;
            $tblattachment->save();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'title' => 'Update Successful',
                'position' => 'middle',
                'icon' => 'success',
                'confirmbtn' => false
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#modalupdatecertificate',
                'do' => 'hide'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deletecerti($certiid)
    {
        try {
            $tblattachment = tblinstructorattachment::find($certiid);
            $tblattachment->is_Deleted = 1;
            $tblattachment->save();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'title' => 'Deleted',
                'position' => 'middle',
                'icon' => 'success',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function addcertitype($attachmenttypeid)
    {
        $this->certifile = null;
        switch ($attachmenttypeid) {
            case 1:
                $this->certiname = "COC";
                break;

            case 2:
                $this->certiname = "COP";
                break;

            case 3:
                $this->certiname = "IMO Licenses";
                break;

            case 4:
                $this->certiname = "SIRB Entries";
                break;

            case 5:
                $this->certiname = "Sea Service Card";
                break;

            default:
                $this->certiname = "Training Certificates";
                break;
        }
        $this->certitype = $attachmenttypeid;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#modaladdcertificate',
            'do' => 'show'
        ]);
    }

    public function formcertiadd()
    {
        try {
            $attachmenttypeid = $this->certitype;
            $certiexpdate = $this->certiexpdate;
            $certifile = $this->certifile;

            switch ($attachmenttypeid) {
                case 1:
                    if ($certifile !== null) {
                        $tblattachment = new tblinstructorattachment;
                        $tblattachment->userid = $this->user->user_id;
                        $tblattachment->attachmenttypeid = $attachmenttypeid;
                        $tblattachment->filepath = $certifile->hashName();
                        $tblattachment->filename = $certifile->getClientOriginalName();
                        $tblattachment->expirationdate = $certiexpdate;
                        $tblattachment->is_Deleted = 0;
                        $tblattachment->save();

                        // Store the new file
                        $certifile->store('uploads/instructorattachment', 'public');
                        $certifile = null;
                        $this->certifile = [];
                        $this->certiexpdate = null;
                        $this->certitype = null;

                        $this->dispatchBrowserEvent('danielsweetalert', [
                            'title' => 'COC uploaded succesfully',
                            'position' => 'middle',
                            'icon' => 'success',
                            'confirmbtn' => false
                        ]);

                        $this->dispatchBrowserEvent('d_modal', [
                            'id' => '#modaladdcertificate',
                            'do' => 'hide'
                        ]);
                    }
                    break;

                case 2:
                    if ($certifile !== null) {
                        $tblattachment = new tblinstructorattachment;
                        $tblattachment->userid = $this->user->user_id;
                        $tblattachment->attachmenttypeid = $attachmenttypeid;
                        $tblattachment->filepath = $certifile->hashName();
                        $tblattachment->filename = $certifile->getClientOriginalName();
                        $tblattachment->expirationdate = $certiexpdate;
                        $tblattachment->is_Deleted = 0;
                        $tblattachment->save();

                        // Store the new file
                        $certifile->store('uploads/instructorattachment', 'public');
                        $certifile = null;

                        $this->dispatchBrowserEvent('danielsweetalert', [
                            'title' => 'COP uploaded succesfully',
                            'position' => 'middle',
                            'icon' => 'success',
                            'confirmbtn' => false
                        ]);

                        $this->dispatchBrowserEvent('d_modal', [
                            'id' => '#modaladdcertificate',
                            'do' => 'hide'
                        ]);
                    }
                    $this->certifile = null;
                    $this->certiexpdate = null;
                    $this->certitype = null;
                    break;

                case 3:
                    if ($certifile !== null) {
                        $tblattachment = new tblinstructorattachment;
                        $tblattachment->userid = $this->user->user_id;
                        $tblattachment->attachmenttypeid = $attachmenttypeid;
                        $tblattachment->filepath = $certifile->hashName();
                        $tblattachment->filename = $certifile->getClientOriginalName();
                        $tblattachment->expirationdate = $certiexpdate;
                        $tblattachment->is_Deleted = 0;
                        $tblattachment->save();

                        // Store the new file
                        $certifile->store('uploads/instructorattachment', 'public');
                        $certifile = null;

                        $this->dispatchBrowserEvent('danielsweetalert', [
                            'title' => 'IMO Licenses uploaded succesfully',
                            'position' => 'middle',
                            'icon' => 'success',
                            'confirmbtn' => false
                        ]);

                        $this->dispatchBrowserEvent('d_modal', [
                            'id' => '#modaladdcertificate',
                            'do' => 'hide'
                        ]);

                        $this->certifile = null;
                        $this->certiexpdate = null;
                        $this->certitype = null;
                    }
                    break;

                case 4:
                    if ($certifile !== null) {
                        $tblattachment = new tblinstructorattachment;
                        $tblattachment->userid = $this->user->user_id;
                        $tblattachment->attachmenttypeid = $attachmenttypeid;
                        $tblattachment->filepath = $certifile->hashName();
                        $tblattachment->filename = $certifile->getClientOriginalName();
                        $tblattachment->expirationdate = $certiexpdate;
                        $tblattachment->is_Deleted = 0;
                        $tblattachment->save();

                        // Store the new file
                        $certifile->store('uploads/instructorattachment', 'public');
                        $certifile = null;

                        $this->dispatchBrowserEvent('danielsweetalert', [
                            'title' => 'SIRB Entries uploaded succesfully',
                            'position' => 'middle',
                            'icon' => 'success',
                            'confirmbtn' => false
                        ]);

                        $this->dispatchBrowserEvent('d_modal', [
                            'id' => '#modaladdcertificate',
                            'do' => 'hide'
                        ]);

                        $this->certifile = null;
                        $this->certiexpdate = null;
                        $this->certitype = null;
                    }
                    break;

                case 5:
                    if ($certifile !== null) {
                        $tblattachment = new tblinstructorattachment;
                        $tblattachment->userid = $this->user->user_id;
                        $tblattachment->attachmenttypeid = $attachmenttypeid;
                        $tblattachment->filepath = $certifile->hashName();
                        $tblattachment->filename = $certifile->getClientOriginalName();
                        $tblattachment->expirationdate = $certiexpdate;
                        $tblattachment->is_Deleted = 0;
                        $tblattachment->save();

                        // Store the new file
                        $certifile->store('uploads/instructorattachment', 'public');
                        $certifile = null;

                        $this->dispatchBrowserEvent('danielsweetalert', [
                            'title' => 'Sea Service Card uploaded succesfully',
                            'position' => 'middle',
                            'icon' => 'success',
                            'confirmbtn' => false
                        ]);

                        $this->dispatchBrowserEvent('d_modal', [
                            'id' => '#modaladdcertificate',
                            'do' => 'hide'
                        ]);

                        $this->certifile = null;
                        $this->certiexpdate = null;
                        $this->certitype = null;
                    }
                    break;

                default:
                    if ($certifile !== null) {
                        $tblattachment = new tblinstructorattachment;
                        $tblattachment->userid = $this->user->user_id;
                        $tblattachment->attachmenttypeid = $attachmenttypeid;
                        $tblattachment->filepath = $certifile->hashName();
                        $tblattachment->filename = $certifile->getClientOriginalName();
                        $tblattachment->expirationdate = $certiexpdate;
                        $tblattachment->is_Deleted = 0;
                        $tblattachment->save();

                        // Store the new file
                        $certifile->store('uploads/instructorattachment', 'public');
                        $certifile = null;

                        $this->dispatchBrowserEvent('danielsweetalert', [
                            'title' => 'Training Certificates uploaded succesfully',
                            'position' => 'middle',
                            'icon' => 'success',
                            'confirmbtn' => false
                        ]);

                        $this->dispatchBrowserEvent('d_modal', [
                            'id' => '#modaladdcertificate',
                            'do' => 'hide'
                        ]);

                        $this->certifile = "";
                        $this->certiexpdate = null;
                        $this->certitype = null;
                    }

                    break;
            }

            $this->certifile = [];
            $this->certiexpdate = null;
            $this->certitype = null;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
