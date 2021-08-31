@extends('layouts.auth')

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
@endif

@section('message-auth')
    @if($invite)
        <div class="row">
            <div class="callout callout-info">
                <h5>Vous avez été invité à rejoindre : club nautique du Pradet</h5>
                <p>Enregistrez vous pour accèder à l'espace de collaboration</p>
            </div>
        </div>
    @endif
@endsection

@section('auth_header', __('adminlte::adminlte.register_message'))

@section('auth_body')
    <form action="{{ $register_url }}" method="post">
        {{ csrf_field() }}

        {{-- team field --}}
        <input type="hidden" name="invite" value={{$invite}}/>
        @if(isset($invite) && $invite === false)
            <div class="input-group mb-3">
                <input type="text" name="team_name" class="form-control {{ $errors->has('team_name') ? 'is-invalid' : '' }}"
                       value="{{ old('team_name') }}" placeholder="{{ __('adminlte::adminlte.team_name') }}" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-ship {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
                @if($errors->has('team_name'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('team_name') }}</strong>
                    </div>
                @endif
            </div>
        @endif

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="firstname" class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}"
                   value="{{ old('firstname') }}" placeholder="{{ __('adminlte::adminlte.firstname') }}" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('firstname'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('firstname') }}</strong>
                </div>
            @endif
        </div>

        <div class="input-group mb-3">
            <input type="text" name="surname" class="form-control {{ $errors->has('surname') ? 'is-invalid' : '' }}"
                   value="{{ old('surname') }}" placeholder="{{ __('adminlte::adminlte.surname') }}" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('surname'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('surname') }}</strong>
                </div>
            @endif
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="{{ __('adminlte::adminlte.password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('password'))
                <div class="error invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
            @endif
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                   placeholder="{{ __('adminlte::adminlte.retype_password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('password_confirmation'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </div>
            @endif
        </div>

        {{-- Register button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            {{ __('adminlte::adminlte.register') }}
        </button>
    </form>
@stop

@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('images/undraw_remotely_2j6y.svg') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>WellSail : Enregistrement d'un club nautique</h3>
                                @if($invite)
                                    <p class="mb-4">Vous avez été invité à rejoindre : club nautique du Pradet,
                                        Enregistrez vous pour accèder à l'espace de travail
                                    </p>
                                @else
                                    <p class="mb-4">Enregistrez vous pour accèder à l'espace de travail</p>
                                @endif
                            </div>
                            <form action="{{ $register_url }}" method="post">
                                <input type="hidden" name="invite" value={{$invite}}/>
                                @csrf
                                @if(isset($invite) && $invite === false)
                                    <div class="form-group first">
                                        <label for="team_name">Nom du club nautique</label>
                                        <input type="text" name="team_name" class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}" id="team_name">
                                        @if($errors->has('team_name'))
                                            <div class="invalid-feedback mt-1">
                                                <strong>{{ $errors->first('team_name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="firstname">Prénom</label>
                                    <input type="text" name="email" class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}" id="firstname">
                                    @if($errors->has('firstname'))
                                        <div class="invalid-feedback mt-1">
                                            <strong>{{ $errors->first('firstname') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="surname">Nom</label>
                                    <input type="text" class="form-control {{ $errors->has('surname') ? 'is-invalid' : '' }}" id="surname" name="surname" >
                                    @if($errors->has('surname'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('surname') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" >
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" name="password" >
                                    @if($errors->has('password'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group last mb-4">
                                    <label for="password_confirmation">Confirmation mot de passe</label>
                                    <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" >
                                    @if($errors->has('password_confirmation'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex mb-5 align-items-center">
                                    <label class="control control--checkbox mb-0"><span class="caption">Se souvenir de moi</span>
                                        <input type="checkbox" checked="checked"/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="{{$login_url}}" class="forgot-pass">J'ai déjà un compte</a></span>
                                </div>
                                <input type="submit" value="Se connecter" class="btn btn-block btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

