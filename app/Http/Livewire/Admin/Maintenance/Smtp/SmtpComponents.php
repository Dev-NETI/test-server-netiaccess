<?php

namespace App\Http\Livewire\Admin\Maintenance\Smtp;

use App\Models\EmailConfig;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class SmtpComponents extends Component
{
    use ConsoleLog;
    public $mailer;
    public $host;
    public $port;
    public $username;
    public $password;
    public $encryption;
    public $mail_from_address;
    public $mail_from_name;
    public $edit = 0;
    public $readonly = "disabled";

    protected $rules = [
        'mailer' => 'required|string',
        'host' => 'required|string',
        'port' => 'required|numeric',
        'username' => 'required|email',
        'password' => 'required|string',
        'encryption' => 'required|string',
        'mail_from_address' => 'required|email',
        'mail_from_name' => 'required|string',
    ];

    public function render()
    {
        try 
        {
            $email_data = EmailConfig::find(1);

            $this->mailer = $email_data->MAIL_MAILER;
            $this->host = $email_data->MAIL_HOST;
            $this->port = $email_data->MAIL_PORT;
            $this->username = $email_data->MAIL_USERNAME;
            $this->password = $email_data->MAIL_PASSWORD;
            $this->encryption = $email_data->MAIL_ENCRYPTION;
            $this->mail_from_address = $email_data->MAIL_FROM_ADDRESS;
            $this->mail_from_name = $email_data->MAIL_FROM_NAME;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        return view('livewire.admin.maintenance.smtp.smtp-components')->layout('layouts.admin.abase');
    }
    
    public function Edit()
    {
        $this->edit = 1;
        $this->readonly = "";
    }

    public function update()
    {
        try 
        {
            $this->validate();
            
            

            try 
            {
                $email_data = EmailConfig::find(1);

                $email_data->update([
                    'MAIL_MAILER' => $this->mailer ,
                    'MAIL_HOST' => $this->host ,
                    'MAIL_PORT' => $this->port ,
                    'MAIL_USERNAME' => $this->username ,
                    'MAIL_PASSWORD' => $this->password ,
                    'MAIL_ENCRYPTION' => $this->encryption ,
                    'MAIL_FROM_ADDRESS' => $this->mail_from_address ,
                    'MAIL_FROM_NAME' => $this->mail_from_name ,
                ]);

                $this->edit = 0;
                $this->readonly = "disabled";
                session()->flash('success' , "Email configuration updated successfully!");
            } 
            catch (\Exception $e) 
            {
                session()->flash('error' , $e->getMessage());
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

}
