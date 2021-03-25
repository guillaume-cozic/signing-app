@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Editer la flotte</h3>
                </div>
                <form action="{{route('fleet.edit', ['fleetId' => $fleet->id])}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nom du support</label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name"
                                   value="{{ old('name', $fleet->name) }}"
                                   placeholder="Entrer le nom du support">
                            @if ($errors->has('name'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="total_available">Nombre total d'embarcations disponible</label>
                            <input type="number" name="total_available" step="1" min="1"
                                   value="{{ old('name', $fleet->totalAvailable) }}"
                                   class="form-control {{ $errors->has('total_available') ? 'is-invalid' : '' }}" id="total_available"
                                   placeholder="Total disponible">
                            @if ($errors->has('total_available'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('total_available') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox"
                                       @if(old('state', $fleet->state) === 'on') checked @endif
                                       class="custom-control-input" id="support_status" name="state">
                                <label class="custom-control-label" for="support_status">Support actif</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Editer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

