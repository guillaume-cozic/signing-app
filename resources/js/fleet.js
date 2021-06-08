if($('#fleets-table').length != 0) {
    var table = $('#fleets-table').DataTable({
        processing: true,
        responsive: true,
        serverSide: true,
        tabIndex: -1,
        "language": {
            "lengthMenu": "Afficher _MENU_ lignes par page",
            "zeroRecords": "Aucun résultat",
            "info": "",
            "infoEmpty": "Aucun résultat",
            "infoFiltered": "",
            "sSearch": "Rechercher",
            "sProcessing" : 'Chargement...',
            "oPaginate": {
                "sFirst": "Première",
                "sPrevious": "Précédent",
                "sNext": "Suivant",
                "sLast": "Dernière"
            },
        },
        stateSave: true,
        stateSaveCallback: function (settings, data) {
            localStorage.setItem("fleets", JSON.stringify(data));
        },
        stateLoadCallback: function (settings) {
            return JSON.parse(localStorage.getItem("fleets"));
        },
        drawCallback: function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        ajax: {
            url: $('#fleets-table').data('href'),
            type: 'POST',
        },
        iDisplayLength: 10,
        showExportButton: false,
        columns: [
            {"name": "id", "visible": false},
            {"name": "name"},
            {"name": "total_available"},
            {"name": "state"},
            {"name": "actions", "orderable": false},
        ],
        "order": [[1, "asc"]],
        fnRowCallback: function (row, data) {
            var indexId = 0;
            var indexState = 3;
            var indexTdState = 2;
            var indexRoute = 5;
            var indexRouteEnable = 6;
            var indexRouteDisable = 7;

            var state = '';
            if (data[indexState] === 'active') {
                state += '<span class="badge bg-success">Actif</span>';
            } else {
                state += '<span class="badge bg-danger">Inactif</span>';
            }

            var actions = '';
            if (data[indexState] === 'active') {
                actions += '<i class="text-red fa fa-ban m-2 btn-disable" ' +
                    'data-toggle="tooltip" data-placement="top" title="Désactiver la flotte"' +
                    'data-href="'+data[indexRouteDisable]+'" data-fleet-id="' + data[indexId] + '" style="cursor: pointer;"></i>';
            } else {
                actions += '<i class="text-green fa fa-play m-2 btn-enable" ' +
                    'data-toggle="tooltip" data-placement="top" title="Activer la flotte"' +
                    'data-href="'+data[indexRouteEnable]+'" data-fleet-id="' + data[indexId] + '" style="cursor: pointer;"></i>';
            }

            actions += '<a href="' + data[indexRoute] + '">' +
                '<i ' +
                'data-toggle="tooltip" data-placement="top" title="Modifier la flotte"' +
                'class="text-blue fa fa-edit m-2" style="cursor: pointer;"></i></a>';

            $('td', row).eq(indexTdState).html(state);
            $('td', row).last().html(actions);
        }
    });


    $('#fleets-table').on('click', '.btn-disable', function(){
        var fleetId = $(this).data('fleet-id');
        var url = $(this).data('href');
        $.showConfirm({
            title: "Voulez vous vraiment désactiver cette flotte ?",
            body: "<div class='alert alert-info'>Cette flotte ne sera plus disponible lors de la création d'une sortie en mer.</div>",
            textTrue: "Oui", textFalse: "Non",
            onSubmit: function (result) {
                if (result) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {fleet_id:fleetId},
                        success: function (){
                            table.ajax.reload(null, false);
                        }
                    });
                }
            }
        });
    });

    $('#fleets-table').on('click', '.btn-enable', function(){
        var fleetId = $(this).data('fleet-id')
        var url = $(this).data('href');

        $.showConfirm({
            title: "Voulez vous vraiment activer cette flotte ?",
            body:"",
            textTrue: "Oui",
            textFalse: "Non",
            onSubmit: function (result) {
                if (result) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {fleet_id:fleetId},
                        success: function (){
                            table.ajax.reload(null, false);
                        }
                    });
                }
            }
        });
    });
}

$("#fileinput").change(function(){
    console.log('hey');
    var fd = new FormData();
    var files = $('#fileinput')[0].files;

    if(files.length > 0 ){
        fd.append('picture-fleet', files[0]);
        $.ajax({
            url: routeUpload,
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response != 0){
                    $("#picture-fleet").attr("src", response);
                }
            },
        });
    }
});
