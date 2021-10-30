@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Liste des modèles de forfaits</h3>
                    <div class="card-tools">
                        <a href="{{ route('larecipe.show', ['version' => '1.0', 'page' => 'rental-package']) }}" target="_blank" class="btn btn-tool"
                           data-toggle="tooltip" data-placement="top" title="Consulter la documentation utilisateur">
                            <i class="fas fa-question-circle text-primary"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered html-datatable">
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
                                           data-toggle="tooltip" data-placement="top" title="Consulter les forfaits client">
                                            <i class="text-info fa fa-list"></i>
                                        </a>
                                        <i data-rental-package-id="{{$rentalPackage->id}}" style="cursor: pointer" class="text-success fa fa-user-plus add-sailor-rental-package"  data-toggle="tooltip" data-placement="top" title="Ajouter un forfait client"></i>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            @include('rental-package.add-form')
        </div>
    </div>
    @include('rental-package.modals.add-sailor-rental-package')
@endsection

