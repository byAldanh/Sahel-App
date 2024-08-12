<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\CardInfo;
use App\Http\Controllers\AdminStatController;

class WalletController extends Controller
{

    // List all wallets
    public function index()
    {
        //put if condition here -> if user_type_id = 4 (admin) -->give him all data
        //if some other --> .... use a resource here
        $wallets = Wallet::all();
        return response()->json($wallets);
    }

    // Create a new wallet
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', //required ==> nullable
            'money_in' => 'required|numeric',
           // 'card_info_id' => 'nullable|exists:card_infos,id', // Ensure it exists in card_infos table
                          //required ==> nullable
        ]);

        $wallet = Wallet::create($request->all());

        return response()->json([
            'message' => 'Wallet created successfully.',
            'wallet' => $wallet
        ], 201);
    }

    // Show a specific wallet
    public function show($id)
    {
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json([
                'message' => 'Wallet not found',
            ], 404); // Not Found
        }

        return response()->json($wallet);
    }

    //return the wallet info based on userID
    public function showByUserID($UserID)
    {
        $wallet = Wallet::where('user_id', $UserID)->first();

        if (!$wallet) {
            return response()->json([
                'message' => 'Wallet not found',
            ], 404); // Not Found
        }

        return response()->json($wallet);
    }
    // Update the user balance
    // public function update(Request $request)
    // {
    //     $userID = auth()->User()->id;
    //     $wallet = Wallet::where('user_id', $userID)->first();

    //     if (!$wallet) {
    //         return response()->json([
    //             'message' => 'Wallet not found',
    //         ], 404); // Not Found
    //     }

    //     $request->validate([
    //         'money_in' => 'nullable|numeric',
    //         'card_info_id' => 'nullable|exists:card_infos,id',
    //     ]);

    //     $wallet->update($request->all());

    //     return response()->json([
    //         'message' => 'Wallet updated successfully.',
    //         'wallet' => $wallet
    //     ]);
    // }

    // Update the user balance

    //showSpecificUserInfo($id) use this method before to get the user info + card_ids + card_numbers
    public function update($cardID)
    {

        $userID = auth()->User()->id;
        
        $userCard = CardInfo::where('id',$cardID)->with();
        
       
         //return $cardNumber = CardInfo::where('wallet_id', $walletID)->get('id');
        // if (!$walletID) {
        //     return response()->json([
        //         'message' => 'Wallet not found',
        //     ], 404); // Not Found
        // }

        // $request->validate([
        //     'money_in' => 'nullable|numeric',
        //     'card_info_id' => 'nullable|exists:card_infos,id',
        // ]);

        // $walletID->update($request->all());

        // return response()->json([
        //     'message' => 'Wallet updated successfully.',
        //     'wallet' => $walletID
        // ]);
    }

    // Delete a specific wallet
    public function destroy($id)
    {
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json([
                'message' => 'Wallet not found',
            ], 404); // Not Found
        }

        $wallet->delete();

        return response()->json([
            'message' => 'Wallet deleted successfully.',
        ]);
    }
}
