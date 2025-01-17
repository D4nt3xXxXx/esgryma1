@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('css/pages/authentication.css') }}">
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/auth/login.js') }}"></script>
@endsection

@section('content')
    <section class="row flexbox-container">
        <div class="col-xl-8 col-11 d-flex justify-content-center">
            <div class="card bg-authentication rounded-0 mb-0">
                <div class="row m-0">
                    <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                        <img src="{{ asset('images/e1c10ded-bbef-4ca4-9e1c-12007bd9afc2.jpg') }}" alt="branding logo">
                    </div>
                    <div class="col-lg-6 col-12 p-0">
                        <div class="card rounded-0 mb-0 px-2">
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <h4 class="mb-0">Inicio de sesión</h4>
                                </div>
                            </div>
                            <p class="px-2">Bienvenido de nuevo, inicie sesión en su cuenta.</p>
                            <div class="card-content">
                                <div class="card-body pt-1">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <fieldset class="form-label-group form-group position-relative has-icon-left">

                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Ingrese su E-mail.." value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            <div class="form-control-position">
                                                <i class="feather icon-user"></i>
                                            </div>
                                            {{--<label for="email">E-Mail:</label>--}}
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </fieldset>

                                        <fieldset class="form-label-group position-relative has-icon-left">

                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Ingrese su clave..."required autocomplete="current-password">

                                            <div class="form-control-position">
                                                <i class="feather icon-lock"></i>
                                            </div>
                                            {{--<label for="password">Contraseña:</label>--}}
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </fieldset>
                                        <div class="form-group d-flex justify-content-between align-items-center">
                                            <div class="text-left">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                                        <span class="vs-checkbox">
                                            <span class="vs-checkbox--check">
                                              <i class="vs-icon feather icon-check"></i>
                                            </span>
                                          </span>
                                                        <span class="">Recordarme</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary float-right btn-inline" style="background-color: rgb(255, 159, 67)!important;">Ingresar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{--<div class="login-footer">
                                <div class="divider">
                                    <div class="divider-text">OR</div>
                                </div>
                                <div class="footer-btn d-inline">
                                    <a href="#" class="btn btn-facebook"><span class="fa fa-facebook"></span></a>
                                    <a href="#" class="btn btn-twitter white"><span class="fa fa-twitter"></span></a>
                                    <a href="#" class="btn btn-google"><span class="fa fa-google"></span></a>
                                    <a href="#" class="btn btn-github"><span class="fa fa-github-alt"></span></a>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
