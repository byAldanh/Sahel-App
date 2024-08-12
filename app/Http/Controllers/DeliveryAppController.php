<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryApp;


class DeliveryAppController extends Controller
{
    
    // To add a delivery app
    public function addDeliveryApp(Request $request)
    {
        $deliveryApp=new DeliveryApp;
        $deliveryApp->app_name=$request->app_name;
        $deliveryApp->save();
        
    }//the end of the method

    public function createToken(Request $request)
    {
        $app=DeliveryApp::find($request->id)->first();
        $token = $app->createToken('my-app')->plainTextToken;
        $app->app_token=$token;
        $app->save();

        return response()->json([
            'token' => $token
        ]); 
    }//the end if  the method 
    
}// the end of the class 
