@extends('layouts.auth')

@php( $password_email_url = View::getSection('password_email_url') ?? config('adminlte.password_email_url', 'password/email') )

@if (config('adminlte.use_route_url', false))
    @php( $password_email_url = $password_email_url ? route($password_email_url) : '' )
@else
    @php( $password_email_url = $password_email_url ? url($password_email_url) : '' )
@endif


@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('images/undraw_remotely_2j6y.svg') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                @if(!ENV('IS_DEMO'))
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>WellSail : {{ __('adminlte::adminlte.password_reset_message') }}</h3>
                            </div>
                            <form action="{{ $password_email_url }}" method="post">

                                @if(session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @csrf
                                <div class="form-group first">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback mt-1">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <input type="submit" value="Confirmer" class="btn btn-block btn-primary">
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger">
                        Cette page est désactivé sur le compte de démonstration
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
