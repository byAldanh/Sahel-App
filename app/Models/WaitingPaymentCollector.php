<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingPaymentCollector extends Model
{
    use HasFactory;

    public function OrderJourney(){
        return $this->belongsTo(OrderJourney::class);
    }//the end of the method 
}//the end of the class 
