<div class="modal" tabindex="-1" role="dialog" id="modal-add-easy-fleets">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajout rapide de flottes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-add-easy-fleets" action="{{ route('fleets.mass.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="callout-info callout">
                        Vous n'avez pas encore ajoutés de flottes
                        Si vous ne trouvez pas votre flotte vous pouvez la créer par la suite.
                    </div>
                    <div class="form-group">
                        <label for="name">Sélectionnez les flottes que vous souhaitez ajouter</label>
                        <select id="select-fleet"
                                style="width:100%;"
                                data-placeholder="Sélectionnez une ou plusieurs flottes"
                                class="autocomplete-fleets-name select2-purple form-control"
                                name="fleets[]" multiple="multiple">
                                @foreach($fleetsInit as $type => $fleetsByType)
                                    <optgroup label="{{$type}}"></optgroup>
                                    @foreach($fleetsByType as $fleet)
                                        <option value="{{ $fleet }}">{{ $fleet }}</option>
                                    @endforeach
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter les flottes</button>
                </div>
            </form>
        </div>
    </div>
</div>

