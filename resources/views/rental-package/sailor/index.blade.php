@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des forfaits utilisateurs</h3>
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
        <div class="col-lg-4">
            @include('rental-package.sailor.add-form')
        </div>
    </div>
@endsection
