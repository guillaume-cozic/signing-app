<div class="p-2">
    <div class="callout callout-info">
        Les réservations pour les jours suivant sont présentées sur cette page.
        Elles sont automatiquement affichées sur la page "En cours" le jour même de la réservation
    </div>
    <table style="width: 100%;" class="table table-striped dashboard-datatable" id="boat-trips-table-reservations"
           data-href="{{route('reservation.list')}}">
        <thead>
             <tr role="row">
                <th data-priority="35">
                    <div class="d-none d-sm-block">
                        <i class="fa fa-ship"></i>Bateaux
                    </div>
                    <div class="d-block d-sm-none">
                        Bateaux
                    </div>
                </th>
                <th data-priority="30" class="desktop"> Total</th>
                <th data-priority="10">
                    <div class="d-none d-sm-block">
                        <i class="fa fa-anchor"></i> Marin
                    </div>
                    <div class="d-block d-sm-none">
                        Marin
                    </div>
                </th>
                <th data-priority="50">Départ</th>
                 <th data-priority="40">Etat</th>
                 <th data-priority="20">
                    <div class="d-none d-sm-block">
                        <i class="fa fa-cogs"></i> Actions
                    </div>
                    <div class="d-block d-sm-none">
                        Actions
                    </div>
                </th>
            </tr>
        </thead>
    </table>
</div>
