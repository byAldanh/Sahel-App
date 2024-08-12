<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\JsonResource\toArray;


class UserResource extends JsonResource
{
 
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name_user'=>$this->name_user,
            'phone_user'=>$this->phone_user,
            'email_user'=>$this->email_user,
            'account_status'=>$this->account_status,
            'user_type_id'=>$this->user_type_id,
            'card_number' => CardNumberResource::collection($this->cardInfoThrough),
        ];
    }
}