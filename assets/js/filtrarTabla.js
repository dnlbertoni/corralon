jQuery.fn.tableFilter = function () {
    //agrego una fila dentro del thead
    var tabla = jQuery(this);

    tabla.find("thead").append('<tr class="row-filtros"></tr>');
    var filaFiltro = tabla.find("thead > tr.row-filtros");
    var nroColumna = 0;
    var filtrosAplicados = new Object();

    tabla.find("thead > tr:first > th").each(function () {
        nroColumna++;
        var celdaFiltro = document.createElement("th");
        filaFiltro.append(celdaFiltro);

        var select = document.createElement("select");
        jQuery(select).addOption("", "Todos");
        jQuery(select).addClass("columna-filtros-" + nroColumna);

        tabla.find("tbody > tr").each(function () {
            var iterCelda = jQuery(this).find("td:nth-child(" + nroColumna + ")");
            jQuery(select).addOption(iterCelda.text(), iterCelda.text());
        });
        jQuery(select).selectOptions("");

        jQuery(select).change(function () {
            // a todos los demas selects les selecciono la opcion Todos
            var selectObj = this;
            /*
             filaFiltro.find("select").each(function(){
             if(jQuery(selectObj).attr('class')!=jQuery(this).attr('class'))
             jQuery(this).selectOptions("");

             });*/
            var valorDeFiltro = jQuery(this).val();
            var clase = new String(jQuery(this).attr('class'));
            var filteringColumn = clase.replace("columna-filtros-", "");
            /** me guardo el valor seleccionado junto con el nro de columna **/
            if (valorDeFiltro == "") {
                delete filtrosAplicados[filteringColumn];
            } else {
                filtrosAplicados[filteringColumn] = valorDeFiltro;
            }

            /*if(filtrosAplicados.length > 0){*/
            tabla.find("tbody > tr").each(function () {
                var matchea = 0;
                var cantidadFiltros = 0;
                for (var nroFila in filtrosAplicados) {
                    cantidadFiltros++;
                    if (jQuery(this).find("td:nth-child(" + nroFila + ")").text() == filtrosAplicados[nroFila]) {
                        matchea++;
                    }
                }
                if (matchea == cantidadFiltros) {
                    jQuery(this).show();
                } else {
                    jQuery(this).hide();
                }
            });
            /*}else{
             tabla.find("tbody > tr").each(function(){ jQuery(this).show()});
             }*/
        });


        jQuery(celdaFiltro).append(select);
    });
}
