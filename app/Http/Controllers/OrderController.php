<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Jobs\unTakenOrder;
use App\Models\BasketOrder;

class OrderController extends Controller
{   
    // Function to store the customers' order 
    // This method will add the order in the order table and then add the products in the baskets_orders table 
    public function addOrder(Request $request)
    {
        
         $userID=auth()->User(); // get the ID of the customer
         $order = new Order;// create an order object 
         $order->customer_id = $userID->id; // Assuming the customer ID is known
         $order->market_id = $request['market_id'];
         $order->total_price = $request['total_price'];
         $order->delivery_price = $request['delivery_price'];
         $order->distance_kilo = $request['distance_kilo'];
         $order->number_of_products = $request['number_of_products'];
         $order->status='pending'; // it is by default, just to make sure 
         $products=$request->products;
         $order_=$order;
         $order->save();

         

         // Also add the product to the Basket table 
         foreach( $products as $product)
         {
            // Each prodcut will be store in new row 
            $orderBasket=new BasketOrder; // to store the customer order 
            $orderBasket->order_id=$order_->id;
            $orderBasket->customer_id=$userID->id;
            $orderBasket->market_id=$order_->market_id;
            $orderBasket->product_id=$product['product_id'];
            $orderBasket->quantity=$product['quantity'];
            $orderBasket->save();
         }//the end of the if 
        // dd($orderBasket->all());
         // Schedule the job to delete the order if it's not claimed within an hour
         unTakenOrder::dispatch($order);

         return response()->json([
             'message' => 'Order Addedd to the Table',
         ]);

    }//the end of the method 

    
    // The method for scheduling 
    protected function orderDeletion(Order $order)
    {
       
        // Schedule a jon now for deleting 
        // $deleteJob=(new unTakenOrder($order))
        // ->delay(now()->addMinutes(5));


       // dispatch($deleteJob); // dealing with a queue 
       unTakenOrder::dispatch($order);
    }//the end of the method

    // Function to view the orders to the collectors 
    public function viewOrders()
    {
        $orders = Order::where('status', 'pending')->get(); // get all the pending orders - to view it to the collectors - 
         return OrderResource::collection($orders);
    }//the end of the method 

    
}//the end of the class 


