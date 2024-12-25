@extends('layouts.layout', ['title' => 'Страница не найдена'])

@section('content')
    <div class="error-template">
        <h1 class="display-1">404</h1>
        <h2>Страница не найдена</h2>
        <div class="error-details">
            К сожалению, запрашиваемая вами страница не найдена. Возможно, она была удалена, изменена или никогда не существовала.
        </div>
        <div class="error-actions" style="margin-top: 30px">
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Вернуться на главную</a>
        </div>
    </div>
@endsection
