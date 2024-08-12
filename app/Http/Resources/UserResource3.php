<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource3 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'collector_id'=>$this->id,
            'name_user'=>$this->name_user,
            'phone_user'=>$this->phone_user,
            'email_user'=>$this->email_user,
            'age'=>$this->getAge($this)
        ];  
    }//the end of the method 


    // To get the age for the user (collector)
    protected function getAge($collector)
    {
        $info = $collector->CollectorMarketValues()
            ->where('collector_market_info_id', 1) //3 = logo
            ->first();
            return $info ? $info->values_info: null;
    }//the end of the method 

}//the end class 
