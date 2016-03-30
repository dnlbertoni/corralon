<?php

class Ctacte extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ctacte_movim_model', '', TRUE);
        $this->load->model('Ctacte_liq_model', '', TRUE);
        $this->load->model('Ctacte_rec_model', '', TRUE);
        $this->load->model('Cuenta_model', '', TRUE);
        $this->load->model('Numeradores_model', '', TRUE);
        $this->load->model('Facmovim_model');
        Template::set('title', 'Modulo Cuentas Corrientes');
        // panel de tareas Regulares
        $datos['tareasSet'] = true;
        $datos['tareas'][] = array('cuenta/crear', 'Agregar CtaCte');
        $datos['tareas'][] = array('ctacte/estadisticas', 'Informacion Extra');

        Template::set($datos);
        Template::set_block('tareas', 'tareas'); // panel de tareas
    }

    function index()
    {
        Assets::add_js('ui-tableFilter');
        $fechoy = getdate();
        $pendientes = $this->Ctacte_movim_model->getTotalesAgrupados('P');
        $liquidadas = $this->Ctacte_liq_model->getAllEstado('P');
        $cuentas = $this->Cuenta_model->listadoFiltrado(1, 1);
        $diaQuery = $fechoy['year'] . '-' . $fechoy['mon'] . '-' . $fechoy['mday'];
        $ultimas = $this->Ctacte_movim_model->getLast(10, $diaQuery);
        $pagadas = $this->Ctacte_liq_model->getAllEstado('C', 10);
        $data['pendientes'] = $pendientes;
        $data['liq'] = $liquidadas;
        $data['cuentas'] = $cuentas;
        $data['ultimas'] = $ultimas;
        $data['pagadas'] = $pagadas;
        $data['hoy'] = $fechoy['mday'] . "/" . $fechoy['mon'] . "/" . $fechoy['year'];
        Template::set($data);
        Template::render();
    }

    function liquidar($cuenta)
    {
        $pendientes = $this->Ctacte_movim_model->getDetalle($cuenta, 'P');
        $data['movimientos'] = $pendientes;
        $data['accion'] = 'ctacte/liquidarDo';
        $data['ocultos'] = array('cuenta' => $cuenta, 'importe' => 0);
        Template::set($data);
        Template::set_view('ctacte/liqForm');
        Template::render();
    }

    function liquidarDo()
    {
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(cuenta)|^(importe)/', $key)) {
                $datosMovim[] = $valor;
            } else {
                $cuenta = $this->input->post('cuenta');
                $importe = $this->input->post('importe');
            };
        };
        $fecdes = $this->Ctacte_movim_model->getFecha('min', 'P', $cuenta);
        $fecfin = $this->Ctacte_movim_model->getFecha('max', 'P', $cuenta);
        $datosLiq = array(
            'fecini' => $fecdes,
            'fecfin' => $fecfin,
            'id_cuenta' => $cuenta,
            'importe' => $importe
        );
        $idLiq = $this->Ctacte_liq_model->insertar($datosLiq);
        $this->Ctacte_movim_model->Liquidar($idLiq, $datosMovim);
        redirect('ctacte/pdf/liquidacion/' . $idLiq, 'location', 301);
    }

    function historial($id = false)
    {
        $fechoy = getdate();
        $id = ($id) ? $id : $this->input->post('cuenta');
        if (!$id) {
            Template::redirect('ctacte/');
        };
        $data['pendientes'] = $this->Ctacte_movim_model->getDetalle($id, "P");
        $data['periodos'] = $this->Ctacte_liq_model->getPeriodos($id);
        $data['promedio'] = $this->Ctacte_liq_model->promedio($id);
        $data['cliente'] = $this->Cuenta_model->getNombre($id);
        $data['periodo'] = $fechoy['year'] . '-' . $fechoy['mon'];
        $data['ocultos'] = array('cuenta' => $id);
        $data['idCuenta'] = $id;
        Template::set($data);
        Template::render();
    }

    function cobrar($idLiq)
    {
        $liq = $this->Ctacte_liq_model->getById($idLiq);
        $data['nombreCuenta'] = $this->Cuenta_model->getNombre($liq->id_cuenta);
        $movim = $this->Ctacte_movim_model->getByLiq($idLiq);
        $data['Liq'] = $liq;
        $data['movimientos'] = $movim;
        $data['ocultos'] = array('idLiq' => $idLiq);
        Template::set($data);
        Template::set_view('ctacte/cobrarForm');
        Template::render();
    }

    function cobrarDo()
    {
        $liq = $this->Ctacte_liq_model->getById($this->input->post('idLiq'));
        //armo comprobante
        $puesto = 80 + PUESTO;
        $numero = $this->Numeradores_model->getNextRecibo($puesto);
        $idRec = $this->Ctacte_rec_model->gabroRecibo($liq->id_cuenta, $liq->id, $this->input->post('pago1'), $formaPago = 1);
        //imprimo
        //grabo
        $this->Ctacte_liq_model->cobroLiq($liq->id, $idRec);
        $this->Ctacte_movim_model->cobroFac($liq->id, $idRec);
        Template::redirect('ctacte/');
    }

    function adeudadas()
    {
        $data['pendientes'] = $this->Ctacte_liq_model->getAllEstado('P');
        Template::set($data);
        Template::set_view('ctacte/listado');
        Template::render();
    }

    function detalleComprobante($id, $accion = 0, $liq = FALSE)
    {
        $data['borrar'] = ($accion == 1) ? true : false;
        $data['fac'] = $this->Ctacte_movim_model->getEncabezado($id, $liq);
        $data['art'] = $this->Ctacte_movim_model->getComprobante($id, $liq);
        $data['idMovim'] = $id;
        $this->load->view('ctacte/detalleComprobante', $data);
    }

    function quitarDeLaCuenta($id)
    {
        $this->Ctacte_movim_model->quitarDeLaCuenta($id);
    }
}
