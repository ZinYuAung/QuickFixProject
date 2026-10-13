<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next,...$roles): Response
    {
        //return $next($request);
        if(!auth()->check()){
            return redirect('/login');
        }

        $user=auth()->user();
        foreach($roles as $role){
            if($role === 'customer' && $user->isCustomer()){
                return $next($request);
            }

            if ($role === 'provider' && $user->isProvider()) {
                return $next($request);
            }
            if ($role === 'admin' && $user->isAdmin()) {
                return $next($request);
            }
            if ($role === 'superadmin' && $user->isSuperAdmin()) {
                return $next($request);
            }

        }

        //if not role correct
        abort(403,'unauthorized action.');
    }
}
