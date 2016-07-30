<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
/**
 * @property Cfgpuestos_model $Cfgpuestos_model
 */

/**
 *
 * @property CI_DB_active_record $db              This is the platform-independent base Active Record implementation class.
 * @property CI_DB_forge $dbforge                 Database Utility Class
 * @property CI_Benchmark $benchmark              This class enables you to mark points and calculate the time difference between them.<br />  Memory consumption can also be displayed.
 * @property CI_Calendar $calendar                This class enables the creation of calendars
 * @property CI_Cart $cart                        Shopping Cart Class
 * @property CI_Config $config                    This class contains functions that enable config files to be managed
 * @property CI_Controller $controller            This class object is the super class that every library in.<br />CodeIgniter will be assigned to.
 * @property CI_Email $email                      Permits email to be sent using Mail, Sendmail, or SMTP.
 * @property CI_Encrypt $encrypt                  Provides two-way keyed encoding using XOR Hashing and Mcrypt
 * @property CI_Exceptions $exceptions            Exceptions Class
 * @property CI_Form_validation $form_validation  Form Validation Class
 * @property CI_Ftp $ftp                          FTP Class
 * @property CI_Hooks $hooks                      Provides a mechanism to extend the base system without hacking.
 * @property CI_Image_lib $image_lib              Image Manipulation class
 * @property CI_Input $input                      Pre-processes global input data for security
 * @property CI_Lang $lang                        Language Class
 * @property CI_Loader $load                      Loads views and files
 * @property CI_Log $log                          Logging Class
 * @property CI_Model $model                      CodeIgniter Model Class
 * @property CI_Output $output                    Responsible for sending final output to browser
 * @property CI_Pagination $pagination            Pagination Class
 * @property CI_Parser $parser                    Parses pseudo-variables contained in the specified template view,<br />replacing them with the data in the second param
 * @property CI_Profiler $profiler                This class enables you to display benchmark, query, and other data<br />in order to help with debugging and optimization.
 * @property CI_Router $router                    Parses URIs and determines routing
 * @property CI_Session $session                  Session Class
 * @property CI_Sha1 $sha1                        Provides 160 bit hashing using The Secure Hash Algorithm
 * @property CI_Table $table                      HTML table generation<br />Lets you create tables manually or from database result objects, or arrays.
 * @property CI_Trackback $trackback              Trackback Sending/Receiving Class
 * @property CI_Typography $typography            Typography Class
 * @property CI_Unit_test $unit_test              Simple testing class
 * @property CI_Upload $upload                    File Uploading Class
 * @property CI_URI $uri                          Parses URIs and determines routing
 * @property CI_User_agent $user_agent            Identifies the platform, browser, robot, or mobile devise of the browsing agent
 * @property CI_Validation $validation            //dead
 * @property CI_Xmlrpc $xmlrpc                    XML-RPC request handler class
 * @property CI_Xmlrpcs $xmlrpcs                  XML-RPC server class
 * @property CI_Zip $zip                          Zip Compression Class
 * @property CI_Javascript $javascript            Javascript Class
 * @property CI_Jquery $jquery                    Jquery Class
 * @property CI_Utf8 $utf8                        Provides support for UTF-8 environments
 * @property CI_Security $security                Security Class, xss, csrf, etc...
 */

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

class Admin_Controller extends MY_Controller {
    function __construct () {
        parent::__construct();
        $this->load->model('Modulos_model');
        $this->load->model('Menues_model');
        $this->load->model ( 'Cfgpuestos_model' );
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

    function getPuesto () {
        return $this->Cfgpuestos_model->getPuesto ( $this->input->ip_address () );
    }

    function getPuestoCnf () {
        return $this->Cfgpuestos_model->getPuestoCnf ( $this->input->ip_address () );
    }

    function getRutaPuesto () {
        return $this->Cfgpuestos_model->getRutaPuesto ( $this->input->ip_address () );
    }


    function getImpresora () {
        return $this->Cfgpuestos_model->getImpresora ( $this->input->ip_address () );
    }
    function getFecha ( $tipo = "humano" ) {
        $fecha = new DateTime();
        switch ( $tipo ) {
            case "db":
                return $fecha->format ( "Y-m-d" );
                break;
            default:
                return $fecha->format ( "d/m/Y" );
                break;
        }
    }

    function getFechaHora ( $tipo = "humano" ) {
        $fecha = new DateTime();
        switch ( $tipo ) {
            case "db":
                return $fecha->format ( "Y-m-d h:i:s" );
                break;
            default:
                return $fecha->format ( "d/m/Y h:i:s" );
                break;
        }
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