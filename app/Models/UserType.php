<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens; /* for the tokens used in the authentication part */
use Illuminate\Notifications\Notifiable;
//use Illuminate\Database\Eloquent\Relations\HasMany;


class UserType extends Model
{
    //use HasFactory;
   
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // user_type : customer / market / collector / super admin 
    protected $fillable = [
        'user_type',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    /*protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/

    public function users(){
        return $this->hasMany(User::class);
    }

    //relation: One user_type has many c_m_info
    public function CollectorMarketInfo(){
        return $this->hasMany(CollectorMarketInfo::class);
    }

    
}//the end of the class 


