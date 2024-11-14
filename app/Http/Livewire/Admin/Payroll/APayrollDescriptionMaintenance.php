<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\Payroll_description;
use Livewire\Component;
use Livewire\WithPagination;

class APayrollDescriptionMaintenance extends Component
{
    use WithPagination;
    public $search, $description_id, $description_name;

    public function render()
    {
        $description_data = Payroll_description::where('description', 'like', '%' . $this->search . '%')->paginate(10);
        return view(
            'livewire.admin.payroll.a-payroll-description-maintenance',
            [
                'description_data' => $description_data
            ]
        )->layout('layouts.admin.abase');
    }

    public function EditDescription($id)
    {
        $description = Payroll_description::find($id);

        $this->description_id = $description->id;
        $this->description_name = $description->description;
    }

    public function CreateDescription()
    {
        $this->validate([
            'description_name' => 'required',
        ]);

        $new_description = new Payroll_description();
        $new_description->description = $this->description_name;
        $new_description->is_deleted = 0;
        $new_description->save();

        $this->dispatchBrowserEvent('close-model');
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Successfully Added'
        ]);
    }


    public function UpdateDescription()
    {
        $this->validate([
            'description_name' => 'required',
        ]);

        $update_description = Payroll_description::find($this->description_id);
        $update_description->description = $this->description_name;
        $update_description->save();

        $this->dispatchBrowserEvent('close-model');
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Successfully changed'
        ]);
    }
}
