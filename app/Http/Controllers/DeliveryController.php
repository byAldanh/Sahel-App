<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryApp;
use App\Models\Delivery;
use App\Http\Requests\DeliveryRequest;

class DeliveryController extends Controller
{
    // This method that will be updated by Delivery App 
    public function updateDelivery(Request $request)
    {   
         $order_id = $request->order_id;
         $delivery_name = $request->delivery_name;
         $delivery_phone = $request->delivery_phone;
         $app_token = $request->app_token;
         $delivery_status = $request->Delivery_status;

    // Check whether the entered token exist in the table or not 
    $deliveryOrder = DeliveryApp::where('app_token', $app_token)->first();
   
    if ($deliveryOrder) { // if it exist ..... 

        // Search for the order using specific order_id
        $delivery = Delivery::where('order_id', $order_id)->first();

        // Check if the app token matches the stored token
        if ($deliveryOrder->app_token === $app_token) {
            // Update the delivery details
            $delivery->delivery_id=$delivery->delivery_id;
            $delivery->delivery_name = $delivery_name;
            $delivery->delivery_phone = $delivery_phone;
            $delivery->Delivery_status = $delivery_status;
            $delivery->save();

            return response()->json([
                'message' => 'Delivery updated successfully',
            ], 200);
        } //the end of the esle block
        else {
            return response()->json([
                'message' => 'Not Authorized ',
            ], 401);
        }//the end of the else block 

    } else { // if it not matched 
        return response()->json([
            'message' => 'Delivery order not found',
        ], 404);
    }//the end of the else block 
    }//the end of the method 

}// the end of the class 
