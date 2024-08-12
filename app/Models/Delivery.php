<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $primaryKey = 'delivery_id';

    public function deliveryApp(){
        return $this->hasMany(DeliveryApp::class);
    }//the end of the method 
}//the end of the method
