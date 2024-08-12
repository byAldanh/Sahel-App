<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'money_in',
        
    ];

    public function cardInfo(){
        return $this->hasMany(CardInfo::class);
    }//the end of the method 

    public function user(){
        return $this->belongsTo(User::class);
    }

    // public function cardInfoTransactions(){
    //     return $this->hasManyThrough(CardInfo::class, Transaction::class);
    // }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}//the end of the class