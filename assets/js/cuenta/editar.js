$(document).ready(function () {
//controlo cuit
    valor = $("#cuit").val();
    estado = ControloCuit(valor);
    ValidoCuit(estado);
    //
    $('form').addClass('ui-widget');
    $("#tipo").buttonset();
    $("#ctacte").buttonset();
    var pos = $("#condiva_id").offset();
    $("#cuentaAdd input").offset({left: pos.left});
    $("#botBack").button();
    $("#botAccion").button();
    $("#tipdoc").change(function () {
        cambioLetra();
        valor = $("#cuit").val();
        estado = ControloCuit(valor);
        ValidoCuit(estado);
    });
    $("#condiva_id").change(function () {
        cambioLetra();
    });
    $("#tipo").change(function () {
        cambioLetra();
    });
    $("#botAccion").click(function () {
        $("form").submit();
    });
    $("#bot_CuitOK").button({icons: {primary: 'ui-icon-check'}});
    $("#bot_CuitOK").click(function () {
        valor = $("#cuit").val();
        estado = ControloCuit(valor);
        ValidoCuit(estado);
    });
});

function cambioLetra() {
    var tipo = (typeof $("#tipo1:checked").val() == "undefined") ? 2 : 1;
    var condi = $("#condiva_id").val();
    var tipdoc = $("#tipdoc").val();
    switch (tipo) {
        case 1:
            if (condi == '1') {
                $("#letra").val('A');
                $("#valorLetra").html('A');
            } else {
                $("#letra").val('B');
                $("#valorLetra").html('B');
            }
            ;
            break;
        case 2:
            switch (condi) {
                case '1':
                    $("#letra").val('A');
                    $("#valorLetra").html('A');
                    break;
                case '2':
                    $("#letra").val('B');
                    $("#valorLetra").html('B');
                    break;
                case '3':
                    $("#letra").val('C');
                    $("#valorLetra").html('C');
                    break;
                case '4':
                    $("#letra").val('C');
                    $("#valorLetra").html('C');
                    break;
                case '5':
                    $("#letra").val('ERROR');
                    $("#valorLetra").html('ERROR');
                    break;
                case '6':
                    $("#letra").val('ERROR');
                    $("#valorLetra").html('ERROR');
                    break;
            }
            ;
            break;
    }
    ;
}
function ControloCuit(cuit) {
    if (typeof (cuit) == 'undefined')
        return true;
    cuit = cuit.toString().replace(/[-_]/g, "");
    if (cuit == '')
        return true; //No estamos validando si el campo esta vacio, eso queda para el "required"
    if (cuit.length != 11)
        return false;
    else {
        var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        var total = 0;
        for (var i = 0; i < mult.length; i++) {
            total += parseInt(cuit[i]) * mult[i];
        }
        var mod = total % 11;
        var digito = mod == 0 ? 0 : mod == 1 ? 9 : 11 - mod;
    }
    return digito == parseInt(cuit[10]);
}
function ValidoCuit(estado) {
    tipo = $("#tipdoc").val();
    clase = $("#cuit").attr('class');
    $("#cuit").removeClass(clase);
    if (estado) {
        clase = 'ui-state-default';
    } else {
        if (tipo == '1') {
            clase = 'ui-state-default';
        } else {
            clase = 'ui-state-error';
        }
        ;
    }
    ;
    $("#cuit").addClass(clase);
}