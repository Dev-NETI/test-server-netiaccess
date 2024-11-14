<?php

namespace App\Http\Livewire\Admin\Widget;

use App\Models\tblcertificatehistory;
use Livewire\Component;

class ADisplayLastSerialNumberComponent extends Component
{
    public function render()
    {
        $history_data = tblcertificatehistory::orderBy('certificatehistoryid', 'DESC')->take(5)->get();
        return view(
            'livewire.admin.widget.a-display-last-serial-number-component',
            [
                'history_data' => $history_data,
            ]
        );
    }
}
