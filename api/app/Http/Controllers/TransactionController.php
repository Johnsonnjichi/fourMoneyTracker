<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        Log::info('TransactionController@store called', ['payload' => $request->all()]);

        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction = DB::transaction(function () use ($validated) {
            return Transaction::create($validated);
        });

        Log::info('Transaction created', ['transaction_id' => $transaction->id]);

        return response()->json($transaction, 201);
    }
}
