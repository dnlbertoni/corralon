<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Configuraciones</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Area de Parametros
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                Actions
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li><a href="#">Action</a>
                                </li>
                                <li><a href="#">Another action</a>
                                </li>
                                <li><a href="#">Something else here</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    defino que es parametrizable
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel de parametros -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-cubes fa-fw"></i> Modulos
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                Acciones
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li><?php echo anchor('setting/addModulo', 'Nuevo Modulo', 'data-toggle="modal" data-target="#remoteModal"'); ?></a></li>
                                <li>Asignar Roles</li>
                                <li class="divider"></li>
                                <li><a href="#">Relacionar con Menues</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Nombre Clase</th>
                                        <th>Icono</th>
                                        <th>Tipo</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($modulosTable as $mod): ?>
                                        <tr>
                                            <td><?php echo $mod->id ?></td>
                                            <td><?php echo $mod->nombre ?></td>
                                            <td><?php echo $mod->clase ?></td>
                                            <td class="text-center"><span class="<?php echo $mod->clase ?>"></span></td>
                                            <td class="text-center">
                                                <?php
                                                switch ($mod->modo_texto) {
                                                    case 1:
                                                        if (trim($mod->clase) == '') {
                                                            $leyenda = "Solo Texto";
                                                            $clase = "label label-warning";
                                                        } else {
                                                            $leyenda = "Texto e Icono";
                                                            $clase = "label label-success";
                                                        }
                                                        break;
                                                    case 0:
                                                        if (trim($mod->clase) == '') {
                                                            $leyenda = "Faltan Definiciones";
                                                            $clase = "label label-danger";
                                                        } else {
                                                            $leyenda = "Solo Icono";
                                                            $clase = "label label-warning";
                                                        }
                                                        break;
                                                }; ?>
                                                <span class="<?php echo $clase ?>"><?php echo $leyenda ?></span>
                                            </td>
                                            <td>
                                                <?php echo anchor('setting/editModulo/' . $mod->id, '<span class="fa fa-edit fa-fw"></span>', 'class="btn btn-primary btn-xs" data-toggle="modal"
                                                        data-target="#remoteModal"'); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-12 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel de modulos -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-cube fa-fw"></i> Menues
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                Acciones
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li><?php echo anchor('setting/addMenu', 'Nuevo menu', 'data-toggle="modal" data-target="#remoteModal"'); ?></li>
                                </li>
                                <li><a href="#">Asignar a Roles</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Relacionar con Modulos</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre Modulo</th>
                                        <th>Nombre</th>
                                        <th>Icono</th>
                                        <th>Link</th>
                                        <th>Tipo</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($menuTable as $men): ?>
                                        <tr>
                                            <td><?php echo $men->id ?></td>
                                            <td><?php echo $men->nombreModulo ?></td>
                                            <td><?php echo $men->nombre ?></td>
                                            <td><span class="<?php echo $men->clase ?>"></span></td>
                                            <td><?php echo $men->link ?></td>
                                            <td>
                                                <?php
                                                if (trim($men->clase) == '') {
                                                    $leyenda = "Solo Texto";
                                                    $clase = "label label-warning";
                                                } else {
                                                    $leyenda = "Texto e Icono";
                                                    $clase = "label label-success";
                                                }; ?>
                                                <span class="<?php echo $clase ?>"><?php echo $leyenda ?></span>
                                            </td>
                                            <td><?php echo anchor('setting/editMenu/' . $men->id, '<span class="fa fa-edit fa-fw"></span>', 'class="btn btn-primary btn-xs" data-toggle="modal"
                                                        data-target="#remoteModal"'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-12 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel de menues -->
        </div>
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Impresoras
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach ($impresoras as $imp): ?>
                            <a href="#" class="list-group-item">
                                <i class="fa fa-comment fa-fw"></i> <?= $imp ?>
                                <span class="pull-right text-muted small"><em>4 minutes ago</em>
                              </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <!-- /.list-group -->
                    <a href="#" class="btn btn-default btn-block">View All Alerts</a>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            <div class="chat-panel panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-comments fa-fw"></i>
                    Seguridad y Permisos
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu slidedown">
                            <li>
                                <a href="#">
                                    <i class="fa fa-refresh fa-fw"></i> Refresh
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-check-circle fa-fw"></i> Available
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-times fa-fw"></i> Busy
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-clock-o fa-fw"></i> Away
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-sign-out fa-fw"></i> Sign Out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <ul class="chat">
                        <li class="left clearfix">
                              <span class="chat-img pull-left">
                                  <img src="http://placehold.it/50/55C1E7/fff" alt="User Avatar" class="img-circle"/>
                              </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">Jack Sparrow</strong>
                                    <small class="pull-right text-muted">
                                        <i class="fa fa-clock-o fa-fw"></i> 12 mins ago
                                    </small>
                                </div>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                    dolor, quis ullamcorper ligula sodales.
                                </p>
                            </div>
                        </li>
                        <li class="right clearfix">
                              <span class="chat-img pull-right">
                                  <img src="http://placehold.it/50/FA6F57/fff" alt="User Avatar" class="img-circle"/>
                              </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <small class=" text-muted">
                                        <i class="fa fa-clock-o fa-fw"></i> 13 mins ago
                                    </small>
                                    <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                </div>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                    dolor, quis ullamcorper ligula sodales.
                                </p>
                            </div>
                        </li>
                        <li class="left clearfix">
                              <span class="chat-img pull-left">
                                  <img src="http://placehold.it/50/55C1E7/fff" alt="User Avatar" class="img-circle"/>
                              </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">Jack Sparrow</strong>
                                    <small class="pull-right text-muted">
                                        <i class="fa fa-clock-o fa-fw"></i> 14 mins ago
                                    </small>
                                </div>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                    dolor, quis ullamcorper ligula sodales.
                                </p>
                            </div>
                        </li>
                        <li class="right clearfix">
                              <span class="chat-img pull-right">
                                  <img src="http://placehold.it/50/FA6F57/fff" alt="User Avatar" class="img-circle"/>
                              </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <small class=" text-muted">
                                        <i class="fa fa-clock-o fa-fw"></i> 15 mins ago
                                    </small>
                                    <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                </div>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                    dolor, quis ullamcorper ligula sodales.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- /.panel-body -->
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm"
                               placeholder="Type your message here..."/>
                          <span class="input-group-btn">
                              <button class="btn btn-warning btn-sm" id="btn-chat">
                                  Send
                              </button>
                          </span>
                    </div>
                </div>
                <!-- /.panel-footer -->
            </div>
            <!-- /.panel .chat-panel -->
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<!-- Modal Remoto -->
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
// Empty modal content when modal is closed
        $('#remoteModal').on('hidden.bs.modal', function () {
            $(this).removeData('bs.modal');
        });
    });
</script>
