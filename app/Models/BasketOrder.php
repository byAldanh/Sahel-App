<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;


class BasketOrder extends Model
{
    use HasFactory;
    protected $table = 'baskets_orders';
    protected $fillable = [
        'order_id',
        'customer_id',
        'market_id',
        'product_id',
        'quantity',
    ];// the end of the variable (array)

    public function order()
    {
        return $this->belongsTo(Order::class);
    }// the end of the method
    
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}// the end of the class 
