@extends('layouts.layout', ['title' => 'Мои кошельки'])

@section('content')
    <h2>Мои кошельки</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Валюта</th>
                <th>Баланс</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wallets as $wallet)
                <tr>
                    <td>{{ $wallet->currency->name }} ({{ $wallet->currency->symbol }})</td>
                    <td>{{ $wallet->balance }}</td>
 
                    <td>
                        <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот кошелек')">Удалить</button>
                        </form>
                        <a href="{{ route('wallets.recharge', $wallet->id) }}" class="btn btn-primary">Пополнить баланс</a>
                    </td>
                </tr>
            @endforeach

            @if ($wallets->isEmpty())
                <tr>
                    <td>Нет кошельков</td>
                </tr>
            @endif
        </tbody>
    </table>

    <a href="{{ route('wallets.create') }}" class="btn btn-success">Создать новый кошелек</a>
@endsection
