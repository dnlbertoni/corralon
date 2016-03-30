<nav class="navbar navbar-inverse" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Brand</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li><?php echo anchor('pos/', 'Home'); ?></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo 'Clientes - Eliminar'; ?><b
                        class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('clients/form', 'Agregar Cliente'); ?></li>
                    <li><?php echo anchor('clients/index', 'Ver Clientes'); ?></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo 'Ventas'; ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('ticket', 'Ticket'); ?></li>
                    <li><?php echo anchor('ticket', 'Cierre Z'); ?></li>
                    <li class="divider"></li>
                    <li><?php echo anchor('ticket', 'Factura  OFF LINE'); ?></li>
                    <li><?php echo anchor('ticket', 'NC OFF LINE'); ?></li>
                    <li><?php echo anchor('ticket', 'Cierre Z OFF LINE'); ?></li>
                    <li><?php echo anchor('quotes/index', 'Ver objetivos'); ?></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo 'Compras'; ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('facturas/add/10', 'Nueva Factura'); ?></li>
                    <li><?php echo anchor('facturas/add/13', 'Nueva NC'); ?></li>
                    <li><?php echo anchor('cuenta/crear', 'Nueva Proveedor'); ?></li>
                    <li><?php echo anchor('facturas/buscar', 'Ver Facturas'); ?></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo 'I.V.A'; ?><b
                        class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('iva', 'Resumen'); ?></li>
                    <li><?php echo anchor('iva/cierre', 'Cierre Perido'); ?></li>
                    <li><?php echo anchor('iva/libro', 'Libro IVA'); ?></li>
                    <li><?php echo anchor('iva/ingbru', 'Percepciones'); ?></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo 'Articulos'; ?><b
                        class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('articulos/', 'Resumen'); ?></li>
                    <li><?php echo anchor('articulos/estadisticas', 'Estadisticas'); ?></li>
                    <li class="divider"></li>
                    <li><?php echo anchor('articulos/rubros', 'Rubros'); ?></li>
                    <li><?php echo anchor('articulos/subrubros', 'Subrubros'); ?></li>
                    <li class="divider"></li>
                    <li><?php echo anchor('articulos/marcas', 'Marcas'); ?></li>
                    <li><?php echo anchor('articulos/submarcas', 'Submarcas'); ?></li>
                    <li class="divider"></li>
                    <li><?php echo anchor('articulos/subirListaAS', 'Lista de Precio '); ?></li>
                    <li><?php echo anchor('articulos/subirListaLS', 'Lista de Precios Sugeridos'); ?></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo 'Carteleria'; ?><b
                        class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('carteles/precios/1', 'Carteles Precios'); ?></li>
                    <li><?php echo anchor('carteles/precios/2', 'Carteles Vinos'); ?></li>
                    <li><?php echo anchor('carteles/navidad', 'Carteles Navidad'); ?></li>
                    <li class="divider"></li>
                    <li><?php echo anchor('carteles/ofertas/3', 'Oferta 3xHoja'); ?></li>
                    <li><?php echo anchor('carteles/ofertas/1', 'Oferta 1xHoja'); ?></li>
                    <li><?php echo anchor('carteles/ofertaMultiple', 'Oferta Multiple'); ?></li>
                    <li><?php echo anchor('carteles/ofertaEscrita', 'Oferta Texto Escrito'); ?></li>
                    <li class="divider"></li>
                    <li><?php echo anchor('carteles/listaDePrecios', 'Lista de Precios'); ?></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo 'CtaCte'; ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('reports/sales_by_client', 'Panel Informativo'); ?></li>
                    <li><?php echo anchor('reports/invoice_aging', 'Nuevo Cliente'); ?></li>
                    <li><?php echo anchor('reports/payment_history', 'Estadisticas'); ?></li>
                </ul>
            </li>
        </ul>
        <ul class="nav pull-right settings">
            <!--
            <li><a href="#"><?php echo 'Bienvenido' . ' ' . $this->session->userdata('user_name'); ?></a></li>
            <li class="divider-vertical"></li>
            -->
            <li><a href="http://docs.fusioninvoice.com/1.3/" target="_blank" class="tip icon"
                   data-original-title="Documentation" data-placement="bottom"><i class="icon-question-sign"></i></a>
            </li>
            <li class="divider-vertical"></li>
            <li class="dropdown">
                <a href="#" class="tip icon dropdown-toggle" data-toggle="dropdown"
                   data-original-title="<?php echo 'Config'; ?>" data-placement="bottom"><i class="icon-cog"></i></a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('email_templates/index', 'Email'); ?></li>
                    <li><?php echo anchor('tax_rates/index', 'Impuestos'); ?></li>
                    <li><?php echo anchor('users/index', 'Usuarios'); ?></li>
                    <li class="divider"></li>
                    <li><?php echo anchor('settings', 'Config. Sistema'); ?></li>
                </ul>
            </li>
            <!--
                    <li class="divider-vertical"></li>
                    <li><a href="<?php echo site_url('sessions/logout'); ?>" class="tip icon logout" data-original-title="<?php echo 'Salir'; ?>" data-placement="bottom"><i class="icon-off"></i></a></li>
            -->
        </ul>
    </div>
</nav>
