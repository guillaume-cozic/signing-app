@extends('adminlte::page')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />

@section('content')
    @can('add boat trip')
        <div class="row d-block d-sm-none">
            <div class="col-12">
                <button class="btn btn-primary btn-block mb-3 btn-add-boat-trip @if($fleetsCount === 0) disabled @else btn-add-boat-trip @endif">
                    <i class="fa fa-plus-circle"></i> Ajouter une sortie
                </button>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#in-progress" role="tab"
                               aria-controls="custom-tabs-three-home" aria-selected="false">En cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#ended" role="tab"
                               aria-controls="custom-tabs-three-profile" aria-selected="false" style="">Terminées</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-reservations-tab" data-toggle="pill" href="#reservations" role="tab"
                               aria-controls="custom-tabs-three-reservations" aria-selected="false" style="">Réservations</a>
                        </li>
                        <li class="">
                            <a aria-selected="false" href="{{ route('larecipe.show', ['version' => '1.0', 'page' => 'boat-trip']) }}" target="_blank" role="tab" class="nav-link"
                               data-toggle="tooltip" data-placement="top" title="Consulter la documentation utilisateur">
                                <i class="fas fa-question-circle text-primary"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane active" id="in-progress">
                            @include('boattrips.datatable')
                        </div>
                        <div class="tab-pane" id="ended">
                            @include('boattrips.datatable-ended')
                        </div>
                        <div class="tab-pane" id="reservations">
                            @include('boattrips.datatable-reservations')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            @can('add reservation')
                <button class="btn btn-info btn-block mb-3 @if($fleetsCount === 0) disabled @else  btn-add-boat-trip-reservation @endif">
                    <i class="fa fa-plus-square"></i> Ajouter une réservation
                </button>
            @endcan
            @can('add boat trip')
                <button class="btn btn-primary btn-block mb-3 @if($fleetsCount === 0) disabled @else btn-add-boat-trip @endif">
                    <i class="fa fa-plus-circle"></i> Ajouter une sortie
                </button>
            @endcan
            @include('dashboard.availability-loader')
        </div>
    </div>
    @include('modal.add-boat-trip')
    @include('modal.add-boat-trip-reservation')
@endsection

