<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->product_id,
            'name' => $this->product_name,
            'image' => public_path( $this->product_image),
            'price' => $this->price,
            //'quantity' => $this->quantity,
            'barcode' => public_path($this->barcode_image),
           'category' => [
                'id' => $this->category->id,
                'name' => $this->category->category_name
            ]
        ];//the end of the return statements
    }//the end of the method 

}//the end of the class 
