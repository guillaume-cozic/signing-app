@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des forfaits</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Flottes</th>
                                <th>Durée de validité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentalPackages as $rentalPackage)
                                <tr>
                                    <td>{{ $rentalPackage->name }}</td>
                                    <td>{{ implode(' ', $rentalPackage->fleetsName) }}</td>
                                    <td>{{ $rentalPackage->validity }}</td>
                                    <td>
                                        <!--a href="#">
                                            <i class="fa fa-edit"></i>
                                        </a-->
                                        <a href="{{ route('sailor-rental-package.index', ['rental_package_id' => $rentalPackage->id]) }}">
                                            <i class="text-info fa fa-list"></i>
                                        </a>
                                        <!--a href="#">
                                            <i class="text-success fa fa-user-plus"></i>
                                        </a-->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4">
            @include('rental-package.add-form')
        </div>
    </div>
@endsection
