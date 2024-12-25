@extends('layouts.layout', ['title' => 'Выполненные заявки'])

@section('content')
    <div class="container">
        <h1>Выполнение заявки</h1>

        @if ($transactions->isEmpty())
            <p>Нет выполненных заявок</p>
        @else 
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
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->sellCurrency->name }}</td>
                            <td>{{ $transaction->buyCurrency->name }}</td>
                            <td>{{ $transaction->sell_amount }}</td>
                            <td>{{ $transaction->buy_amount }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>{{ $transaction->updated_at->format('d-m-Y-H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection