@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
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
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ajouter un support</h3>
                </div>
                <form action="{{route('ships.add')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nom du support</label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name" placeholder="Entrer le nom du support">
                            @if ($errors->has('name'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="total_available">Nombre total de support disponible</label>
                            <input type="number" name="total_available" step="1" value="1" min="1" class="form-control {{ $errors->has('total_available') ? 'is-invalid' : '' }}" id="total_available" placeholder="Total disponible">
                            @if ($errors->has('total_available'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('total_available') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" checked class="custom-control-input" id="support_status" name="state">
                                <label class="custom-control-label" for="support_status">Support actif</label>
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
