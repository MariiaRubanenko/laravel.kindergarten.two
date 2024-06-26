<?php

// namespace App\Http\Middleware;

// // use App\Providers\RouteServiceProvider;
// // use Closure;
// // use Illuminate\Http\Request;
// // use Illuminate\Support\Facades\Auth;
// // use Symfony\Component\HttpFoundation\Response;
// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Http\Response;
// use Illuminate\Support\Facades\Auth;

// class RedirectIfAuthenticated
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next, string ...$guards): Response
//     {
//         // $guards = empty($guards) ? [null] : $guards;

//         // foreach ($guards as $guard) {
//         //     if (Auth::guard($guard)->check()) {
//         //         return redirect(RouteServiceProvider::HOME);
//         //     }
//         // }

//         // return $next($request);
//         $guards = empty($guards) ? [null] : $guards;

//         foreach ($guards as $guard) {
//             if (Auth::guard($guard)->check()) {
               
//                 return response([
//                     'message' => 'You are already authenticated.',
//                 ], Response::HTTP_UNPROCESSABLE_ENTITY);
//             }
//         }

//         return $next($request);
//     }
    
// }

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
             
                return response([
                    'message' => 'You are already authenticated.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return $next($request);
    }
}
