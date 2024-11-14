<?php

namespace App\Http\Livewire\Instructor\TPER;

use App\Models\tblenroled;
use App\Models\Tper_evaluation_response;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ITperSecondPageComponent extends Component
{
    public $enroled_id;
    public $enroled_data;
    public $trainee_weak_points;
    public $general_comments;
    public $re_training;
    public $response_data;

    protected $rules = [
        'trainee_weak_points' => 'required|min:5|max:2000' , 
        'general_comments' => 'required|min:5|max:2000' , 
        're_training' => 'required' , 
    ];

    public function mount()
    {
        $this->enroled_id = Session::get('enroled_id');
        $this->enroled_data = tblenroled::find($this->enroled_id);

        $this->response_data = Tper_evaluation_response::where('enroled_id', Session::get('enroled_id'))
                                                        ->orderBy('tper_question_id', 'asc')->get();

        if(count($this->response_data) > 0){
            $this->trainee_weak_points = $this->response_data->where('tper_question_id', 1)->first();
            $this->trainee_weak_points = $this->trainee_weak_points->response;
    
            $this->general_comments = $this->response_data->where('tper_question_id', 2)->first();
            $this->general_comments = $this->general_comments->response;
    
            $this->re_training = $this->response_data->where('tper_question_id', 3)->first();
            $this->re_training = $this->re_training->response;
        }
    }

    public function render()
    {
        return view('livewire.instructor.t-p-e-r.i-tper-second-page-component')->layout('layouts.admin.abase');
    }

    public function store()
    {
        $this->validate();
        
        try 
        {
            $attribute_array_id = [1,2,3];
            $response_array = [$this->trainee_weak_points,$this->general_comments,$this->re_training];

            if(count($this->response_data) > 0 ){
                $this->delete_old_response($this->enroled_id);
            }

            for ($i=0; $i <= 2 ; $i++) 
            { 
                Tper_evaluation_response::create([
                    'tper_question_id' => $attribute_array_id[$i] , 
                    'enroled_id' => $this->enroled_id , 
                    'response' => $response_array[$i]
                ]);

                
            }

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Response saved successfully!'
            ]);

            return redirect()->route('i.view-batch' , ['training_id' => $this->enroled_data->schedule->scheduleid]);
        } 
        catch (\Exception $e) 
        {
            session()->flash('error' , 'Error : '.$e->getMessage());
        }
    }

    public function delete_old_response($enroledid)
    {
            Tper_evaluation_response::where('enroled_id',$enroledid)->delete();
    }
}
