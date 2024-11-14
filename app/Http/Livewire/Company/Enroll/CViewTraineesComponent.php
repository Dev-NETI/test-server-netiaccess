<?php

namespace App\Http\Livewire\Company\Enroll;

use App\Mail\SendBillingAccountEmail;
use App\Mail\SendConfirmedEnrollment;
use App\Models\refbrgy;
use App\Models\refcitymun;
use App\Models\refprovince;
use App\Models\refregion;
use App\Models\tblatdmealprice;
use App\Models\tblbillingaccount;
use App\Models\tblcompany;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use App\Models\tblenroled;
use App\Models\tblfleet;
use App\Models\tblnationality;
use App\Models\tblrank;
use App\Models\tbltraineeaccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class CViewTraineesComponent extends Component
{
    use WithPagination;
    public $provinces = [];
    public $citys = [];
    public $messages = [];

    public $f_name;
    public $m_name;
    public $l_name;
    public $suffix;
    public $birth_day;
    public $birth_place;

    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedBrgy = null;

    public $brgys = [];
    public $street;
    public $postal;

    protected $listeners = ['eventClicked'];

    public $search;
    public $trainee_id;
    public $selectedCourse;
    public $batch = [];
    public $selectedSched;
    public $selected_schedule;
    public $schedule;
    public $numberofenroled;
    public $dateonlinefrom = null;
    public $dateonlineto = null;
    public $dateonsitefrom = null;
    public $dateonsiteto = null;
    public $payment_features;
    public $bus_id;
    public $formatted_registration_num;
    public $t_fee;

    public $start_date;
    public $end_date;
    public $room_start;
    public $room_end;

    public $dorm_name;
    public $dorm_price = null;
    public $duration;
    public $total_price_dorm = 0;
    public $meal_price = 0;
    public $total = 0;
    public $totalmealdorm = 0;
    public $total_meal = 0;
    public $selectedDorm = null;
    public $selectedPackage;

    public $address;

    public $s_exp_rank;
    public $s_company;
    public $s_fleet;

    public $i_password = 12345;
    public $c_password = 12345;
    public $email;
    public $contact_num;

    public $showAnotherForm = false;
    public $selectedNationality;
    public $selectedGender;
    public $srn_num;
    public $tin_num;

    public function eventClicked($schedid)
    {
        try {
            $selected_schedule = tblcourseschedule::find($schedid);
            $isEnrolled = $this->isEnrolled($this->trainee_id);
            $pending_enrolled_trainees = $this->CheckNumberOfTrainees($schedid);
            $conflictingSchedules = $this->getConflictingScheduleId($this->trainee_id, $schedid);

            if (count($conflictingSchedules) > 0) {
                $conflictingEnroledIds = $this->getConflictingEnroledIds($conflictingSchedules);

                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Conflicting with your other courses. The following registration numbers are in conflict: ' . implode(', ', $conflictingEnroledIds),
                ]);
            }

            if ($isEnrolled) {
                $this->selectedSched = null;
                $this->dateonlinefrom = null;
                $this->dateonsitefrom = null;
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'The trainee are already enrolled in this course.'
                ]);

                return;
            }


            if ($pending_enrolled_trainees >= $selected_schedule->course->maximumtrainees) {
                $this->selectedSched = null;
                $this->dateonlinefrom = null;
                $this->dateonsitefrom = null;
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'This training schedule are reached the maximum trainees. Please select another.'
                ]);

                return;
            }


            $this->dispatchBrowserEvent('livewire:load');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    private function isEnrolled($trainee_id)
    {
        $schedule = tblcourseschedule::find($this->selectedSched);
        $user = tbltraineeaccount::find($trainee_id);

        // Check if the user is enrolled in the course
        return tblenroled::where('traineeid', $user->traineeid)
            ->where('courseid', $schedule->courseid)
            ->where('deletedid', 0)
            ->where('dropid', 0)
            ->exists();
    }


    private function CheckNumberOfTrainees($schedid)
    {
        // Check if the user is enrolled in the course
        return tblenroled::where('scheduleid', $schedid)
            ->where(function ($query) {
                $query->where('tblenroled.pendingid', 0)
                    ->orWhere('tblenroled.pendingid', 1);
            })
            ->where('tblenroled.deletedid', 0)
            ->count();
    }

    private function getConflictingScheduleId($traineeId, $selected_sched)
    {
        $enrolledCourses = tblenroled::where('traineeid', $traineeId)
            ->where('deletedid', 0)
            ->where('dropid', 0)
            ->get();

        $conflictingSchedules = collect();

        $selectedSchedule = tblcourseschedule::find($selected_sched);

        foreach ($enrolledCourses as $enrolledCourse) {
            $conflictingSchedule = tblcourseschedule::where('scheduleid', $enrolledCourse->scheduleid)
                ->whereBetween('startdateformat', [$selectedSchedule->startdateformat, $selectedSchedule->enddateformat])
                ->first();

            if ($conflictingSchedule) {
                $conflictingSchedules->push($enrolledCourse->enroledid);
            }
        }

        return $conflictingSchedules->unique()->all();
    }

    private function getConflictingEnroledIds($conflictingSchedules)
    {
        $conflictingEnroledIds = [];

        foreach ($conflictingSchedules as $conflictingScheduleId) {
            $enrolledCourse = tblenroled::find($conflictingScheduleId);
            if ($enrolledCourse) {
                $conflictingEnroledIds[] = $enrolledCourse->enroledid;
            }
        }
        return $conflictingEnroledIds;
    }

    private function reset_input_trainee()
    {
        $this->f_name = null;
        $this->m_name = null;
        $this->l_name = null;
        $this->suffix = null;
        $this->birth_day = null;
        $this->birth_place = null;
        $this->selectedGender = null;
        $this->selectedNationality = null;
        $this->srn_num = null;
        $this->tin_num = null;
        $this->selectedRegion = null;
        $this->selectedProvince = null;
        $this->selectedCity = null;
        $this->selectedBrgy = null;
        $this->street = null;
        $this->postal = null;
        $this->address = null;
        $this->s_exp_rank = null;
        $this->s_company = null;
        $this->s_fleet = null;
        $this->email = null;
        $this->contact_num = null;
        $this->i_password = null;
        $this->c_password = null;
    }

    public function reset_input()
    {
        $this->selectedSched = '';
        $this->start_date = null;
        $this->numberofenroled = null;
        $this->dateonlinefrom = null;
        $this->dateonlineto = null;
        $this->dateonsitefrom = null;
        $this->dateonsiteto = null;
        $this->payment_features = null;
        $this->bus_id = null;
        $this->selectedDorm = null;
        $this->schedule = null;
    }


    public function updatedSelectedCourse($selectedCourse)
    {
        $year = date('Y');
        // Get the user's email
        $email = Auth::user()->email;

        // Define the keyword mappings
        $keywords = [
            'AGM_DRY' => 'agmdryvsl',
            'DRY_FLEET' => 'drytechofficer',
            'AGM_LIQ' => 'agmliqvsl',
            'LIQUID_FLEET' => 'liquidtechofficer',
            'COORDINATING_TECH_OFFICER' => 'crdtechoff'
        ];

        function checkEmailForKeywords($email, $keywords)
        {
            foreach ($keywords as $keyword => $value) {
                if (strpos($email, $value) !== false) {
                    return true;
                }
            }
            return false;
        }

        $result = checkEmailForKeywords($email, $keywords);

        try {
            $this->reset_input();

            if (Auth::user()->company_id == 1) {
                if (($result === true) && ($selectedCourse == 35 || $selectedCourse == 33 || $selectedCourse == 31 || $selectedCourse == 32 || $selectedCourse == 39 || $selectedCourse == 14)) {
                    $this->batch = tblcourseschedule::where('courseid', $selectedCourse)
                        ->where(function ($query) {
                            $query->where('specialclassid', 0)
                                ->orWhere('specialclassid', 1);
                        })
                        ->where('cutoffid', 0)
                        ->orderBy('startdateformat')
                        ->whereYear('startdateformat', $year)
                        ->get();
                } else {
                    $this->batch = tblcourseschedule::where('courseid', $selectedCourse)->where('specialclassid', 0)->where('cutoffid', 0)->orderBy('startdateformat')->whereYear('startdateformat', $year)->get();
                }
            } else {
                $this->batch = tblcourseschedule::where('courseid', $selectedCourse)->where('specialclassid', 0)->where('cutoffid', 0)->orderBy('startdateformat')->whereYear('startdateformat', $year)->get();
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedSelectedSched($selectedSched)
    {
        try {
            $this->schedule = tblcourseschedule::where('scheduleid', $selectedSched)->first();

            $this->numberofenroled = tblenroled::where('scheduleid', $this->schedule->scheduleid)->where('pendingid', 0)->count();
            $this->dateonlinefrom = $this->schedule->dateonlinefrom;
            $this->dateonlineto = $this->schedule->dateonlineto;
            $this->dateonsitefrom = $this->schedule->dateonsitefrom;
            $this->dateonsiteto = $this->schedule->dateonsiteto;

            $this->start_date = $this->schedule->startdateformat;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedSelectedRegion($selectedRegion)
    {
        try {
            $this->provinces = refprovince::where('regCode', $selectedRegion)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedSelectedProvince($selectedProvince)
    {
        try {
            $this->citys = refcitymun::where('provCode', $selectedProvince)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedSelectedCity($selectedCity)
    {
        try {
            $this->brgys = refbrgy::where('citymunCode', $selectedCity)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function createTrainee()
    {
        try {
            $rules = [
                'f_name' => 'required',
                'l_name' => 'required',
                'birth_day' => 'required|date',
                'birth_place' => 'required',
                'selectedGender' => 'required',
                'srn_num' => 'nullable|numeric',
                'tin_num' => 'nullable|numeric|digits:9',
                'selectedRegion' => 'nullable',
                'selectedProvince' => 'nullable',
                'selectedCity' => 'nullable',
                'selectedBrgy' => 'nullable',
                'street' => 'nullable',
                'postal' => 'nullable',
                's_exp_rank' => 'required',
                's_company' => 'required',
                'email' => 'nullable|email|unique:tbltraineeaccount,email',
                // 'i_password' => [
                //     'nullable',
                //     'min:8',
                //     'regex:/^(?=.*[A-Z])(?=.*[!@#$%^&*()_+|])[A-Za-z\d!@#$%^&*()_+|]{8,}$/',
                // ],
                'i_password' => ['nullable', 'min:8'],
                'c_password' => 'nullable|same:i_password',
            ];

            // Define validation rules and messages
            $messages = [
                'f_name.required' => 'The first name field is required.',
                'l_name.required' => 'The last name field is required.',
                'birth_day.required' => 'The birth day field is required.',
                'birth_day.date' => 'Please enter a valid date for the birth day.',
                'birth_place.required' => 'The birth place field is required.',
                'selectedGender.required' => 'The gender field is required.',
                's_exp_rank.required' => 'The experience rank field is required.',
                's_company.required' => 'The company field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'The email address is already in use.',
                'i_password.min' => 'The password must be at least 8 characters long.',
                'c_password.same' => 'The confirm password does not match the input password.',
                'i_password.regex' => 'The password must contain at least one capital letter and one symbol.',
            ];

            // Run the validation
            $validator = Validator::make([
                'f_name' => $this->f_name,
                'l_name' => $this->l_name,
                'birth_day' => $this->birth_day,
                'birth_place' => $this->birth_place,
                'selectedGender' => $this->selectedGender,
                'selectedRegion' => $this->selectedRegion,
                'selectedProvince' => $this->selectedProvince,
                'selectedCity' => $this->selectedCity,
                'selectedBrgy' => $this->selectedBrgy,
                'street' => $this->street,
                'postal' => $this->postal,
                's_exp_rank' => $this->s_exp_rank,
                's_company' => $this->s_company,
                'email' => $this->email,
                'i_password' => $this->i_password,
                'c_password' => $this->c_password,
            ], $rules, $messages);

            // Check if validation fails
            if ($validator->fails()) {
                // Extract error messages from the validator
                $this->messages = $validator->errors()->messages();

                // Emit the 'validationErrors' event with the error messages
                $this->emit('validationErrors', $this->messages);
                return;
            }

            // Validation passed, proceed with other logic
            $latest_user = tbltraineeaccount::orderBy('traineeid', 'desc')->first();
            $latest_id = $latest_user->id ?? '0';
            $hash_id =  Crypt::encrypt($latest_id);

            $new_acc = new tbltraineeaccount();
            $new_acc->hash_id = $hash_id;
            $new_acc->f_name = $this->f_name;
            $new_acc->m_name = $this->m_name;
            $new_acc->l_name = $this->l_name;
            $new_acc->suffix = $this->suffix;
            $new_acc->birthday = $this->birth_day;
            $new_acc->birthplace = $this->birth_place;
            $new_acc->genderid = $this->selectedGender;
            $new_acc->nationalityid = $this->selectedNationality;
            $new_acc->srn_num = $this->srn_num;
            $new_acc->tin_num = $this->tin_num;


            if ($this->showAnotherForm == false) {
                $new_acc->regCode = $this->selectedRegion;
                $new_acc->provCode = $this->selectedProvince;
                $new_acc->citynumCode = $this->selectedCity;
                $new_acc->brgyCode = $this->selectedBrgy;
                $new_acc->street = $this->street;
                $new_acc->postal = $this->postal;
            } else {
                $new_acc->address = $this->address;
            }


            $new_acc->rank_id = $this->s_exp_rank;
            $new_acc->company_id = $this->s_company;
            $new_acc->fleet_id = $this->s_fleet;

            $new_acc->email = $this->email;
            $new_acc->contact_num = $this->contact_num;
            $new_acc->password =  Hash::make($this->i_password);
            $new_acc->password_tip =  $this->i_password;


            // Save the user account
            $new_acc->save();
            $this->reset_input_trainee();

            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Created Successfully'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function generateRegistrationNumber()
    {
        $registration_num = mt_rand(1000000, 9999999);
        $year = date('Y');
        $start_month = date('m', strtotime($this->start_date));
        $day = date('d', strtotime($this->start_date));
        $formatted_registration_num = $year . $start_month . $registration_num;

        $this->formatted_registration_num = $formatted_registration_num;
    }
    public function enroll()
    {
        try {
            $selected_schedule = tblcourseschedule::find($this->selectedSched);
            $isEnrolled = $this->isEnrolled($this->trainee_id);
            $pending_enrolled_trainees = $this->CheckNumberOfTrainees($this->selectedSched);
            $conflictingSchedules = $this->getConflictingScheduleId($this->trainee_id, $this->selectedSched);

            if (count($conflictingSchedules) > 0) {
                $conflictingEnroledIds = $this->getConflictingEnroledIds($conflictingSchedules);

                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Conflicting with your other courses. The following registration numbers are in conflict: ' . implode(', ', $conflictingEnroledIds),
                ]);
            }

            if ($isEnrolled) {

                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'The trainee are already enrolled in this course.'
                ]);

                return;
            }


            if ($pending_enrolled_trainees >= $selected_schedule->course->maximumtrainees) {

                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'This training schedule are reached the maximum trainees. Please select another.'
                ]);

                return;
            }


            $this->validate([
                'selectedCourse' => 'required',
                'selectedSched' => 'required',
            ]);

            $trainee = tbltraineeaccount::find($this->trainee_id);

            $this->generateRegistrationNumber();

            $new_enrol = new tblenroled();
            $new_enrol->registrationcode = $this->formatted_registration_num;
            $new_enrol->pendingid = 0;

            $new_enrol->scheduleid = $this->selectedSched;
            $new_enrol->courseid = $this->selectedCourse;
            $new_enrol->traineeid = $trainee->traineeid;

            // $new_enrol->busmodeid = $this->bus_mode;
            $new_enrol->paymentmodeid = 1;
            $new_enrol->dormid = $this->selectedDorm;
            $new_enrol->fleetid = $trainee->fleet_id;

            $new_enrol->checkindate = $this->room_start;
            $new_enrol->checkoutdate = $this->room_end;

            //getting duration of date
            $checkin = Carbon::parse($this->room_start);
            $checkout = Carbon::parse($this->room_end);
            $new_enrol->duration = $checkin->diffInDays($checkout) + 1;

            $dorm_price = tbldorm::find($this->selectedDorm);

            // Check if $dorm_price is not null before accessing its properties
            if ($dorm_price) {
                //total price for dorm
                $total_price_dorm = $dorm_price->atddormprice * ($checkin->diffInDays($checkout) + 1);
                $new_enrol->dorm_price = $total_price_dorm;

                $total_meal =  tblatdmealprice::find(1)->atdmealprice * ($checkin->diffInDays($checkout) + 1);
                //gett the meal price
                $new_enrol->meal_price = $total_meal;
            }

            if ($this->bus_id) {
                if ($this->bus_id == 1) {
                    $new_enrol->busid = 1;
                    $new_enrol->busmodeid = $this->bus_id;
                } else if ($this->bus_id == 2) {
                    $new_enrol->busid = 1;
                    $new_enrol->busmodeid = $this->bus_id;
                }
            }

            $new_enrol->t_fee_price = $this->t_fee;
            $new_enrol->t_fee_package = $this->selectedPackage;

            $this->schedule->numberofenroled += 1;

            //calculate the total price
            $total = $this->total_price_dorm + $this->t_fee + $this->meal_price;

            $new_enrol->total = $total;
            $new_enrol->enrolledby = Auth::user()->formal_name();
            $dateconfirmed = Carbon::now('Asia/Manila');
            $new_enrol->dateconfirmed = $dateconfirmed;

            $new_enrol->save();
            $this->schedule->save();

            $latestId = $new_enrol->enroledid;
            Session::put('latest_enrol_id', $latestId);

            $enrol = tblenroled::where('enroledid', $latestId)->first();

            $data = [
                'enrol' => $enrol,
            ];

            $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-admission-slip', $data);
            $pdf->setPaper('a4', 'landscape');
            Mail::to($enrol->trainee->email)->send(new SendConfirmedEnrollment($enrol, $trainee, $pdf));


            $this->reset_input();
            // $this->selectedCourse = null;
            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Enroll Successfully'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function getTrainee($trainee_id)
    {
        $this->trainee_id = $trainee_id;
    }

    public function render()
    {
        $query = tbltraineeaccount::where('company_id', Auth::user()->company_id);

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('f_name', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('m_name', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $t_accounts = $query->orderBy('l_name')->paginate(10);

        $c_trainees = tbltraineeaccount::all()->count();

        $this->s_company = Auth::user()->company_id;

        $count_enroll = tblenroled::all()->count();

        if (Auth::user()->u_type == 3 && Auth::user()->company_id == 1) {

            $courses = tblcourses::where('deletedid', 0)
                ->where('coursetypeid', 3)
                ->orWhere('coursetypeid', 4)
                ->orderBy('coursecode', 'ASC')
                ->get();

            $courses2 = tblcourses::whereIn('courseid', [91, 92, 68, 69, 113])
                ->where('deletedid', 0)
                ->orderBy('coursecode', 'ASC')
                ->get();


            $courses = $courses2->merge($courses);
        } elseif (Auth::user()->u_type == 3 && Auth::user()->company_id == 257) {
            $courses = tblcourses::where('deletedid', 0)->whereIn('courseid', [91, 92])->orderBy('coursecode', 'ASC')->get();
        } else {
            $courses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->where('coursetypeid', 3)->orWhere('coursetypeid', 4)->get();
        }

        $dorm = tbldorm::all();

        $companys = tblcompany::orderBy('company', 'ASC')->get();
        $exp_ranks = tblrank::orderBy('rank', 'ASC')->get();
        $fleets = tblfleet::where('deletedid', 0)->orderBy('fleet', 'ASC')->get();

        $t_accounts = $query->orderBy('l_name')->paginate(10);
        $c_trainees = $query->count();
        $nationalities = tblnationality::get();
        $regions = refregion::all();

        if ($this->selectedDorm) {
            $this->room_start =   $this->schedule->dateonsitefrom;
            $this->room_end =   $this->schedule->dateonsiteto;
        } else {
            $this->room_start =  null;
            $this->room_end =  null;
        }

        return view('livewire.company.enroll.c-view-trainees-component', [
            'nationalities' => $nationalities,
            't_accounts' => $t_accounts,
            'c_trainees' => $c_trainees,
            'count_enroll' => $count_enroll,
            'courses' => $courses,
            'dorm' => $dorm,
            'exp_ranks' => $exp_ranks,
            'companys' => $companys,
            'fleets' => $fleets,
            'regions' => $regions,
            'message' => $this->messages,
        ])->layout('layouts.admin.abase');
    }
}
