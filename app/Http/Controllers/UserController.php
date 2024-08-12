<?php

namespace App\Http\Controllers;

use  Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash; // to hash the passwords ( for security )
use App\Http\Requests\StoreUserRequest; // validating creating account request
use App\Http\Requests\LoginUserRequest; // validating log in request
use App\Mail\OTPMail; // for sending the OTP
use App\Mail\StatusMail; // for sending to collector or market about updated status to valid 
use App\Http\Resources\UserResource; // for viewing user info 
use App\Models\CollectorMarketValues; // additional info for some users 
use App\Http\Requests\CollectorRequest; // check the validation for collector info 
//use Illuminate\Support\Facades\Storage; // dealing with the image 
use App\Http\Resources\MarketResource;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\UserResource3; 
use App\Http\Resources\MarketResource2;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MarketUpdateRequest;
use App\Models\Order;
use App\Providers\MarketDashBoard;
use App\Providers\AdminDashBoard;

class UserController extends Controller
{
    /* 
    CREATE AN ACCOUNT - REGISTER - 
    This method is used to create an account for user,
    either collector, market, or customer.
    At the first it will check the validation in StoreUserRequest,
    then by using the (id_type_) the system assign the 'account_status' 
    either "valid" , "Under procedure", or "admin" for the real admin
    */
    public function create(Request $request)
    {
        //return $request->all();
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

        /* We need an if statement, to know the status of the account,
        if the user is customer, the account will be valid 
        */

        $user = new User; //creating an object of the model class 

        //storing the optional attribute by the system
        //CUSTOMER 
        if ($request['user_type_id'] == 1) { 
            $request['account_status'] = "valid";
        } //the end of the if statement

        //COLLECTOR/ MARKET 
        elseif ($request['user_type_id'] == 2 || $request['user_type_id'] == 3) { 
            $request['account_status'] = "Under procedure";
        } //the end of the else statement 

        //ADMIN 
        else { 
            $request['account_status'] = "Admin";
        } // the end of the else block 

        //assign the values to the user object 
        $user->name_user = $request->name_user;
        $user->phone_user = $request->phone_user;
        $user->email_user = $request->email_user;
        $user->password_user = Hash::make($request->password_user);
        $user->account_status = $request->account_status;
        $user->user_type_id = $request->user_type_id;
        $user->location = $request->location;


        // Creating an OTP and send it to the user email  ....
        $email = $request->email_user; // to get the email of the user 
        $otp = rand(1000, 9999);

        $expireDate = Carbon::now()->addMinutes(10); // deal with the currnet time 
        $user->otp_generated = $otp;
        $user->otp_expires_at = $expireDate;
        $user->save(); // save the object in the table corresponding to that model 

        Mail::to($email)->send(new OtpMail($user)); // sending the email 

        // Handling addition information for Market 
        if ($user->user_type_id == 3) {
            foreach ($request->questions as $question) {

                if($question['question_id'] == 2){
                CollectorMarketValues::create([
                    'collector_market_info_id' => $question['question_id'],
                    'values_info' => $question['value'],
                    'user_id' => $user->id,
                ]); 

                }//the end of the if 
                elseif($question['question_id'] == 3)
                {
                    $question = $question['value'];
                    $path = 'images/commercial_register/';
                    $fileName = $user->id. '_commercial_register_'. $user->user_type_id. '_'. $user->name_user. '_'. time(). ".". $question->getClientOriginalExtension();
                    $question->storeAs('public/'.$path,$fileName);
                   
                    CollectorMarketValues::create([
                        'collector_market_info_id' =>3,
                        'values_info' => $path .$fileName,
                        'user_id' => $user->id,
                    ]); 
                }//the end of the if else 
                elseif($question['question_id'] == 4){
                    $question = $question['value'];
                    $path = 'images/logos/';
                    $fileName = $user->id. '_logo_'. $user->user_type_id. '_'. $user->name_user. '_'. time(). ".". $question->getClientOriginalExtension();
                    $question->storeAs('public/'.$path,$fileName);
                   
                    CollectorMarketValues::create([
                        'collector_market_info_id' => 4,
                        'values_info' => $path.$fileName,
                        'user_id' => $user->id,
                    ]); 
                }//the end of the if else 
            }//the end of the for each 
        }//the end of the if 


        if($user->user_type_id == 2){// if the user is collector then a default information will be filled 
            CollectorMarketValues::create([
                'collector_market_info_id' => 5,
                'values_info' => 'active',
                'user_id' => $user->id,
            ]);
            // if the user_type_id == 2 it will only take the age 
            foreach ($request->questions as $question) {
                if($question['question_id'] == 1){
                    CollectorMarketValues::create([
                        'collector_market_info_id' => $question['question_id'],
                        'values_info' => $question['value'],
                        'user_id' => $user->id,
                    ]); 
                }
            }
        // the end of the return statement 
        }//the end of the if statement 

//         if($user->user_type_id==2 || $user->user_type_id==3)
//         {

//             $response = Http::post('http://127.0.0.1:8000/api//api/notifications');

//         if ($response->successful()) {
//        // $responseData = $response->json();
//         // Do something with the response data
//          return response()->json(['message' => 'Notification created successfully'], 201);
//         } else {
//         $statusCode = $response->status();
//          $errorMessage = $response->body();
//         // Handle the error
//         return response()->json(['message' => 'Error creating notification'], $statusCode);
// }
        // }//the end of the if statement 

        if ($user->user_type_id == 4) {
            // Create token for the admin 
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;


            return response()->json([
                'message' => 'Account successfully created',
                'token' => $token,
// delete_update_reset
                'otp' => $otp // otp forever for the admin , will not be changed 

            ]);
        } //the end of the if statement 
        else {

            return response()->json([
                'message' => 'Account successfully created',
                 'otp' => $otp
            ]);
        } //the end of the else statement 
    } // the end of the create method 

