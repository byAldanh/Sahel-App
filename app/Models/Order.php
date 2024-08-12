<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BasketOrder;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderJourney;
use App\Models\Product;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'market_id',
        'total_price',
        'delivery_price',
        'distance_kilo',
        'number_of_products'
    ];

    public function user(){
        return $this->belongsToMany(User::class);
    }//the end of the method 

    public function OrderJourney(){
        return $this->hasOne(OrderJourney::class);
    }//the end of the method 

    public function basket()
    {
        return $this->hasOne(BasketOrder::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class,'product_id');
    }
    
}//the end of the class 