<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SaasRoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        switch($role){
            case 'admin':
                if($userRole == 'admin'){
                    return $next($request);
                }
                break;
            case 'seller':
                if($userRole == 'seller'){
                    return $next($request);
                }
                break;
            case 'customer':
                if($userRole == 'customer'){
                    return $next($request);
                }
                break;
            default:
                return redirect()->route('login');
        }

        switch($userRole){
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'seller':
                return redirect()->route('seller.dashboard');
            case 'customer':
                return redirect()->route('customer.dashboard');
        }

        return redirect()->route('login');
    }
}
