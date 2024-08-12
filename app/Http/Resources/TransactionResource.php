<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            
            //'name_user'=>$this->name_user, // users_table
            'collector_id' =>$this->OrderJourney->user->name_user, 
            'order_id' => $this->OrderJourney->waitingPaymentCollector->waiting_bill_id,
            'order_id' => $this->OrderJourney->waitingPaymentCollector->date,
            'total_price'=>$this->total_price,        // order table
            'order_stat' => $this->OrderJourney->waitingPaymentCollector->payment_status,
           
        ];

        // return [
        // ];
    }//the end of the method 
}//the end of the class 
