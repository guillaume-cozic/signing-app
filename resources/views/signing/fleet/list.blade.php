@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-body p-2">
                    @include('signing.fleet.datatable')
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ajouter une flotte</h3>
                </div>
                <form action="{{route('fleet.add')}}" method="POST">
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
                                <input type="checkbox"
                                       @if(old('state', 'active') === 'on') checked @endif
                                       class="custom-control-input" id="support_status" name="state">
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


@section('adminlte_js')
    @parent

    <script type="text/javascript">
        var routeEnable = '{{ route('fleet.enable') }}';
        var routeDisable = '{{ route('fleet.disable') }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#fleets-table').on('click', '.btn-disable', function(){
            if(!confirm('Voulez vous vraiment désactiver cette flotte')){
                return;
            }
            var fleetId = $(this).data('fleet-id')
            $.ajax({
                url: routeDisable,
                method: 'POST',
                data: {fleet_id:fleetId},
                success: function (){
                    table.ajax.reload(null, false);
                }
            });
        });

        $('#fleets-table').on('click', '.btn-enable', function(){
            if(!confirm('Voulez vous vraiment activer cette flotte')){
                return;
            }
            var fleetId = $(this).data('fleet-id')
            $.ajax({
                url: routeEnable,
                method: 'POST',
                data: {fleet_id:fleetId},
                success: function (){
                    table.ajax.reload(null, false);
                }
            });
        });
    </script>
@endsection
