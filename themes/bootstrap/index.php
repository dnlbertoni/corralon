<!doctype html>
<head>
    <meta charset="utf-8">
    <!-- Use the .htaccess and remove these lines to avoid edge case issues -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Autoservico Santa Lucia</title>
    <meta name="description" content="">
    <meta name="author" content="danielBertoni">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php echo Assets::js('jquery.min') ?>
    <?php echo Assets::js('bootstrap.min') ?>
    <?php echo Assets::css('bootstrap.min') ?>
    <?php echo Assets::js() ?>
    <?php echo Assets::css() ?>
</head>

<body>
<?php echo Template::block('navegacion', 'barraNav'); ?>
<div class="sidebar">
    <ul>
        <li><a href="<?php echo site_url('dashboard'); ?>"><img alt=""
                                                                src="<?php echo base_url(); ?>themes/light/img/icons/dashboard24x24.png"
                                                                title="<?php echo 'Home'; ?>"/></a></li>
        <li><a href="<?php echo site_url('clients/index'); ?>"><img alt=""
                                                                    src="<?php echo base_url(); ?>themes/light/img/icons/clients24x24.png"
                                                                    title="<?php echo 'Clientes'; ?>"/></a></li>
        <li><a href="<?php echo site_url('quotes/index'); ?>"><img alt=""
                                                                   src="<?php echo base_url(); ?>themes/light/img/icons/quotes24x24.png"
                                                                   title="<?php echo 'Objetivos'; ?>"/></a></li>
        <li><a href="<?php echo site_url('invoices/index'); ?>"><img alt=""
                                                                     src="<?php echo base_url(); ?>themes/light/img/icons/invoices24x24.png"
                                                                     title="<?php echo 'Facturas'; ?>"/></a></li>
        <li><a href="<?php echo site_url('payments/index'); ?>"><img alt=""
                                                                     src="<?php echo base_url(); ?>themes/light/img/icons/payments24x24.png"
                                                                     title="<?php echo 'Pagos'; ?>"/></a></li>
    </ul>
</div>
<div class="main-area">
    <div id="ventanaAjax"></div>
    <?php echo Template::yield(); ?>
</div>
<!--end.content-->
</body>
</html>