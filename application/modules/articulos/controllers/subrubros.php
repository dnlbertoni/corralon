<?php

class Subrubros extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Rubros_model", "", TRUE);
        $this->load->model("Subrubros_model", "", TRUE);
    }

    function index()
    {
        //panel de tareas
        $datos['tareas'][] = array('articulos/precios/', 'Cambio Precios');
        $datos['tareas'][] = array('articulos/rubros/', 'Rubros');
        $datos['tareas'][] = array('articulos/subrubros/', 'Subrubros');
        $datos['tareas'][] = array('articulos/marcas/', 'Marcas');
        $datos['tareas'][] = array('articulos/submarcas/', 'Submarcas');
        /*
        Template::set($datos);

        Template::set_block('tareas', 'tareas'); // panel de tareas
    */
        $subrubros = $this->Subrubros_model->getAllConArticulos();
        $data['subrubros'] = $subrubros;
        Assets::add_js('ui-tableFilter');
        Template::set($data);
        Template::render();
    }

    function agregar($metodo = "html")
    {
        $subrubro = array('DESCRIPCION_SUBRUBRO' => '',
            'ALIAS_SUBRUBRO' => '',
            'ID_RUBRO' => 1,
            'ESTADO_SUBRUBRO' => 1
        );
        $data['subrubro'] = (object)$subrubro;
        $data['ocultos'] = array('id' => '');
        $data['rubroSel'] = $this->Rubros_model->ListaSelect();
        $data['cancelar'] = $metodo;
        if ($metodo == "html") {
            $data['accionSub'] = 'articulos/subrubros/agregarDo';
            Template::set($data);
            Template::set_view('articulos/subrubros/ver');
            Template::render();
        } else {
            $data['accionSub'] = 'articulos/subrubros/agregarDo/ajax';
            $this->load->view('articulos/subrubros/ver', $data);
        }
    }

    function agregarDo($metodo = "html")
    {
        if ($this->input->post('otro') == "SI") {
            $datosRub = array('DESCRIPCION_RUBRO' => strtoupper($this->input->post('descripcion')),
                'ALIAS_RUBRO' => strtoupper($this->input->post('alias')),
                'ESTADO_RUBRO' => $this->input->post('estado')
            );
            $rubro = $this->Rubros_model->add($datosRub);
        } else {
            $rubro = $this->input->post('rubro');
        };
        $datos = array('DESCRIPCION_SUBRUBRO' => strtoupper($this->input->post('descripcion')),
            'ALIAS_SUBRUBRO' => strtoupper($this->input->post('alias')),
            'ID_RUBRO' => $rubro,
            'ESTADO_SUBRUBRO' => $this->input->post('estado')
        );
        $id = $this->Subrubros_model->add($datos);
        if ($metodo == "html") {
            Template::redirect('articulos/subrubros');
        } else {
            echo "<span class='codigo'>", $id, "</span><span class='nombre' style='display:hide;'>" . strtoupper($this->input->post('descripcion')) . "</span>";
        }
        Template::render();
    }

    function editar($id, $metodo = "html")
    {
        $data['subrubro'] = $this->Subrubros_model->getById($id);
        $data['ocultos'] = array('id' => $id);
        $data['rubroSel'] = $this->Rubros_model->ListaSelect();
        $data['cancelar'] = $metodo;
        if ($metodo == "html") {
            $data['accionSub'] = 'articulos/subrubros/editarDo';
            Template::set($data);
            Template::set_view('articulos/subrubros/ver');
            Template::render();
        } else {
            $data['accionSub'] = 'articulos/subrubros/editarDo/ajax';
            $this->load->view('articulos/subrubros/ver', $data);
        };
    }

    function editarDo($metodo = "html")
    {
        $datos = array('DESCRIPCION_SUBRUBRO' => strtoupper($this->input->post('descripcion')),
            'ALIAS_SUBRUBRO' => strtoupper($this->input->post('alias')),
            'ID_RUBRO' => $this->input->post('rubro'),
            'ESTADO_SUBRUBRO' => $this->input->post('estado')
        );
        $id = $this->input->post('id');
        $this->Subrubros_model->update($datos, $id);
        if ($metodo == "html") {
            Template::redirect('articulos/subrubros');
        };
    }

    function borrar($id)
    {
        $this->Subrubros_model->borrar($id);
        Template::redirect('articulos');
    }

    function combosubrubros()
    {
        $id = $this->input->post("id");
        $rpta = '';
        $subrubros = $this->Subrubros_model->getFromRubro($id);
        $cant = 0;
        foreach ($subrubros AS $sub) {
            $rpta .= sprintf("<option value='%s'%s>%s</option>", $sub->id, ($cant > 0) ? '' : "selected='selected'", $sub->nombre);
            $cant++;
        };
        echo $rpta;
    }

    function searchAjax($target)
    {
        $data['target'] = $target;
        $this->load->view('articulos/subrubros/searchForm', $data);
    }

    function searchAjaxDo()
    {
        $valor = strtoupper(trim($this->input->post('subrubroTXT')));
        $subrubros = $this->Subrubros_model->buscoNombre($valor);
        $data['subrubroTXT'] = $valor;
        $data['subrubros'] = $subrubros;
        $data['vacio'] = ($subrubros) ? false : true;
        $data['target'] = $this->input->post('destino');
        $data['targetRubro'] = sprintf("'%sindex.php/articulos/subrubros/agregar/ajax'", base_url());
        $this->load->view('articulos/subrubros/listadoAjax', $data);
    }

    function verArticulos($id)
    {
        $data['subrubro'] = $this->Subrubros_model->getNombre($id);
        $sub = $this->Subrubros_model->getById($id);
        $data['rubro'] = $this->Rubros_model->getNombre($sub->ID_RUBRO);
        $data['articulos'] = $this->Subrubros_model->getArticulosFromSubrubro($id);
        $this->load->view('subrubros/listadoArticulos', $data);
    }
}
