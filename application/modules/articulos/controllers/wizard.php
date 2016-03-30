<?php

/*
 * Controlador cabecera del modulo articulos
 * FEATURES:
 *
 */

class Wizard extends MY_Controller
{
    private $CB;
    private $idEmpresa;
    private $Articulo;
    private $ruta;
    private $Venta;
    private $Masivo = false; //variable usada para determinar si vuelvo o no a la pagina de arreglo masivo

    function __construct()
    {
        parent::__construct();
        $this->load->model('Articulos_model');
        $this->load->model('Empresas_model');
        $this->load->model('Wizard_model');
        $this->load->model('Rubros_model');
        $this->load->model('Subrubros_model', '', TRUE);
        $this->load->model('Marcas_model', '', TRUE);
        $this->load->model('Submarcas_model', '', TRUE);
        $this->load->model('Facmovim_model');
        //$this->output->enable_profiler(true);
    }

    function index($CB = false)
    {
        //routeo los pasos del wizard
        //$CB="'".$CB."'";
        if (!$CB) {
            $CB = $this->input->post('codigobarra');
        } else {
            $this->Masivo = true;
        }
        //$CB='7790150260609'; // no existe -> mate cocido la virginia en saquitos
        //$CB='7506195176733'; //existe ->DETERGENT MAGISTRAL MARINA X600ML
        //$CB = '7790040377806';  //existe -> CRIOLLITAS X 3 X 300 G
        $this->_getArticulo($CB);
        if ($CB) {
            $this->CB = $CB;
            $this->decrypCodigoBarra($this->CB);
        } else {
            if ($this->input->post('codigobarra')) {
                $this->CB = $this->input->post('codigobarra');
                $this->decrypCodigoBarra($this->CB);
            } else {
                $this->Cancelar();
            };
        };
    }

    function Cancelar()
    {
        Template::redirect('articulos/');
    }

    function decrypCodigoBarra($codigobarra)
    {
        $CB = trim($codigobarra);
        if (strlen($CB) == 13) {
            $idEmpresa = substr($CB, 0, 7);
        } else {
            $idEmpresa = substr($CB, 0, 5);
        }
        $empresa = $this->Empresas_model->getById($idEmpresa);
        if ($empresa) {
            $this->idEmpresa = $empresa->id;
            $this->ruta = 1; //camino primero por marca
            $this->definoMarca();
        } else {
            $this->idEmpresa = false;
            $this->ruta = 0; //camino primero por rubro
            $this->definoRubro();
        }
    }

    function definoRubro()
    {
        if ($this->input->post('CB')) {
            $this->CB = $this->input->post('CB');
        };
        $this->_getArticulo($this->CB);
        $CodigoB = $this->Empresas_model->getRubrosFromCodigobarra($this->idEmpresa);
        $totalSubrubro = 0;
        $aux = false;
        foreach ($CodigoB as $c) {
            if ($aux != $c->rubroId) {
                $totalRubro[$c->rubroId] = 0;
                $aux = $c->rubroId;
                $totalRubro[$c->rubroId] += $c->cantidad;
            } else {
                $totalRubro[$c->rubroId] += $c->cantidad;
            }
            $totalSubrubro += $c->cantidad;
        }
        foreach ($CodigoB as $c) {
            $c->acierto = sprintf("--> %2.2f ", $totalRubro[$c->rubroId] / $totalSubrubro * 100);
        }
        $data['accion'] = "articulos/wizard/definoRubroDo";
        $data['ocultos'] = array('CB' => $this->CB,
            'empresa' => $this->idEmpresa,
            'tipo' => 'id_subrubro',
            'valor' => $this->Articulo->ID_SUBRUBRO,
            'ruta' => $this->ruta,
            'masivo' => $this->Masivo
        );
        $data['tit'] = "Definicion del Rubro del Producto";
        $data['sugeridos'] = $CodigoB;
        $data['todos'] = $this->Empresas_model->getAllConRubrosExcluidos($this->idEmpresa);
        $data['ventas'] = count($this->Venta);
        $data['ultimaVenta'] = $this->Venta[0]->fecha;
        Template::set('articulo', $this->Articulo);
        Template::set('idMaster', 'rubroId');
        Template::set('idMov', 'subrubroId');
        Template::set('nombreMaster', 'rubroNombre');
        Template::set('nombreMov', 'subrubroNombre');
        Template::set('textoAsignar', '<span id="tipo">SUBRURBO</span> : ( <span id="codigo">' . $this->Articulo->ID_SUBRUBRO . '</span> ) <span id="nombre">' . $this->Articulo->DESCRIPCION_SUBRUBRO . '</span>');
        $rubros = $this->Rubros_model->ListaSelect();
        array_push($rubros, array('O' => 'Otro Rubro NUEVO'));
        $data['maestroSel'] = $rubros;
        Template::set('accionMov', 'articulos/subrubros/agregarDo/ajax');
        Template::set('maestro', 'rubros');
        Template::set('maestroAux', 'rubro');
        Template::set('descripcion', 'descripcion');
        Template::set('alias', 'alias');
        Template::set('otro', 'otro');
        Template::set($data);
        Template::set_block('ninguno', 'articulos/wizard/ninguna');
        Template::set_block('sugeridos', 'articulos/wizard/sugeridos');
        Template::set_block('todos', 'articulos/wizard/todosEASY');
        Template::set_view('articulos/wizard/detalle');
        Template::render();
    }

