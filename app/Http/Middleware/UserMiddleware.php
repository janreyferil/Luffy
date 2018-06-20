<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::where('role','admin')->get();
        if (count($user) == 0) {
         return response()->json([
                'redirect' => true,
                'message' => 'Sorry but theirs no admin yet'
            ]);
        }
        return $next($request);
    }
}
