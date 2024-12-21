@extends('layouts.layout', ['title' => 'Список заявок'])

@section('content')


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between">
        <h4>Баланс</h4>

        @if ($wallets->isEmpty())
            <strong>0</strong>
        @else   
            @foreach ($wallets as $wallet)
                <div>
                    <strong>{{ $wallet->balance }} ({{ $wallet->currency->symbol }})</strong>
                </div>
            @endforeach
        @endif
    </div>

    <h2>Открытые заявки на обмен</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Продается</th>
                <th>Количество</th>
                <th>Покупка</th>
                <th>Количество</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->sellCurrency->name }}</td>
                    <td>{{ $transaction->sell_amount }}</td>
                    <td>{{ $transaction->buyCurrency->name }}</td>
                    <td>{{ $transaction->buy_amount }}</td>
                    <td>
                        @auth
                            {{-- @if ($transaction->user_id !== auth()->id())
                                <form action="{{ route('transactions.match', $transaction->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Обменять</button>
                                </form>
                            @endif --}}
                            @if ($transaction->user_id === auth()->id())
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы точно хотите удалить эту заявку?')">Удалить</button>
                                </form>
                            @endif
                        @endauth
                    </td>
                </tr>
            @endforeach

            @if ($transactions->isEmpty())
                <tr>
                    <td>Нет заявок</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
