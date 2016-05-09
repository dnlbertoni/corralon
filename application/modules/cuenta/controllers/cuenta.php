<?php

/**
 * Class Cuenta
 *
 * @property  Cuenta_model $Cuenta_model
 */
class Cuenta extends Admin_Controller {
    function __construct () {
        parent::__construct();
        $this->load->model ( 'Cuenta_model' );
    }

    function index($tipo = false)
    {
        $data['cuentas'] = $this->Cuenta_model->getIndex($tipo);
        if ( $tipo ) {
            $data['title'] = ( $tipo == 1 ) ? "Clientes" : "Proveedores";
        } else {
            $data['title'] = "Clientes & Proveedores";
        }
        Template::set($data);
        Template::render();
    }

    function crear () {
        $this->output->enable_profiler ( false );
        // set configurar varaibles comunes
        $data['title'] = 'Agregar Cuenta';
        $data['message'] = '';
        $data['action'] = site_url('cuenta/crearDo');
        $data['link_back'] = 'cuenta/index/';
        // load view
        $cuenta_data = array('condiva_id' => 1,
            'nombre' => '',
            'datos_fac' => 0,
            'nombre_facturacion' => '',
            'cuit' => 0,
            'tipdoc' => 1,
            'direccion' => '',
            'telefono' => '',
            'email' => '',
            'tipo' => 1,
            'estado' => 1,
            'ctacte' => 0,
            'letra' => 'B');
        $data['cuenta'] = (object)$cuenta_data;
        $this->load->model('Condiva_model', '', true);
        $data['accion'] = 'cuenta/crearDo';
        $data['condiva'] = $this->Condiva_model->toDropDown();
        $this->load->view ( 'cuenta/editar', $data );
    }

    function crearDo()
    {
        $data['title'] = 'Agregar Cuenta';
        $data['action'] = site_url('cuenta/crear');
        $data['link_back'] = anchor('cuenta/index/', 'Volver a la Lista de Cuentas');
        $datos = array('condiva_id' => $this->input->post('condiva_id'),
            'nombre' => strtoupper($this->input->post('nombre')),
            'datos_fac' => strtoupper($this->input->post('datos_fac')),
            'nombre_facturacion' => strtoupper($this->input->post('nombre_facturacion')),
            'cuit' => $this->input->post('cuit'),
            'tipdoc' => $this->input->post('tipdoc'),
            'direccion' => strtoupper($this->input->post('direccion')),
            'telefono' => $this->input->post('telefono'),
            'email' => strtolower($this->input->post('email')),
            'tipo' => $this->input->post('tipo'),
            'letra' => $this->input->post('letra'),
            'ctacte' => $this->input->post('ctacte'),
            'estado' => $this->input->post('estado'));
        $id = $this->Cuenta_model->save($datos);
        $data['cuenta'] = (object)$datos;
        $this->load->model('Condiva_model', '', true);
        $data['condiva'] = $this->Condiva_model->toDropDown();
        // set user message
        $data['message'] = '<div class="success">Nueva Cuenta Creada Con exito</div>';
        Template::set($data);
        Template::set_view('cuenta/editar');
        Template::render();
    }

    function editar($id)
    {
        $this->load->model('Condiva_model', '', true);
        $cuenta = $this->Cuenta_model->getById($id);
        $data['condiva'] = $this->Condiva_model->toDropDown();
        $totales = $this->Cuenta_model->StaticsSimples($id);
        $data['totFact'] = $totales->importe;
        $data['totComp'] = $totales->cantidad;
        $data['cuenta'] = $cuenta;
        $data['link_back'] = 'cuenta/index/';
        $data['accion'] = 'cuenta/editarDo';
        $data['ocultos'] = array('id' => $id);
        //Assets::add_js('cuenta/editar');
        Template::set($data);
        Template::set_view('cuenta/editar');
        Template::render();
    }

    function editarDo()
    {
        $datos = array('condiva_id' => $this->input->post('condiva_id'),
            'nombre' => strtoupper($this->input->post('nombre')),
            'datos_fac' => strtoupper($this->input->post('datos_fac')),
            'nombre_facturacion' => strtoupper($this->input->post('nombre_facturacion')),
            'cuit' => $this->input->post('cuit'),
            'tipdoc' => $this->input->post('tipdoc'),
            'direccion' => strtoupper($this->input->post('direccion')),
            'telefono' => $this->input->post('telefono'),
            'email' => strtolower($this->input->post('email')),
            'tipo' => $this->input->post('tipo'),
            'letra' => $this->input->post('letra'),
            'ctacte' => $this->input->post('ctacte'),
            'estado' => $this->input->post('estado'));
        $this->Cuenta_model->update($datos, $this->input->post('id'));
        Template::redirect('cuenta');
    }

