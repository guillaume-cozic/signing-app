<div class="modal" tabindex="-1" role="dialog" id="modal-message-boattrip-not-closed">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-bullhorn"></i>
                    Attention certaines sorties en mer n'ont pas étaient cloturées !
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('boat-trips.mass-end') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card card-outline card-danger">
                        <div class="card-body">
                            Attention certaines sorties en mer n'ont pas étaient cloturées !
                            Les sorties non cloturées ne sont pas prises en compte dans les différentes statistiques
                            et peuvent fausser le nombre réel de sorties.
                        </div>
                    </div>
                    <div data-ajax-href="{{ route('boat-trips.not-ended') }}"></div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="pull-right btn btn-danger" value="Confirmer"/>
                </div>
            </form>
        </div>
    </div>
</div>
