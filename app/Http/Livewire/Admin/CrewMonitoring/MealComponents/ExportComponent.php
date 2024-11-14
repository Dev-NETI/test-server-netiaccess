<?php

namespace App\Http\Livewire\Admin\CrewMonitoring\MealComponents;

use App\Models\tblmealmonitoring;
use Exception;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Livewire\Admin\CrewMonitoring\MealComponents\ExportMealLogComponent;

class ExportComponent extends Component
{
    use ConsoleLog;
    public $date_from;
    public $meal_record;
    public $date_to;
    
    protected $rules = [
        "date_from" => 'required|date',
        "date_to" => 'required|date'  
    ];

    public function render()
    {
        return view('livewire.admin.crew-monitoring.meal-components.export-component');
    }

    public function export()
    {
        $this->validate();
        try 
        {
            $meal_data = tblmealmonitoring::whereBetween('created_at', [$this->date_from,$this->date_to])
                                            ->orderBy('created_at', "DESC")
                                            ->get();

            foreach($meal_data as $data){
                switch ($data->mealtype) {
                    case 1:
                        $mealtype = "BREAKFAST";
                        break;

                    case 2:
                        $mealtype = "LUNCH";
                        break;
                    
                    case 3:
                        $mealtype = "DINNER";
                        break;
                    
                    default:
                        $mealtype = '';
                        break;
                }
                // dd($data->enrolinfo->dormitory->room->roomtype->roomtype);

                $this->meal_record[] = [
                    "enroledid" => optional($data)->enrolinfo->enroledid ?? '',
                    "name" => optional($data)->enrolinfo->trainee->name_for_meal ?? '',
                    "rank" => optional($data)->enrolinfo->trainee->rank->rankacronym ?? '',
                    "company" => optional($data)->enrolinfo->trainee->company->company ?? '',
                    "batch" => optional($data)->enrolinfo->schedule->batchno ?? '',
                    "course" => optional($data)->enrolinfo->schedule->course->coursecode ?? '',
                    "training_date" => optional($data)->enrolinfo->schedule->training_date ?? '',
                    "meal_type" => $mealtype,
                    "scanned_date" => optional($data)->scanned_date ?? '',
                    "scanned_time" => optional($data)->scanned_time ?? '',
                    "dorm" => optional($data)->enrolinfo->dorm->dorm ?? 'DORM NOT AVAILED',
                    "room_type" => optional($data)->enrolinfo->dormitory->room->roomtype->roomtype ?? 'CANCELLED OR NO SHOW',
                ];
            }
            return Excel::download(new ExportMealLogComponent($this->meal_record), 'Trainee_Meal_Log_from_'.$this->date_from.'_to_'.$this->date_to.'.xlsx');
        } 
        catch (Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function generateExcel()
    {

    }

}