    function ver()
    {

    }

    function borrar($id)
    {
        $this->Cuenta_model->borrar($id);
        Template::redirect('cuenta');
    }

    function buscoCuit()
    {
        $valor = $this->input->post('cuit');
        $data['valor'] = $this->input->post('cuit');
        $data['cuentas'] = $this->Cuenta_model->buscoCuit($valor);
        $data['valor'] = $valor;
        //$this->fb->log($data['cuentas']);
        Assets::add_js('cuenta/autofiltro'); // script de funciones jQuery
        Template::set($data);
        Template::set_view('cuenta/autofiltro');
        Template::render();
    }

    function searchAjax($target)
    {
        $data['target'] = $target;
        $this->load->view('cuenta/searchForm', $data);
    }

    function searchAjaxDo()
    {
        $valor = strtoupper($this->input->post('nombreTXT'));
        if (is_numeric($valor)) {
            $valor = intval($valor);
            $cuentas = $this->Cuenta_model->getById($valor);
            $data['valor'] = $valor;
            $data['byId'] = true;
        } else {
            $valor = strtoupper(trim($valor));
            $cuentas = $this->Cuenta_model->ListadoFiltradoNombre($valor);
            $data['valor'] = $valor;
            $data['byId'] = false;
        }
        $data['vacio'] = ($cuentas) ? false : true;
        $data['cuentas'] = $cuentas;
        $data['target'] = $this->input->post('destino');
        $data['targetCuenta'] = sprintf("'%sindex.php/cuenta/agregar/ajax'", base_url());
        //$this->load->view('cuenta/listadoAjax', $data);
        $jsonString = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonString;
    }

    function buscoNombreAjax()
    {
        $valor = $this->input->post('nombreTXT');
        if (is_numeric($valor)) {
            $valor = intval($valor);
            $data['cuentas'] = $this->Cuenta_model->getById($valor);
            $data['valor'] = $valor;
            $data['byId'] = true;
        } else {
            $valor = strtoupper(trim($valor));
            $data['cuentas'] = $this->Cuenta_model->ListadoFiltradoNombre($valor);
            $data['valor'] = $valor;
            $data['byId'] = false;
        }
        //$this->fb->log($data['cuentas']);
        $this->load->view('cuenta/nombreAjax', $data);
    }

    function buscoNombre()
    {
        $valor = $this->input->post('nombreTXT');
        if (is_numeric($valor)) {
            $valor = intval($valor);
            $data['cuentas'] = $this->Cuenta_model->getById($valor);
            $data['valor'] = $valor;
            $data['byId'] = true;
        } else {
            $valor = strtoupper(trim($valor));
            $data['cuentas'] = $this->Cuenta_model->ListadoFiltradoNombre($valor);
            $data['valor'] = $valor;
            $data['byId'] = false;
        }
        Assets::add_js('cuenta/index'); //agrego las funciones de Jquery
        Template::set($data);
        Template::set_view('cuenta/index');
        Template::render();
    }

    function muestroAjax()
    {
        $valor = $this->input->post('nombreTXT');
        if (is_numeric($valor)) {
            $valor = intval($valor);
            $data['datos'] = $this->Cuenta_model->getById($valor);
            $data['valor'] = $valor;
            $data['byId'] = true;
        } else {
            $valor = strtoupper(trim($valor));
            $data['datos'] = $this->Cuenta_model->ListadoFiltradoNombre($valor);
            $data['valor'] = $valor;
            $data['byId'] = false;
        }
        $this->load->view('cuenta/muestroAjax', $data);
    }

    function searchCuentaX($filtro = 0)
    {
        $this->output->enable_profiler(false);
        $data['cuenta'] = $this->input->post('cuentaTXT');
        $data['filtro'] = $filtro;
        $this->load->view('cuenta/searchCuentaX', $data);
    }

    function searchCuentaXDo()
    {
        $this->output->enable_profiler(false);
        if ($this->input->post('cuentaTXT')) {
            $cuenta = strtoupper(trim($this->input->post('cuentaTXT')));
            $filtro = $this->input->post('filtro');
            if (ctype_digit($this->input->post('cuentaTXT'))) {
                $cuenta = intval($cuenta);
                $data['directo'] = true;
                $data['cuentas'] = $this->Cuenta_model->getById($cuenta);
            } else {
                $data['directo'] = false;
                $data['cuentas'] = $this->Cuenta_model->ListadoFiltradoNombre($cuenta, $filtro);
            };
            //$this->load->view('resultCuentaX', $data);
            $jsonString = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonString;
        };
    }
}
