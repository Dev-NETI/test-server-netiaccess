<?php

namespace App\Http\Livewire\Trainee\Enroll;

use App\Models\tblcourses;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TEnrollCards extends Component
{

  public $courses_man;
  public $courses_up;
  public $courses_nmc;
  public $courses_nmcr;
  public $courses_ji;
  public $courses_pj;
  public $courses_gov;


  public function render()
  {
    $user = Auth::guard('trainee')->user();

    //MANDATORY
    $this->courses_man = tblcourses::with('mode') // Eager-load the 'mode' relationship
      ->where(function ($query) use ($user) {
        $query->where('ranklevelid', 10)
          ->orWhere('ranklevelid', 9)
          ->orWhere('ranklevelid', $user->rank->ranklevelid);
      })
      ->where('deletedid', 0)
      ->where('coursetypeid', 1)
      ->orderBy('coursecode', 'ASC')
      ->get();

    //UPGRADING
    $this->courses_up = tblcourses::with('mode')
      ->where(function ($query) use ($user) {
        $query->where('coursedepartmentid', $user->rank->rankdepartment->rankdepartmentid)
          ->orWhere('coursedepartmentid', 4) //user department
          ->orWhere('ranklevelid', $user->rank->ranklevelid) //based on user rank
          ->orWhere('ranklevelid', 10);
      })
      ->whereNotIn('courseid', [117, 118]) // Exclude specific course IDs
      ->where('deletedid', 0)
      ->where('coursetypeid', 2)
      ->orderBy('coursecode', 'ASC')
      ->get();

    //NMC
    $this->courses_nmc = tblcourses::with('mode') // Eager-load the 'mode' relationship
      ->where(function ($query) use ($user) {
        if ($user->rank->ranklevelid == 5 || $user->rank->ranklevelid == 6) {
          $query->where('ranklevelid', 10) //all rank
            ->orWhere('ranklevelid', 9) //all officer
            ->orWhere('ranklevelid', $user->rank->ranklevelid); //based on user rank
        } else {
          $query->where('ranklevelid', 10) //all rank
            ->orWhere('ranklevelid', $user->rank->ranklevelid) //based on user rank
            ->where('coursedepartmentid', $user->rank->rankdepartment->rankdepartmentid); //user department
        }
      })
      ->where('deletedid', 0)
      ->where('coursetypeid', 3)
      ->orderBy('coursecode', 'ASC')
      ->get();

    //NMCR
    $this->courses_nmcr = tblcourses::with('mode') // Eager-load the 'mode' relationship
      ->where(function ($query) use ($user) {
        $query->where('coursedepartmentid', $user->rank->rankdepartment->rankdepartmentid)
          ->orWhere('coursedepartmentid', 4) //user department
          ->orWhere('ranklevelid', $user->rank->ranklevelid); //based on user rank
      })
      ->where('deletedid', 0)
      ->where('coursetypeid', 4)
      ->orderBy('coursecode', 'ASC')
      ->get();

    //JISS
    $this->courses_ji = tblcourses::with('mode') // Eager-load the 'mode' relationship
      ->where(function ($query) use ($user) {
        $query->where('coursedepartmentid', $user->rank->rankdepartment->rankdepartmentid)
          ->orWhere('coursedepartmentid', 4) //user department
          ->orWhere('ranklevelid', $user->rank->ranklevelid); //based on user rank
      })
      ->where('deletedid', 0)
      ->where('coursetypeid', 5)
      ->orderBy('coursecode', 'ASC')
      ->get();

    //PJMCC
    $this->courses_pj = tblcourses::with('mode') // Eager-load the 'mode' relationship
      ->where(function ($query) use ($user) {
        $query->where('coursedepartmentid', $user->rank->rankdepartment->rankdepartmentid)
          ->orWhere('coursedepartmentid', 4) //user department
          ->orWhere('ranklevelid', $user->rank->ranklevelid); //based on user rank
      })
      ->where('deletedid', 0)
      ->where('coursetypeid', 7)
      ->orderBy('coursecode', 'ASC')
      ->get();

    //OTHER GOV
    $this->courses_gov = tblcourses::with('mode') // Eager-load the 'mode' relationship
      ->where(function ($query) use ($user) {
        $query->where('coursedepartmentid', $user->rank->rankdepartment->rankdepartmentid)
          ->orWhere('coursedepartmentid', 4) //user department
          ->orWhere('ranklevelid', $user->rank->ranklevelid)
          ->orWhere('ranklevelid', 10);
      })
      ->where('deletedid', 0)
      ->where('coursetypeid', 8)
      ->orderBy('coursecode', 'ASC')
      ->get();


    return view('livewire.trainee.enroll.t-enroll-cards')->layout('layouts.trainee.tbase');
  }
}
