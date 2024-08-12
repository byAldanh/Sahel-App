<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MarketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'market_id'=>$this->id,
            'name_user' => $this->name_user,
            'email_user'=>$this->email_user,
            'branch'=>$this->getBranch($this),
            'logo' => $this->getImageLogo($this)
        ];
    }//the end of the method 

    protected function getBranch($user): ?string
    {
        $info = $user->CollectorMarketValues()
        ->where('collector_market_info_id', 2) // 2 = branch 
        ->first();

        return $info ? $info->values_info:null; 
    }//the end of the method 

    protected function getImageLogo($user): ?string
    {
        $info = $user->CollectorMarketValues()
            ->where('collector_market_info_id', 4) //4 = logo
            ->first();

        return $info ? Storage::url($info->values_info): null;
    }

}
