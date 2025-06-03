@extends('layouts.app_auth')

@section('content')
    <div class="auth-form">

        <div class="card my-5">
            <div class="card-body">
                <a href="#" class="d-flex justify-content-center">
{{--                    <img src="{{asset('assets/images/logo-dark.svg')}}" alt="image" class="img-fluid brand-logo"/>--}}
                </a>
                <div class="row">
                    <div class="d-flex justify-content-center">
                        <div class="auth-header">
                            <h2 class="text-secondary mt-5"><b>BEM-VINDO DE VOLTA</b></h2>
                            <p class="f-16 mt-2">Insira as suas credências para continuar</p>
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
                        <label for="floatingInput">Email</label>
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
                        <label for="floatingInput1">Senha</label>
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
                            <label class="form-check-label text-muted" for="remember_me">Lembre-me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <h5 class="text-secondary">
                                <a href="{{ route('password.request') }}" class="text-primary">Esqueceu a senha?</a>
                            </h5>
                        @endif
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-secondary">Entrar</button>
                    </div>
                </form>

                <hr/>
                <h5 class="d-flex justify-content-center">Não tem uma conta?</h5>
            </div>
        </div>
    </div>
@endsection
