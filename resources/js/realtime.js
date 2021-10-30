if(showModalNotClosed == true) {
    $('#modal-message-boattrip-not-closed').modal('show');
}

$('[data-ajax-href]').each(function (){
    var item = $(this);
    var uri = item.attr('data-ajax-href');

    $.ajax({
        url: uri,
        success:function (html){
            item.html(html);
        }
    });
});

function reloadDashboard() {
    if($('#boat-trips-table').length != 0) {
        loadAvailability();
        tableBoatTrips.ajax.reload(null, false);
    }
}

import Echo from 'laravel-echo';

try {
    let echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    });

    echo.channel('notification')
        .listen('NotificationCreated', (e) => {
            reloadDashboard();
            $.notify({
                icon: e.avatar,
                title: e.title,
                message: e.message
            }, {
                type: 'minimalist',
                delay: 3000,
                icon_type: 'image',
                template: '<div data-notify="container" class="col-xs-6 col-sm-3 alert alert-{0}" role="alert">' +
                    '<img data-notify="icon" class="img-circle pull-left">' +
                    '<span data-notify="title">{1}</span>' +
                    '<span data-notify="message">{2}</span>' +
                    '</div>'
            });
        });
}catch (e){
    console.log('Server socket not running');
}
