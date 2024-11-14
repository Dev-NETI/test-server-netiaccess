<?php

namespace App\Policies;

use App\Models\tbltraineeaccount;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnrollmentPolicy
{
    /**
     * Create a new policy instance.
     */

    public function RegistrarEnrollment(User $user)
    {
        return $user->dep_type == 7 || $user->dep_type == 8 || $user->u_type == 3 || $user->dep_type == 1 || $user->dep_type == 3
            ? Response::allow()
            : Response::deny('You are unauthorized to access this module.');
    }
}
