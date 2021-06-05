window.$ = window.jQuery = require('jquery');

var baseUrl = 'http://dev.signing.com:8002';

$(function () {
    $.ajax({
        url:baseUrl + '/api/v1/fleet/rent',
        success(data){
            $('#div-rent').html(data);
        }
    });
});
