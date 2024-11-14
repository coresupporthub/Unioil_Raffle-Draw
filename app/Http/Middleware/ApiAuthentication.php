<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = User::where('id', Auth::id())->where('authenticated', 'true')->first();

            if(!$user){
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'You must be logged in to access this resource.'
                ], 401);
            }

            return $next($request);
        }else{
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You must be logged in to access this resource.'
            ], 401);
        }


    }
}
