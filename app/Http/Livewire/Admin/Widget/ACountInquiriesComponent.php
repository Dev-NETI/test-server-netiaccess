<?php

namespace App\Http\Livewire\Admin\Widget;

use App\Models\tblemailinquiry;
use App\Models\tblinquirytype;
use Livewire\Component;

class ACountInquiriesComponent extends Component
{
    public function render()
    {
        $email_OpenCount = tblemailinquiry::where('is_answered', 0)->count();
        return view('livewire.admin.widget.a-count-inquiries-component', 
    [ 
        'email_OpenCount' => $email_OpenCount,
    ]);
    }
}
