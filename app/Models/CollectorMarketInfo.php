<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectorMarketInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'info',
        'user_type_id'
    ];// the end of the variable (array)

    public function userType(){
        return $this->belongsTo(UserType::class);
    }//the end of the method

    public function CollectorMarketValues(){
        return $this->hasOne(CollectorMarketValues::class);
    }//the end of the method 

}