    //-------------------------------------------------------------------------------


    // For log in 
    public function login(LoginUserRequest $request)
    {
        //check if the entered email and password are correct 
        $user = User::where('email_user', $request['email_user'])->first();

        $message = " ";
        if (!$user || !Hash::check($request['password_user'], $user->password_user)) {
            $message = "Invalid Credentials";
        } //the end of the if statement

        if($user->user_type_id!=4)
       { $email = $request->email_user; // to get the email of the user 
        $otp = rand(1000, 9999);

        $expireDate = Carbon::now()->addMinutes(10);
        $user->otp_generated = $otp;
        $user->otp_expires_at = $expireDate;
        $user->save(); // Must save it again, so it will be updated in the table of "users"
        Mail::to($email)->send(new OtpMail($user));

       }//the end of the if statement

       // Admin will not need to access thr verify otp .... 
       elseif ($user->user_type_id == 4) { // will not create new ot for the admin 
            // Create token for the admin 
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;


            return response()->json([
                'message' => 'Account successfully created',
                'token' => $token,
                'otp' => $user->otp_generated
            ]);
        } //the end of the if statement 

        //TOKEN IS GIVEN AFTER VERIFYING THE OTP
        //$token = $user->createToken($user->name . '-AuthToken')->plainTextToken; //NEW

        /*
        if the email/password are correct a token will be generated and returned to the user
         each time the user logged in a new token will be generated 
         */
        return response()->json([
            'OTP' => $otp,
            //'token' => $token,
            'user_info' => [
                'id' => $user->id,
                'name_user' => $user->name_user,
                'phone_user' => $user->phone_user,
                'email_user' => $user->email_user,
                'account_status' => $user->account_status,
                'user_type_id' => $user->user_type_id,
            ]
        ]);
    } 


    // The method for reseting the password for the user 
    public function resetPassword(Request $request)
    {
        $user=User::where('email_user',$request->email_user)->first();

        if($user)
        {
            $otp = rand(1000, 9999);

            $expireDate = Carbon::now()->addMinutes(10); // deal with the currnet time 
            $user->otp_generated = $otp;
            $user->otp_expires_at = $expireDate;
            $email=$user->email_user;
            $user->save(); // save the object in the table corresponding to that model 

            Mail::to($email)->send(new OtpMail($user)); // sending the email 

            return response()->json([
                'OTP' => $otp,
                'message' => 'OTP has been Sended to the Email'
            ]);

        }//the end of the if statement 

        return response()->json([
            'message' => 'Enter Correct Email, please . . . '
        ]);

    }//the end of the method 


