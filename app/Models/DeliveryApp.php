<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class DeliveryApp extends Model
{
    use HasFactory;
    use HasApiTokens;


    public function delivery(){
        return $this->hasMany(Delivery::class);
    }

    public function cardInfo(){
        return $this->hasOne(CardInfo::class);
    }

   
}//the end of the method 