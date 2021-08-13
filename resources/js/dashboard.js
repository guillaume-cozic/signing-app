import notify from "./notify";

window.tableBoatTrips = null;
$('[data-toggle="tooltip"]').tooltip({
    trigger : 'hover'
});

$('[data-toggle="tooltip"]').on('click', function () {
    $(this).tooltip('dispose');
});

$('#timepicker').datetimepicker({
    format:'H:mm',
});

$('#datetimepicker').datetimepicker({
    locale: 'fr',
    format:'YYYY-MM-DD H:mm',
    icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
    }
});

$('input[name=is_member]').change(function(){
    if($('input[name=is_member]').prop('checked') == true){
        $('input[name=is_instructor]').prop('checked', false);
    }
});

$('input[name=is_instructor]').change(function(){
    if($('input[name=is_instructor]').prop('checked') == true){
        $('input[name=is_member]').prop('checked', false);
    }
});



function initTimePicker(minutes) {
    var timestamp = Date.now() + minutes * 60 * 1000;
    var date = new Date(timestamp);
    var hours = date.getHours();
    var minutesD = "0" + date.getMinutes();
    var formattedTime = hours + ':' + minutesD.substr(-2);
    $('#timepicker > input').val(formattedTime);
}

initTimePicker(5);
$('#hour-start').change(function (){
    var minutes = $(this).val();
    initTimePicker(minutes);
});

$('#start_now').change(function (){
    if($(this).prop('checked') === true){
        $('.time-setter').fadeOut();
    }else{
        $('.time-setter').fadeIn();
    }
});

$('.btn-add-boat-trip').click(function(){
    $('.time-setter').show();
    $('#modal-add-boat-trip form').trigger('reset');
    $('#modal-add-boat-trip').modal('show');
});

$('.btn-add-boat-trip-reservation').click(function(){
    $('#modal-add-boat-trip-reservation').modal('show');
});

$('#availability').on('click', '.btn-add-boat-trip', function(){
    var fleetId = $(this).data('fleet-id');
    $('#modal-add-boat-trip .main-boat option[value='+fleetId+']').prop('selected', true);
    $('#modal-add-boat-trip').modal('show');
});

if($('#boat-trips-table').length != 0) {
    window.tableBoatTrips = $('#boat-trips-table').DataTable({
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
            $('[data-toggle="tooltip"]').tooltip({
                trigger : 'hover'
            });
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
            {"name": "start_at"},
            {"name": "should_return"},
            {"name": "return", 'orderable': false},
        ],
        "order": [[4, "asc"]],
        fnRowCallback: function (row, data) {}
    });


    $('.dashboard-datatable').on('click', '.btn-cancel', function () {
        var url = $(this).data('href');
        var body = "<div class=\"callout callout-danger\">Les sorties supprimées ne sont pas comptabilisées pas dans les différentes statistiques</div>";
        $.showConfirm({
            title: "Voulez vous vraiment supprimer cette sortie ?", body: body, textTrue: "Oui", textFalse: "Non",
            onSubmit: function (result) {
                if (result) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        success: function (){
                            tableBoatTrips.ajax.reload(null, false);
                            loadAvailability();
                            notify('La sortie a bien été supprimée');
                            loadSuggestions();
                        }
                    });
                }
            }
        });
    });
}

$('#boat-trips-table').on('click', '.btn-start', function () {
    var url = $(this).data('href');
    startBoatTrip(url);
});

$('.dashboard-datatable').on('click', '.btn-more', function () {
    var note = $(this).data('note');
    $.showConfirm({
        title: "Note diverse", body: note, textTrue: "Ok", textFalse: "fermer",
        onSubmit: function (result) {}
    });
});

function startBoatTrip(url)
{
    $.showConfirm({
        title: "Voulez vous vraiment démarrer cette sortie ?", body: "", textTrue: "Oui", textFalse: "Non",
        onSubmit: function (result) {
            if (result) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    success: function (){
                        tableBoatTrips.ajax.reload(null, false);
                        loadAvailability();
                        notify('La sortie a bien été démarée');
                        loadSuggestions();
                    }
                });
            }
        }
    });
}

function endBoatTrip(url)
{
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
                        notify('La sortie a bien été terminée');
                        loadSuggestions();
                    }
                });
            }
        }
    });
}

$('#boat-trips-table').on('click', '.btn-end', function () {
    var url = $(this).data('href');
    endBoatTrip(url);
});


