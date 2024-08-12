<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\post;
use App\Models\OrderJourney;
use App\Models\Order;
use App\Models\User;
use App\Models\Delivery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class OrderJourneyController extends Controller
{
    public function  acceptOrder(Request $request)
    {
        $collector = auth()->User(); // get the authorized user 

        $orderJourney = new OrderJourney; // create an object

    $order = Order::where('id', $request->id)->first();

    if ($order) {
        $order->status = 'Done';
        $order->save();

        if ($order->id) {
            $orderJourney->order_id = $order->id;
            $orderJourney->Order_status = 'Taken';
            $orderJourney->collector_id = $collector->id;
            $orderJourney->save();

            return response()->json([
                'message' => 'Added Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }
    } else {
        return response()->json([
            'message' => 'Order not found',
        ], 404);
    }
    }//the end of the method

    // It will be updated, when it collected - then sending a request to the delivery app -  
    public function updateOrderStatus(Request $request)
    {
        $orderCollected = OrderJourney::find($request->id); // get the order with specific id 
        $orderCollected->Order_status='Order Collected'; // change the status to collected 
        $orderCollected->save();

        if($orderCollected!='Delivered')
       { $returnBack=OrderJourneyController::toDelivery($orderCollected, $request); // call the method for the delivery 
        return $returnBack;
       }//the end of the if statement 
       else {
        return response()->json([
            "message" => "Order Deliverd to the Customer",
           
        ]);
       }
    }//the end of the method 

    // This will be called in the updateOrderStatus method - to send the order to the delivery- 
    protected static function toDelivery(OrderJourney $order,Request $request)
    {
      $delivery=new Delivery; // create an object in the Delivery app 
      // Will be updated by the Delivery app itself 
      $delivery->delivery_name=' ';
      $delivery->delivery_phone=' ';
      $delivery->order_id=$order->id;
      $delivery->delivery_app_id=1; // khiffa
      $delivery->Delivery_status=' ';
      $delivery->save();

      // Need more editing to make it perfect 
       $userOrder= Order::where('id',$order->order_id)->first(); // the order 
       $customer=User::where('id',$userOrder->customer_id)->get()->first(); // the customer for the order
       $market=User::where('id',$userOrder->market_id)->get()->first(); // the market 
         $response=Http::withHeaders([
             'Accept'=>'application/json',
         ])//to solve the hit problem 
         ->post('https://renad-khiffa.firstcity.ai/api/orders',[
         'company_token'=>'5|ulJJw3OsSLsn81xattlefz18AOlS3Z8OE5yPmBV8885b2b34',
         'phone_number'=>$customer["phone_user"], // phone for the customer 
          'beneficiary_name'=>$market->name_user, // name of the market 
          'beneficiary_city'=>'makkah',
          'beneficiary_district'=>'makkah',
          'service_provider_id'=>$userOrder->market_id, // service provider means the market id 
          'delivery_cost'=>$userOrder->delivery_price,
          'distance'=>$userOrder->distance_kilo,
          'order_id'=>$order->id
        ]);//the end of the response

        Log::info('API response:', ['response' => $response->body(), 'status' => $response->status()]);

       if ($response->successful()) {
             return response()->json([
             "message" => "Post sent to Khiffa successfully",
             'data' => $response->json()
         ]);
         }// the end of the if 
         else {
             Log::warning('Non-successful response:', ['status' => $response->status(), 'body' => $response->body()]);
             return response()->json([
             "message" => "Failed to send post to Khiffa",
             'error' => $response->body()
         ], $response->status());
         } //the end of the else 
    return response()->json([
        'message' => 'Added Successfuly',
    ]);//the end of the return statement 

    }//the end of the method
    
}// the end of the class 
