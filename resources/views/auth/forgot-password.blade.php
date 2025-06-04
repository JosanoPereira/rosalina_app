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
                            <h2 class="text-secondary mt-5"><b>REDEFINIR SENHA</b></h2>
                            <p class="f-16 mt-2">Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de
                                e-mail e enviaremos um link para redefinição de senha que permitirá que você escolha uma
                                nova.</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('password.email') }}">
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

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-secondary">Redefinir</button>
                    </div>
                </form>

                <hr/>
                <h5 class="d-flex justify-content-center">
                    <a href="{{ route('login') }}" class="text-primary">Lembra da senha?</a></h5>
            </div>
        </div>
    </div>
@endsection
