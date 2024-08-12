<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource2 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name_user'=>$this->name_user,
// <<<<<<< delete_update_reset
            'phone_user'=>$this->phone_user,
            'email_user'=>$this->email_user,
            'location'=>$this->location,
            'card_number' => CardNumberResource::collection($this->cardInfoThrough),
        ];   
     }//the end of the method 
}//the end of the class 

