@extends('layouts.core', ['title' => 'Log in', 'noSbadmin' => true])
@section('app')
<main>
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
            <div class="card text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="width: 45vw;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <img src="{{ asset('img/logo-sat.jpg') }}" class="rounded-circle d-block mx-auto mt-3 " alt="{{ asset('img/logo-sat.jpg') }}" style="width: 15rem;">
                            <p class="fs-5 text-center fw-bold my-3">CV. SINAR AGUNG TEKNIK</p>
                        </div>
                        <div class="col-6">
                            <p class="fs-4 text-center fw-bold mt-3">Welcome Back</p>
                            <p class="fs-6 text-center">welcome back! Please enter your credential</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end ">{{ __('Email') }}</label>
                                    <div class="col-md-8">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                    <div class="col-md-8">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
