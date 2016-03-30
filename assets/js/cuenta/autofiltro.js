$(document).ready(function () {
    //$("input:text:visible:first").focus();
    var TEXTO = $("#nombreTXT");
    TEXTO.focus();
    TEXTO.change(function () {
        $('#buscoNombre').submit();
    });
});
