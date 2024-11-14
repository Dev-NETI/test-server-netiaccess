<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminComponentPolicy
{
    public function authorizeComponent(User $user, $role_id)
    {
        return $user->adminroles->pluck('role_id')->contains($role_id)
            ? Response::allow()
            : Response::deny('You are unauthorized to access this module.');
    }

    public function authorizeRequestPdeComponent(User $user, $role_id)
    {
        return $user->adminroles->pluck('role_id')->contains($role_id) || $user->u_type == 5 || $user->u_type == 3 
            ? Response::allow()
            : Response::deny('You are unauthorized to access this module.');
    }

    public function authorizeHandout(User $user, $role_id)
    {
        return $user->adminroles->pluck('role_id')->contains($role_id) || $user->u_type == 3 
            ? Response::allow()
            : Response::deny('You are unauthorized to access this module.');
    }

    public function restrictBillingModule(User $user)
    {
        return $user->email == "noc@neti.com.ph" ?
            Response::allow()
            : Response::deny('Coming Soon!');
    }

    public function authorizeDelete(User $user)
    {
        return $user->dep_type == 1 
            ? Response::allow()
            : Response::deny('Only IT Admin can access this module.');
    }
}
