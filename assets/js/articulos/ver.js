$(document).ready(function () {
    Botones = $("div [id^='edit-']");
    Botones.button({icons: {primary: 'ui-icon-pencil'}});
    Botones.click(function () {
        valor = $(this).attr("id").split("-");
        inputVal = '#' + valor[1];
        $(inputVal).removeAttr('readonly');
        $(inputVal).removeAttr('disabled');
        $(inputVal).css("background-color", "#FFFF00");
        $(inputVal).focus();
        $(inputVal).addClass('focus');
    });
    $("#DESCRIPCION_ARTICULO").attr('size', '40');
    $("#detalle").attr('size', '40');
    $("#radio-iva").buttonset();
    $("#radio-estado-articulo").buttonset();
    $(".boton").button();
    $('select :disabled').css('font-color', '#000');
    $("#botSave").click(function () {
        $("#form-articulo").submit();
    });
    if (parseInt($("#nuevo").val()) == 1) {
        $('input').css('background-color', '#FFFF00');
        $('#DESCRIPCION_ARTICULO').focus();
    }
    ;
    $("#botDetalle").click(function () {
        generoNombre();
    });
    $("#botCopiar").click(function () {
        valor = $("#detalle").val();
        $("#DESCRIPCION_ARTICULO").val(valor);
    })
});

function generoNombre() {
    pagina = $("#paginaAjaxGenero").val();
    subrubro = $("#ID_SUBRUBRO").val();
    submarca = $("#ID_MARCA").val();
    especif = $("#especificacion").val();
    medida = $("#medida").val();
    $.ajax({
        url: pagina,
        contentType: "application/x-www-form-urlencoded",
        global: false,
        type: "POST",
        data: ({
            subrubro: subrubro,
            submarca: submarca,
            especif: especif,
            medida: medida,
        }),
        dataType: "html",
        async: true,
        success: function (msg) {
            $("#detalle").val(msg);
        }
    }).responseText;
}
