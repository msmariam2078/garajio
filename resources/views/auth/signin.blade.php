@extends('layouts.master2')

@section('css')
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection

@section('content')
    @php
        $registerPage = getSettingsValByName('register_page');
    @endphp

    <div class="container bg-light py-5" style="min-height: 100vh;">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <div class="card shadow rounded p-4">
                    <div class="text-center mb-3">
                        <img src="{{ URL::asset('assets/img/faces/logo.jpg') }}" alt="Logo" class="img-fluid"
                            style="height: 43px;">
                    </div>
                    <h4 class="text-center text-danger mb-4">Welcome to Garajeo</h4>

                    <form action="{{ route('storelogin') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="userName" class="form-label">Email</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                                id="userName" placeholder="Email">
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pwd" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="pwd" placeholder="Password">
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="mb-3 text-end">
                                <a class="text-decoration-none text-danger" href="{{ route('password.request') }}">Forgot
                                    your password?</a>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-danger w-100">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="mb-0">Don't have an account? <a href="#"
                                class="text-decoration-none text-danger">Sign up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
