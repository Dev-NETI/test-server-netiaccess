<?php

namespace App\Http\Livewire;

use App\Models\tblcourses;
use App\Models\tbltraineeaccount;
// use DevRaeph\PDFPasswordProtect\Facade\PDFPasswordProtect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class RequestAccessHandoutComponent extends Component
{
    protected $handout_data;
    public $handoutpath = [];
    public $handoutpassword = [];
    public $email_address;
    public $handout_password;

    public function render()
    {
        return view('livewire.request-access-handout-component')->layout('layouts.base');
    }

    private function getHandoutPassword()
    {
        $handout_data = tblcourses::all();
        foreach ($handout_data as $key => $data) {
            $this->handoutpassword[$key] = $data->handout_password;
            $this->handoutpath[$key] = $data->handoutpath;
        }
    }

    public function verifyHandoutPassword()
    {
        try {
            $this->getHandoutPassword();

            $check_email = tbltraineeaccount::where('email', $this->email_address)->first();

            if ($this->checkPassword() && $check_email) {
                // Get the handout path associated with the entered password
                $handoutPath = $this->getHandoutPath();

                // Store the handout path in the session
                Session::put('handoutpath', $handoutPath);

                return redirect()->route('req.view-handout');
            } else {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Wrong password or email, try to access again!'
                ]);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    protected function checkPassword()
    {
        return in_array($this->handout_password, $this->handoutpassword);
    }

    protected function getHandoutPath()
    {
        $key = array_search($this->handout_password, $this->handoutpassword);
        return $this->handoutpath[$key] ?? null;
    }

    public function downloadPDF()
    {

        $this->getHandoutPassword();

        $check_email = tbltraineeaccount::where('email', $this->email_address)->first();

        if ($this->checkPassword()  && $check_email) {
            // Get the handout path associated with the entered password
            $handoutPath = $this->getHandoutPath();

            // Store the handout path in the session
            Session::put('handoutpath', $handoutPath);
            Session::put('handoutpassword', $this->handout_password);


            return redirect()->route('req.download-handout');
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Wrong password or email, try to access again!'
            ]);
        }
    }
}
