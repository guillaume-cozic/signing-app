
$(document).ready(function() {
    $('.btn-add-boat-trip').click(function(){
        $('#modal-add-boat-trip').modal('show');
    });

    $('#availability').on('click', '.btn-add-boat-trip', function(){
        var fleetId = $(this).data('fleet-id');
        $('#main-boat option[value='+fleetId+']').prop('selected', true);
        $('#modal-add-boat-trip').modal('show');
    });

    if($('#boat-trips-table').length != 0) {
        var tableBoatTrips = $('#boat-trips-table').DataTable({
            processing: true,
            responsive: {
                details: {
                    type: 'inline',
                }
            },
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
                localStorage.setItem("boattrips", JSON.stringify(data));
            },
            stateLoadCallback: function (settings) {
                return JSON.parse(localStorage.getItem("boattrips"));
            },
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            ajax: {
                url: $('#boat-trips-table').data('href'),
                type: 'POST',
            },
            iDisplayLength: 10,
            showExportButton: false,
            columns: [
                {"name": "boats", 'orderable': false},
                {"name": "total", 'orderable': false},
                {"name": "name"},
                {"name": "should_return"},
                {"name": "return", 'orderable': false},
            ],
            "order": [[3, "asc"]],
            fnRowCallback: function (row, data) {}
        });


        $('#boat-trips-table').on('click', '.btn-cancel', function () {
            var url = $(this).data('href');
            $.showConfirm({
                title: "Voulez vous vraiment supprimer cette sortie ?", body: "", textTrue: "Oui", textFalse: "Non",
                onSubmit: function (result) {
                    if (result) {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            success: function (){
                                tableBoatTrips.ajax.reload(null, false);
                                loadAvailability();
                            }
                        });
                    }
                }
            });
        });

        $('#boat-trips-table').on('click', '.btn-end', function () {
            var url = $(this).data('href');
            $.showConfirm({
                title: "Terminer la sortie ?", body: "", textTrue: "Oui", textFalse: "Non",
                onSubmit: function (result) {
                    if (result) {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            success: function (){
                                tableBoatTrips.ajax.reload(null, false);
                                loadAvailability();
                            }
                        });
                    }
                }
            });
        });
    }

    var countBoatsList = 2;
    $('#btn-add-boats').click(function (){
        $.ajax({
            url : $(this).data('href'),
            method: 'POST',
            data: {count:countBoatsList},
            success:function (data){
                $('#list-add-boat-trip').append(data);
                countBoatsList++;
            }
        });
    });

    $('#modal-add-boat-trip').on('click', '.delete-boat', function (){
        $(this).parents('.row-boat-trip').remove();
    });

    $('#timepicker').datetimepicker({
        format: 'LT'
    });

    function addBoatTrip(url, form) {
        $.ajax({
            url : url,
            method : 'POST',
            data : form.serialize(),
            dataType : 'json',
            success:function (data){
                tableBoatTrips.ajax.reload(null, false);
                loadAvailability();
                $('#modal-add-boat-trip').modal('hide');
                form.trigger('reset');
                $('.row-boat-trip').html('');
                $.notify({
                    title: '<strong></strong>',
                    message: 'La sortie a bien été créée'
                },{
                    type: 'success',
                    z_index: 20000
                });
            },
            error:function (){
                $('#alert-boat-not-available').slideDown();
            }
        });
        return false;
    }

    $('#form-add-boat-trip').submit(function (){
        addBoatTrip($(this).attr('action'), $(this));
        return false;
    });

    $('#btn-force').click(function (){
        addBoatTrip($('#form-add-boat-trip').attr('action')+'/force', $('#form-add-boat-trip'));
        return false;
    });


    if($('#ended-boat-trips-table').length != 0) {
        var tableBoatTripsEnded = $('#ended-boat-trips-table').DataTable({
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
                localStorage.setItem("boattrips-ended", JSON.stringify(data));
            },
            stateLoadCallback: function (settings) {
                return JSON.parse(localStorage.getItem("boattrips-ended"));
            },
            drawCallback: function (settings) {
                //$('[data-toggle="tooltip"]').tooltip();
            },
            ajax: {
                url: $('#ended-boat-trips-table').data('href'),
                type: 'POST',
            },
            iDisplayLength: 10,
            showExportButton: false,
            columns: [
                {"name": "boats", 'orderable': false},
                {"name": "name"},
                {"name": "end_at"},
            ],
            "order": [[1, "asc"]],
            fnRowCallback: function (row, data) {}
        });
    }
});
