<?php

class Rubros extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Rubros_model", "", TRUE);
        $this->load->model("Subrubros_model", "", TRUE);
        /*panel de tareas
        $datos['tareas'][] = array('articulos/precios/', '');
        $datos['tareas'][] = array('articulos/marcas/', '');
        $datos['tareas'][] = array('articulos/rubros/', 'Rubros');
        Template::set($datos);
        Template::set_block('tareas', 'tareas'); // panel de tareas */
    }

    function index()
    {
        //panel de tareas
        $datos['tareas'][] = array('articulos/precios/', 'Cambio Precios');
        $datos['tareas'][] = array('articulos/rubros/', 'Rubros');
        $datos['tareas'][] = array('articulos/subrubros/', 'Subrubros');
        $datos['tareas'][] = array('articulos/marcas/', 'Marcas');
        $datos['tareas'][] = array('articulos/submarcas/', 'Submarcas');
        Template::set($datos);
        Template::set_block('tareas', 'tareas'); // panel de tareas
        Assets::add_js('ui-tableFilter');

        $rubros = $this->Rubros_model->getAll('DESCRIPCION_RUBRO');
        $data['rubros'] = $rubros;
        Template::set($data);
        Template::render();
    }

    function subrubros($rubro)
    {
        $data['subrubros'] = $this->Subrubros_model->getFromRubro($rubro);
        $data['generales'] = $this->Subrubros_model->getFromRubro(1);
        $data['titulo'] = $this->Rubros_model->getNombre($rubro);
        $data['tituloGen'] = $this->Rubros_model->getNombre(1);
        Template::set($data);
        Template::set_view('articulos/rubros/mezcla');
        Template::render();
    }

    function agregar($metodo = "html")
    {
        $data['accion'] = 'articulos/rubros/agregarDo';
        $rubro = array('DESCRIPCION_RUBRO' => '',
            'ALIAS_RUBRO' => '',
            'UNIDAD_RUBRO' => 'BUL',
            'ESTADO_RUBRO' => 1
        );
        $data['rubro'] = (object)$rubro;
        $data['unidadSel'] = array('BUL' => 'BULTOS', 'PES' => 'PESO', 'LIQ' => 'LIQUIDOS');
        $data['ocultos'] = array('id' => '');
        if ($metodo == "html") {
            Template::set($data);
            Template::set_view('articulos/rubros/ver');
            Template::render();
        } else {
            $this->load->view('articulos/rubros/ver', $data);
        }
    }

    function agregarDo()
    {
        $datos = array('DESCRIPCION_RUBRO' => strtoupper($this->input->post('descripcion')),
            'ALIAS_RUBRO' => strtoupper($this->input->post('alias')),
            'UNIDAD_RUBRO' => strtoupper($this->input->post('unidad')),
            'ESTADO_RUBRO' => $this->input->post('estado')
        );
        $id = $this->Rubros_model->add($datos);
        Template::redirect('rubros');
    }

    function editar($id)
    {
        $data['accion'] = 'articulos/rubros/editarDo';
        $data['rubro'] = $this->Rubros_model->getById($id);
        $data['unidadSel'] = array('BUL' => 'BULTOS', 'PES' => 'PESO', 'LIQ' => 'LIQUIDOS');
        $data['ocultos'] = array('id' => $id);
        Template::set($data);
        Template::set_view('articulos/rubros/ver');
        Template::render();
    }

    function editarDo()
    {
        $datos = array('DESCRIPCION_RUBRO' => strtoupper($this->input->post('descripcion')),
            'ALIAS_RUBRO' => strtoupper($this->input->post('alias')),
            'UNIDAD_RUBRO' => strtoupper($this->input->post('unidad')),
            'ESTADO_RUBRO' => $this->input->post('estado')
        );
        $id = $this->input->post('id');
        $this->Rubros_model->update($datos, $id);
        Template::redirect('rubros');
    }

    function borrar($id)
    {
        $this->Rubros_model->borrar($id);
        Template::redirect('rubros');
    }
}
