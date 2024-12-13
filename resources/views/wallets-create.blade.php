@extends('layouts.layout', ['title' => 'Создание кошелька'])

@section('content')
    <h2>Создание кошелька</h2>

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


    <form action="{{ route('wallets.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="currency" class="form-label">Выберите валюту</label>
            <select name="currency_id" id="currency" class="form-select">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->symbol }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="balance" class="form-label">Баланс</label>
            <input type="number" step="0.1" name="balance" id="balance" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Создать кошелек</button>
    </form>
@endsection
