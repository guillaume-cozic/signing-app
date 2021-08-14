@extends('adminlte::page')

@section('content')
    <div class="card">
    <div class="card-body row">
        <div class="col-5 text-center d-flex align-items-center justify-content-center">
            <div class="">
                <h2>WellSail</h2>
            </div>
        </div>
        <div class="col-7">
            <div class="form-group">
                <label for="inputName">Nom</label>
                <input type="text" id="inputName" class="form-control">
            </div>
            <div class="form-group">
                <label for="inputEmail">E-Mail</label>
                <input type="email" id="inputEmail" class="form-control">
            </div>
            <div class="form-group">
                <label for="inputSubject">Sujet</label>
                <input type="text" id="inputSubject" class="form-control">
            </div>
            <div class="form-group">
                <label for="inputMessage">Message</label>
                <textarea id="inputMessage" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Envoyer message">
            </div>
        </div>
    </div>
</div>
@endsection
