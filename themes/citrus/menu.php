<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Cambia la navegacion</span>
                <span class="fa-wrench">Configuraciones</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- You'll want to use a responsive image option so this logo looks good on devices - I recommend using something like retina.js (do a quick Google search for it and you'll find it) -->
            <a class="navbar-brand" href="index.html">Autoservicio Santa Lucia</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><?php echo anchor('pos/factura/presupuesto', '<span class="fa fa-shopping-cart"></span>') ?></li>
                <li><?php echo anchor('setting/', '<span class="fa fa-wrench"></span>') ?></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-money"></span> Puesto
                        Venta <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="portfolio-1-col.html">1 Column Portfolio</a></li>
                        <li><a href="portfolio-2-col.html">2 Column Portfolio</a></li>
                        <li><a href="portfolio-3-col.html">3 Column Portfolio</a></li>
                        <li><a href="portfolio-4-col.html">4 Column Portfolio</a></li>
                        <li><a href="portfolio-item.html">Single Portfolio Item</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-legal"></span>
                        I.V.A.<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="blog-home-1.html">Blog Home 1</a></li>
                        <li><a href="blog-home-2.html">Blog Home 2</a></li>
                        <li><a href="blog-post.html">Blog Post</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-truck"></span> Stock
                        <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="full-width.html">Articulos</a></li>
                        <li><a href="sidebar.html">Rubros/Subrubros</a></li>
                        <li><a href="faq.html">Marcas/Submarcas</a></li>
                        <li class="divider"></li>
                        <li><?php echo anchor('stock/busquedas', 'Busquedas') ?></li>
                        <li><?php echo anchor('stock/cambioPrecios', 'Cambio Precios') ?></li>
                        <li class="divider"></li>
                        <li><?php echo anchor('carteles/', 'Carteles/Listados') ?></li>
                        <li class="divider"></li>
                        <li><?php echo anchor('stock/inventario/', 'Inventario') ?></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-suitcase"></span>
                        Cta.Cte. <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="full-width.html">Full Width Page</a></li>
                        <li><a href="sidebar.html">Sidebar Page</a></li>
                        <li><a href="faq.html">FAQ</a></li>
                        <li><a href="404.html">404</a></li>
                        <li><a href="pricing.html">Pricing Table</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>