/******/ (() => { // webpackBootstrap
/*!*******************************!*\
  !*** ./resources/js/embed.js ***!
  \*******************************/
var baseUrl = 'http://dev.signing.com:8002';
var xhr = new XMLHttpRequest();
xhr.open("GET", baseUrl + '/api/v1/fleet/rents');
xhr.send();

xhr.onload = function () {
  if (xhr.status != 200) {
    alert("Erreur " + xhr.status + " : " + xhr.statusText);
    return;
  }

  document.getElementById('div-rent').innerHTML = xhr.response;
};

xhr.onerror = function () {
  alert("La requête a échoué");
};
/******/ })()
;