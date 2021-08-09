@include('rental-package.modals.add-hours')
@include('rental-package.modals.decrease-hours')
<div class="p-2">
    <table class="table table-striped no-wrap display datatable" id="sailor-rental-package-table"
           data-href="{{route('sailor-rental-package.list', ['rental_package_id' => $rentalPackageId ?? null ])}}">
        <thead>
        <tr role="row">
            <th>Nom de la personne</th>
            <th>Forfait</th>
            <th>Nombre d'heures disponibles</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>
</div>
