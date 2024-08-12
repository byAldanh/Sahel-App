<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Category;
use App\Models\BasketOrder;


class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer_id' => $this->customer_id,
            'market_id' => $this->market_id,
            'total_price' => $this->total_price,
            'delivery_price' => $this->delivery_price,
            'distance_kilo' => $this->distance_kilo,
            'number_of_products' => $this->number_of_products,
           // 'products' =>$this->basket
        ];
    }//the end of the handle method 

    // public function getProduct($prodcut)
    // {
    //     $info = $prodcut->product();

    //    dd($product);
    // }//the end of the method 

 // return [
        //     'customer_id' => $this->customer_id,
        //     'market_id' => $this->market_id,
        //     'total_price' => $this->total_price,
        //     'delivery_price' => $this->delivery_price,
        //     'distance_kilo' => $this->distance_kilo,
        //     'number_of_products' => $this->number_of_products,
        //     'products'=>[
        //         'product id'=>$this->product->product_id,
        //         'quantity'=>$this->product->quantity
        //     ]
        // ];//the end of the return statement

}
