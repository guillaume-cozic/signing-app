@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Liste des flottes</h3>
                    <div class="card-tools">
                        <a href="{{ route('larecipe.show', ['version' => '1.0', 'page' => 'fleets']) }}" target="_blank" class="btn btn-tool"
                           data-toggle="tooltip" data-placement="top" title="Consulter la documentation utilisateur">
                            <i class="fas fa-question-circle text-primary"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-2">
                    @include('signing.fleet.datatable')
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Ajouter une flotte</h3>
                </div>
                <form action="{{route('fleet.add')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nom du support</label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   name="name" id="name" placeholder="Entrer le nom du support" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="total_available">Nombre total d'embarcations disponible</label>
                            <input type="number" name="total_available" step="1" value="1" min="1" class="form-control {{ $errors->has('total_available') ? 'is-invalid' : '' }}" id="total_available" placeholder="Total disponible">
                            @if ($errors->has('total_available'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('total_available') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" @if(old('state', 'on') === 'on') checked @endif
                                       class="custom-control-input" id="support_status" name="state">
                                <label class="custom-control-label" for="support_status">Support actif</label>
                            </div>
                        </div>
                        @if(session()->has('fleet_error'))
                            <div class="alert alert-danger">
                                {{ session()->get('fleet_error') }}
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var showModalInit = '{{ $showModalInit }}';
    </script>
    @include('modal.add-fleets-tuto')
@endsection

