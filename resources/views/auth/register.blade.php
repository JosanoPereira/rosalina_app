@extends('layouts.app_auth')

@section('content')
    <div class="auth-form">

        <div class="card my-5">
            <div class="card-body">
                <a href="#" class="d-flex justify-content-center">
                    <img src="{{asset('assets/images/logo-rosalina.png')}}" alt="image" class="img-fluid brand-logo"/>
                </a>
                <div class="row">
                    <div class="d-flex justify-content-center">
                        <div class="auth-header">
                            <h2 class="text-secondary mt-5"><b>BEM-VINDO</b></h2>
                            <p class="f-16 mt-2">Insira seus dados para o devido cadastro</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" placeholder="Nome"
                                       name="nome" value="{{ old('nome') }}"/>
                                <label for="floatingInput">Primeiro Nome</label>
                                @if ($errors->has('nome'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('nome') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput1" placeholder="Apelido"
                                       name="apelido" value="{{ old('apelido') }}"/>
                                <label for="floatingInput1">Apelido</label>
                                @if ($errors->has('apelido'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('apelido') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput1" placeholder="Telefone"
                                       name="telefone" value="{{ old('telefone') }}"/>
                                <label for="floatingInput1">Telefone</label>
                                @if ($errors->has('telefone'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('telefone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput1" placeholder="NIF"
                                       name="bi" value="{{ old('bi') }}"/>
                                <label for="floatingInput1">BI</label>
                                @if ($errors->has('bi'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('bi') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="floatingSelect"
                                        aria-label="Floating label select example"
                                        name="generos_id">
                                    <option value="" selected>Selecione o sexo</option>
                                    @foreach($generos as $genero)
                                        <option value="{{ $genero->id }}"
                                            {{ old('generos_id') == $genero->id ? 'selected' : '' }}>{{ $genero->genero }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingInput1">Genero</label>
                                @if ($errors->has('generos_id'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('generos_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="floatingInput1" placeholder=""
                                       name="nascimento" value="{{ old('nascimento') }}"/>
                                <label for="floatingInput1">Data Nascimento</label>
                                @if ($errors->has('nascimento'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('nascimento') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingInput1" placeholder="Senha"
                               name="password"
                               autocomplete="current-password"/>
                        <label for="floatingInput1">Senha</label>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingInput1" placeholder="Confirme a senha"
                               name="password_confirmation"
                               autocomplete="current-password"/>
                        <label for="floatingInput1">Confirmação da Senha</label>
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-secondary">Cadastrar</button>
                    </div>
                </form>

                <hr/>
                <h5 class="d-flex justify-content-center">
                    <a href="{{ route('login') }}" class="text-primary">Já tem uma conta?</a>
                </h5>
            </div>
        </div>
    </div>
@endsection
