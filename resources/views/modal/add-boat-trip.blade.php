<div class="modal bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-add-boat-trip">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajouter une sortie en mer
                    <a href="{{ route('larecipe.show', ['version' => '1.0', 'page' => 'boat-trip']) }}" target="_blank" class="btn btn-tool"
                       data-toggle="tooltip" data-placement="top" title="Consulter la documentation utilisateur">
                        <i class="fas fa-question-circle text-primary"></i>
                    </a>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-add-boat-trip" action="{{ route('boat-trips.add') }}" method="POST" class="form-add-boat-trip">
                @csrf
                <div class="modal-body">
                    <div class="callout callout-info">
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="sailor_id" value="" class="input_sailor_id"/>
                                <div class="form-group">
                                    <label for="name">
                                        Identité de la personne naviguant
                                    </label>
                                    <input type="search" class="form-control autocomplete-sailor-name"
                                           name="name" id="name"
                                           placeholder="Nom du client"
                                           autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <div class="div-detail-sailor">
                            <div class="detail-sailor">

                            </div>
                            <div class="form-check">
                                <input name="do_not_decrease" id="do-not-decreases" class="form-check-input" type="checkbox">
                                <label for="do-not-decreases" class="form-check-label">Ne pas décompter les heures sur le forfait</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input name="is_member" id="is_member" class="form-check-input" type="checkbox">
                                    <label for="is_member" class="form-check-label">Adhérent</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input name="is_instructor" id="is_instructor" class="form-check-input" type="checkbox">
                                    <label for="is_instructor" class="form-check-label">Moniteur</label>
                                </div>
                            </div>
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
                                    <button data-href="{{ route('boat-trips.modal') }}" type="button"
                                           class="btn-add-boats btn btn-primary btn-sm pull-right">
                                        <i class="fa fa-plus-circle"></i> Ajouter un type d'embarcation
                                    </button>
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
                                    <label>Ou sélectionner l'heure de départ</label>
                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                        <input autocomplete="off" data-toggle="datetimepicker" type="text" name="start_at" class="form-control datetimepicker-input" data-target="#timepicker">
                                        <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
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
                        <div class="col-sm-12 col-12">
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
                        <div class="col-sm-12 col-12">
                            <div class="form-group">
                                <label>Notes diverses</label>
                                <textarea class="form-control" rows="3" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="alert-boat-not-available callout callout-danger" style="display: none;">
                        <h5><i class="icon fas fa-ban"></i> Attention!</h5>
                        Malheureusement au moins une des embarcations n'est pas disponible.<br/>
                        Voulez vous tout de même continuer et créer la sortie en mer.
                        <button type="button" class="btn-force btn btn-danger">Forcer la création de la sortie</button>
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

