import notify from "./notify";

$('.fleets-select').select2({
    theme: 'classic',
});

$('.html-datatable').dataTable({
    responsive: true,
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
});

$('.add-sailor-rental-package').click(function(){
    var divError = $('#modal-sailor-rental-create .alert-error');
    divError.html('');
    divError.hide();
    var rentalPackageId = $(this).data('rental-package-id');
    $('#rental-package option[value='+rentalPackageId+']').prop('selected', true);
    $('#modal-sailor-rental-create').modal('show');
});

$('#modal-sailor-rental-create form').submit(function (){
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        data: form.serialize(),
        type: 'POST',
        success:function (data){
            notify('Le forfait client a bien été créé');
            $('#modal-sailor-rental-create').modal('hide');
            form.trigger('reset');
        },
        statusCode: {
            422: function (data) {
                var response = JSON.parse(data.responseText);
                var errorString = '<ul>';
                $.each(response.errors, function (key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                form.find('.alert-error').html(errorString);
                form.find('.alert-error').slideDown();
            }
        }
    })
    return false;
});

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
            $('[data-toggle="tooltip"]').tooltip({
                trigger : 'hover'
            });
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


