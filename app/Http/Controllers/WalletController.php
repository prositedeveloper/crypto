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
        return view('wallets-index', compact('wallets')); // Используем правильное имя файла
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
