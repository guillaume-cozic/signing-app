@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des forfaits clients</h3>
                    @if(isset($rentalPackageId))
                        <div class="card-tools">
                            <a href="{{ route('sailor-rental-package.index') }}" class="btn btn-sm btn-primary">
                                Voir tous les forfaits
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @include('rental-package.sailor.list-rental-package')
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            @include('rental-package.sailor.add-form')
        </div>
    </div>
@endsection
