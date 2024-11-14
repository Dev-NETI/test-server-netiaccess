<?php

namespace App\Http\Livewire\Admin\Communication\Inquiries;

use App\Models\tblemailinquiry;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\tblinquirytype;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;

class InquiriesComponents extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $emailinquiryid;
    public $selectstatus;

    //VIEW DETAILS 
    public $viewname;
    public $viewemail;
    public $viewnumber;
    public $viewinquirytype;
    public $viewinquiry;
    public $viewstatus;
    public $viewansweredby;


    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 33);
    }



    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes for $search
    }

    public function statusedit($emailinquiryid)
    {
        try {
            $edit_status = tblemailinquiry::find($emailinquiryid);
            $this->emailinquiryid = $edit_status->emailinquiryid;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatestatus()
    {
        try {
            $update_status = tblemailinquiry::find($this->emailinquiryid);
            $update_status->is_answered = $this->selectstatus;
            $update_status->answered_by = Auth::user()->formal_name();
            $update_status->save();


            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Changes saved!'
            ]);


            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#updatestatus',
                'do' => 'hide'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function viewdetails($emailinquiryid)
    {
        try {
            $view_details = tblemailinquiry::find($emailinquiryid);

            // Check if the email inquiry exists
            if ($view_details) {
                // Access the related inquiry type
                $inquirytype = $view_details->inquiryType;

                // Check if the related inquiry type exists
                if ($inquirytype) {
                    // Access the value of inquirytype from the related model
                    $inquirytypeValue = $inquirytype->inquirytype;

                    // Set the component properties
                    $this->emailinquiryid = $view_details->emailinquiryid;
                    $this->viewname = $view_details->name;
                    $this->viewemail = $view_details->email;
                    $this->viewnumber = $view_details->mobile;
                    $this->viewinquirytype = $inquirytypeValue;
                    $this->viewinquiry = $view_details->inquiry_text;
                    $this->viewstatus = $view_details->is_answered;
                    $this->viewansweredby = $view_details->answered_by;
                } else {
                    // Handle the case where the related inquiry type is not found
                    // You might want to log an error, show a message, or redirect the user
                    // Example: Log::error('Related inquiry type not found.');
                    // Example: abort(404, 'Related inquiry type not found.');
                }
            } else {
                // Handle the case where the email inquiry is not found
                // You might want to log an error, show a message, or redirect the user
                // Example: Log::error('Email inquiry not found.');
                // Example: abort(404, 'Email inquiry not found.');
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function redirectMail($emailId)
    {
        $retemail = urlencode("https://mail.google.com/mail/?view=cm&fs=1&to={$emailId}&cc=bod@neti.com.ph;prpd@neti.com.ph;noc@neti.com.ph");

        // Use Livewire to emit a script to open a new window or tab
        $script = "<script> window.open('$retemail', '_blank'); </script>";
        $this->emit('openMailLink', $script);
    }



    public function delete_inquiry($id)
    {
        $inquiry_data = tblemailinquiry::find($id);
        $inquiry_data->delete();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Deleted!',
        ]);
    }


    public function render()
    {
        try {
            $query = tblemailinquiry::query();

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('mobile', 'like', '%' . $this->search . '%');
                });
            }


            //PAGINATE AND COUNT

            $email_inquiry = $query->orderBy('created_at', 'desc')->paginate(10);
            $email_allCount = tblemailinquiry::count();
            $email_OpenCount = tblemailinquiry::where('is_answered', 0)->count();
            $email_ClosedCount = tblemailinquiry::where('is_answered', 1)->count();


            // OPEN INQUIRY LIST
            $Openinquirylist = tblemailinquiry::where('is_answered', 0)->paginate(10);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.communication.inquiries.inquiries-components', [
            'email_inquiry' => $email_inquiry,
            'email_allCount' => $email_allCount,
            'email_OpenCount' => $email_OpenCount,
            'email_ClosedCount' => $email_ClosedCount,
            'Openinquirylist' =>  $Openinquirylist
        ])->layout('layouts.admin.abase');
    }
}
