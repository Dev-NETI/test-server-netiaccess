<?php

namespace App\Http\Livewire\Admin\CrewMonitoring\MealComponents;

use Livewire\Component;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Session;

class RequestMessageCardComponent extends Component
{
    public $enroled_id;
    public $name;
    public $course;
    public $image;
    public $training_date;
    public $image_path = 'https://img.freepik.com/free-vector/red-prohibited-sign-no-icon-warning-stop-symbol-safety-danger-isolated-vector-illustration_56104-912.jpg?size=626&ext=jpg&ga=GA1.1.1700460183.1708387200&semt=sph';
    public $text_color = "text-danger";
    public $badge_color = "text-bg-danger";
    public $badge_msg = "ERROR!";

    public function mount()
    {
        if(Session::get('enroled_id')){
            $this->enroled_id = Session::get('enroled_id');
            $trainee_data = tblenroled::find($this->enroled_id);
            $this->name = $trainee_data->trainee->name_for_meal;
            $this->course = $trainee_data->schedule->course->full_course_name;
            $this->training_date = $trainee_data->schedule->training_date;
            $this->image_path =   $trainee_data->trainee->public_image_path;
            $this->text_color = "text-success";
            $this->badge_color = "text-bg-success";
            $this->badge_msg = "SUCCESS!";
        }

        Session::forget('enroled_id');
        Session::forget('error');
        // Session::forget('invalid');
    }

    public function render()
    {
        return view('livewire.admin.crew-monitoring.meal-components.request-message-card-component');
    }
}
