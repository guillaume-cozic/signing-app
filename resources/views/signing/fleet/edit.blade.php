@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="false" style="">
                                Editer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="true" style="">
                                Tarifs de location
                            </a>
                        </li>
                    </ul>
                </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                <form action="{{route('fleet.edit', ['fleetId' => $fleet->id])}}" method="POST">
                                    @csrf
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
                                               @if(old('state', $fleet->state === 'active' ? 'on' : null) === 'on') checked @endif
                                               class="custom-control-input" id="support_status" name="state">
                                        <label class="custom-control-label" for="support_status">Support actif</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Editer</button>
                                </form>
                            </div>
                            <div class="tab-pane fade active show" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                @include('signing.fleet.rental-rates')
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

