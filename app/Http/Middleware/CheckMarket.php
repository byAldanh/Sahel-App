<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMarket
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next):Response
    {
        $user=$request->User(); // get the user from this request
        if($user->user_type_id==3){
            return $next($request); //Allow
        }

        return response()->json([
            'ERROR'=>'Unauthorized'
        ],403);
    }//the end of the method 
}//the end of the class 
