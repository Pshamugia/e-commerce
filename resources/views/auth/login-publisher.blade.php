@extends('layouts.app')

@section('title', 'Login')
 
@section('content')

<div class="container mt-5 col-md-6" style="position: relative; padding-top:10%; padding-bottom:5%;">

 <div>   <h4> თუ ჯერ არ ხარ დარეგისტრირებული, დარეგისტრირდი  <a href="{{ route('register.publisher.form')}}"> აქ </a> </h4> </div>
    <!-- Email Errors -->
    @if ($errors->has('email'))
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-circle"></i> <!-- Optional icon -->
            {{ $errors->first('email') }} <!-- Show first email error -->
        </div>
    @endif

    <!-- Password Errors -->
    @if ($errors->has('password'))
        <div class="alert alert-danger">
            <i class="bi bi-lock-fill"></i> <!-- Optional icon for password -->
            {{ $errors->first('password') }} <!-- Show first password error -->
        </div>
    @endif

    <div style="border-radius: 5px; border:1px solid #ccc; padding: 22px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('ელფოსტა')" /> <br><br>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="email" class="form-control" placeholder="ელფოსტა" aria-label="Email" aria-describedby="basic-addon1" name="email" :value="old('email')" required autofocus autocomplete="username">
                </div>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('პაროლი')" /> <br><br>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span> <!-- Lock icon -->
                    <input type="password" class="form-control" placeholder="პაროლი" aria-label="Password" name="password" required autocomplete="current-password">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('დამახსოვრება') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('პაროლის აღდგენა') }}
                    </a>
                @endif

                <button type="submit" class="btn btn-primary" style="margin-left:5%">
                    {{ __('შესვლა') }} </button>
            </div>
        </form>
    </div>
</div>

@endsection
