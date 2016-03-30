<?php

class Empresas extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Articulos_model', '', true);
        $this->load->model('Marcas_model', '', TRUE);
        $this->load->model('Empresas_model', '', TRUE);
    }

    function index()
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'index.php/articulos/empresas/index';
        $full = $this->Articulos_model->getEmpresas();
        $config['total_rows'] = count($full);
        $config['per_page'] = '21';
        $this->pagination->initialize($config);
        $num = $this->uri->segment(4);
        $empresas = $this->Articulos_model->getEmpresas(21, $num);
        $data['empresas'] = $empresas;
        $marcas = $this->Empresas_model->genericosAll();
        $data['marcas'] = $marcas;
        $data['totalEmpresas'] = count($full);
        $data['totalArticulos'] = 0;
        foreach ($full as $articulo) {
            $data['totalArticulos'] += $articulo->cantidad;
        }
        $data['totalArticulosMarcas'] = 0;
        foreach ($marcas as $articulo) {
            $data['totalArticulosMarcas'] += $articulo->genericos;
        }
        $data['totalMarcas'] = count($marcas);
        Template::set($data);
        Template::render();
    }

    function verArticulos($idEmpresa, $tipo = "empresa")
    {
        if ($tipo == "marca") {
            $empresas = $this->Empresas_model->getEmpresasFromMarca($idEmpresa);
        } else {
            $emp = array('id' => $idEmpresa);
            $empresas[] = (object)$emp;
        };
        $articulos = $this->Articulos_model->getArticulosFromEmpresa($empresas);
        $data['articulos'] = $articulos;
        $data['empresa'] = $idEmpresa;
        $data['nombreEmpresa'] = $this->Marcas_model->getNombre($idEmpresa);
        $url = 'submarcas';
        $funcion = 'searchAjax';
        $data['titulo'] = "'>> EMPRESA <<'";
        $data['urlBuscoAjaxEmpresa'] = sprintf("'%sindex.php/articulos/%s/%s/%s'", base_url(), $url, $funcion, 'resultadoAjaxEmpresa');
        $data['urlBuscoAjaxMarca'] = sprintf("'%sindex.php/articulos/%s/%s/%s'", base_url(), $url, $funcion, 'resultadoAjaxMarca');
        $url = 'subrubros';
        $data['urlBuscoAjaxRubro'] = sprintf("'%sindex.php/articulos/%s/%s/%s'", base_url(), $url, $funcion, 'resultadoAjaxRubro');
        Assets::add_js('selectBoxes');
        Assets::add_js('filtrarTabla');
        Assets::add_js('ui-tableFilter');
        Template::set($data);
        Template::set_view('articulos/empresas/listadoArticulos');
        Template::render();
    }

    function asignaEmpresa()
    {
        $datos = array('id' => $this->input->post('id'), 'id_marca' => $this->input->post('id_marca'));
        $this->Empresas_model->add($datos, 'id');
        Template::redirect('empresas');
    }

    function asigna()
    {
        $campo = ($this->input->post('tipo') == "rubro") ? 'id_subrubro' : 'id_marca';
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(valor)|^(tipo)/', $key)) {
                $datos = array($campo => $this->input->post('valor'));
                $this->Articulos_model->update($datos, $valor);
            }
        };
        //Template::render();
        Template::redirect('empresas');
    }
}
