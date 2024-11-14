<?php

namespace App\Http\Livewire\Instructor\TPER;

use App\Models\tblenroled;
use App\Models\Tper_evaluation_factor;
use App\Models\Tper_evaluation_rating;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ITperComponent extends Component
{
    use ConsoleLog;
    public $enroled_id;
    public $tper_factor_id = [];
    public $tper_factor_data_count;
    public $allRadioButtonsSelected = true;
    public $selected_radio_count = 0;
    public $tper_factor_data_answers;
    public $selected_radio1;
    public $selected_radio2;
    public $selected_radio3;
    public $selected_radio4;
    public $selected_radio5;
    public $selected_radio6;
    public $selected_radio7;
    public $selected_radio8;
    public $selected_radio9;
    public $selected_radio10;
    public $selected_radio11;
    public $answer_data;

    protected $rules = [
        'selected_radio1' => 'required',
        'selected_radio2' => 'required',
        'selected_radio3' => 'required',
        'selected_radio4' => 'required',
        'selected_radio5' => 'required',
        'selected_radio6' => 'required',
        'selected_radio7' => 'required',
        'selected_radio8' => 'required',
        'selected_radio9' => 'required',
        'selected_radio10' => 'required',
        'selected_radio11' => 'required',
    ];

    protected $messages = [
        'selected_radio1.required' => 'Please select an option!',
        'selected_radio2.required' => 'Please select an option!',
        'selected_radio3.required' => 'Please select an option!',
        'selected_radio4.required' => 'Please select an option!',
        'selected_radio5.required' => 'Please select an option!',
        'selected_radio6.required' => 'Please select an option!',
        'selected_radio7.required' => 'Please select an option!',
        'selected_radio8.required' => 'Please select an option!',
        'selected_radio9.required' => 'Please select an option!',
        'selected_radio10.required' => 'Please select an option!',
        'selected_radio11.required' => 'Please select an option!',
    ];

    public function mount()
    {
        // dd(Session::get('enroled_id'));
        $this->enroled_id = Session::get('enroled_id');   
        $this->answer_data = Tper_evaluation_rating::where('enroled_id', $this->enroled_id)->orderBy('tper_id','asc')->get();
        if(count($this->answer_data) > 0){
            $this->selected_radio1 = $this->answer_data[0]->rating;
            $this->selected_radio2 = $this->answer_data[1]->rating;
            $this->selected_radio3 = $this->answer_data[2]->rating;
            $this->selected_radio4 = $this->answer_data[3]->rating;
            $this->selected_radio5 = $this->answer_data[4]->rating;
            $this->selected_radio6 = $this->answer_data[5]->rating;
            $this->selected_radio7 = $this->answer_data[6]->rating;
            $this->selected_radio8 = $this->answer_data[7]->rating;
            $this->selected_radio9 = $this->answer_data[8]->rating;
            $this->selected_radio10 = $this->answer_data[9]->rating;
            $this->selected_radio11 = $this->answer_data[10]->rating;  
        }                      
    }

    public function submitForm()
    {
        $this->validate();
            try {
                
                if(count($this->answer_data) > 0){
                    $this->delete_existing_ratings($this->enroled_id);
                }  
    
                $tper_factor_id_array = ['1','2','3','4','5','6','7','8','9','10','11'];
                $rating_array = [$this->selected_radio1,$this->selected_radio2,$this->selected_radio3,$this->selected_radio4,$this->selected_radio5,$this->selected_radio6,
                $this->selected_radio7,$this->selected_radio8,$this->selected_radio9,$this->selected_radio10,$this->selected_radio11];
                foreach($tper_factor_id_array as $index => $data){
                        $this->createRatings($tper_factor_id_array[$index],$this->enroled_id,$rating_array[$index]);
                }
    
                return redirect()->route('i.t_per_2');
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }
    }

    public function delete_existing_ratings($enroled_id)
    {
        Tper_evaluation_rating::where('enroled_id', $enroled_id)->delete();
    }
    public function createRatings($tperid,$enroledid,$rating)
    {
                try 
                {
                    Tper_evaluation_rating::create([
                        'tper_id' => $tperid , 
                        'enroled_id' => $enroledid , 
                        'rating' => $rating
                    ]);   
                } 
                catch (\Exception $e) 
                {
                    dd($e->getMessage());
                }
    }

    public function render()
    {                  
        $enroled_data = tblenroled::find($this->enroled_id);
        $tper_factor_data = Tper_evaluation_factor::orderBy('id' , 'asc')
                                                    ->get();                          
        return view('livewire.instructor.t-p-e-r.i-tper-component', 
        compact('enroled_data','tper_factor_data') )->layout('layouts.admin.abase');
    }
}
