<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Models\tblcourses;
use Illuminate\Support\Facades\Validator;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class ACertificateShowMaintenanceComponent extends Component
{
    use ConsoleLog;
    use WithFileUploads;
    public $course, $coursename, $last_num, $certF, $certFSize, $certFStyle;
    public $crewF, $crewFSize, $crewFStyle;
    public $bdayF, $bdayFSize, $bdayFStyle;
    public $remarksF, $remarksFSize, $remarksFStyle, $remarks_desc;
    public $cert_a, $reg_a, $crew_a, $bday_a, $qr_a, $pic_a, $remarks_a, $coc_x, $coc_y, $coa_x, $coa_y;
    public $file;

    public function mount($course_id)
    {
        try 
        {
            $this->course = tblcourses::find($course_id);
            $this->coursename = $this->course->coursename;
            $this->last_num = $this->course->lastcertificatenumber;
            $this->certF = $this->course->certfontid;
            $this->certFSize = $this->course->certfontsizeid;
            $this->certFStyle = $this->course->certfontstyleid;
            $this->crewF = $this->course->crewnamefontid;
            $this->crewFSize = $this->course->crewnamefontsizeid;
            $this->crewFStyle = $this->course->crewnamefontstyleid;
            $this->bdayF = $this->course->birthdayfontid;
            $this->bdayFSize = $this->course->birthdayfontsizeid;
            $this->bdayFStyle = $this->course->birthdayfontstyleid;
            $this->remarksF = $this->course->remarksfontid;
            $this->remarksFSize = $this->course->remarksfontsizeid;
            $this->remarksFStyle = $this->course->remarksfontstyleid;
            $this->remarks_desc = $this->course->certificateremarks;

            $this->cert_a = $this->course->certificatenumberalignment;
            $this->reg_a = $this->course->registrationnumberalignment;
            $this->crew_a = $this->course->namealignment;
            $this->bday_a = $this->course->birthdayalignment;
            $this->qr_a = $this->course->qrcodealignment;
            $this->pic_a = $this->course->picturealignment;
            $this->remarks_a = $this->course->remarksalignment;
            $this->coc_x = $this->course->cocgmesignX;
            $this->coc_y = $this->course->cocgmesignY;
            $this->coa_x = $this->course->coagmesignX;
            $this->coa_y = $this->course->coagmesignY;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function save()
    {
        try 
        {
            $rules = [
                'last_num' => ['nullable', 'regex:/^[0-9,]*$/'], // Allow numeric values with commas
                'certFSize' => ['nullable', 'regex:/^[0-9,]*$/'],
                'crewFSize' => ['nullable', 'regex:/^[0-9,]*$/'],
                'bdayFSize' => ['nullable', 'regex:/^[0-9,]*$/'],
                'remarksFSize' => ['nullable', 'regex:/^[0-9,]*$/'],
                'cert_a' => ['nullable',],
                'reg_a' => ['nullable',],
                'crew_a' => ['nullable',],
                'bday_a' => ['nullable',],
                'qr_a' => ['nullable',],
                'pic_a' => ['nullable',],
                'remarks_a' => ['nullable',],
                'coc_x' => ['nullable', 'regex:/^[0-9,]*$/'],
                'coc_y' => ['nullable', 'regex:/^[0-9,]*$/'],
                'coa_x' => ['nullable', 'regex:/^[0-9,]*$/'],
                'coa_y' => ['nullable', 'regex:/^[0-9,]*$/'],
            ];
    
    
            $messages = [
                'last_num.regex' => 'The Last number certificate must be a number.',
                'certFSize.regex' => 'The Certificate Font Size must be a number.',
                'crewFSize.regex' => 'The Crew Font Size must be a number.',
                'bdayFSize.regex' => 'The Bithday Font Size must be a number.',
                'remarksFSize.regex' => 'The Remark Font Size must be a number.',
                'remarks_a.regex' => 'The Remark Alignment must be a number.',
                'coc_x.regex' => 'The COC X Alignment must be a number.',
                'coc_y.regex' => 'The COC Y Alignment must be a number.',
                'coa_x.regex' => 'The COA X Alignment must be a number.',
                'coa_y.regex' => 'The COA Y Alignment must be a number.',
            ];
    
    
            $validator = Validator::make([
                'last_num' => $this->last_num,
                'certFSize' => $this->certFSize,
                'crewFSize' => $this->crewFSize,
                'bdayFSize' => $this->bdayFSize,
                'remarksFSize' => $this->remarksFSize,
                'cert_a' => $this->cert_a,
                'reg_a' => $this->reg_a,
                'crew_a' => $this->crew_a,
                'bday_a' => $this->bday_a,
                'qr_a' => $this->qr_a,
                'pic_a' => $this->pic_a,
                'remarks_a' => $this->remarks_a,
                'coc_x' => $this->coc_x,
                'coc_y' => $this->coc_y,
                'coa_x' => $this->coa_x,
                'coa_y' => $this->coa_y,
            ], $rules, $messages);
    
            if ($validator->fails()) {
                $this->addErrorMessages($validator->errors()->messages());
                return;
            }
    
            $update_course = tblcourses::find($this->course->courseid);
            $update_course->lastcertificatenumber  = $this->last_num;
            $update_course->certfontid = $this->certF;
            $update_course->certfontsizeid  = $this->certFSize;
            $update_course->certfontstyleid  = $this->certFStyle;
            $update_course->crewnamefontid = $this->crewF;
            $update_course->crewnamefontsizeid  = $this->crewFSize;
            $update_course->crewnamefontstyleid = $this->crewFStyle;
            $update_course->birthdayfontid  = $this->bdayF;
            $update_course->birthdayfontsizeid = $this->bdayFSize;
            $update_course->birthdayfontstyleid = $this->bdayFStyle;
            $update_course->remarksfontid = $this->remarksF;
            $update_course->remarksfontsizeid = $this->remarksFSize;
            $update_course->remarksfontstyleid = $this->remarksFStyle;
            $update_course->certificateremarks = $this->remarks_desc;
    
            $update_course->certificatenumberalignment = $this->cert_a;
            $update_course->registrationnumberalignment = $this->reg_a;
            $update_course->namealignment = $this->crew_a;
            $update_course->birthdayalignment = $this->bday_a;
            $update_course->qrcodealignment  = $this->qr_a;
            $update_course->picturealignment = $this->pic_a;
            $update_course->remarksalignment = $this->remarks_a;
            $update_course->cocgmesignX = $this->coc_x;
            $update_course->cocgmesignY = $this->coc_y;
            $update_course->coagmesignX = $this->coa_x;
            $update_course->coagmesignY = $this->coa_y;
            $update_course->save();
    
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Update successfully'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }


    public function upload()
    {
        try 
        {
            $this->validate([
                'file' => 'nullable|mimes:pdf',
            ]);
    
            if ($this->file !== null) {
    
                $upload_pdf = tblcourses::find($this->course->courseid);
    
                $originalFileName = $this->file->getClientOriginalName();
                $db_filepath = "certificatetemplate/" . $originalFileName;
    
                // Update the imagepath and save the record
                $upload_pdf->certificatepath = $db_filepath;
                $upload_pdf->save();
    
                // Store the new file
                $this->file->storeAs('uploads/certificatetemplate', $originalFileName, 'public'); 
                $this->file = null;
    
                // Dispatch success event
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Uploaded Template Successfully'
                ]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    private function addErrorMessages(array $messages)
    {
        try 
        {
            foreach ($messages as $field => $message) {
                $this->addError($field, $message[0]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.certificate.a-certificate-show-maintenance-component')->layout('layouts.admin.abase');
    }
}
