<div>
    <table class="table table-striped" id="boat-trips-table">
        <thead>
            <tr>
                <th>Bateaux</th>
                <th>Nom</th>
                <th>Retour</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@section('adminlte_js')
    @parent

    <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">

        var route = '{{ route('boat-trips.list.data') }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var tableBoatTrips = $('#boat-trips-table').DataTable( {
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
                url: route,
                type: 'POST',
            },
            iDisplayLength: 10,
            showExportButton: false,
            columns: [
                {"name": "boats", 'orderable': false },
                {"name": "name"},
                {"name": "should_return"},
                {"name": "return", 'orderable': false },
            ],
            "order": [[ 1, "asc" ]],
            fnRowCallback: function(row, data) {}
        });


        $('#boat-trips-table').on('click', '.btn-cancel', function (){
            if(!confirm('Voulez vous vraiment supprimer cette sortie ?')){
                return;
            }
            $.ajax({
                url: $(this).data('href'),
                dataType: ' json',
                method: 'post',
                success:function (data){
                    tableBoatTrips.ajax.reload(null, false);
                }
            });
        });

        $('#boat-trips-table').on('click', '.btn-end', function (){
            if(!confirm('Terminer la sortie ?')){
                return;
            }
            $.ajax({
                url: $(this).data('href'),
                dataType: ' json',
                method: 'post',
                success:function (data){
                    tableBoatTrips.ajax.reload(null, false);
                }
            });
        });
    </script>
@endsection
