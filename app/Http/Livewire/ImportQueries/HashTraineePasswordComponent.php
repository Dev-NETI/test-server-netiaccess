<?php

namespace App\Http\Livewire\ImportQueries;

use App\Models\tbltraineeaccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class HashTraineePasswordComponent extends Component
{

    public function hashPassword()
    {
        $chunkSize = 200; 
        tbltraineeaccount::chunk($chunkSize, function ($trainee_data) {
            foreach ($trainee_data as $data) {
                try {
                    $data->password = Hash::make($data->password_tip); // Hash password
                    $data->hash_id = Hash::make($data->traineeid); // Hash ID
                    $data->save();
                    dump('Data saved');
                } catch (\Exception $e) {
                    dump($e);
                }
            }
        });

        dd('Password hashing completed.');
    }

    public function hashAdminPassword()
    {
        $user_data = User::all();

        foreach($user_data as $data)
        {
                try 
                {
                    $data->password = Hash::make($data->password_tip);//hash password
                    $data->hash_id = Hash::make($data->id);//hash_id
                    $data->save();
                    dump('Data saved');
                } 
                catch (\Exception $e) 
                {
                    dump($e);
                }
                
        }

    }

    public function render()
    {
        return view('livewire.import-queries.hash-trainee-password-component');
    }

}
