<?php

namespace App\Http\Livewire\Admin\Communication\Inquiries;

use App\Models\tblemailinquiry;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class InquiriesDetailsComponents extends Component
{
    use ConsoleLog;
    public $hash_id;
    public $emailinquiry;

    public function mount($hash_id)
    {
        try 
        {
            $this->emailinquiry = tblemailinquiry::where('hash_id', $hash_id)->first();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.communication.inquiries.inquiries-details-components')->layout('layouts.admin.abase');
    }
}
