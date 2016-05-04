<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 29/04/2016
 * Time: 09:13 PM
 *
 * @property Articulos_model $Articulos_model
 */
class Articulos extends Admin_Controller {

    function __construct () {
        parent::__construct ();
        $this->load->model ( 'Articulos_model' );
    }

    function index () {
        $this->load->library ( 'pagination' ); //Cargamos la librería de paginación
        $this->load->config ( 'pagination' );
        $config['base_url'] = base_url () . 'stock/articulos/'; // parametro base de la aplicación, si tenemos un .htaccess nos evitamos el index.php
        $config['total_rows'] = $this->Articulos_model->getNumeroFilas ();//calcula el número de filas
        $config["uri_segment"] = 3;//el segmento de la paginación
        $this->pagination->initialize ( $config ); //inicializamos la paginación
        $articulos = $this->Articulos_model->getAll ( false, $this->pagination->per_page, $this->uri->segment ( 3 ) );
        Template::set ( 'articulos', $articulos );
        Template::set ( 'paginacion', $this->pagination->create_links () );
        Template::render ();
    }
}