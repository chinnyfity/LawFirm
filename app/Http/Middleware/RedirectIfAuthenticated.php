<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    //public function handle(Request $request, Closure $next, ...$guards)
    public function handle($request, Closure $next, $guards = null)
    {
        //$guards = empty($guards) ? [null] : $guards;

        /* foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        } */
        
        switch ($guards) {
            case 'admin_tbl':
                if (Auth::guard($guards)->check()) {
                    return redirect()->route('indexs'); // must be the same with the name in route (web.php)
                }
                break;
            
            default:
                if (Auth::guard($guards)->check()) {
                    return redirect()->route('dashboard'); // must be the same with the name in route (web.php)
                }
                break;
        }

        return $next($request);
    }
}
