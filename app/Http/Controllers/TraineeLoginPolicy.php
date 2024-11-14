<?php

namespace App\Http\Controllers;

use App\Models\tbltraineeaccount;
use Illuminate\Http\Request;

class TraineeLoginPolicy extends Controller
{
    public function checkAccountStatus($email)
    {
        $data = tbltraineeaccount::where('email', $email)->first();

        if ($data) {
            if ($data->is_active == 0) {
                // Account is not active
                return false;
            }
        }

        // Account is active or does not exist
        return true;
    }

    //
    public function attempt_counter($email)
    {
        $data = tbltraineeaccount::where('email', $email)->first();


        if ($data) {
            $data->update([
                'login_attempt_count' => $data->login_attempt_count + 1
            ]);
        }
    }

    public function remaining_attempt($email)
    {
        $data = tbltraineeaccount::where('email', $email)->first();



        if ($data) {
            return 5 - $data->login_attempt_count;
        }
    }

    public function reset_attempt($email)
    {
        $data = tbltraineeaccount::where('email', $email)->first();



        if ($data) {
            $data->update([
                'login_attempt_count' => 1
            ]);
        }
    }

    public function reset_lockout_time($email)
    {
        $data = tbltraineeaccount::where('email', $email)->first();



        if ($data) {
            $data->update([
                'lockout_timestamp' => NULL
            ]);
        }
    }

    public function save_lockout_time($email)
    {
        $data = tbltraineeaccount::where('email', $email)->first();



        if ($data) {
            $data->update([
                'lockout_timestamp' => now()
            ]);
        }
    }

    public function get_lockout_time($email)
    {
        $data = tbltraineeaccount::where('email', $email)->first();



        if ($data) {
            return $data->lockout_timestamp;
        } else {
            return null;
        }
    }
}
