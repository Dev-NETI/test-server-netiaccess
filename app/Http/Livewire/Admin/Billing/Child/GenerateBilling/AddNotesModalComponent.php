<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\tblbillingstatement;
use App\Models\tblbillingstatementcomments;
use App\Models\tbltransferbilling;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddNotesModalComponent extends Component
{
    public $commentTxt;
    public $scheduleid;

    public function removeComments()
    {

        $update = tblbillingstatementcomments::where('scheduleid', $this->scheduleid)->first();

        $update->isactive = 0;
        $check = $update->save();

        if ($check) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Comment Removed'
            ]);
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Oops! Something went wrong. Please try again.'
            ]);
        }
    }

    public function saveComments()
    {
        $istransfered = session('transferedBilling');
        $comment = $this->commentTxt;
        $scheduleid = $this->scheduleid;

        if ($istransfered) {
            $datatoupdate = tbltransferbilling::where('scheduleid', $scheduleid)->get();

            foreach ($datatoupdate as $key => $value) {
                $value->update([
                    'notes_comments' => $comment
                ]);
            }

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Comment added.'
            ]);
        } else {
            $name = Auth::user()->fullname;

            $data = [
                'scheduleid' => json_encode($scheduleid),
                'comment' => $comment,
                'creator' => $name,
            ];


            try {
                tblbillingstatementcomments::create($data);
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Comment Saved'
                ]);
            } catch (\Exception $th) {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Oops! Something went wrong. Please try again.'
                ]);
            }
        }
    }

    public function render()
    {
        $existedNote = tblbillingstatementcomments::where('scheduleid', $this->scheduleid)->where('isactive', 1)->first();
        return view('livewire.admin.billing.child.generate-billing.add-notes-modal-component', compact('existedNote'));
    }
}
