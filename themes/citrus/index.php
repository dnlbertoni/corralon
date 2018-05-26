<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="DnL">

    <title>Sistema de Gestion de <?php echo $NombreEmpresa?></title>

    <!-- Bootstrap core CSS -->
    <link href="/themes/citrus/screen.css" rel="stylesheet">
    <link href="/themes/citrus/css/bootstrapCitrus.css" rel="stylesheet">
    <link href="/assets/css/bootstrap-switch.min.css" rel="stylesheet">
    <!-- Add custom CSS here -->
    <link href="/themes/citrus/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- JavaScript -->
    <script src="/themes/citrus/js/jquery2.js"></script>
    <script src="/themes/citrus/js/bootstrapCitrus.js"></script>
    <script src="/assets/js/bootstrap-switch.min.js"></script>


</head>

<body>
<!-- menu navegacion -->
<?php echo Template::block('menu', 'barra') ?>


<div class="container">
    <div class="section">
        <?php echo Template::yield1 (); ?>
    </div>
    <footer>
        <hr/>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; 2016</p>
            </div>
        </div>
    </footer>
</div><!-- /.container -->

</body>
</html>
