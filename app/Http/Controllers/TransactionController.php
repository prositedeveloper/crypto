<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        ]);

        $wallet = Wallet::where('user_id', auth()->id())
            ->where('currency_id', $request->sell_currency_id)
            ->first();

        if (!$wallet || $wallet->balance < $request->sell_amount) {
            return redirect()->back()->withErrors('Недостаточно средств для обмена!');
        }

        $matchingTransactions = Transaction::where('status', 'open')
            ->where('buy_currency_id', $request->sell_currency_id) 
            ->where('sell_currency_id', $request->buy_currency_id) 
            ->get();

        if ($matchingTransactions->isEmpty()) {
            Transaction::create([
                'user_id' => auth()->id(),
                'sell_currency_id' => $request->sell_currency_id,
                'buy_currency_id' => $request->buy_currency_id,
                'sell_amount' => $request->sell_amount,
                'buy_amount' => $request->buy_amount,
                'status' => 'open', 
            ]);

            return redirect()->route('transactions.list')->with('success', 'Заявка успешно создана! Ожидайте подходящую сделку.');
        }

        $matchingTransaction = $matchingTransactions->first();

        $opponentWallet = Wallet::where('user_id', $matchingTransaction->user_id)
            ->where('currency_id', $request->buy_currency_id)
            ->first();

        if (!$opponentWallet || $opponentWallet->balance < $request->buy_amount) {
            return redirect()->back()->withErrors('У оппонента недостаточно средств для обмена!');
        }

        $wallet->balance = bcsub($wallet->balance, $request->sell_amount, 8); // уменьшаем баланс
        $wallet->save();

        $opponentWallet->balance = bcsub($opponentWallet->balance, $request->buy_amount, 8); // уменьшаем баланс
        $opponentWallet->save();

        $matchingTransaction->status = 'closed'; 
        $matchingTransaction->save();

        Transaction::create([
            'user_id' => auth()->id(),
            'sell_currency_id' => $request->sell_currency_id,
            'buy_currency_id' => $request->buy_currency_id,
            'sell_amount' => $request->sell_amount,
            'buy_amount' => $request->buy_amount,
            'status' => 'closed', 
        ]);

        return redirect()->route('transactions.list')->with('success', 'Обмен успешен!');
    }

    public function list()
    {
        $transactions = Transaction::where('status', 'open')->get();
        $wallets = Wallet::where('user_id', auth()->id())->get(); 
        return view('transaction-index', compact('transactions', 'wallets'));
    }


    // public function match($transactionId)
    // {
    //     $buyTransaction = Transaction::findOrFail($transactionId);
    //     $matchingTransactions = Transaction::where('status', 'open')
    //         ->where('buy_currency_id', $buyTransaction->sell_currency_id) // покупаемая валюта продавца
    //         ->where('sell_currency_id', $buyTransaction->buy_currency_id) // продаваемая валюта покупателя
    //         ->get();

    //     foreach ($matchingTransactions as $sellTransaction) {

    //         if ($buyTransaction->status !== 'open' || $sellTransaction->status !== 'open') {
    //             continue;
    //         }

    //         $buyWallet = Wallet::where('user_id', $buyTransaction->user_id)
    //             ->where('currency_id', $buyTransaction->buy_currency_id)
    //             ->first();

    //         $sellWallet = Wallet::where('user_id', $sellTransaction->user_id)
    //             ->where('currency_id', $sellTransaction->sell_currency_id)
    //             ->first();


    //         if ($buyWallet && $sellWallet &&
    //             $buyWallet->balance >= $buyTransaction->buy_amount &&
    //             $sellWallet->balance >= $sellTransaction->sell_amount) {

    //             $buyWallet->balance = bcsub($buyWallet->balance, $buyTransaction->buy_amount, 8);  
    //             $buyWallet->balance = bcadd($buyWallet->balance, $sellTransaction->sell_amount, 8);  
    //             $buyWallet->save();

    //             $sellWallet->balance = bcsub($sellWallet->balance, $sellTransaction->sell_amount, 8); 
    //             $sellWallet->balance = bcadd($sellWallet->balance, $buyTransaction->buy_amount, 8);  
    //             $sellWallet->save();
                

    //             $buyTransaction->status = 'closed';
    //             $sellTransaction->status = 'closed';

    //             $buyTransaction->save();
    //             $sellTransaction->save();

    //             return redirect()->route('transactions.list')->with('success', 'Обмен успешен!');
    //         }
    //     }

    //     return redirect()->route('transactions.list')->withErrors('Не удалось выполнить обмен. Проверьте наличие подходящих заявок и баланс.');
    // }



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
