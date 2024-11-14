<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Response;

class CoursePolicy
{
    /**
     * Create a new policy instance.
     */
    public function AuthorizeEditCourse(User $user)
    {
            return $user->u_type == 3 
            ? Response::allow() 
            : Response::deny("You are unauthorized to access this module!");
    }
}
