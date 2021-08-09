<div class="modal" tabindex="-1" role="dialog" id="modal-sailor-rental-add-hours">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajouter des heures sur le forfait</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-sailor-rental-add-hours" action="" method="POST" class="form-sailor-rental-add-hours">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add-hours-input">Ajouter des heures</label>
                        <input id="add-hours-input" class="form-control" type="number" step="0.5" value="" name="hours" min="0"/>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
