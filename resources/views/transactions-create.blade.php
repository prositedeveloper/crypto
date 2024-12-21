@extends('layouts.layout', ['title' => 'Создание заявки на обмен'])

@section('content')
    <h2>Создание заявки на обмен</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="sell_currency" class="form-label">Выберите валюту для продажи</label>
            <select name="sell_currency_id" id="sell_currency" class="form-select">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->symbol }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="sell_amount" class="form-label">Количество для продажи</label>
            <input type="number" step="0.01" name="sell_amount" id="sell_amount" class="form-control">
        </div>

        <div class="mb-3">
            <label for="buy_currency" class="form-label">Выберите валюту для покупки</label>
            <select name="buy_currency_id" id="buy_currency" class="form-select">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->symbol }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="buy_amount" class="form-label">Количество для покупки</label>
            <input type="number" step="0.01" name="buy_amount" id="buy_amount" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Создать заявку</button>
    </form>
@endsection