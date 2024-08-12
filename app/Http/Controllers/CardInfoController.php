<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardInfoRequest;
use App\Models\CardInfo;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CardInfoController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $cardInfos = CardInfo::all();
        return response()->json($cardInfos);
    }

   //Store a newly created resource in storage.
   public function create(Request $request)
{
    $customMessages = [
        'iban.unique' => 'The IBAN you entered is already in use. Please use a different IBAN.',
        'card_number.unique' => 'The card number you entered is already in use. Please use a different card number.',
    ];

    try {
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|max:34|unique:card_infos',
            'card_number' => 'required|digits:16|unique:card_infos',
            'name_on_card' => 'required|string|max:255',
            'expiry_date' => 'required|date',
            'type_of_card' => 'required|string|max:255',
        ], $customMessages);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'errors' => $e->errors(),
        ], 422);
    }

    $validatedData['wallet_id'] = auth()->user()->wallet->id;

    $cardInfo = CardInfo::create($validatedData);

    return response()->json([
        'message' => 'Card information created successfully',
        'cardInfo' => $cardInfo,
    ]);
}

    // public function create(CardInfoRequest $request)
    // {
    //     $userInfo=auth()->User();
    //     $userID =$userInfo->id;
    //     $card = new CardInfo();

    //     $card->bank_name = $request['bank_name'];
    //     $card->iban = $request['iban'];
    //     $card->card_number = $request['card_number'];
    //     $card->name_on_card = $request['name_on_card'];
    //     $card->expiry_date = $request['expiry_date'];
    //     $card->type_of_card = $request['type_of_card'];
    //     $card->wallet_id = Wallet::with('userID', $userID)->get();

    //     $card->save();

    //     return response()->json([
    //         'message' => 'Card information created successfully',
    //         'cardInfo' => $card,
    //     ]);
    // }

    // Display the specified resource.
    public function show($id)
    {
        $cardInfo = CardInfo::find($id);

        if (!$cardInfo) {
            return response()->json([
                'message' => 'Card information not found',
            ], 404);
        }

        return response()->json($cardInfo);
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'bank_name' => 'sometimes|required|string',
            'iban' => 'sometimes|required|string',
            'card_number' => 'sometimes|required|integer',
            'name_on_card' => 'sometimes|required|string',
            'expiry_date' => 'sometimes|required|date',
            'type_of_card' => 'sometimes|required|string',
        ]);

        $cardInfo = CardInfo::find($id);

        if (!$cardInfo) {
            return response()->json([
                'message' => 'Card information not found',
            ], 404);
        }

        $cardInfo->update($validatedData);
        return response()->json([
            'message' => 'Card information updated successfully',
            'cardInfo' => $cardInfo,

        ]);
    }

    // Remove the specified resource from storage.
    public function delete($id)
    {
        $cardInfo = CardInfo::find($id);

        if (!$cardInfo) {
            return response()->json([
                'message' => 'Card information not found',
            ], 404);
        }

        $cardInfo->delete();
        return response()->json([
            'message' => 'Card information deleted successfully',
        ]);
    }
}

