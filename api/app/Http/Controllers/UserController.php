<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(Request $request)
    {

        Log::info('UserController@store called', ['payload' => $request->all()]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Log::info('User created', ['user_id' => $user->id]);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        Log::info('UserController@show called', ['user_id' => $user->id]);

        $user->load('wallets');
        $overallBalance = $user->wallets->sum('balance');

        return response()->json([
            'user' => $user,
            'overall_balance' => $overallBalance,
        ]);
    }
}
