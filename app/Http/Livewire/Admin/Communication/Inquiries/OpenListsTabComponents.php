<?php

namespace App\Http\Livewire\Admin\Communication\Inquiries;

use Livewire\Component;
use App\Models\tblemailinquiry;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\WithPagination;

class OpenListsTabComponents extends Component
{
    use WithPagination;
    use ConsoleLog;
    public function render()
    {
        try 
        {
            // Replace '1' with the specific ID you want to find
            $inquiry = tblemailinquiry::with('inquiryType')->find(1);
            $OpenInquiries =  $inquiry->paginate(10);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.communication.inquiries.open-lists-tab-components', [
            'OpenpaginatedInquiries' =>  $OpenInquiries,
        ])->layout('layouts.admin.abase');
    }
}