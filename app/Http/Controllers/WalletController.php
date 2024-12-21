<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', auth()->id())->get();
        return view('wallets-index', compact('wallets')); 
    }

    public function recharge($id)
    {
        $wallet = Wallet::findOrFail($id);

        return view('wallets-recharge', compact('wallet'));
    }

    public function processRecharge(Request $request, $id)
    {
        $wallet = Wallet::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $wallet->balance += $request->amount;

        $wallet->save();

        return redirect()->route('wallets.index')->with('success', 'Баланс успешно пополнен!');
    }


    public function create()
    {
        $currencies = Currency::all();
        return view('wallets-create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'balance' => 'required|numeric|min:0'
        ]);

        $existWallet = Wallet::where('user_id', auth()->id())
                        ->where('currency_id', $request->currency_id)
                        ->first();

        if ($existWallet)
        {
            return redirect()->route('wallets.create')
                            ->withErrors(['currency_id' => 'У вас есть кошелек с этой валютой']);
        }

        Wallet::create([
            'user_id' => auth()->id(),
            'currency_id' => $request->currency_id,
            'balance' => $request->balance
        ]);

        return redirect()->route('wallets.index')->with('success', 'Кошелек успешно создан');
    }

    public function destroy($id)
    {
        $wallet = Wallet::where('user_id', auth()->id())->findOrFail($id);

        $wallet->delete();

        return redirect()->route('wallets.index')->with('success', 'Кошелек успешно удален');
    }
}
