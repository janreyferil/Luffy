<?php

namespace App\Http\Middleware;

use Closure;
use App\Status;
use Illuminate\Support\Facades\Auth;
class isUserMiddleware
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
        if(auth()->user()->role == 'admin'){
            return $next($request);
        }
        $status = Status::where('user_id',Auth::user()->id)->first();
        if($status->permission === 'pending'){
            return response()->json([
                'redirect' => true,
                'message' => 'Just wait for the response of admin'
            ]);
        } elseif($status->permission === 'failed') {
            return response()->json([
                'redirect' => true,
                'message' => 'Sorry you are not allowed in this website'
            ]);
        }    
        return $next($request);
    }
}
