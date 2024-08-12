<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usertype;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserTypeController extends Controller
{
    // To store the type pf the user 
    // tis method called from the system, not at the first by the user

    //Collector -> 1
    // Market -> 2
    // Customer -> 3
    // Admin -> 4
    public function create_user_type(Request $request)
    {
        // No validation .... 
        $create_user_type= Usertype::create([
            'user_type'=>$request->user_type
        ]);
        
        return response()->json([
            'message' => 'User type Created',
        ]);

    }// the end of the create method 

    // collector -> 4
    // market -> 5
    public function type()
    {
        $types=Usertype::all(); // we can perform resource here 
        return $types;

    }// the end of the method 

    public function delete(Request $request){
        $id = $request->id; // assign the email in the request to a variable

        // Find the user by email and delete the record
        $user = Usertype::where('id', $id)->first(); // returning the first matching record 
        if ($user) {
            $user->delete();
    
            // Return a JSON response
            return response()->json([
                'message' => 'User deleted successfully',
                'email' => $id
            ]);
        }
} // the end of the method

}// the end of the class 
