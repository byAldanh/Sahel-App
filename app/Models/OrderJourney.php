<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderJourney extends Model
{
    use HasFactory;
    
    public function order(){
        return $this->belongsTo(Order::class);
    }//the end of the method

    public function user(){
        return $this->belongsTo(User::class);
    }//the end of the method

    public function waitingPaymentCollector(){
        return $this->hasOne(WaitingPaymentCollector::class);
    }//the end of the method 
}//the end of the method
