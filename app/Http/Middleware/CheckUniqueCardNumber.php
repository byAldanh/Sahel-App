<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CardInfo;

class CheckUniqueCardNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the card_number exists in the request
        if ($request->has('card_number')) {
            $cardNumber = $request->input('card_number');

            // Check if the card_number already exists in the database
            $exists = CardInfo::where('card_number', $cardNumber)->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Card number already exists',
                ], 409); // Conflict
            }
        }

        return $next($request);
    }
}
