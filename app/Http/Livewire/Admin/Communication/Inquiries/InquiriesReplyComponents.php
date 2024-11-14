<?php

namespace App\Http\Livewire\Admin\Communication\Inquiries;

use Livewire\Component;
use App\Models\tblemailinquiry;
use Lean\ConsoleLog\ConsoleLog;
use Illuminate\Support\Facades\DB;

class InquiriesReplyComponents extends Component
{
    use ConsoleLog;
    public $retemail;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function fetchEmail()
    {
        try 
        {
            $emailinquiries = tblemailinquiry::where('emailinquiryid');
            $query = "SELECT * FROM tblemailinquiry WHERE id = {$this->id}";
            $result = DB::select($query);

            if (!empty($result)) {
                $this->retemail = $result[0]->Email;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        $this->redirect('https://mail.google.com/mail/?view=cm&fs=1&to=' . $this->retemail . '&cc=bod@neti.com.ph;prpd@neti.com.ph;noc@neti.com.ph');
    }
    
    public function render()
    {
        return view('livewire.admin.communication.inquiries.inquiries-reply-components');
    }
}
