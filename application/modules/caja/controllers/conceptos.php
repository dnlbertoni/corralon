<?php

/**
 * Description of conceptos
 *
 * @author dnl
 */
class Conceptos extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        Template::set_theme('moderno/');
    }

    function index()
    {
        $this->load->model('Conceptos_model');
        $conceptos = $this->Conceptos_model->getAll();
        Template::set('datos', $conceptos);
        Template::render();
    }

    function add()
    {
        Template::set('accion', 'add');
        Template::set_view("caja/conceptos/edit");
        Template::render();
    }
}
