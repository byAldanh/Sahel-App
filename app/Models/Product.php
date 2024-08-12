<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\BasketOrder;


class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';


    protected $fillable = [
        'product_name',
        'product_image',
        'price',
        'quantity',
        'barcode_image',
        'category_id',
        'market_id',
    ];//the end of the variable 

    public function user(){
        return $this->belongsTo(User::class);
    }//the end of the method 

    public function category(){
        return $this->belongsTo(Category::class);
    }//the end of the method

    public function baskets()
    {
        return $this->hasMany(BasketOrder::class, 'product_id');
    }

}//the end of the class 
