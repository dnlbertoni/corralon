<?php

/*
 * Controlador de los carteles para imprimir precios
 */

class Carteles extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Articulos_model', '', true);
        Template::set_theme('moderno/');
    }

    function index()
    {

        $Menu[1][0]['link'] = "carteles/navidad";
        $Menu[1][0]['nombre'] = "Carteles Navidad";

        $Menu[1][1]['link'] = "carteles/ofertas/3";
        $Menu[1][1]['nombre'] = "Oferta 3 X Hoja";

        $Menu[1][2]['link'] = "carteles/ofertas/1";
        $Menu[1][2]['nombre'] = "Oferta 1 X Hoja";

        $Menu[0][3]['link'] = "carteles/precios/1";
        $Menu[0][3]['nombre'] = "Carteles Precios";

        $Menu[0][4]['link'] = "carteles/precios/2";
        $Menu[0][4]['nombre'] = "Carteles Vinos";

        $Menu[1][5]['link'] = "carteles/ofertaMultiple";
        $Menu[1][5]['nombre'] = "Oferta Multiple";

        $Menu[2][6]['link'] = "carteles/listaDePrecios";
        $Menu[2][6]['nombre'] = "Lista de Precios";

        $Menu[2][7]['link'] = "carteles/cartelVerduras";
        $Menu[2][7]['nombre'] = "Carteles de Verduras";

        $Menu[1][7]['link'] = "carteles/ofertaEscrita";
        $Menu[1][7]['nombre'] = "Oferta Cualquier Cosa";

        $Menu[0][8]['link'] = "carteles/precios/3";
        $Menu[0][8]['nombre'] = "Cartel Grande";

        $data['Menu'] = $Menu;
        Template::set($data);
        Template::render();
    }

    function navidad()
    {
        Assets::add_js('carteles/navidad');
        Template::render();
    }

    function precios($tamano = 1)
    {
        if ($this->input->post('dias')) {
            $dias = $this->input->post('dias');
        } else {
            $dias = 0;
        };
        if ($dias == 0) {
            $Articulos = $this->Articulos_model->PendientesImpresion();
        } else {
            $Articulos = $this->Articulos_model->ModificadosHace($dias);
        };
        $data['tamano'] = $tamano;
        $data['dias'] = $dias;
        switch ($tamano) {
            case 1:
                $data['accion'] = 'carteles/topdf/cartelesPrecios';
                break;
            case 2:
                $data['accion'] = 'carteles/topdf/cartelesVinos';
                break;
            case 3:
                $data['accion'] = 'carteles/topdf/cartelesGrandes';
                break;
        };

        $data['articulos'] = $Articulos;
        $data['total'] = count($Articulos);
        Template::set($data);
        Template::render();
    }

    function ofertas($tamano = 1)
    {
        $fechoy = new DateTime();
        $fechoy->modify("+1 week");
        $data['tamano'] = $tamano;
        $data['accion'] = ($tamano == 1) ? 'carteles/topdf/oferta/1' : 'carteles/topdf/oferta/3';
        $data['fecha'] = $fechoy->format('d/m/Y');
        Assets::add_js('carteles/ofertas');
        Template::set($data);
        Template::render();
    }

    function ofertaMultiple($tamano = 1)
    {
        $fechoy = new DateTime();
        $fechoy->modify("+1 week");
        $data['tamano'] = $tamano;
        $data['accion'] = ($tamano == 1) ? 'carteles/topdf/ofertaMultiple/1' : 'carteles/topdf/ofertaMultiple/3';
        $data['fecha'] = $fechoy->format('d/m/Y');
        $data['precio'] = true;
        Assets::add_js('carteles/ofertas');
        Template::set($data);
        Template::set_view('carteles/ofertas');
        Template::render();
    }

    function buscoDetalles()
    {
        $this->output->enable_profiler(false);
        $Articulos = $this->Articulos_model->getDetalle($this->input->post('codigobarra'));
        if ($Articulos) {
            $retornoAjax = "<tr>";
            $retornoAjax .= "<td id='codart_" . $Articulos->id . "'>" . $Articulos->id . "</td>";
            $retornoAjax .= "<td width='50%'>" . $Articulos->nombre . "</td>";
            $retornoAjax .= "<td>" . $Articulos->precio . "</td>";
            $retornoAjax .= "<td>" . $Articulos->codigobarra . "</td>";
            $retornoAjax .= "<td><input type='hidden' name='" . $Articulos->id . "' value='" . $Articulos->id . "' /></td>";
            $retornoAjax .= "</tr>";
            echo $retornoAjax;
        };
    }

    function listaDePrecios()
    {
        $this->load->model('Rubros_model', '', true);
        $data['rubrosSel'] = $this->Rubros_model->ListaSelect();
        $data['rubro'] = 0;
        $data['accion'] = 'carteles/topdf/listaDePrecios';
        Template::set($data);
        Template::set_view('carteles/listaprecios');
        Template::render();
    }

    function listaDePreciosDo()
    {
        $this->output->enable_profiler(false);
        $articulos = $this->Articulos_model->getArticulosFromRubro($this->input->post('rubro'));
        $retornoAjax = '';
        $artis = 0;
        foreach ($articulos as $arti) {
            $artis++;
            $Articulo = $this->Articulos_model->getDetalle($arti->CB);
            if ($Articulo) {
                $retornoAjax .= "<tr>";
                $retornoAjax .= "<td id='codart_" . $Articulo->id . "'>" . $Articulo->id . "</td>";
                $retornoAjax .= "<td width='50%'>" . $Articulo->nombre . "</td>";
                $retornoAjax .= "<td>" . $Articulo->precio . "</td>";
                $retornoAjax .= "<td>" . $Articulo->codigobarra . "</td>";
                $retornoAjax .= "<td><input type='checkbox' name='" . $Articulo->id . "' value='" . $Articulo->id . "' /></td>";
                $retornoAjax .= "</tr>";
            };
        }
        $retornoAjax .= "<tr><td colsapn='5'>Total de Articulos " . $artis . "</td></tr>";
        echo $retornoAjax;

    }

    function cartelVerduras()
    {
        $this->load->model('Rubros_model', '', true);
        $data['rubrosSel'] = $this->Rubros_model->ListaSelect();
        $data['rubro'] = 11;
        $data['accion'] = 'carteles/topdf/cartelVerduras';
        Template::set($data);
        Template::set_view('carteles/listaprecios');
        Template::render();
    }

    function ofertaEscrita()
    {
        $fechoy = new DateTime();
        $fechoy->modify("+1 week");
        $data['accion'] = 'carteles/topdf/ofertaEscrita/';
        $data['fecha'] = $fechoy->format('d/m/Y');
        Template::set($data);
        Template::render();
    }
}