<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 20/04/2016
 * Time: 07:05 AM
 * @property Rubros_model $Rubros_model
 * @property Subrubros_model $Subrubros_model
 * @property CI_Pagination $pagination
 */
class Rubros extends Admin_Controller {
    function __construct () {
        parent::__construct ();
        $this->load->model ( 'Rubros_model' );
        $this->load->model ( 'Subrubros_model' );
    }

    function index () {
        $this->load->library ( 'pagination' ); //Cargamos la librería de paginación
        $this->load->config ( 'pagination' );
        $config['base_url'] = base_url () . 'stock/rubros/'; // parametro base de la aplicación, si tenemos un .htaccess nos evitamos el index.php
        $config['total_rows'] = $this->Rubros_model->getNumeroFilas ();//calcula el número de filas
        $config["uri_segment"] = 3;//el segmento de la paginación
        $this->pagination->initialize ( $config ); //inicializamos la paginación
        $rubros = $this->Rubros_model->getAll ( false, $this->pagination->per_page, $this->uri->segment ( 3 ) );
        Template::set ( 'rubros', $rubros );
        Template::set ( 'paginacion', $this->pagination->create_links () );
        Template::render ();
    }

    function nuevo () {
        Template::set_view ( 'stock/rubros/edit' );
        Template::render ();
    }
}