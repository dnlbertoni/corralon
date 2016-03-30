<?php

class Submarcas extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Marcas_model", "", TRUE);
        $this->load->model("Submarcas_model", "", TRUE);
    }

    function index()
    {
        $submarcas = $this->Submarcas_model->getAllConArticulos();
        $data['submarcas'] = $submarcas;
        Assets::add_js('ui-tableFilter');
        Template::set($data);
        Template::render();
    }

    function agregar($metodo = "html")
    {
        $data['accion'] = 'articulos/submarcas/agregarDo';
        $submarca = array('DETALLE_SUBMARCA' => '',
            'ALIAS_SUBMARCA' => '',
            'ID_MARCA' => 1,
            'ESTADO_SUBMARCA' => 1
        );
        $data['submarca'] = (object)$submarca;
        $data['ocultos'] = array('id' => '');
        $data['marcaSel'] = $this->Marcas_model->ListaSelect();
        $data['cancelar'] = $metodo;
        if ($metodo == "html") {
            Template::set($data);
            Template::set_view('articulos/submarcas/ver');
            Template::render();
        } else {
            $data['accion'] .= '/ajax';
            $this->load->view('articulos/submarcas/ver', $data);
        }
    }

    function agregarDo($metodo = "html")
    {
        if ($this->input->post('otra') == "SI") {
            $datosMar = array('DETALLE_MARCA' => strtoupper($this->input->post('descripcion')),
                'ABRE_MARCA' => strtoupper($this->input->post('alias'))
            );
            $marca = $this->Marcas_model->add($datosMar);
        } else {
            $marca = $this->input->post('marca');
        }
        $datos = array('DETALLE_SUBMARCA' => strtoupper($this->input->post('descripcion')),
            'ALIAS_SUBMARCA' => strtoupper($this->input->post('alias')),
            'ID_MARCA' => $marca,
            'ESTADO_SUBMARCA' => $this->input->post('estado')
        );
        $id = $this->Submarcas_model->add($datos);
        if ($metodo == "html") {
            Template::redirect('articulos/submarcas');
        } else {
            echo "<span class='codigo'>", $id, "</span><span class='nombre' style='display:hide;'>" . strtoupper($this->input->post('descripcion')) . "</span>";
        }
    }

    function editar($id, $metodo = "html")
    {
        $data['accion'] = 'articulos/submarcas/editarDo';
        $data['submarca'] = $this->Submarcas_model->getById($id);
        $data['ocultos'] = array('id' => $id);
        $data['marcaSel'] = $this->Marcas_model->ListaSelect();
        $data['cancelar'] = $metodo;
        if ($metodo == "html") {
            $data['accion'] = 'articulos/submarcas/editarDo';
            Template::set($data);
            Template::set_view('articulos/submarcas/ver');
            Template::render();
        } else {
            $data['accion'] = 'articulos/submarcas/editarDo/ajax';
            $this->load->view('articulos/submarcas/ver', $data);
        }
    }

    function editarDo($metodo = "html")
    {
        $datos = array('DETALLE_SUBMARCA' => strtoupper($this->input->post('descripcion')),
            'ALIAS_SUBMARCA' => strtoupper($this->input->post('alias')),
            'ID_MARCA' => $this->input->post('marca'),
            'ESTADO_SUBMARCA' => $this->input->post('estado')
        );
        $id = $this->input->post('id');
        $this->Submarcas_model->update($datos, $id);
        if ($metodo == "html") {
            Template::redirect('articulos/submarcas');
        };
    }

    function borrar($id)
    {
        $this->Submarcas_model->borrar($id);
        Template::redirect('articulos/submarcas');
    }

    function combosubmarcas()
    {
        $id = $this->input->post("id");
        $rpta = '';
        $subrubros = $this->Submarcas_model->getFromMarca($id);
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
        $this->load->view('articulos/submarcas/searchForm', $data);
    }

    function searchAjaxDo()
    {
        $valor = strtoupper(trim($this->input->post('submarcaTXT')));
        $submarcas = $this->Submarcas_model->buscoNombre($valor);
        $data['submarcaTXT'] = $valor;
        $data['vacio'] = ($submarcas) ? false : true;
        $data['submarcas'] = $submarcas;
        $data['target'] = $this->input->post('destino');
        $data['targetMarca'] = sprintf("'%sindex.php/articulos/submarcas/agregar/ajax'", base_url());
        $this->load->view('articulos/submarcas/listadoAjax', $data);
    }

    function verArticulos($id = false)
    {
        if ($id) {
            $data['submarca'] = $this->Submarcas_model->getNombre($id);
            $sub = $this->Submarcas_model->getById($id);
            $data['marca'] = $this->Marcas_model->getNombre($sub->ID_MARCA);
        } else {
            $data['submarca'] = 'Sin Submarca Marcas';
            $data['marca'] = 'Sin MArca';
        };
        $data['articulos'] = $this->Submarcas_model->getArticulosFromSubmarca($id);
        $this->load->view('submarcas/listadoArticulos', $data);
    }
}
