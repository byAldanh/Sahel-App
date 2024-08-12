<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\BasketOrder;
use App\Models\Product;
class BasketOrderController extends Controller
{
    
    public function viewOrder($order_id)
    {
        $order=Order::find($order_id); // find the order record 
        $basket=BasketOrder::where('order_id',$order_id)->get(); // get the basket of that order 
        $onlyId=$basket->pluck('product_id')->toArray(); // exctract the product_id as an array 
        $products = Product::whereIn('product_id', $onlyId) // get the products where in the array (onlyId)
        ->where('market_id', $order->market_id)
        ->get();      

        // Join the BasketOrder records with the corresponding Product instances
        $basketProducts = $basket->map(function ($basketItem) use ($products) {
        $product = $products->firstWhere('product_id', $basketItem->product_id);
        return array_merge($basketItem->toArray(), $product->toArray(),[
            'quantity' => $basketItem->quantity // merge the quantity column from BasketOrder
        ]);
    });

    
       return [
        'order_id'=>$order->id,
        'customer_id'=>$order->customer_id,
        'market_id'=>$order->market_id,
        'total_price'=>$order->total_price,
        'delivery_price'=>$order->delivery_price,
        'distance_kilo'=>$order->distance_kilo,
        'number_of_products'=>$order->number_of_products,
        'products'=>$basketProducts
       ]; // the end of the return statment 

    }//the end of the method 

}// the end of the class 
