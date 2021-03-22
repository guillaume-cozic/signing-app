<div class="modal" tabindex="-1" role="dialog" id="modal-add-boat-trip">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajouter une sortie</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add-boat-trip" class="">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" id="name" placeholder="Nom de la personne">
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="name">Embarcation</label>
                                <select class="form-control">
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
                                <input class="form-control" value="1" type="number" step="1" min="1"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="name">Nombre d'heure</label>
                                <input class="form-control" value="0.5" type="number" step="0.5" min="1"/>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="name">Heure de départ</label>
                                <input class="form-control" value="0.5" type="number" step="0.5" min="1"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary">Ajouter une sortie</button>
            </div>
        </div>
    </div>
</div>
