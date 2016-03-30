<?php

/**
 * Created by PhpStorm.
 * User: dnl
 * Date: 18/05/14
 * Time: 18:07
 */
class Inventario extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        //Template::set_theme('citrus/'); // TODO sacar e unificar
        $this->load->model('Articulos_model');
        $this->load->model('Stkinvmae_model');
        $this->load->model('Stkinvmovim_model');
        $this->load->model('Subrubros_model');
    }

    function index()
    {
        $last = $this->Stkinvmae_model->getUltimo();
        $estado = "No existen inventarios Realizados aun";
        if (isset($last->estado)) {
            switch ($last->estado) {
                case 1:
                    $estado = "<p>Abierto el dia " . $last->fecha_inicio . "<p>";
                    $estado .= "<p>Modificado por ultima vez el " . $last->ultima_actualizacion . "</p>";
                    break;
                case 2:
                    $estado = "<p>Cerrado el dia " . $last->fecha_final . "<p>";
                    $estado .= "<p>Modificado por ultima vez el " . $last->ultima_actualizacion . "</p>";
                    break;
            }
        }
        Template::set('last', $last);
        Template::set('estadoUltimo', $estado);
        Template::render();
    }

    function open()
    {
        $last = $this->Stkinvmae_model->getUltimo();
        $permitido = (!isset($last->estado)) ? !isset($last->estado) : true;
        Template::set('permitido', $permitido);
        Template::render();
    }

    function openDo()
    {
        $this->Stkinvmae_model->inicioInventario();
        Template::redirect('stock/inventario/');
    }

    function close()
    {
        $last = $this->Stkinvmae_model->getUltimo();
        Template::set('last', $last);
        Template::render();
    }

    function closeDo()
    {
        $this->Stkinvmae_model->cierroInventario();
        Template::redirect('stock/inventario/');
    }

    function conteo($deposito, $inventario)
    {
        $articulos = $this->Stkinvmovim_model->getFromDeposito($deposito, $inventario);
        switch ($deposito) {
            case 'dpc1':
                $depositoNombre = "Primer Sector del Deposito Principal";
                break;
            case 'dpc2':
                $depositoNombre = "Segundo Sector del Deposito Principal";
                break;
            case 'dpc3':
                $depositoNombre = "Garage y demas sectores";
                break;
            case 'salon':
                $depositoNombre = "Salon de ventas y gondolas";
                break;
        }
        Template::set('articulos', $articulos);
        Template::set('unidadesDefault', ($deposito == 'salon') ? 1 : 0);
        Template::set('bultosDefault', ($deposito == 'salon') ? 0 : 1);
        Template::set('paginaAjaxDatosArticulo', '"' . base_url() . 'index.php/stock/inventario/datosArticulo"');
        Template::set('depositoId', $deposito);
        Template::set('inventarioId', $inventario);
        Template::set('depositoNombre', $depositoNombre);
        Template::render();
    }

    function agregoAlConteo()
    {
        $this->output->enable_profiler(false);
        $CB = $this->input->post('CB');
        $cantidad = $this->input->post('cantidad');
        $unidades = $this->input->post('unidades');
        $cantidadBultos = $this->input->post('cantidadBultos');
        $cantidadXbultos = $this->input->post('cantidadXbultos');
        $inventario = $this->input->post('inventario');
        $deposito = $this->input->post('deposito');
        $articulo = $this->Articulos_model->getArticulo($CB);
        $renglonId = $this->Stkinvmovim_model->agregarAlConteo($articulo->id, $CB, $articulo->detalle, $cantidad, $unidades, $cantidadBultos, $cantidadXbultos, $deposito, $inventario);
        /* modifico la cantidad por bultos si el conteo es en los depositos */
        if ($deposito != 'salon' && $cantidadXbultos > 3) {
            $this->Subrubros_model->actualizoCantidadBultos($articulo->idsubrubro, $cantidadXbultos);
            $this->Articulos_model->actualizoCantidadBultos($articulo->id, $cantidadXbultos);
        };
        $hoy = new DateTime();
        $resultado = array(
            'id' => $renglonId,
            'nombre' => $articulo->detalle,
            'codigobarra' => $CB,
            'unidades' => $unidades,
            'cantbultos' => $cantidadBultos,
            'cantxbultos' => $cantidadXbultos,
            'cantidad' => $cantidad,
            'fecha' => $hoy->format('d/m/Y H:i:s')
        );
        echo json_encode($resultado);
    }

    function sacarDelConteo($id)
    {
        $this->output->enable_profiler(false);
        $this->Stkinvmovim_model->borrar($id);
        echo json_encode(array('mensaje' => 'OK'));
    }

    function datosArticulo($CB)
    {
        $this->output->enable_profiler(false);
        $articulo = $this->Articulos_model->getDatosInventario($CB);
        $datos = array('nombre' => $articulo->nombre,
            'bultos' => ($articulo->cantidadBultoSub > 0) ? $articulo->cantidadBultoSub : $articulo->cantidadBulto
        );
        echo json_encode($datos);
    }

    function modificarConteo($tipo, $variable, $id)
    {
        $this->output->enable_profiler(false);
        $cantidad = ($variable == "plus") ? 1 : -1;
        $campo = ($tipo == "box") ? "cant_bultos" : "unidades_sueltas";
        $articulo = $this->Stkinvmovim_model->modificaCantidad($id, $campo, $cantidad);
        echo json_encode(array('numero' => $articulo->{$campo}, "total" => $articulo->cantidad));
    }
}