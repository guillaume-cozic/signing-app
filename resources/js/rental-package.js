$('.fleets-select').select2({});

if($('#sailor-rental-package-table').length != 0) {
    var sailorRentalDatatable = $('#sailor-rental-package-table').DataTable({
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
            localStorage.setItem("sailor-rental-package", JSON.stringify(data));
        },
        stateLoadCallback: function (settings) {
            return JSON.parse(localStorage.getItem("sailor-rental-package"));
        },
        drawCallback: function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        ajax: {
            url: $('#sailor-rental-package-table').data('href'),
            type: 'POST',
        },
        iDisplayLength: 10,
        showExportButton: false,
        columns: [
            {"name": "sailor_name"},
            {"name": "rental_name"},
            {"name": "hours"},
            {"name": "actions", 'orderable': false},
        ],
        "order": [[1, "asc"]],
        fnRowCallback: function (row, data) {}
    });
}

$('.datatable').on('click', '.add-hours-to-sailor-rental', function(){
    $('#modal-sailor-rental-add-hours').modal('show');
    $('#form-sailor-rental-add-hours').trigger('reset');
    $('#form-sailor-rental-add-hours').attr('action', $(this).data('src'));
});

$('.datatable').on('click', '.decrease-hours-to-sailor-rental', function(){
    $('#modal-sailor-rental-decrease-hours').modal('show');
    $('#form-sailor-rental-decrease-hours').trigger('reset');
    $('#form-sailor-rental-decrease-hours').attr('action', $(this).data('src'));
});

$('#form-sailor-rental-add-hours').submit(function(){
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            sailorRentalDatatable.ajax.reload(null, false);
            $('#modal-sailor-rental-add-hours').modal('hide');
        }
    });
   return false;
});

$('#form-sailor-rental-decrease-hours').submit(function(){
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            sailorRentalDatatable.ajax.reload(null, false);
            $('#modal-sailor-rental-decrease-hours').modal('hide');
        }
    });
   return false;
});


