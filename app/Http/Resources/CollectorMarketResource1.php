<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectorMarketResource1 extends JsonResource
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
            'phone_user'=>$this->phone_user,
            'email_user'=>$this->email_user,
            'values_info'=> CollectorMarketResource2::collection($this->CollectorMarketValues),
        ];  
      }
}
