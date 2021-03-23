@extends('adminlte::page')

@section('content')
<form method="POST" action="{{ route('user.profile.save') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img
                             id="profile"
                             class="profile-user-img img-fluid img-circle"
                             src="{{ isset($user['avatar']) ? asset($user['avatar']) : \Illuminate\Support\Facades\Auth::user()->adminlte_image() }}"
                             alt="User profile picture">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="settings">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Prénom</label>
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('firstname', $user['firstname']) }}"
                                                name="firstname"
                                               class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}"
                                               id="inputName" placeholder="Prénom"
                                        >
                                        @if ($errors->has('firstname'))
                                            <span class="error invalid-feedback">
                                                <strong>{{ $errors->first('firstname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Nom</label>
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('surname', $user['surname']) }}"
                                                name="surname"
                                               class="form-control {{ $errors->has('surname') ? 'is-invalid' : '' }}"
                                               id="inputName" placeholder="Nom"
                                        >
                                        @if ($errors->has('surname'))
                                            <span class="error invalid-feedback">
                                                <strong>{{ $errors->first('surname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('email', $user['email']) }}"
                                                name="email"
                                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               id="inputName" placeholder="Email"
                                        >
                                        @if ($errors->has('email'))
                                            <span class="error invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Avatar</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="file" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-danger">Sauvegarder</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
