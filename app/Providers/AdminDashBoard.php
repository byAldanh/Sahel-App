<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Collection;

class AdminDashBoard extends ServiceProvider
{
    public static function dashBoardUsers(Collection $users)
    {
        $customers=($users->where('user_type_id',1))->count();
        $markers=($users->where('user_type_id',3))->count();
        $collectors=($users->where('user_type_id',2))->count();

        return [
            'customers' => $customers,
            'markers' => $markers,
            'collectors' => $collectors,
        ];
    }//the end of the method 

}//the end of the class 
