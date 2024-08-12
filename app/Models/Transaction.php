<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Transaction extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'card_id',
        'amount',
        'wallet_id'
    ];

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }

    public function cardInfo(){
        return $this->belongsTo(CardInfo::class);

    }
}
