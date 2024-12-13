<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create()
    {
        $currencies = Currency::all();
        return view('transactions-create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sell_currency_id' => 'required|exists:currencies,id',
            'buy_currency_id' => 'required|exists:currencies,id',
            'sell_amount' => 'required|numeric|min:0',
            'buy_amount' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'sell_currency_id' => $request->sell_currency_id,
            'buy_currency_id' => $request->buy_currency_id,
            'sell_amount' => $request->sell_amount,
            'buy_amount' => $request->buy_amount,
            'price' => $request->price,
            'status' => 'open'
        ]);

        $this->autoMatch();

        return redirect()->route('transactions.list')->with('success', 'Заявка успешно создана!');
    }

    public function list()
    {
        $transactions = Transaction::where('status', 'open')->get();
        return view('transaction-index', compact(var_name: 'transactions'));
    }

    public function autoMatch()
    {
        $buyTransactions = Transaction::where('status', 'open')->get();
        
        foreach($buyTransactions as $buyTransaction)
        {
            $matchingTransactions = Transaction::where('status', 'open')
                ->where('buy_currency_id', $buyTransaction->sell_currency_id)
                ->where('sell_currency_id', $buyTransaction->ssell_currency_id)
                ->where('price', $buyTransaction->price)
                ->get();

            foreach($matchingTransactions as $sellTransaction)
            {
                $buyWallet = Wallet::where('user_id', $buyTransaction->user_id)
                    ->where('currency_id', $buyTransaction->buy_currency_id)
                    ->first();

                $sellWallet = Wallet::where('user_id', $buyTransaction->user_id)
                    ->where('currency_id', $buyTransaction->sell_currency_id)
                    ->first();

                if ($buyWallet && $sellWallet && 
                    $buyWallet->balance >= $buyTransaction->buy_amount && 
                    $sellWallet->balance >= $sellTransaction->sell_amount)
                {
                    $buyWallet->balance -= $buyTransaction->buy_amount;
                    $buyWallet->save();

                    $sellWallet->balance -= $sellTransaction->sell_amount;
                    $sellWallet->save();

                    $buyWallet->currency()->associate($buyTransaction->sell_currency_id);
                    $buyWallet->balance += $sellTransaction->sell_amount;
                    $buyWallet->save();

                    $sellWallet->currency()->associate($sellTransaction->buy_currency_id);
                    $sellWallet->balance += $buyTransaction->sell_amount;
                    $sellWallet->save();

                    $buyTransaction->status = 'closed';
                    $buyTransaction->save();

                    $sellTransaction->status = 'closed';
                    $sellTransaction->save();
                }
            }
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);

        if ($transaction->user_id !== auth()->id())
        {
            return redirect()->route('transactions.list')->withErrors('У вас нет прав для удаления этой заявки!');
        }

        $transaction->delete();

        return redirect()->route('transactions.list')->with('success', 'Заявка успешна удалена!');
    }
}
