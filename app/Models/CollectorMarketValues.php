<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectorMarketValues extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function CollectorMarketInfo(){
        return $this->belongsTo(CollectorMarketInfo::class);
    }

    protected $fillable = [
        'collector_market_info_id',
        'values_info',
        'user_id'
    ];// the end of the variable (array)
    
}//the end of the method 