    function definoRubroDo()
    {
        $this->CB = $this->input->post('CB');
        $this->idEmpresa = substr($this->CB, 0, 7);
        $this->Articulos_model->updateArticulo($this->input->post('CB'), $this->input->post('tipo'), $this->input->post('valor'));
        $this->ruta = $this->input->post('ruta');
        $this->Masivo = $this->input->post('masivo');
        if ($this->input->post('ruta') == 1) {
            $this->definoDetalle();
        } else {
            $this->definoMarca();
        };
    }

    function definoMarca()
    {
        if ($this->input->post('CB')) {
            $this->CB = $this->input->post('CB');
        };
        $this->_getArticulo($this->CB);
        $CodigoB = $this->Empresas_model->getMarcasFromCodigobarra($this->idEmpresa);
        $totalSubmarca = 0;
        $aux = false;
        foreach ($CodigoB as $c) {
            if ($aux != $c->marcaId) {
                $totalMarca[$c->marcaId] = 0;
                $aux = $c->marcaId;
                $totalMarca[$c->marcaId] += $c->cantidad;
            } else {
                $totalMarca[$c->marcaId] += $c->cantidad;
            }
            $totalSubmarca += $c->cantidad;
        }
        foreach ($CodigoB as $c) {
            $c->aciertoSubmarca = sprintf("--> %2.2f ", $c->cantidad / $totalSubmarca * 100);
            $c->acierto = sprintf("--> %2.2f ", $totalMarca[$c->marcaId] / $totalSubmarca * 100);
        }
        $data['ocultos'] = array('CB' => $this->CB,
            'empresa' => $this->idEmpresa,
            'tipo' => 'id_marca',
            'valor' => $this->Articulo->ID_SUBMARCA,
            'ruta' => $this->ruta,
            'masivo' => $this->Masivo
        );
        $data['accion'] = "articulos/wizard/definoMarcaDo";
        $data['tit'] = "Definicion de la Marca del Producto";
        $data['sugeridos'] = $CodigoB;
        $data['todos'] = $this->Empresas_model->getAllConMarcasExcluidas($this->idEmpresa);
        Template::set('articulo', $this->Articulo);
        Template::set('idMaster', 'marcaId');
        Template::set('idMov', 'submarcaId');
        Template::set('nombreMaster', 'marcaNombre');
        Template::set('nombreMov', 'submarcaNombre');
        $marcas = $this->Marcas_model->toDropDown('id_marca', 'detalle_marca');
        array_push($marcas, array('O' => 'Otra Marca NUEVA'));
        $data['maestroSel'] = $marcas;
        $data['ultimaVenta'] = $this->Venta[0]->fecha;
        $data['ventas'] = count($this->Venta);
        Template::set('accionMov', 'articulos/submarcas/agregarDo/ajax');
        Template::set('maestro', 'marcas');
        Template::set('maestroAux', 'marca');
        Template::set('descripcion', 'descripcion');
        Template::set('alias', 'alias');
        Template::set('otro', 'otra');
        Template::set($data);
        Template::set('textoAsignar', '<span id="tipo">SUBMARCA</span> : ( <span id="codigo">' . $this->Articulo->ID_SUBMARCA . '</span> ) <span id="nombre">' . $this->Articulo->DETALLE_SUBMARCA . '</span>');
        Template::set_block('sugeridos', 'articulos/wizard/sugeridos');
        Template::set_block('todos', 'articulos/wizard/todosEASY');
        Template::set_block('ninguno', 'articulos/wizard/ninguna');
        Template::set_view('articulos/wizard/detalle');
        Template::render();
    }

    function definoMarcaDo()
    {
        $this->CB = $this->input->post('CB');
        $this->idEmpresa = substr($this->CB, 0, 7);
        $this->Articulos_model->updateArticulo($this->input->post('CB'), $this->input->post('tipo'), $this->input->post('valor'));
        $this->Masivo = $this->input->post('masivo');
        $this->ruta = $this->input->post('ruta');
        if ($this->input->post('ruta') == 1) {
            $this->definoRubro();
        } else {
            $this->definoDetalle();
        };
    }

    function definoDetalle()
    {
        if ($this->input->post('CB')) {
            $this->CB = $this->input->post('CB');
        };
        $this->_getArticulo($this->CB);
        $data['ocultos'] = array('CB' => $this->CB,
            'id' => $this->Articulo->ID_ARTICULO,
            'masivo' => $this->Masivo
        );
        $data['accion'] = "articulos/wizard/definoDetalleDo";
        $data['tit'] = "Definicion de las Caracteristicas Extras";
        $data['medidas'] = array('NADA', 'GR', 'CM3', 'UNID');
        $data['palabrasClaves'] = array('NADA', 'tradicional', 'diet', 'light', 'suave', 'fuerte', 'normal', 'clasico', 'extra');
        $data['palabrasClavesRubro'] = $this->Articulos_model->getKeyWords($this->Articulo->ID_SUBRUBRO);
        $data['palabrasClavesMedida'] = $this->Articulos_model->getKeyUnits($this->Articulo->ID_SUBRUBRO);
        Template::set($data);
        Template::set('articulo', $this->Articulo);
        Template::set_view('articulos/wizard/detalleExt');
        Template::render();
    }

