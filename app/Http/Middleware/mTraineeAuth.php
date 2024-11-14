<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class mTraineeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('trainee')->check()) {
            // The user is logged in
            if (Auth::guard('trainee')->user()->u_type === 2) {
                return $next($request);
            } else {
                session()->flush();
                return redirect()->route('t.login');
            }
        } else {
            // The user is not logged in, handle the situation accordingly
            // For example, you might want to redirect them to the login page.
            return redirect()->route('t.login');
        }
        
    }
}
