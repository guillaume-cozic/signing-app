<div class="modal" tabindex="-1" role="dialog" id="modal-add-boat-trip">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajouter une sortie</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-add-boat-trip" action="{{ route('boat-trips.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nom de la personne">
                    </div>
                    <div id="list-add-boat-trip">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="name">Embarcation</label>
                                            <select class="form-control" name="boats[1][id]">
                                                @if(isset($fleets))
                                                    @foreach($fleets as $fleet)
                                                        <option value="{{ $fleet->id }}">{{ $fleet->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="name">Nombre</label>
                                            <input class="form-control" name="boats[1][number]" value="1" type="number" step="1" min="1"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="button" id="btn-add-boats" class="btn btn-primary btn-sm pull-right" value="Ajouter un type d'embarcation">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="name">Nombre d'heure</label>
                                <input class="form-control" name="hours" value="1" type="number" step="0.5" min="0.5"/>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Heure de départ</label>
                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                        <input type="text" name="start_at" class="form-control datetimepicker-input" data-target="#timepicker">
                                        <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="alert-boat-not-available" class="alert alert-danger" style="display: none;">
                        <h5><i class="icon fas fa-ban"></i> Attention!</h5>
                        Au moins un support demandé n'est pas disponible.<br/>
                        Voulez vous tout de même continuer et créer la sortie en mer.
                        <button type="button" class="btn btn-default">Forcer la création de la sortie</button>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter une sortie</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('adminlte_js')
    @parent

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />


    <script type="text/javascript">
        $(function(){
            var route = '{{ route('boat-trips.modal') }}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var countBoatsList = 2;
            $('#btn-add-boats').click(function (){
                $.ajax({
                    url : route,
                    method: 'POST',
                    data: {count:countBoatsList},
                    success:function (data){
                        $('#list-add-boat-trip').append(data);
                        countBoatsList++;
                    }
                });
            });

            $('#modal-add-boat-trip').on('click', '.delete-boat', function (){
                $(this).parents('.row-boat-trip').remove();
            });

            $('#timepicker').datetimepicker({
                format: 'LT'
            });

            $('#form-add-boat-trip').submit(function (){
                $.ajax({
                    url : $(this).attr('action'),
                    method : 'POST',
                    data : $(this).serialize(),
                    dataType : 'json',
                    success:function (data){
                        tableBoatTrips.ajax.reload(null, false);
                        loadAvailability();
                        $('#modal-add-boat-trip').modal('hide');
                    },
                    error:function (){
                        $('#alert-boat-not-available').slideDown();
                    }
                });
                return false;
            });
        });
    </script>
@endsection