    function definoDetalleDo()
    {
        $this->CB = $this->input->post('CB');
        $this->Masivo = $this->input->post('masivo');
        $this->idEmpresa = substr($this->CB, 0, 7);
        $medida = trim($this->input->post('medida'));
        $medidas = ($medida != '') ? strtoupper($medida) . ' ' . strtoupper(($this->input->post('medidas') == 'OTRO') ? '' : $this->input->post('medidas')) : '';
        $datos = array('descripcion_articulo' => $this->input->post('detalle'),
            'detalle' => $this->input->post('detalle'),
            'especificacion' => strtoupper($this->input->post('especificacion')),
            'medida' => $medidas
        );
        $this->Articulos_model->updateMod($this->input->post('id'), $datos);
        $this->definoPrecio();
    }

    function definoPrecio()
    {
        if ($this->input->post('CB')) {
            $this->CB = $this->input->post('CB');
        };
        $this->_getArticulo($this->CB);
        $data['ocultos'] = array('CB' => $this->CB,
            'id' => $this->Articulo->ID_ARTICULO,
            'tipo' => 'preciovta_articulo',
            'valor' => '',
            'masivo' => $this->Masivo
        );
        $data['accion'] = "articulos/wizard/definoPrecioDo";
        $data['tit'] = "Definicion del Precio";
        $data['markupRubros'] = $this->Articulos_model->getMarkupRubro($this->Articulo->ID_SUBRUBRO);
        Template::set($data);
        Template::set('articulo', $this->Articulo);
        Template::set_view('articulos/wizard/detallePrecio');
        Template::render();
    }

    function definoPrecioDo()
    {
        $this->CB = $this->input->post('CB');
        $this->Masivo = $this->input->post('masivo');
        $this->idEmpresa = substr($this->CB, 0, 7);
        $datos = array('preciovta_articulo' => $this->input->post('preciovta_articulo'),
            'tasaiva_articulo' => $this->input->post('TASAIVA_ARTICULO'),
            'preciocosto_articulo' => $this->input->post('preciocosto_articulo'),
            'markup_articulo' => $this->input->post('markup_articulo')
        );
        $this->Articulos_model->updateMod($this->input->post('id'), $datos);
        $this->Articulos_model->updateArticulo($this->CB, 'wizard', 1);
        $this->end();
    }

    function end($parametro = false)
    {
        if ($parametro) {
            $this->Masivo = $parametro;
        }
        if ($this->Masivo) {
            $this->masivo();
        } else {
            Template::redirect('articulos/');
        }
    }

    function _getArticulo($CB)
    {
        $arti = $this->Articulos_model->getByCodigobarra($CB);
        $this->Venta = $this->Facmovim_model->getByCodigobarra($CB);
        if ($arti) {
            $articulo = $this->Articulos_model->getByIdFull($arti->ID_ARTICULO);
            $this->Articulo = $articulo;
        } else {
            $this->Articulo = $this->Articulos_model->Inicializar();
            $this->Articulo->CODIGOBARRA_ARTICULO = $CB;
            $this->Articulo->empresa = $this->idEmpresa;
            $this->Articulo->wizard = 1;
            $datos = (array)$this->Articulo;
            $id = $this->Articulos_model->add($datos);
            $articulo = $this->Articulos_model->getByIdFull($id);
            $this->Articulo = $articulo;
        }
        $this->Articulos_model->updateArticulo($CB, 'empresa', $this->idEmpresa);
    }

    function masivo($orden = 'nombre')
    {
        $articulos = $this->Articulos_model->filtroWizard($orden);
        $progreso = $this->Articulos_model->getStatusWizard();
        $diasDetalle = $this->Wizard_model->getWizardDias();
        $semanas = $this->Wizard_model->getWizardSemanas();
        $hoy = new DateTime();
        $dias = (count($articulos) / ($diasDetalle->cantidad / $diasDetalle->dias));
        $format = "+" . intval($dias) . " days";
        $hoy->modify($format);
        Template::set('semanas', $semanas);
        Template::set('dd', $diasDetalle);
        Template::set('fechaAdivinada', $hoy->format('d/m/Y'));
        Template::set('progreso', $progreso);
        Template::set('articulos', $articulos);
        Template::set_view('articulos/wizard/masivo');
        Template::render();
    }

    function outWizard($codigo)
    {
        if ($codigo) {
            $this->CB = $codigo;
        };
        $this->Articulos_model->updateArticulo($this->CB, 'wizard', 2);
        Template::redirect('articulos/wizard/masivo');
    }
}
