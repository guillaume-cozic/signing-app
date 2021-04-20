export default function notify(message, type = 'success') {
    $.notify({
        message: message
    },{
        type: type,
        z_index: 20000,
        delay: 3000,
        element: "#main-content"
    });
}
