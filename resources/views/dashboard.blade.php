@extends('adminlte::page')


@section('content')
    <div class="row d-block d-sm-none">
        <div class="col-12">
            <button class="btn btn-primary btn-block mb-3 btn-add-boat-trip">Ajouter une sortie</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card">
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
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane active" style="padding:10px;" id="in-progress">
                            @include('boattrips.datatable')
                        </div>
                        <div class="tab-pane" id="ended">
                            1
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            <button class="btn btn-primary btn-block mb-3 btn-add-boat-trip">Ajouter une sortie</button>
            @include('dashboard.availability-loader')
        </div>
    </div>
    @include('modal.add-boat-trip')
@endsection

