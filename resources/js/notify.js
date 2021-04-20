const notifySuccess = function notifySuccess(title, message) {
    $.notify({
        title: '<strong>'+title+'</strong>',
        message: message
    },{
        type: 'success',
        z_index: 20000
    });
}

export default {notifySuccess};
