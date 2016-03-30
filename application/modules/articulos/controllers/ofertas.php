<?php

/**
 * Description of ofertas
 *
 * @author dnl
 */
class Ofertas extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ofertas_model');
    }

    function index()
    {
        $ofertas = $this->Ofertas_model->datosIndex();
        Template::set('ofertas', $ofertas);
        Template::render();
    }

    function nueva()
    {
        Template::render();
    }
}
