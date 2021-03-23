@extends('adminlte::page')

@section('content')
<form method="POST" action="{{ route('user.profile.save') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab" style="">Settings</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="settings">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Prénom</label>
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('firstname', $user['firstname']) }}"
                                                name="firstname"
                                               class="form-control" id="inputName" placeholder="Prénom"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Nom</label>
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('surname', $user['surname']) }}"
                                                name="surname"
                                               class="form-control" id="inputName" placeholder="Nom"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('email', $user['email']) }}"
                                                name="email"
                                               class="form-control" id="inputName" placeholder="Email"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                            </label>
                                        </div>
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