    // Update password for specific user 
    public function updatePassword(Request $request)
    {
        $user=auth()->User(); //get the user 
        $userUpdate=User::where('email_user',$user->email_user);

        if($userUpdate)
        {
            $user->password_user=Hash::make($request->password_user);
            $user->save(); // to save the updated information 
            return response()->json([
                'message' => 'Password updated successfuly',
                'password'=>$user->password_user
            ]);
        }//the end of the if statement 

        return response()->json([
            'message' => 'Password did not updated'
        ]);

    }//the end of the method 



    // This method for checking the otp and then generate the token for the user 
    // This method only for all users except the admin 
    public function verifyOtp(Request $request)
    {
        try {
            // Validating the input
            $request->validate([
                'email_user' => 'required|email',
                'otp_entered' => 'required|numeric',
            ]); //the end of the validate method 

            // Fetch the user info
            $user = User::where('email_user', $request->email_user)->first();
            $user->otp_entered = intval($request->otp_entered);
            //Fetch the otp associated with the user 
            $userotp = $user->otp_generated;
            $user->save();
            //dd($user);

            // if the entered otp equal to the generated one and the expired date less than or equal now 
            if ($request->otp_entered == $userotp) //$user->otp_expires_at <= now() 
            {
                $token = $user->createToken($user->name_user)->plainTextToken; // creating the token
                return response()->json([
                    'access_token' => $token,
                    'message' => 'User logged in successfully'
                ]);
            } //the end of the if statement
            else {
                return response()->json([
                    'message' => 'Invalid or expired OTP',
                    'otp_generated' => $user->otp_generated,
                    'otp_entered' => $user->otp_entered
                ], 401);
            } //the end of the else block
        } // the end of the try block 
        catch (\Throwable $th) {
            // Catch other errors 
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        } // the end of the catch block

    } //the end of the verifyOtp method



    // Update the user info 
    public function update(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'email_user' => ['required', 'email'], // Ensure current email is required
        'password' => 'required|min:8', // Current password
        'name_user' => 'sometimes|max:255',
        'phone_user' => 'sometimes|string|max:10', // Ensure it matches the database schema
        'new_email_user' => ['sometimes', 'email', 'unique:users,email_user'], // Ensure new email is unique
        'new_password' => 'sometimes|min:8' // New password (optional)
    ]);

    // Retrieve the user record by ID
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404); // Return 404 if the user is not found
    }

    // Check if the provided email and password match
    if ($user->email_user !== $request->email_user) {
        return response()->json([
            'message' => 'Invalid email',
            'debug' => [
                'user_email' => $user->email_user,
                'request_email' => $request->email_user
            ]
        ], 403); // Return 403 if email does not match
    }

    if (!Hash::check($request->password, $user->password_user)) {
        return response()->json([
            'message' => 'Invalid password',
            'debug' => [
                'user_password_hash' => $user->password_user,
                'request_password' => $request->password
            ]
        ], 403); // Return 403 if password does not match
    }

    // Update allowed fields
    if ($request->has('name_user')) {
        $user->name_user = $request->name_user;
    }
    if ($request->has('phone_user')) {
        $user->phone_user = $request->phone_user;
    }
    if ($request->has('new_email_user')) {
        $user->email_user = $request->new_email_user;
    }
    if ($request->has('new_password')) {
        $user->password_user = Hash::make($request->new_password);
    }

    $user->save(); // Save the updated user record

    return response()->json([
        'message' => 'User updated successfully',
        'user' => [
            'name_user' => $user->name_user,
            'phone_user' => $user->phone_user,
            'email_user' => $user->email_user,
            'account_status' => $user->account_status,
            'user_type_id' => $user->user_type_id
        ]// the end of the return 
    ]);// the end of the response 
}//the end of the method

