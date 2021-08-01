/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

function reloadDashboard() {
    if($('#boat-trips-table').length != 0) {
        loadAvailability();
        tableBoatTrips.ajax.reload(null, false);
    }
}

import Echo from 'laravel-echo'


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
