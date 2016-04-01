<?php

/**
 * controlador de las configuraciones
 *
 * @author dnl
 * @version 1
 *
 * @property $Cfgparametros_model $Cfgparametros_model
 *
 */
class Setting extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        //Assets::js("iconpicker/js/bootstrap-iconpicker");
        //Assets::add_css('iconpicker/css/bootstrap-iconpicker.min');
    }

    /**
     * muestra el index de las configuraciones
     */
    function index(){
        $modulos = $this->Modulos_model->getAll();
        $menues = $this->Menues_model->getAll();
        $parametros = $this->Cfgparametros_model->getAll();
        Template::set('impresoras', $this->Printers->get());
        Template::set('modulosTable', $modulos);
        Template::set('menuTable', $menues);
        Template::set('menuParametros', $parametros);
        $parametros = $this->Cfgparametros_model->getAll();
        Template::render();
    }

    /** @TODO definir el modulo y el menu dependiendo si es desarrollo o produccino */
    public function addModulo()    {
        $this->output->enable_profiler(FALSE);
        $data['titulo'] = "Agregar Modulo";
        $data['accion'] = 'setting/addModuloDo';
        $data['modulo'] = $this->Modulos_model->getInicial();
        $this->load->view('setting/modulosView', $data);
    }

    public function addModuloDo()
    {
        foreach ($_POST as $key => $value) {
            $datos[$key] = $value;
        };
        $this->Modulos_model->add($datos);
        Template::redirect('setting');
    }

    public function editModulo($id)
    {
        $this->output->enable_profiler(FALSE);
        $data['titulo'] = "Modificar Modulo";
        $data['accion'] = 'setting/editModuloDo';
        $data['modulo'] = $this->Modulos_model->getById($id);
        $this->load->view('setting/modulosView', $data);
    }

    public function editModuloDo()
    {
        foreach ($_POST as $key => $value) {
            if ($key != 'id') {
                $datos[$key] = $value;
            }
        };
        $this->Modulos_model->update($datos, $this->input->post('id'));
        Template::redirect('setting');
    }

    public function addMenu()
    {
        $this->output->enable_profiler(FALSE);
        $data['titulo'] = "Agregar Menu";
        $data['accion'] = 'setting/addMenuDo';
        $data['modulosSel'] = $this->Modulos_model->toDropDown('id', 'nombre');
        $data['menu'] = $this->Menues_model->getInicial();
        $this->load->view('setting/menuView', $data);
    }

    public function addMenuDo()
    {
        foreach ($_POST as $key => $value) {
            $datos[$key] = $value;
        };
        $this->Menues_model->add($datos);
        Template::redirect('setting');
    }

    public function editMenu($id)
    {
        $this->output->enable_profiler(FALSE);
        $data['titulo'] = "Modificar Menu";
        $data['accion'] = 'setting/editMenuDo';
        $data['menu'] = $this->Menues_model->getById($id);
        $data['modulosSel'] = $this->Modulos_model->toDropDown('id', 'nombre');
        $this->load->view('setting/menuView', $data);
    }

    public function editMenuDo()
    {
        foreach ($_POST as $key => $value) {
            if ($key != 'id') {
                $datos[$key] = $value;
            }
        };
        $this->Menues_model->update($datos, $this->input->post('id'));
        Template::redirect('setting');
    }
    /** CRUD parametros */

    public function addParametro()    {
        $this->output->enable_profiler(FALSE);
        $data['titulo'] = "Agregar Parametro";
        $data['accion'] = 'setting/addParametroDo';
        $data['parametro'] = $this->Cfgparametros_model->getInicial();
        $this->load->view('setting/parametrosView', $data);
    }

    public function addParametroDo(){
        foreach ($_POST as $key => $value) {
            $datos[$key] = $value;
        };
        $this->Cfgparametros_model->add($datos);
        Template::redirect('setting');
    }

    public function editParametro($id){
        $this->output->enable_profiler(FALSE);
        $data['titulo'] = "Modificar Parametro";
        $data['accion'] = 'setting/editParametroDo';
        $data['parametro'] = $this->Cfgparametros_model->getById($id);
        $this->load->view('setting/modulosView', $data);
    }

    public function editParammetroDo(){
        foreach ($_POST as $key => $value) {
            if ($key != 'id') {
                $datos[$key] = $value;
            }
        };
        $this->Cfgparametros_model->update($datos, $this->input->post('id'));
        Template::redirect('setting');
    }
}
