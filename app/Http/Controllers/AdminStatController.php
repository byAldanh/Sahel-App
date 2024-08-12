<?php
//php artisan serve --host=192.168.8.191
namespace App\Http\Controllers;

use App\Http\Resources\CollectorMarketResource;
use App\Http\Resources\CollectorMarketResource1;
use App\Http\Resources\CollectorMarketResource1_2;
use App\Http\Resources\CollectorMarketResource1_3;
use App\Http\Resources\MarketResourceAllInfo;
use App\Http\Resources\TransactionResource;
use App\Models\Order;
use App\Models\User;
use App\Models\WaitingPaymentCollector;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResource1;
use App\Http\Resources\UserResource2;
use App\Models\CollectorMarketInfo;
use App\Models\CollectorMarketValues;

class AdminStatController extends Controller
{
    
    //helping function
     public function getUsersByType($typeId)
     {
         return User::where('user_type_id', $typeId)->count();
     }
  
    
    //Display the total number of users
     public function getNumberOfUsers()
    {
        $userCount = User::count();
        return response()->json(['number_of_users' => $userCount ]);
    }

    //get the total number of collectors
    public function getNumberOfCollectors()
    {
        $userCount = $this->getUsersByType(2);
        return response()->json(['number_of_collectors' => $userCount]);
    }

    //get the total number of markets
    public function getNumberOfMarkets()
    {
        $userCount = $this->getUsersByType(3);
        return response()->json(['number_of_markets' => $userCount]);
    }

//---------------------------------------------------------------------------------

    //get the total profit
    public function getTotalProfit()
    {
        $totalPriceOfOrders = Order::sum('total_price');

        return response()->json(['total_app_profit' => $totalPriceOfOrders]);
    }

    //get App Profits (owner profits => 15% of the products prices)
    public function getAppProfit()
    {
        $totalPriceOfOrders = Order::sum('total_price');

        $totalProfit = $totalPriceOfOrders * 0.15;

        return response()->json(['total_app_profit' => $totalProfit]);
    }

    // get the total profit for collectors (5%)
    public function getCollectorProfit()
    {
        $totalPriceOfOrders = Order::sum('total_price');

        $collectingPrice = $totalPriceOfOrders * 0.05;

        return response()->json(['total_collectors_profit' => $collectingPrice]);
    }

    //get the total number of orders
    public function getNumberOfOrders()
    {
        $orderCount = Order::count();
        return response()->json(['number_of_orders' => $orderCount]);
    }

    public function getNumberOfFinishedOrders()
    {
        $finshiedorderCount = WaitingPaymentCollector::count();
        return response()->json(['number_of_finished_orders' => $finshiedorderCount]);
    }


//------------------------------------------------------------------------------------
//START OF SHOW INFO
//----------------------------------------------------------------------------------------
//USER INFO METHODS

    //return all the info for all the users
    public function showAllUsersDetailedInfo(){
        $users = User::with('cardInfoThrough')->get();
        return UserResource::collection($users);
    }

      //return email - phone - name of the users
      public function showBasicUsersInfo(){
        $users = User::all();
        return UserResource2::collection($users);
    }

    // return specific user info based on their id
    public function showSpecificUserInfo($id)
{
    // Find the user by ID from the request
    $user = User::find($id);

    // Check if the user exists
    if ($user) {
        // Return the user info based on the UserResource format
        return new UserResource($user);
    } else {
        // Return an error message if the user is not found
        return response()->json(['error' => 'User not found'], 404);
    }
}

//------------------------------------------------------------------------------------
//COLLECTOR INFO METHODS

//return all the info for all the collectors
public function showAllCollectorsDetailedInfo()
{
    $users = User::where('user_type_id', 2)->with('CollectorMarketValues.CollectorMarketInfo')->with('cardInfoThrough')->get();
    //return $users;
     return CollectorMarketResource::collection($users);
}

//Show email  -  name  -  phone  for all the collectors 
public function showBasicCollectorsInfo()
{
    $users = User::where('user_type_id', 2)->with('CollectorMarketValues.CollectorMarketInfo')>with('cardInfoThrough')->get();
   
    // $users = User::where('user_type_id', 2)->get(['email_user', 'phone_user', 'name_user']);
    return CollectorMarketResource1::collection($users);
}

// عرض طلبات تسجيل الكوليكتورز
//Show email  -  name  -  phone  for all the collectors 
public function showCollectorsRegistrationRequests()
{
    $users = User::where('user_type_id', 2)->with('CollectorMarketValues.CollectorMarketInfo')->get();
    return CollectorMarketResource1_3::collection($users);
}


//Show all the info for a specific collector  given its id
public function showSpecificCollectorInfo($id)
{
    // Find the user by ID from the request
    $user = User::where('id',$id)->where('user_type_id', 2)->with('CollectorMarketValues.CollectorMarketInfo')->first();
    //use first() here because it returns only 1 object and in this case we only want the first record that apply to our condition
    //get() returns an array

    // Check if the user exists
    if ($user) {
        // Return the user info based on the UserResource format
        return new CollectorMarketResource($user);
    } else {
        // Return an error message if the user is not found
        return response()->json(['error' => 'Collector not found'], 404);
    }
}


//------------------------------------------------------------------------------------  
//MARKET INFO METHODS

//Show all info for all the markets 
public function showAllMarketsDetailedInfo()
{
    $users = User::where('user_type_id', 3)->with('CollectorMarketValues.CollectorMarketInfo')->with('cardInfoThrough')->get();
    //return $users;
     return MarketResourceAllInfo::collection($users);
}

// عرض طلبات تسجيل المتاجر
//Show name - branch - commercial_register - 
public function showBasicMarketsInfo()
{
    $users = User::where('user_type_id', 3)->with('CollectorMarketValues.CollectorMarketInfo')->with('cardInfoThrough')->get();
     return CollectorMarketResource1_2::collection($users);
}

//Show all the info for a specific market given its id
public function showSpecificMarketInfo($id)
{
    $user = User::where('id',$id)->where('user_type_id',3)->with('CollectorMarketValues.CollectorMarketInfo')->first();

    if ($user) {
        return new CollectorMarketResource($user);
    } else {
        // Return error message
        return response()->json(['error' => 'Market Not Found'], 404);
    }
}

//------------------------------------------------------------------------------------
//END OF SHOW INFO
//------------------------------------------------------------------------------------
//Show transactions

public function showTransactionsInfo(){     //relations: Order MODEL => OrderJ Model => waitingPaymentCollector Model
   // $users = User::where('user_type_id', 2)->with('order.OrderJourney.waitingPaymentCollector')->get();
   $orders = Order::with('order.OrderJourney.waitingPaymentCollector')->get();
    
    return TransactionResource::collection($orders);

}
//-----------------------------------------------------------------------------------

//------------------------------------------------------------------------------------
//Show transactions

public function showStoreRegistrationRequests(){     
    $markets = $this->getUsersByType(3);
    return  $markets;
     //return CollectorMarketResource1_2::collection($markets);
 
 }
 //-----------------------------------------------------------------------------------
        // To to Delete a user from the users table 
    public function delete(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json([
                'message' => 'User Not Found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'Deleted',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
