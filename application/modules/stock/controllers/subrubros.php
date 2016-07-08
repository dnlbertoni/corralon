<?php

/**
 * Created by PhpStorm.
 * User: sistemas
 * Date: 07/07/2016
 * Time: 23:13
 */
class Subrubros extends Admin_Controller {
    function __construct () {
        parent::__construct ();
    }

    function searchAjax ( $target ) {
        $data['target'] = $target;
        $this->load->view ( 'stock/subrubros/searchForm', $data );
    }

    function searchAjaxDo () {
        $valor = strtoupper ( trim ( $this->input->post ( 'subrubroTXT' ) ) );
        $subrubros = $this->Subrubros_model->buscoNombre ( $valor );
        $data['subrubroTXT'] = $valor;
        $data['subrubros'] = $subrubros;
        $data['vacio'] = ( $subrubros ) ? false : true;
        $data['target'] = $this->input->post ( 'destino' );
        $data['targetRubro'] = sprintf ( "'%sindex.php/stock/subrubros/agregar/ajax'", base_url () );
        $this->load->view ( 'stock/subrubros/listadoAjax', $data );
    }

    function verArticulos ( $id ) {
        $data['subrubro'] = $this->Subrubros_model->getNombre ( $id );
        $sub = $this->Subrubros_model->getById ( $id );
        $data['rubro'] = $this->Rubros_model->getNombre ( $sub->ID_RUBRO );
        $data['articulos'] = $this->Subrubros_model->getArticulosFromSubrubro ( $id );
        $this->load->view ( 'subrubros/listadoArticulos', $data );
    }
}