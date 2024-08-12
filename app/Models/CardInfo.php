<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'iban',
        'card_number',
        'name_on_card',
        'expiry_date',
        'type_of_card',
        'wallet_id'
    ];
    
    public function deliveryApp(){
        return $this->hasOne(DeliveryApp::class);
    }

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}//the end of the class 
