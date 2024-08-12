<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    public function product(){
        return $this->hasMany(Product::class);
        
    }//the end of the method 


    protected $fillable = [
        'category_name'
    ];

}//the end of the class 
