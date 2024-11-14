<?php

namespace App\Http\Livewire\OutsideFunctions;

use App\Models\tblenroled;
use Carbon\Carbon;
use Livewire\Component;

class RecordTraineeConfirmationComponent extends Component
{
    public $enroledid;
    public $attendingid;

    public function mount($enroledid, $attendingid)
    {
        $this->enroledid = $enroledid;
        $this->attendingid = $attendingid;

        $update_enroled = tblenroled::find($this->enroledid);
        $update_enroled->IsAttending = $this->attendingid;
        $update_enroled->save();
    }

    public function render()
    {
        return view(
            'livewire.outside-functions.record-trainee-confirmation-component',
            [
                'attendingid' => $this->attendingid
            ]
        )->layout('layouts.base');
    }
}
