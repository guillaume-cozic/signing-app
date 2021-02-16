@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Nom du support</th>
                            <th>Total disponible</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>kayak simple (1 pers)</td>
                            <td>25</td>
                            <td>Actif</td>
                            <td>
                                <i class="text-red fa fa-ban"></i>
                                <i class="text-blue fa fa-edit"></i>
                                <i class="text-blue fa fa-shopping-cart"></i>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ajouter un support</h3>
                </div>
                <form>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nom du support</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nombre total de support disponible</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" checked class="custom-control-input" id="customSwitch3">
                                <label class="custom-control-label" for="customSwitch3">Support actif</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
