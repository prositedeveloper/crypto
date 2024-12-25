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

    public function completedTransactions()
    {
        $transactions = Transaction::where('status', 'closed')
                        ->with('user', 'sellCurrency', 'buyCurrency')
                        ->get();

        return view('transactions-completed', compact('transactions'));
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
