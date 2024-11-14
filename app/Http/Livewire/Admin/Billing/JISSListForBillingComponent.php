<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Mail\SendJissEmailNotification;
use App\Models\tbljissbilling;
use App\Models\tbljissbillingattachments;
use App\Models\tbljisscompany;
use App\Models\tbljisscompanyemail;
use App\Models\tbljisscourses;
use App\Models\tbljisspricematrix;
use App\Models\tblnationality;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JISSListForBillingComponent extends JISSBillingPdfGenerationComponent
{
    use WithPagination;
    use WithFileUploads;
    public $billingstatusid;
    public $billingid, $isTraineeIncluded, $JISSAttachmentFile, $JISSAttachmentFileOR, $serialNumber;
    public $data;
    public $addexp = 0;
    public $ToggleType = 0;
    public $TraineeNumber = 1;
    public $TraineeInfo = [], $attachments = [];
    public $file;
    public $SelectedCompany;
    public $vatOrSC;
    public $vatOrSCModel = 0;
    public $SelectedCourse;
    public $meal_expenses = null;
    public $dorm_expenses = null;
    public $transpo_expenses = null;
    public $CourseTitle;
    public $MonthCovered;
    public $IDtoupdate;
    public $uploadAttachmentID;
    public $byUploads = false;

    protected $listeners = ['deleteJISSBilling', 'render'];

    public function resetVariables()
    {
        $this->emit('resetVariables');
    }

    public function upload()
    {
        try {
            $this->byUploads = true;
            $this->TraineeInfo = [];
            $spreadsheet = IOFactory::load($this->file->path());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, 'name', 'nationality');
            $check = 0;
            foreach ($data as $key => $values) {
                if ($values[0] == "Trainee's Name" && $values[1] == "Nationality") {
                    $check = 1;
                }

                if ($check == 1) {
                    if ($values[0] != NULL && $values[1] != NULL && $values[0] != "Trainee's Name" && $values[1] != "Nationality" || $values[0] != "" && $values[1] != "" && $values[0] != "Trainee's Name" && $values[1] != "Nationality") {
                        $this->TraineeInfo[] = [
                            'name' => $values[0],
                            'nationality' => $values[1],
                        ];
                    }
                } else {
                    $this->dispatchBrowserEvent('error-log', [
                        'title' => 'File uploaded is not compatible'
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Oops! There is an error. Maybe the file type or file is corrupted. If not contact Administrator @ noc@neti.com.ph',
            ]);
        }
    }

    public function toggleExpenses($val)
    {
        $this->addexp = $val;
    }

    public function editBilling($id)
    {
        $billing = tbljissbilling::find($id);
        $this->SelectedCourse = $billing->courseid;
        $this->SelectedCompany = $billing->company;
        $this->CourseTitle = $billing->trainingtitle;
        $this->MonthCovered = $billing->month_covered;
        $this->serialNumber = $billing->serialnumber;
        $this->vatOrSCModel = $billing->vat_service_charge;
        $this->isTraineeIncluded = $billing->istraineenameincluded;
        $this->meal_expenses = $billing->meal_expenses;
        $this->dorm_expenses = $billing->dorm_expenses;
        $this->transpo_expenses = $billing->transpo_expenses;
        $this->IDtoupdate = $id;
        $this->TraineeInfo = json_decode($billing->trainees);
        $this->TraineeNumber = count($this->TraineeInfo);

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#editBillingModal',
            'do' => 'show'
        ]);
    }

    public function executeUpdateBilling()
    {
        $billing = tbljissbilling::find($this->IDtoupdate);
        $trainees = json_encode($this->TraineeInfo);
        $billing->update([
            "company" => $this->SelectedCompany,
            "courseid" => $this->SelectedCourse,
            "trainingtitle" => $this->CourseTitle,
            "serialnumber" => $this->serialNumber,
            "vat_service_charge" => $this->vatOrSCModel,
            "istraineenameincluded" => $this->isTraineeIncluded,
            "month_covered" => $this->MonthCovered,
            "meal_expenses" => $this->meal_expenses,
            "dorm_expenses" => $this->dorm_expenses,
            "transpo_expenses" => $this->transpo_expenses,
            'trainees' => $trainees,
        ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Billing Updated!'
        ]);

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#editBillingModal',
            'do' => 'hide'
        ]);
    }

    public function checkAvailablePM($id)
    {
        $billing = tbljissbilling::find($id);
        // dd($id);
        $pm =  tbljisspricematrix::where('companyid', $billing->company)->where('courseid', $billing->courseid)->where('is_Deleted', 0)->count();
        if ($pm > 0) {
            $this->redirect(route('a.jiss-pdf', $billing->id));
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Price matrix need to be added!'
            ]);
        }
    }

    public function uploadAttachmentsOR()
    {
        $id = $this->uploadAttachmentID;

        $attachESig = tbljissbilling::find($id);

        $filepath = $this->JISSAttachmentFileOR->store('uploads/jissbillingattachments/', 'public');

        $checkFile = tbljissbillingattachments::where('jissbillingid', $id)->where('filetype', 2)->first();

        if (!empty($checkFile)) {
            $checkFile->update([
                'attachmentpath' => $filepath
            ]);

            $attachESig->update([
                'billingstatusid' => 8
            ]);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Official Receipt Uploaded, Transaction Completed!'
            ]);

            $this->redirect(route('a.jiss-billing'));
        } else {
            $isCreated = tbljissbillingattachments::create([
                'jissbillingid' => $id,
                'filetype' => 2,
                'attachmentpath' => $filepath
            ]);

            if ($isCreated) {

                $attachESig->update([
                    'billingstatusid' => 8
                ]);

                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Official Receipt Uploaded, Transaction Completed!'
                ]);

                $this->redirect(route('a.jiss-billing'));
            } else {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Oops!, There is something wrong uploading your file.'
                ]);
            }
        }
    }

    public function attachEsigBODManager($id)
    {
        $update = tbljissbilling::find($id)->update([
            'approver_2' => 1
        ]);

        if ($update) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Signature Attached!'
            ]);
        }
    }

    public function removeEsigBODManager($id)
    {
        $update = tbljissbilling::find($id)->update([
            'approver_2' => 0
        ]);

        if ($update) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Signature Removed!'
            ]);
        }
    }

    public function sendToBODManagerBoard($id)
    {

        $email = env('JISS_EMAIL_BOD_MANAGER');
        $attachESig = tbljissbilling::find($id);

        if ($attachESig->filepath != NULL) {
            $filepath = storage_path($attachESig->filepath);
        } else {
            $filepath = NULL;
        }

        $content = 'JISS Billing Statement forwarded in you board (BOD Manager Review Board) and need to review. With a serial number ' . $attachESig->serialnumber;
        $this->sendEmail($content, $email, $filepath);

        if ($attachESig->approver_1 == 1) {
            $attachESig->update([
                'billingstatusid' => 1
            ]);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Statement sent!'
            ]);

            $this->redirect(route('a.jiss-billing'));
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Please attach your signature first!'
            ]);
        }
    }

    public function confirmProofofPayment($id)
    {
        $attachESig = tbljissbilling::find($id);
        $attachESig->update([
            'billingstatusid' => 6
        ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Proof of Payment Confirmed!'
        ]);

        $this->redirect(route('a.jiss-list', 6));
    }

    public function sendToClient($id)
    {
        $export = true;
        $this->generatePDF($id, $export);
        $data = tbljissbilling::find($id);
        $emails = tbljisscompanyemail::where('jisscompanyid', $data->company)->get();
        // $emails = env('JISS_SAMPLE_COMPANY_EMAIL');
        if ($data->filepath == NULL) {
            $filepath = NULL;
        } else {
            $filepath = public_path('storage/' . $data->filepath);
        }
        $content = 'JISS Billing Statement forwarded and need to review. With a serial number ' . $data->serialnumber . '. Please check the attachment below.';

        $jissbillingid = $id;
        $newFileName = "jiss_billing_statement_id(" . $jissbillingid . ")_compid(" . $data->companyinfo->company . ").pdf";
        $filepath = 'uploads/jissbillingexported/' . $newFileName;
        $attachESig = tbljissbilling::find($id);
        $attachESig->update([
            'billingstatusid' => 4,
            'filepath' => $filepath
        ]);

        foreach ($emails as $key => $value) {
            $this->sendEmail($content, $value->email, $filepath);
        }

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Statement sent!'
        ]);

        $this->redirect(route('a.jiss-billing'));
    }

    public function openUploadORModal($id)
    {
        $this->uploadAttachmentID = $id;
        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#modalUploadJissOR'
        ]);
    }

    public function billingForward($id)
    {
        $this->billingid = $id;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#modalForwarding',
            'do' => 'show'
        ]);
    }

    public function sendToBOD($id)
    {
        $attachESig = tbljissbilling::find($id);
        if ($attachESig->approver_3 == 1) {
            $email = env('JISS_EMAIL_BOD_MANAGER');

            if ($attachESig->filepath == NULL) {
                $filepath = NULL;
            } else {
                $filepath = storage_path($attachESig->filepath);
            }

            $content = 'JISS Billing Statement forwarded in you board (BOD Manager Review Board) and need to review. With serial number ' . $attachESig->serialnumber;
            $this->sendEmail($content, $email, $filepath);

            $attachESig->update([
                'billingstatusid' => 3
            ]);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Statement sent!'
            ]);

            $this->redirect(route('a.jiss-billing'));
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Please attach your signature first!'
            ]);
        }
    }

    public function sendToGMBoard($id)
    {
        $attachESig = tbljissbilling::find($id);
        if ($attachESig->approver_2 == 1) {

            if ($attachESig->filepath == NULL) {
                $filepath = NULL;
            } else {
                $filepath = public_path('storage/' . $attachESig->filepath);
            }

            $email = env('JISS_EMAIL_GM');
            $content = 'JISS Billing Statement forwarded in you board (GM Review Board) and need to review. With a serialnumber ' . $attachESig->serialnumber;
            $this->sendEmail($content, $email, $filepath);

            $attachESig->update([
                'billingstatusid' => 2
            ]);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Statement sent!'
            ]);

            $this->redirect(route('a.jiss-billing'));
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Please attach your signature first!'
            ]);
        }
    }

    public function removeESig($id)
    {
        $attachESig = tbljissbilling::find($id);
        $attachESig->update([
            'approver_1' => 0
        ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Signature removed!'
        ]);
    }

    public function openBillingModal($id)
    {
        $this->data = tbljissbilling::find($id);
        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#modalIframePDF'
        ]);
    }

    public function openModalForward($id)
    {
        $this->billingid = $id;

        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#modalForwarding',
        ]);
    }

    public function removeESig3($id)
    {
        $attachESig = tbljissbilling::find($id);
        $attachESig->update([
            'approver_3' => 0
        ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Signature removed!'
        ]);
    }

    public function attachESig($id)
    {
        $attachESig = tbljissbilling::find($id);
        $attachESig->update([
            'approver_1' => 1
        ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Signature attached!'
        ]);
    }

    public function sendBack($id, $statusid)
    {
        $data = tbljissbilling::find($id);
        $filepath = public_path('storage/' . $data->filepath);
        $data->update([
            "billingstatusid" => $statusid
        ]);

        switch ($statusid) {
            case 0:
                $addtionaltxt = "Pending Statement Board with serialnumber of " . $data->serialnumber;
                $email = env('JISS_EMAIL_PERSONNEL');
                break;

            case 1:
                $addtionaltxt = "BOD Manager Review Board with serialnumber of " . $data->serialnumber;
                $email = env('JISS_EMAIL_BOD_MANAGER');
                break;

            case 2:
                $addtionaltxt = "GM Review Board with serialnumber of " . $data->serialnumber;
                $email = env('JISS_EMAIL_GM');
                break;

            case 3:
                $addtionaltxt = "BOD Manager Review Board with serialnumber of " . $data->serialnumber;
                $email = env('JISS_EMAIL_BOD_MANAGER');
                break;

            case 4:
                $addtionaltxt = "Client Confirmation Board with serialnumber of " . $data->serialnumber;
                $email = 'daniel.narciso@neti.com.ph';

                break;

            case 5:
                $addtionaltxt = "View Proof of Payment Board with serialnumber of " . $data->serialnumber;
                $email = 'daniel.narciso@neti.com.ph';

                break;

            case 6:
                $addtionaltxt = "Official Receipt Issuance Board with serialnumber of " . $data->serialnumber;
                $email = 'daniel.narciso@neti.com.ph';

                break;

            case 7:
                $addtionaltxt = "Official Receipt Confirmation Board with serialnumber of " . $data->serialnumber;
                $email = 'daniel.narciso@neti.com.ph';

                break;

            case 8:
                $addtionaltxt = "Transaction Close Board with serialnumber of " . $data->serialnumber;
                $email = 'daniel.narciso@neti.com.ph';

                break;

            default:
                $addtionaltxt = "";
                break;
        }

        $email = env('JISS_EMAIL_BOD_MANAGER');
        $content = "Billing returned to " . $addtionaltxt;
        $this->sendEmail($content, $email, $filepath);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Billing returned!'
        ]);
    }

    public function attachESig3($id)
    {
        $attachESig = tbljissbilling::find($id);
        $attachESig->update([
            'approver_3' => 1
        ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Signature attached!'
        ]);
    }

    public function mount($billingstatusid)
    {
        $this->billingstatusid = $billingstatusid;

        switch ($billingstatusid) {
            case '0':
                Gate::authorize('authorizeBillingAccess', 97);
                break;

            case '1':
                Gate::authorize('authorizeBillingAccess', 98);
                break;

            case '2':
                Gate::authorize('authorizeBillingAccess', 99);
                break;

            case '3':
                Gate::authorize('authorizeBillingAccess', 100);
                break;

            case '4':
                Gate::authorize('authorizeBillingAccess', 101);
                break;

            case '5':
                Gate::authorize('authorizeBillingAccess', 102);
                break;

            case '6':
                Gate::authorize('authorizeBillingAccess', 103);
                break;

            case '7':
                Gate::authorize('authorizeBillingAccess', 104);
                break;

            case '8':
                Gate::authorize('authorizeBillingAccess', 105);
                break;

            case '9':
                Gate::authorize('authorizeBillingAccess', 106);
                break;

            default:
                $this->redirect(route('a.jiss-billing'));
                break;
        }
    }

    public function openUploadPPModal($id)
    {
        $this->uploadAttachmentID = $id;
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#modalUploadJissAttachment',
            'do' => 'show'
        ]);
    }

    public function uploadAttachments()
    {
        $this->validate([
            'JISSAttachmentFile' => 'required|file|max:10240|mimes:pdf',
        ]);
        $id = $this->uploadAttachmentID;
        $path = $this->JISSAttachmentFile->store('uploads/jissbillingattachments/', 'public');

        $ifExist = tbljissbillingattachments::where('jissbillingid', $id)->first();

        if (!empty($ifExist) > 0) {
            $check = $ifExist->update([
                'attachmentpath' => $path
            ]);
        } else {
            $check = tbljissbillingattachments::create([
                'jissbillingid' => $id,
                'filetype' => 1,
                'attachmentpath' => $path
            ]);
        }

        if ($check) {
            $jissdata = tbljissbilling::find($id);
            $jissdata->update([
                'billingstatusid' => 7
            ]);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'File uploaded!'
            ]);

            $this->redirect(route('a.jiss-billing'));
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'There is something wrong uploading your file.'
            ]);
        }
    }

    public function confirmJISSBilling($id)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'You want to delete this record.',
            'id' => $id,
            'funct' => 'deleteJISSBilling'
        ]);
    }

    public function deleteJISSBilling($id)
    {
        $data = tbljissbilling::find($id);
        $data->delete();

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Deleted!'
        ]);
    }

    public function openModalViewAttachment($id)
    {
        $this->attachments = tbljissbillingattachments::where('jissbillingid', $id)->get();
        $this->dispatchBrowserEvent('d_modal', [
            'id' => "#modalAttachments",
            'do' => 'show'
        ]);
    }

    public function view($path)
    {
        return redirect()->away($path);
    }

    public function render()
    {
        if ($this->vatOrSCModel == 1) {
            $this->vatOrSC = "Service Charge 8 USD";
        } else {
            $this->vatOrSC = "12 % VAT";
        }

        $nationality = tblnationality::where('deletedid', 0)->get();
        $LoadCourse = tbljisscourses::all();
        $LoadCompany = tbljisscompany::all();
        $LoadBilling = tbljissbilling::where('billingstatusid', $this->billingstatusid)->orderBy('created_at', 'DESC')->paginate(10);
        return view('livewire.admin.billing.j-i-s-s-list-for-billing-component', compact('LoadBilling', 'LoadCourse', 'LoadCompany', 'nationality'))->layout('layouts.admin.abase');
    }

    public function sendEmail($content, $email, $filepath)
    {
        if ($filepath != NULL) {
            $sendemail = Mail::to($email)
                ->cc('daniel.narciso@neti.com.ph')
                ->send(new SendJissEmailNotification($content, $email, $filepath));
        } else {
            $sendemail = Mail::to($email)
                ->cc('daniel.narciso@neti.com.ph')
                ->send(new SendJissEmailNotification($content, $email));
        }
    }
}
