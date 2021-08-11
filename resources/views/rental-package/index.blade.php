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
                                <th>Nom du forfait</th>
                                <th>Flottes</th>
                                <th>Durée de validité <small>(en jours)</small></th>
                                <th>Nombre de forfaits valides</th>
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
                                        @if($rentalPackage->number > 0)
                                            <span class="badge badge-success">{{ $rentalPackage->number }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $rentalPackage->number }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a
                                            data-toggle="tooltip" data-placement="top" title="Editer le forfait"
                                            href="{{ route('rental-package.edit', ['id' => $rentalPackage->id]) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="p-1" href="{{ route('sailor-rental-package.index', ['rental_package_id' => $rentalPackage->id]) }}"
                                           data-toggle="tooltip" data-placement="top" title="Consulter les forfaits client"
                                        >
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
