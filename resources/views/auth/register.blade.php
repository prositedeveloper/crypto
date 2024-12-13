@extends('layouts.layout', ['title' => 'Регистрация'])

@section('content')
    <div class="row justify-content-center" style="margin-top: 50px;">
        <div class="col-md-6">
            <h2 class="text-center">Регистрация</h2>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="password" class="form-label">Имя</label>
                    <input type="text" class="form-control" placeholder="Введите имя" name="name">

                    @error('name')
                        <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label for="password" class="form-label">Подтверждение пароля</label>
                    <input type="password" class="form-control" placeholder="Подтвердите пароль" name="password_confirmation">

                    @error('password_confirmation')
                        <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <p class="text-center">У вас есть аккаунт? - <a href="{{ route('login') }}">авторизуйтесь</a></p>
                </div>
                <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
            </form>
        </div>
    </div>
@endsection