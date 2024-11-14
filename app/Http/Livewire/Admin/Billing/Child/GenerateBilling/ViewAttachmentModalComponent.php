<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use App\Models\billingattachment;
use App\Models\tbltransferbillingattachment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ZipArchive;

class ViewAttachmentModalComponent extends Component
{
    use AuthorizesRequests;
    public $scheduleid;
    public $companyid;
    public $billingAttachments;
    protected $listeners = ['deleteAttachment'];

    public function render()
    {
        $transferedBilling = session('transferedBilling');
        if (is_array($this->scheduleid)) {
            $scheduleid = implode(',', $this->scheduleid);
        } else {
            $scheduleid = $this->scheduleid;
        }

        if ($transferedBilling) {
            $attachments = tbltransferbillingattachment::where('scheduleid', 'like', '%' . $scheduleid . '%')->get();

            $this->billingAttachments = $attachments;
        } else {

            try {
                $attachments = billingattachment::where('scheduleid', 'like', '%' . $scheduleid  . '%')
                    ->where('companyid', $this->companyid)
                    ->where('is_deleted', 0)
                    ->get();


                $this->billingAttachments = $attachments;
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }
        }

        return view('livewire.admin.billing.child.generate-billing.view-attachment-modal-component', compact('attachments'));
    }

    public function downloadAllAttachment()
    {
        $attachments = $this->billingAttachments;
        $fileNames = [];

        foreach ($attachments as $key => $value) {
            $fileNames[] = [
                'filepath' => $value['filepath'],
                'title' => $value['title']
            ];
        }

        $zip = new ZipArchive();
        $zipFileName = 'billingattachments.zip';
        $zipFilePath = storage_path('uploads/' . $zipFileName);
        $path = storage_path('app/public/uploads/billingAttachment/');

        // Check if the storage directory exists, if not, create it
        if (!is_dir(storage_path('uploads'))) {
            mkdir(storage_path('uploads'));
        }

        // Open the zip file
        $openResult = $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openResult !== TRUE) {
            // Output the open result and the zip file path for debugging
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Error #503'
            ]);
        }

        foreach ($fileNames as $index => $fileName) {
            $filePath = $path . $fileName['filepath'];
            $fileInfo = pathinfo($fileName['filepath']);
            $extension = $fileInfo['extension'];

            if (file_exists($filePath)) {
                $relativeName = basename($fileName['title'] . '.' . $extension);
                $zip->addFile($filePath, $relativeName);
            } else {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Error #404'
                ]);
            }
        }


        $zip->close();

        // Check if the zip file was created successfully
        if (!file_exists($zipFilePath)) {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Error #500'
            ]);
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'You want to delete this attachment?',
            'id' => $id,
            'funct' => 'deleteAttachment'
        ]);
    }

    public function deleteAttachment($id)
    {
        $transferedBilling = session('transferedBilling');

        if ($transferedBilling) {
            $attachment = tbltransferbillingattachment::find($id);

            dd($attachment);
        } else {
            $attachment = billingattachment::find($id);
            $attachment->update([
                'is_deleted' => 1
            ]);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Deleted Successfully'
            ]);
        }
    }

    public function view($path)
    {
        Gate::authorize('authorizeBillingAccess', 59);
        return redirect()->away($path);
    }
}
