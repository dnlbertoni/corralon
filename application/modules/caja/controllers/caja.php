<?php

/**
 * Description of caja
 *
 * @author dnl
 * @property Cajaencab_model $Cajaencab_model
 * @property Facencab_model $Facencab_model
 * @property Presuencab_model $Presuencab_model
 */
class Caja extends Admin_Controller {

    function __construct () {
        parent::__construct();
        $this->load->model ( 'Cajaencab_model' );
    }

    function index () {
        Template::render();
    }

    function open () {
        $data['puesto'] = $this->getPuesto ();
        $data['caja'] = $this->Cajaencab_model->getCajaPuesto ( $this->getPuesto (), 1 );
        $data['fecha'] = $this->getFecha ();
        Template::set ( $data );
        Template::render ();
    }

    function openDo () { }

    function facturar () {
        $this->load->model ( "Presuencab_model" );
        $data['presupuestos'] = $this->Presuencab_model->getPendientes ();
        Template::set ( $data );
        Template::render ();
    }

    function emitirFactura ( $idPresupuesto ) {

    }
}
