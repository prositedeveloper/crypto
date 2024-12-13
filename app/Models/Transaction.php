<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'sell_currency_id',
        'buy_currency_id',
        'sell_amount',
        'buy_amount',
        'price',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sellCurrency()
    {
        return $this->belongsTo(Currency::class, 'sell_currency_id');
    }

    public function buyCurrency()
    {
        return $this->belongsTo(Currency::class, 'buy_currency_id');
    }
}
