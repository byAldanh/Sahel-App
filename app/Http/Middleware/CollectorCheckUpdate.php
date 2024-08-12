<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollectorCheckUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check if the entered user is a collector with the id =1 
        $user=$request->User(); // get the user from this request
        if($user->user_type_id==2){
            return $next($request); //Allow
        }

        return response()->json([
            'ERROR'=>'Unauthorized'
        ],403);
    }//the end of the method 
}
