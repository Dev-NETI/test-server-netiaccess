<?php

namespace App\Http\Livewire\Admin\Admin;

use App\Models\tblcompany;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AdminComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $email;
    public $lastname;
    public $middlename;
    public $firstname;
    public $password;
    public $contact_num;
    public $u_type;
    public $dep_type;
    protected $passwordhash;
    protected $hashid;
    public $userid;
    public $company_id;
    public $suffix;



    public function addadmin(){
        try 
        {
            $query = user::orderBy('user_id', 'DESC')->first();
            $userid = $query->user_id;
            $userid++;

            $hashid = hash('sha256', $userid);
            $passwordhash = Hash::make($this->password);

            $this->validate([
                'firstname' => 'required',
                'middlename' => 'nullable',
                'lastname' => 'required',
                'email' => 'required|email',
                // 'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users,email',
                'contact_num' => 'required|regex:/^[0-9]{11}$/|unique:users,contact_num',
                'u_type' => 'required',
            ]);

            user::create([
                'l_name' => $this->lastname,
                'hash_id' => $hashid,
                'm_name' => $this->middlename,
                'f_name' => $this->firstname,
                'email' => $this->email,
                'password_tip' => $this->password,
                'user_id' => $userid,
                'password' => $passwordhash,
                'contact_num' => $this->contact_num,
                'is_active' => 1,
                'u_type' => $this->u_type,
                'dep_type' => $this->dep_type,
                'company_id' => $this->company_id,
                'suffix' => $this->suffix,
            ]);

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Administrator successfully created',
                'confirmbtn' => 'Ok'
            ]);

            $this->dispatchBrowserEvent('d_modal',[
                'id' => '#addadminmodal',
                'do' => 'hide'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function edit($id)
    {
        try 
        {
            $user = User::find($id);
            $this->userid = $user->id;
            $this->lastname = $user->l_name;
            $this->firstname = $user->f_name;
            $this->middlename = $user->m_name;
            $this->email = $user->email;
            $this->password = $user->password_tip;
            $this->contact_num = $user->contact_num;
            $this->u_type = $user->u_type;
            $this->dep_type = $user->dep_type;
            $this->company_id = $user->company_id;
            $this->suffix = $user->suffix;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function update()
    {
        try 
        {
            $passwordhash = Hash::make($this->password);
            $user = User::find($this->userid);
            $user->l_name = $this->lastname;
            $user->f_name =   $this->firstname;
            $user->m_name = $this->middlename;
            $user->email =  $this->email;
            $user->password_tip = $this->password;
            $user->password =  $passwordhash;
            $user->contact_num =  $this->contact_num;
            $user->u_type =  $this->u_type;
            $user->dep_type =  $this->dep_type;
            $user->company_id = $this->company_id;
            $user->suffix =  $this->suffix;
            $user->save();

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Update successfully created',
                'confirmbtn' => 'Ok'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function assignRoles($user_id)
    {
            Session::put('userid' , $user_id);

            return redirect()->route('a.assign-roles');
    }

    public function generatePassword()
    {
        $length = 12; 
        $uppercase = true;
        $lowercase = true;
        $numbers = true;
        $symbols = true;

        $characters = '';
        $characters .= $uppercase ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '';
        $characters .= $lowercase ? 'abcdefghijklmnopqrstuvwxyz' : '';
        $characters .= $numbers ? '0123456789' : '';
        $characters .= $symbols ? '@#$%^&*]+$' : '';

        $password = '';
        $characterSetLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $characterSetLength - 1)];
        }

        $this->password = $password;
    }
    
    public function render()
    {
        try 
        {
            $all_company = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();

            $query = User::where('u_type', 1)->with('company');

            if ($this->search) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->Where('f_name', 'like', $searchTerm)
                        ->orWhere('m_name', 'like', $searchTerm)
                        ->orWhere('l_name', 'like', $searchTerm);;
                })->orWhereHas('company', function ($q) use ($searchTerm) {
                    $q->where('company', 'like', $searchTerm);
                });
            }
            
            $a_accounts = $query->paginate(10);
        
            $a_accountsall = User::count();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.admin.admin-component',[
            'a_accounts' => $a_accounts,
            'a_accountsall' => $a_accountsall,
            'all_company' => $all_company,
        ])->layout('layouts.admin.abase');
    }

    
}
