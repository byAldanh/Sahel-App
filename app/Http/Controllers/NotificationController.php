<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function create(Request $request)
    {
        return response()->json(['message' => 'Notification controller'], 201);
    }//the end of the method 
}
