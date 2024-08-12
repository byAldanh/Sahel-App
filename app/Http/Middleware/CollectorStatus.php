<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CollectorMarketValues;

class CollectorStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=$request->User()->id; // get the authorized user 
        $collectorMarketValues=CollectorMarketValues::where('user_id',$user)->first();

        if($collectorMarketValues->values_info=='active'){ // if the user was an Admin 
            return $next($request); //Allow
        }//the end of the if statement 

        return response()->json([
            'ERROR'=>'Unauthorized'
        ],403); // the end of the return statement 
    }//the end of the handle method 

}//the end of the class 
