<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoneyTrackerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_create_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('name', 'John Doe');
    }

    public function test_can_create_wallet()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->postJson('/api/wallets', [
            'user_id' => $user->id,
            'name' => 'Business Wallet',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('name', 'Business Wallet');
    }

    public function test_can_add_transactions_and_calculate_balance()
    {
        $user = \App\Models\User::factory()->create();
        $wallet = \App\Models\Wallet::create([
            'user_id' => $user->id,
            'name' => 'Personal Wallet',
        ]);

        // Add Income
        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet->id,
            'amount' => 1000,
            'type' => 'income',
            'description' => 'Salary',
        ]);

        // Add Expense
        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet->id,
            'amount' => 200,
            'type' => 'expense',
            'description' => 'Groceries',
        ]);

        // Check Wallet Balance
        $response = $this->getJson("/api/wallets/{$wallet->id}");
        $response->assertStatus(200)
                 ->assertJsonPath('balance', 800);

        // Check overall balance in user profile
        $userProfileRes = $this->getJson("/api/users/{$user->id}");
        $userProfileRes->assertStatus(200)
                      ->assertJsonPath('overall_balance', 800);
    }

    public function test_user_profile_shows_all_wallets()
    {
        $user = \App\Models\User::factory()->create();
        \App\Models\Wallet::create(['user_id' => $user->id, 'name' => 'W1', 'balance' => 100]);
        \App\Models\Wallet::create(['user_id' => $user->id, 'name' => 'W2', 'balance' => 200]);

        $response = $this->getJson("/api/users/{$user->id}");
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'user.wallets')
                 ->assertJsonPath('overall_balance', 300);
    }
}
