@extends('layouts.layout', ['title' => 'Список заявок'])

@section('content')
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
                <th>Цена</th>
                <th>Покупка</th>
                <th>Количество</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->sellCurrency->name }}</td>
                    <td>{{ $transaction->sell_amount }}</td>
                    <td>{{ $transaction->price }}</td>
                    <td>{{ $transaction->buyCurrency->name }}</td>
                    <td>{{ $transaction->buy_amount }}</td>
                    <td> 
                        @if ($transaction->user_id === auth()->id())
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы точно хотите удалить эту заявку?')">Удалить</button>
                            </form>
                        @endif
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
