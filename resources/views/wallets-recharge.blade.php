@extends('layouts.layout', ['title' => 'Пополнение баланса'])

@section('content')
    <h2>Пополнение баланса для {{ $wallet->currency->name }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('wallets.process_recharge', $wallet->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Сумма пополнения</label>
            <input type="number" name="amount" class="form-control" step="0.01">
        </div>

        <button type="submit" class="btn btn-primary">Пополнить</button>
    </form>
@endsection