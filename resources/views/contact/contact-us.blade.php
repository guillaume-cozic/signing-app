@extends('adminlte::page')

@section('content')
    <div class="card card-outline card-primary">
    <div class="card-body row">
        <div class="col-5 text-center d-flex align-items-center justify-content-center">
            <div class="">
                <h2>WellSail</h2>
            </div>
        </div>
        <div class="col-7">
            @if(session()->has('contact_ok'))
                <div class="alert alert-success">
                    {{ session()->get('contact_ok') }}
                </div>
            @endif
            <form action="{{ route('contact') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="inputName">Nom *</label>
                    <input value="{{ old('name', $name) }}" type="text" id="inputName" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name">
                    @if ($errors->has('name'))
                        <span class="error invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="inputEmail">E-Mail *</label>
                    <input value="{{ old('name', $email) }}" type="email" id="inputEmail" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email">
                    @if ($errors->has('email'))
                        <span class="error invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="inputSubject">Téléphone</label>
                    <input type="text" id="inputSubject" class="form-control" name="phone">
                </div>
                <div class="form-group">
                    <label for="inputMessage">Message *</label>
                    <textarea id="inputMessage" class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" rows="4" name="message"></textarea>
                    @if ($errors->has('message'))
                        <span class="error invalid-feedback">
                            <strong>{{ $errors->first('message') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Envoyer message">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