public function updateMarket(Request $request)
{
    $market=auth()->User(); // get the market info 

    // Get the additional information of that market 
    $values=$market->CollectorMarketValues()
    ->where('user_id',$market->id)
    ->get();
    
    $branchUpdate=$values->where('collector_market_info_id',2); // branch
    $fileUpdate=$values->where('collector_market_info_id',3); //commercial 
    $logoUpdate=$values->where('collector_market_info_id',4); // logo


    // Update the information 
     // Update allowed fields
     if ($request->has('name_user')) {
        $market->name_user = $request->name_user;
    }
    if ($request->has('phone_user')) {
        $market->phone_user = $request->phone_user;
    }
    if ($request->has('new_email_user')) {
        $market->email_user = $request->new_email_user;
    }
    if ($request->has('new_password')) {
        $market->password_user = Hash::make($request->new_password);
    }
    if($request->has('market_logo'))
    {
        $path = 'public/images/logos';
        $logoImage=$request->market_logo;
        $fileName = $market->id . '_logo_' . time() . '.' . $logoImage->getClientOriginalExtension();
        $logoImage->storeAs($path, $fileName);
        $logoImage=$path.'/'.$fileName;

        foreach ($logoUpdate as $record) {
            $record->values_info = $logoImage;
            $record->save();
        }//the end of the for each loop 

    }// the end of the if statement 
    if($request->has('commercial_regitser'))
    {
        $path = 'public/images/commercial_register';
        $commercialFile=$request->commercial_regitser;
        $fileName = $market->id . '_commercial_register_' . time() . '.' . $commercialFile->getClientOriginalExtension();
        $commercialFile->storeAs($path, $fileName);
        $commercialFile=$path.'/'.$fileName;
       
       foreach ($fileUpdate as $record) {
                $record->values_info = $commercialFile;
                $record->save();
            }//the end of the for each loop 
        
       
    }//the end of the if statement 

    if($request->has('branch'))
    {
        foreach ($branchUpdate as $record) {
            $record->values_info = $request->branch;
            $record->save();
        }//the end of the for each loop 
    }
   
    $market->save(); // Save the updated user record
    return response()->json([
        'message' => 'User updated successfully',
        'user' => [
            'name_user' => $market->name_user,
            'phone_user' => $market->phone_user,
            'email_user' => $market->email_user,
            'account_status' => $market->account_status,
            'user_type_id' => $market->user_type_id
        ]// the end of the return 
    ]);// the end of the response 
}//the end of the method 


// Method to get the files and Logos 
protected static function getCommercial($user): ?string 
    {
        $info = $user->CollectorMarketValues()
        ->where('collector_market_info_id', 3) // 3 = commercial register  
        ->first();
        return $info ? $info->values_info:null; 
    }//the end of the method

    // Get the logo image 
    protected static function getImageLogo($user): ?string
    {
        $info = $user->CollectorMarketValues()
            ->where('collector_market_info_id', 4) //4 = logo
            ->first();

        return $info ? Storage::url($info->values_info): null;
    }//the end of the method


// To view all the market information - including the files - 
public function viewMarketAccount()
{
    $market=auth()->User(); // get the market info 
    return new MarketResource2($market); // single record only 
}//the end of the method 

