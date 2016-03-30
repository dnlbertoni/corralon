<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(ENVIRONMENT === 'desarrollo');
        /*
         * defino los modulos que van en el menu
         */
        $Modulos[] = array('link' => 'cuenta/',
            'nombre' => 'Clie/Prov'
        );
        $Modulos[] = array('link' => 'pos/',
            'nombre' => 'Puesto Vta'
        );
        $Modulos[] = array('link' => 'facturas/',
            'nombre' => 'Facturacion'
        );
        $Modulos[] = array('link' => 'iva/',
            'nombre' => 'I.V.A.'
        );
        $Modulos[] = array('link' => 'articulos/',
            'nombre' => 'Articulos'
        );
        $Modulos[] = array('link' => 'carteles/',
            'nombre' => 'Carteles'
        );
        $Modulos[] = array('link' => 'banco/',
            'nombre' => 'Banco'
        );
        $Modulos[] = array('link' => 'ctacte/',
            'nombre' => 'CtaCte'
        );
        $dataM['Modulos'] = $Modulos;
        Template::set($dataM);
        setlocale(LC_MONETARY, 'es_AR');
    }
}

class Admin_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Modulos_model');
        $this->load->model('Menues_model');
        $this->output->enable_profiler(ENVIRONMENT === 'desarrollo');
        /*
         * defino los modulos que van en el menu
         */
        $modulos = $this->Modulos_model->getAll(ACTIVO);
        $barra = $this->Menues_model->getAll(ACTIVO);
        Template::set_theme('citrus/');
        Template::set("menu", $barra);
        Template::set("modulos", $modulos);
        setlocale(LC_MONETARY, 'es_AR');
    }
}

class POS_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Modulos_model');
        $this->load->model('Menues_model');
        $this->load->model('Cfgpuestos_model');
        $this->output->enable_profiler(ENVIRONMENT === 'desarrollo');
        /*
         * defino los modulos que van en el menu
         */
        $modulos = $this->Modulos_model->getAll(ACTIVO);
        $barra = $this->Menues_model->getAll(ACTIVO);
        Template::set_theme('citrus/');
        Template::set("menu", $barra);
        Template::set("modulos", $modulos);
        setlocale(LC_MONETARY, 'es_AR');
    }
}