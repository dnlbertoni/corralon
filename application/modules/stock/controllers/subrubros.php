<?php

/**
 * Created by PhpStorm.
 * User: sistemas
 * Date: 07/07/2016
 * Time: 23:13
 * @property Rubros_model $Rubros_model
 * @property Subrubros_model $Subrubros_model
 * @property CI_Pagination $pagination
 */
class Subrubros extends Admin_Controller {
    function __construct () {
        parent::__construct ();
        $this->load->model ( 'Rubros_model' );
        $this->load->model ( 'Subrubros_model' );
    }

    function index () {
        $this->load->library ( 'pagination' ); //Cargamos la librería de paginación
        $this->load->config ( 'pagination' );
        $config['base_url'] = base_url () . 'stock/subrubros/'; // parametro base de la aplicación, si tenemos un .htaccess nos evitamos el index.php
        $config['total_rows'] = $this->Subrubros_model->getNumeroFilas ();//calcula el número de filas
        $config["uri_segment"] = 3;//el segmento de la paginación
        $this->pagination->initialize ( $config ); //inicializamos la paginación
        $subrubros = $this->Subrubros_model->getAllConArticulos ( false, $this->pagination->per_page, $this->uri->segment ( 3 ) );
        Template::set ( 'subrubros', $subrubros );
        Template::set ( 'paginacion', $this->pagination->create_links () );
        Template::render ();
    }

    function nuevo () {
        Template::set ( 'accion', 'stock/subrubros/nuevoDo' );
        $rubros = $this->Rubros_model->toDropDown ( 'ID_RUBRO', 'DESCRIPCION_RUBRO' );
        Template::set ( 'rubrosSel', $rubros );
        Template::set ( 'subrubro', $this->Subrubros_model->getInicial () );
        Template::set_view ( 'stock/subrubros/edit' );
        Template::render ();
    }

    function nuevoDo () {
        foreach ( $_POST as $key => $value ) {
            if ( $key != "ID_SUB    RUBRO" ) {
                if ( gettype ( $value ) == "string" ) {
                    $value = strtoupper ( $value );
                }
                $datos[$key] = $value;
            }
        }
        $this->Subrubros_model->add ( $datos );
        Template::redirect ( 'stock/subrubros' );
    }

    function edit ( $id ) {
        Template::set ( 'accion', 'stock/subrubros/editDo' );
        $rubros = $this->Rubros_model->toDropDown ( 'ID_RUBRO', 'DESCRIPCION_RUBRO' );
        Template::set ( 'rubrosSel', $rubros );
        Template::set ( 'subrubro', $this->Subrubros_model->getById ( $id ) );
        Template::set_view ( 'stock/subrubros/edit' );
        Template::render ();
    }

    function editDo () {
        foreach ( $_POST as $key => $value ) {
            if ( $key != "ID_SUBRUBRO" ) {
                if ( gettype ( $value ) == "string" ) {
                    $value = strtoupper ( $value );
                }
                $datos[$key] = $value;
            }
        }
        $id = $_POST['ID_SUBRUBRO'];
        $this->Subrubros_model->update ( $datos, $id );
        Template::redirect ( 'stock/subrubros' );
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