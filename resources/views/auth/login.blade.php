@extends('layouts.app_auth')

@section('content')
    <div class="auth-form">

        <div class="card my-5">
            <div class="card-body">
                <a href="#" class="d-flex justify-content-center">
                    <img src="{{asset('assets/images/logo-dark.svg')}}" alt="image" class="img-fluid brand-logo"/>
                </a>
                <div class="row">
                    <div class="d-flex justify-content-center">
                        <div class="auth-header">
                            <h2 class="text-secondary mt-5"><b>Hi, Welcome Back</b></h2>
                            <p class="f-16 mt-2">Enter your credentials to continue</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput"
                               placeholder="Email address / Username"
                               name="email" value="{{ old('email') }}"
                               autofocus autocomplete="email"/>
                        <label for="floatingInput">Email address / Username</label>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingInput1" placeholder="Password"
                               name="password"
                               autocomplete="current-password"/>
                        <label for="floatingInput1">Password</label>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="d-flex mt-1 justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input input-primary" type="checkbox" id="remember_me"
                                   name="remember"/>
                            <label class="form-check-label text-muted" for="remember_me">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <h5 class="text-secondary">
                                <a href="{{ route('password.request') }}" class="text-primary">Forgot Password?</a>
                            </h5>
                        @endif
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-secondary">Sign In</button>
                    </div>
                </form>

                <hr/>
                <h5 class="d-flex justify-content-center">Don't have an account?</h5>
            </div>
        </div>
    </div>
@endsection
