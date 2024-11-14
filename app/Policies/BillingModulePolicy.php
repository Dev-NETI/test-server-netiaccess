<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class BillingModulePolicy
{
    /**
     * Create a new policy instance.
     */
    protected $msg = 'Restricted Access: We apologize, but the system has determined that you do not have the necessary permissions. 
    For access inquiries, please contact our IT department.';

    public function authorizeBillingAccess(User $user, $role_id)
    {
        return $user->adminroles->pluck('role_id')->contains($role_id) || $user->u_type == 3
                ? Response::allow() 
                : Response::deny($this->msg);
    }

    public function authorizeSendBackBilling(User $user, $role_id)
    {
        return $user->adminroles->pluck('role_id')->contains($role_id)
                ? Response::allow() 
                : Response::deny($this->msg);
    }

    public function EditVesselAuthorization(User $user, $role_id)
    {
        return $user->adminroles->pluck('role_id')->contains($role_id)
                ? Response::allow() 
                : Response::deny($this->msg);
    }

}
