<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $latest_user = User::orderBy('id', 'desc')->first();
        $latest_code = $latest_user->id ?? '0';
        $hash_id =  Crypt::encrypt($latest_code);

        Validator::make($input, [
            'f_name' => ['required', 'string', 'max:255'],
            'm_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'f_name' => $input['f_name'],
            'm_name' => $input['m_name'],
            'l_name' => $input['l_name'],
            'email' => $input['email'],
            'hash_id' => $hash_id,  
            'password' => Hash::make($input['password']),
            'password_tip' => $input['password'],
            
        ]);
    }
}
