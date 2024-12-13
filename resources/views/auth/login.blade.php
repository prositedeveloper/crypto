@extends('layouts.layout', ['title' => 'Авторизация'])

@section('content')
    <div class="row justify-content-center" style="margin-top: 50px;">
        <div class="col-md-6">
            <h2 class="text-center">Вход в систему</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="Введите логин" name="email">

                    @error('email')
                        <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" placeholder="Введите пароль" name="password">

                    @error('password')
                        <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <p class="text-center">У вас нет аккаунта? - <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
                </div>
                <button type="submit" class="btn btn-primary w-100">Войти</button>
            </form>
        </div>
    </div>
@endsection