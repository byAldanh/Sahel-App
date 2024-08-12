<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CollectorMarketValues;
use Illuminate\Support\Facades\Storage;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

//enable the UserObserver here -- the created method in the observer will automatically create a wallet when a user is created
#[ObservedBy([UserObserver::class])]


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name_user',
        'phone_user',
        'email_user',
        'password_user',
        'account_status',
        'otp_generated',
        'otp_entered',
        'otp_expires_at',
        'location',
        'user_type_id',
        'wallet_id',
        'card_number'
    ];// the end of the variable (array)


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    // Relations 
    public function userType(){
        return $this->belongsTo(UserType::class);
    }//the end of the method

    public function CollectorMarketValues(){
        return $this->hasMany(CollectorMarketValues::class);
    }//the end of the method

    public function order(){
        return $this->hasMany(Order::class);
    }//the end of the method

    public function product(){
        return $this->hasMany(Product::class);
    }//the end of the method

    public function wallet(){
        return $this->hasOne(Wallet::class);
    }


    // This method will be called when the user is a market 
    public static function getImageLogo(User $user)
    {
        //dd($user); // correct 
        // Method only called when the user is market 
        $marketId = $user->id; // Get the ID for the market

        // Retrieve the market logo from the values table
        $marketInfo = CollectorMarketValues::where('user_id', $marketId)
                  ->where('collector_market_info_id', 3) // 4 for logos 
                  ->first();
                  
        // Checl if there is a record 
        if ($marketInfo) {
            
            $marketLogo = Storage::url('public/images/commercial_register/' . $marketInfo->values_info);
            //$marketLogo=public_path($marketInfo->values_info);
            return $marketLogo;
        } else {
        // Return a default or placeholder image URL if the market logo is not found
             return 'Image not Found';
        } 
    }//the end of the method


    // public function image(Request $request)
    // {   
    //     // $userMarket=User::where('id',$request->id)
    //     // ->where('user_type_id',3)
    //     // ->get() 
    //     // ->first(); // get the user first
       
    //     $marketId=$userMarket->id;
    //     $marketInfo = CollectorMarketValues::where('user_id', $marketId)
    //               ->where('collector_market_info_id', 3)
    //               ->get()
    //               ->first();
    //     if ($marketInfo) {
    //               $marketLogo = Storage::url('public/images/commercial_register/' . $marketInfo->values_info);
    //               return response()->json([
    //                 "Logo" => $marketLogo
    //             ]); 
    //     } else {
    //         return response()->json([
    //             "Logo" => "Not Found"
    //         ]); 
    //      }
        
    // }//the end of the method

  // a tri-relation User has many CardInfo through Wallet
    public function cardInfoThrough(){
        return $this->hasManyThrough(CardInfo::class , Wallet::class);
    }
    //The first argument passed to the hasManyThrough method is the name of the final
    //model we wish to access, while the second argument is the name of the
    //intermediate model.
}//the end of the class 