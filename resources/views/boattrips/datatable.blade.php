<div>
<table class="table table-striped" id="boat-trips-table">
    <thead>
        <tr>
            <th>Bateaux</th>
            <th>Nom</th>
            <th>Retour</th>
        </tr>
    </thead>
</table>
</div>
@section('adminlte_js')
    @parent

    <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#boat-trips-table').DataTable( {
            processing: true,
            responsive: true,
            serverSide: true,
            tabIndex: -1,
            "language": {
                "lengthMenu": "Display _MENU_ records per page",
                "zeroRecords": "Nothing found - sorry",
                "info": "",
                "infoEmpty": "No records available",
                "infoFiltered": ""
            },
            stateSave: true,
            stateSaveCallback: function (settings, data) {
                localStorage.setItem("boattrips", JSON.stringify(data));
            },
            stateLoadCallback: function (settings) {
                return JSON.parse(localStorage.getItem("boattrips"));
            },
            drawCallback: function( settings ) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            ajax: {
                url: 'http://dev.signing.com:8002/boat-trips/list',
                type: 'POST',
            },
            iDisplayLength: 10,
            showExportButton: false,
            columns: [
                {"name": "boats"},
                {"name": "name"},
                {"name": "return"},
            ],
            "order": [[ 1, "asc" ]],
            fnRowCallback: function( row, data) {}
        });

    </script>
@endsection
