<?php

/**
 * Description of estadisticas
 *
 * @author dnl
 */
class Estadisticas extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Facencab_model');
    }

    function index()
    {
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $data['meses'] = $meses;
        $data['anos'] = $this->Facencab_model->getAnosCTACTE();
        Template::set($data);
        Template::render();
    }

    function resumenPeriodo()
    {
        $clientes = $this->Facencab_model->getTotalesCTACTE($this->input->post('ano_id') * 100 + ($this->input->post('mes_id') + 1));
        Template::set('clientes', $clientes);
        Template::set('periodo', $this->input->post('ano_id') * 100 + ($this->input->post('mes_id') + 1));
        Template::set_view('ctacte/estadisticas/resumen');
        Template::render();
    }
}
