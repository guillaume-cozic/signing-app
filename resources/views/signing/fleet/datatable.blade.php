<table class="table table-striped" id="fleets-table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nom du support</th>
            <th>Total disponible</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>

@section('adminlte_js')
    @parent

    <script type="text/javascript">
        var route = '{{ route('fleet.list.data') }}';
        var table = $('#fleets-table').DataTable( {
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
                localStorage.setItem("fleets", JSON.stringify(data));
            },
            stateLoadCallback: function (settings) {
                return JSON.parse(localStorage.getItem("fleets"));
            },
            drawCallback: function( settings ) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            ajax: {
                url: route,
                type: 'POST',
            },
            iDisplayLength: 10,
            showExportButton: false,
            columns: [
                {"name": "id", "visible":false},
                {"name": "name"},
                {"name": "total_available"},
                {"name": "state"},
                {"name": "actions", "orderable":false},
            ],
            "order": [[ 1, "asc" ]],
            fnRowCallback: function( row, data) {
                var indexId = 0;
                var indexState = 3;
                var indexTdState = 2;
                var indexRoute = 5;

                var state = '';
                if(data[indexState] === 'active'){
                    state += '<span class="badge bg-success">Actif</span>';
                }else {
                    state += '<span class="badge bg-danger">Inactif</span>';
                }

                var actions = '';
                if(data[indexState] === 'active') {
                    actions += '<i class="text-red fa fa-ban m-2 btn-disable" data-fleet-id="' + data[indexId] + '" style="cursor: pointer;"></i>';
                }else {
                    actions += '<i class="text-green fa fa-play m-2 btn-enable" data-fleet-id="' + data[indexId] + '" style="cursor: pointer;"></i>';
                }

                actions += '<a href="'+data[indexRoute]+'"><i class="text-blue fa fa-edit m-2" style="cursor: pointer;"></i></a>';

                $('td', row).eq(indexTdState).html(state);
                $('td', row).last().html(actions);
            }
        });
    </script>
@endsection