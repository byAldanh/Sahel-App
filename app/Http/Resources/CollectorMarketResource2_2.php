<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectorMarketResource2_2 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
       
            'info'=>$this->CollectorMarketInfo->info,
            'answer'=>$this->values_info,

        ];    
      }
}
