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
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Bateau</th>
                            <th>Total</th>
                            <th style="width: 40px">Disponibilité</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Update software</td>
                            <td>
                                <div class="progress progress-xs">
                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-danger">55%</span></td>
                        </tr>
                        <tr>
                            <td>Clean database</td>
                            <td>
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-warning" style="width: 70%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-warning">70%</span></td>
                        </tr>
                        <tr>
                            <td>Cron job running</td>
                            <td>
                                <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-primary" style="width: 30%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary">30%</span></td>
                        </tr>
                        <tr>
                            <td>Fix and squish bugs</td>
                            <td>
                                <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-success" style="width: 90%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-success">90%</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('modal.add-boat-trip')
@endsection

@section('adminlte_js')

    <script type="text/javascript">
        $('.btn-add-boat-trip').click(function(){
            $('#modal-add-boat-trip').modal('show');
        });
    </script>
@endsection
