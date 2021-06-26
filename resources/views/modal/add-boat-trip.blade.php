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
                                            <select id="main-boat" class="form-control" name="boats[1][id]">
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
                                    <input data-href="{{ route('boat-trips.modal') }}" type="button" id="btn-add-boats" class="btn btn-primary btn-sm pull-right" value="Ajouter un type d'embarcation">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-6">
                            <div class="form-group">
                                <label for="hours">Nombre d'heure(s)</label>
                                <input id="hours" class="form-control" name="hours" value="1" type="number" step="0.5" min="0.5"/>
                            </div>
                        </div>
                        <div class="col-sm-6 col-6">
                            <div class="form-group time-setter">
                                <label for="hour-start">Heure de départ</label>
                                <select id="hour-start" class="form-control">
                                    <option value="5">Dans 5 minutes</option>
                                    <option value="10">Dans 10 minutes</option>
                                    <option value="15">Dans 15 minutes</option>
                                    <option value="20">Dans 20 minutes</option>
                                    <option value="30">Dans 30 minutes</option>
                                    <option value="60">Dans 1 heure</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-12">
                            <div class="bootstrap-timepicker time-setter">
                                <div class="form-group">
                                    <label>Ou</label>
                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                        <input type="text" name="start_at" class="form-control datetimepicker-input" data-target="#timepicker">
                                        <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input id="start_auto" type="checkbox" name="start_auto"> Faire partir automatiquement à l'heure indiquée
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-12">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label>
                                            <input id="start_now" type="checkbox" name="start_now"> Faire partir maintenant
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="alert-boat-not-available" class="alert alert-danger" style="display: none;">
                        <h5><i class="icon fas fa-ban"></i> Attention!</h5>
                        Au moins un support demandé n'est pas disponible.<br/>
                        Voulez vous tout de même continuer et créer la sortie en mer.
                        <button id="btn-force" type="button" class="btn btn-default">Forcer la création de la sortie</button>
                    </div>
                    <div id="alert-error-add-boat-trip" class="alert alert-danger" style="display: none;">
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