/* 
The method where the admin update the account status for the market and collector 
and deal with the delivery app, when the user is market to create service provider in their system 

*/
public function updateUserStatus(Request $request,$id)
{
    //dd($request); // till know it is correct 

    // Validate the request data
    $request->validate([
        'account_status' => 'required|string|max:255', // Ensure the new account status is required
    ]);

    // Retrieve the user record by ID
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404); // Return 404 if the user is not found
    }//the end of the if 

    // Update the account status
    $user->account_status = $request->account_status;
    $user->save();// save th updated information 

    // To fill the branch ONLY 
    $userInfo=CollectorMarketValues::where('user_id',$user->id) // get the values for that user
    ->where('collector_market_info_id',2) // get only the value for the branch info 
    ->get();
    $user->save(); // Save the updated user record

    // if the user was market, request will be send to the delievery app 
    if($user->user_type_id==3 && $user->account_status=='valid') 
    {
        //dd($user); // the correct user 
        $imageUrl = User::getImageLogo($user); // to get the url for the market logo 
         $response=Http::withHeaders([
             'Accept'=>'application/json',
         ])//to solve the hit problem 
         ->post('https://renad-khiffa.firstcity.ai/api/service-providers',[
         'name'=>$user->name_user, // name of the market 
          'location_x'=>12, // location of the market
          'location_y'=>12, // location of the market
          'city'=>'makkah',
          'district'=>'makkah', // service provider means the market id 
          'path'=>$imageUrl,
          'polygon'=>'Sahel markets',
          'company_token'=>'5|ulJJw3OsSLsn81xattlefz18AOlS3Z8OE5yPmBV8885b2b34',
          'service_provider_id'=>$user->id,
        ]);//the end of the response
       
    

    Log::info('API response:', ['response' => $response->body(), 'status' => $response->status()]);

       if ($response->successful()) {
             return response()->json([
             "message" => "Post sent to Khiffa successfully",
             'data' => $response->json()
         ]);
         }// the end of the if 
         else {
             Log::warning('Non-successful response:', ['status' => $response->status(), 'body' => $response->body()]);
             return response()->json([
             "message" => "Failed to send post to Khiffa",
             'error' => $response->body()
         ], $response->status());
         } //the end of the else 
    return response()->json([
        'message' => 'Added Successfuly',
    ]);//the end of the return statement 
}//the end of the if statement 
return response()->json([
    'message' => 'Not sended successfuly',
]);//the end of the return statement 


}// the end of the method 


    // View the  collector account status to the admin 
    public function viewCollectorsStatus()
    {

        $collectors=User::where('user_type_id',2) // 2 means collectors 
        ->where('account_status','Under procedure')
        ->get();

        return UserResource3::collection($collectors);
    }//the end of the method


    // View the market account status to the admin 
    public function viewMarketsStatus()
    {
        $markets=User::where('user_type_id',3) // 3 means markets 
        ->where('account_status','Under procedure')
        ->get();

        return MarketResource::collection($markets);
    }//the end of the method 

    // MEthod for logging out 
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    } //the end of the method


    // viewMarkets method - used to view all the markets for the customer - 
    public function viewMarkets()
    {
         $markets = User::where('user_type_id', 3) // the user is market 
        ->where('account_status', 'valid')  // and the account status is valid
        ->select('id', 'name_user', 'user_type_id', 'account_status')
        ->with('CollectorMarketValues') // the name of the relation 
        ->get();

    return MarketResource::collection($markets);
    } // the end of the class 

    
    // Method that will update the collector status in the collectormarketvalues table 
    // Active and Inactive
    public function collectorUpdate(CollectorRequest $request)
    {
        // find the user with the unique id 
        $user = CollectorMarketValues::where('user_id', $request->user_id)->first();
        $user->values_info=$request->values_info;
        $user->save(); // to save the updating (must be used)


        return response()->json([
            "message" => "Collector Status Updated Successfully"
        ]);
    }// the end of the method 

    
    // Get the collector status 
    public function getCollectorStatus($id)
    {
        $collector=CollectorMarketValues::where('user_id',$id)
        ->where('collector_market_info_id',5)
        ->get();

        // Extract specific column from a collection 
        $collectorStatus = $collector->pluck('values_info')->toArray();

        return response()->json([
            "collector_status" => $collectorStatus
        ]);
    }//the end of the method 


    // Search for specific market 
    public function marketFilter(Request $request)
    {
    $markets = User::where('user_type_id', 3)->get(); // get the markets 
    $filterName=$markets->where('name_user', $request->name_user); // search for the required market
    $filterStatus=$filterName->where('account_status', 'valid'); // check the status of the market 

    return  MarketResource::collection($filterStatus); // view the information of the market 
    }//the end of the method 


    // To view the dash board of the market 
    public function viewDashMarket()
    {
        $market=auth()->User(); // to get the market id 
        return MarketDashBoard::dashBoard($market); // call the service for the market dash board 
    }//the end of the method 


    // To view the dash board for the admin 
    public function viewDashAdmin()
    {
        // to view the number of users in the app 
        $users=User::all();
        $numOfUsers=AdminDashBoard::dashBoardUsers($users);

        
    }//the end of the method

}// the end of the class     
