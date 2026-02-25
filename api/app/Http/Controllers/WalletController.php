<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function store(Request $request)
    {
        Log::info('WalletController@store called', ['payload' => $request->all()]);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        $wallet = Wallet::create($validated);

        Log::info('Wallet created', ['wallet_id' => $wallet->id]);

        return response()->json($wallet, 201);
    }

    public function show(Wallet $wallet)
    {
        Log::info('WalletController@show called', ['wallet_id' => $wallet->id]);

        $wallet->load('transactions');

        return response()->json($wallet);
    }
}
