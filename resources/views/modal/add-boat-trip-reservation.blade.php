<div class="modal" tabindex="-1" role="dialog" id="modal-add-boat-trip-reservation">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajouter une réservation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-add-boat-trip-reservation" action="{{ route('boat-trips.add') }}" method="POST" class="form-add-boat-trip">
                <input type="hidden" name="is_reservation" value="true"/>
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nom de la personne">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input name="is_member" id="r_is_member" class="form-check-input" type="checkbox">
                            <label for="r_is_member" class="form-check-label">Adhérent</label>
                        </div>
                        <div class="form-check">
                            <input name="is_instructor" id="r_is_instructor" class="form-check-input" type="checkbox">
                            <label for="r_is_instructor" class="form-check-label">Moniteur</label>
                        </div>
                    </div>
                    <div class="list-add-boat-trip">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="name">Embarcation</label>
                                            <select class="form-control main-boat" name="boats[1][id]">
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
                                    <input data-href="{{ route('boat-trips.modal') }}" type="button"
                                           class="btn-add-boats btn btn-primary btn-sm pull-right" value="Ajouter un type d'embarcation">
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
                        <div class="col-sm-12 col-12">
                            <div class="bootstrap-timepicker time-setter">
                                <div class="form-group">
                                    <label>Départ</label>
                                    <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                        <input autocomplete="off" type="text" name="start_at" data-toggle="datetimepicker" class="form-control datetimepicker-input" data-target="#datetimepicker">
                                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-12">
                            <div class="form-group">
                                <label>Notes diverses</label>
                                <textarea class="form-control" rows="3" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="alert-boat-not-available alert alert-danger" style="display: none;">
                        <h5><i class="icon fas fa-ban"></i> Attention!</h5>
                        Au moins un support demandé n'est pas disponible.<br/>
                        Voulez vous tout de même continuer et créer la sortie en mer.
                        <button id="btn-force" type="button" class="btn btn-default">Forcer la création de la sortie</button>
                    </div>
                    <div class="alert-error-add-boat-trip alert alert-danger" style="display: none;">
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