$('#div-suggestion').on('click', '.btn-end', function () {
    var url = $(this).data('href');
    endBoatTrip(url);
});

$('#div-suggestion').on('click', '.btn-start', function () {
    var url = $(this).data('href');
    startBoatTrip(url);
});


var countBoatsList = 2;
$('.btn-add-boats').click(function (){
    var btn = $(this);
    $.ajax({
        url : $(this).data('href'),
        method: 'POST',
        data: {count:countBoatsList},
        success:function (data){
            btn.parents('.list-add-boat-trip').append(data);
            countBoatsList++;
        }
    });
});

$('.list-add-boat-trip').on('click', '.delete-boat', function (){
    $(this).parents('.row-boat-trip').remove();
});

function addBoatTrip(url, form) {
    $.ajax({
        url : url,
        method : 'POST',
        data : form.serialize(),
        dataType : 'json',
        success:function (data){
            tableBoatTrips.ajax.reload(null, false);
            tableBoatTripsReservations.ajax.reload(null, false);
            loadAvailability();
            $('#modal-add-boat-trip').modal('hide');
            $('#modal-add-boat-trip-reservation').modal('hide');
            form.trigger('reset');
            $('.row-boat-trip').html('');
            notify('La sortie a bien été créée');
            loadSuggestions();
            form.find('.alert-error-add-boat-trip').hide();
            form.find('.alert-boat-not-available').hide();
        },
        statusCode: {
            422: function (data) {
                var response = JSON.parse(data.responseText);
                var errorString = '<ul>';
                $.each(response.errors, function(key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                form.find('.alert-error-add-boat-trip').html(errorString);
                form.find('.alert-error-add-boat-trip').slideDown();
            },
            430: function (response){
                form.find('.alert-boat-not-available').slideDown();
            }
        },

    });
    return false;
}

$('.form-add-boat-trip').submit(function (){
    addBoatTrip($(this).attr('action'), $(this));
    return false;
});

$('.btn-force').click(function (){
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
            $('[data-toggle="tooltip"]').tooltip();
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

if($('#boat-trips-table-reservations').length != 0) {
    var tableBoatTripsReservations = $('#boat-trips-table-reservations').DataTable({
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
            localStorage.setItem("boattrips-reservations", JSON.stringify(data));
        },
        stateLoadCallback: function (settings) {
            return JSON.parse(localStorage.getItem("boattrips-reservations"));
        },
        drawCallback: function (settings) {
            $('[data-toggle="tooltip"]').tooltip({
                trigger : 'hover'
            });
        },
        ajax: {
            url: $('#boat-trips-table-reservations').data('href'),
            type: 'POST',
            data: {reservations:true}
        },
        iDisplayLength: 10,
        showExportButton: false,
        columns: [
            {"name": "boats", 'orderable': false},
            {"name": "total", 'orderable': false},
            {"name": "name"},
            {"name": "start_at"},
            {"name": "return", 'orderable': false},
            {"name": "actions", 'orderable': false},
        ],
        fnRowCallback: function (row, data) {}
    });
}

function loadSuggestions() {
    var route = $('#div-suggestion').data('href');
    $.ajax({
        url : route,
        success: function (data){
            $('#div-suggestion').html(data);
            var countSuggestions = $('#count-suggestions').val();
            $('.control-sidebar-id').html('<span class="badge badge-warning navbar-badge">'+countSuggestions+'</span>');
        }
    });
}
loadSuggestions();


function reloadMainDashboard() {
    if($('#boat-trips-table').length != 0) {
        loadAvailability();
        tableBoatTrips.ajax.reload(null, false);
    }
}

setInterval(function (){ loadSuggestions() },60*1000);
setInterval(function (){ reloadMainDashboard() },60*1000);

$('.control-sidebar-id').click(function(){
    if($('#boat-trips-table').length != 0) {
        $('#boat-trips-table').css('width', '100%');
        tableBoatTrips.draw();
    }
});

$('.autocomplete-sailor-name').autoComplete({
    resolverSettings: {
        url: 'sailors'
    },
    minLength:1,
    formatResult: function (item) {
        return {
            value: item.value,
            text:  item.text,
            html: [
                item.html
            ]
        };
    },
});

$('.autocomplete-sailor-name').on('autocomplete.select', function (e, i) {
    $('.input_sailor_id').val(i.value);
});

$('.autocomplete-sailor-name').on('change', function (e, i) {
    $('.input_sailor_id').val('');
});
