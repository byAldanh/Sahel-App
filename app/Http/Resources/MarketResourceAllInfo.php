<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MarketResourceAllInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
            'branch'=>$this->getBranch($this),
            'commercial_register' => $this->getCommercialRegister($this)
        ];
    }//the end of the method 

    protected function getBranch($user): ?string
    {
        $info = $user->CollectorMarketValues()
        ->where('collector_market_info_id', 2) // 2 = branch 
        ->first();

        return $info ? $info->values_info:null; 
    }//the end of the method 

    protected function getCommercialRegister($user): ?string
    {
        $info = $user->CollectorMarketValues()
            ->where('collector_market_info_id', 3) //3 = السجل التجاري
            ->first();

        return $info ? Storage::url($info->values_info): null;
    }

}
