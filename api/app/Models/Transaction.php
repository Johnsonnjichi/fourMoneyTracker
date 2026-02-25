<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['wallet_id', 'amount', 'type', 'description'];

    protected static function booted()
    {
        static::created(function ($transaction) {
            $wallet = $transaction->wallet;
            if ($transaction->type === 'income') {
                $wallet->increment('balance', $transaction->amount);
            } else {
                $wallet->decrement('balance', $transaction->amount);
            }
        });
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
