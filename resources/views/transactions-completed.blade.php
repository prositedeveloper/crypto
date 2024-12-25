@extends('layouts.layout', ['title' => 'Завершенные заявки'])

@section('content')
    <div class="container">
        <h1>Завершенные заявки</h1>

        @if (count($transactions) == 0)
            <p>Нет завершенных заявок</p>
        @else
            @foreach ($transactions as $transaction)
                <div class="transaction-card">
                    <h3>Транзакция #{{ $transaction['transaction_number'] }}</h3>

                    <h4>Моя заявка</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Продажа валюты</th>
                                <th>Покупка валюты</th>
                                <th>Количество проданных</th>
                                <th>Количество купленных</th>
                                <th>Дата транзакции</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transaction['user_transaction']->sellCurrency->name }}</td>
                                <td>{{ $transaction['user_transaction']->buyCurrency->name }}</td>
                                <td>{{ $transaction['user_transaction']->sell_amount }}</td>
                                <td>{{ $transaction['user_transaction']->buy_amount }}</td>
                                <td>{{ $transaction['user_transaction']->updated_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4>Заявка партнера</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Продажа валюты</th>
                                <th>Покупка валюты</th>
                                <th>Количество проданных</th>
                                <th>Количество купленных</th>
                                <th>Партнер по обмену</th>
                                <th>Дата транзакции</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transaction['counterparty_transaction']->sellCurrency->name }}</td>
                                <td>{{ $transaction['counterparty_transaction']->buyCurrency->name }}</td>
                                <td>{{ $transaction['counterparty_transaction']->sell_amount }}</td>
                                <td>{{ $transaction['counterparty_transaction']->buy_amount }}</td>
                                <td>{{ $transaction['counterparty_transaction']->user->name }}</td>  <!-- Display the counterparty's name -->
                                <td>{{ $transaction['counterparty_transaction']->updated_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>

    {{ $userTransactions->links() }}
@endsection
