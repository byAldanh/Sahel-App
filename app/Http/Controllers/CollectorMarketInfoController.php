<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectorMarketInfo;


class CollectorMarketInfoController extends Controller
{
    // The method that fill the table 
    public function fill_info(Request $request)
    {
        $create_user_account= CollectorMarketInfo::create([
            'info'=>$request->info,
            'id_user_type'=>$request->id_user_type,
        ]); // the end of the create method 
        return response()->json([
            'message' => 'Fill_info filled correctly',
        ]); // the end of the return statement 

    }// the end of the method 
}
