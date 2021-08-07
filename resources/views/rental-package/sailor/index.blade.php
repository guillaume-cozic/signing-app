@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card">
                @include('rental-package.sailor.list-rental-package')
            </div>
        </div>
        <div class="col-lg-4">
            @include('rental-package.sailor.add-form')
        </div>
    </div>
@endsection
