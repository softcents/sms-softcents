<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Http\Controllers\Controller;

class BalanceCheckController extends Controller
{
    public function index()
    {
        $user = User::where(['client_id' => request('clientId'), 'client_secret' => request('clientSecret')])->first();
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => __('User not found. Please provide us your valid client id and client secret.')
            ]);
        }

        return response()->json([
            'status' => 200,
            'balance' => $user->balance
        ]);
    }
}
