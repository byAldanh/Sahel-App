<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectorMarketResource1_2 extends JsonResource
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
            'email_user'=>$this->email_user,
            'values_info'=> CollectorMarketResource2_2::collection($this->CollectorMarketValues->where('collector_market_info_id', 3)),
        ];   
      }
}
