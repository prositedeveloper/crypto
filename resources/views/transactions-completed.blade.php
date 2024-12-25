@extends('layouts.layout', ['title' => 'Завершенные заявки'])

@section('content')
    <div class="container">
        <h1>Завершенные заявки</h1>

        @if (!$userTransaction || !$counterpartyTransaction)
            <p>Нет завершенных заявок</p>
        @else
            <div class="transaction-card">
                <h3>Мои заявки</h3>
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
                            <td>{{ $userTransaction->sellCurrency->name }}</td>
                            <td>{{ $userTransaction->buyCurrency->name }}</td>
                            <td>{{ $userTransaction->sell_amount }}</td>
                            <td>{{ $userTransaction->buy_amount }}</td>
                            <td>{{ $userTransaction->updated_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>

                <h3>Заявки партнеров</h3>
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
                            <td>{{ $counterpartyTransaction->sellCurrency->name }}</td>
                            <td>{{ $counterpartyTransaction->buyCurrency->name }}</td>
                            <td>{{ $counterpartyTransaction->sell_amount }}</td>
                            <td>{{ $counterpartyTransaction->buy_amount }}</td>
                            <td>{{ $counterpartyTransaction->user->name }}</td> 
                            <td>{{ $counterpartyTransaction->updated_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
