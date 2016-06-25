<?php

class Factura extends MY_Controller
{
    var $puesto;
    var $PrinterRemito;

    function __construct () {
        parent::__construct();
        $this->puesto = PUESTO;
        $this->PrinterRemito = 2; // 1 controlador 2 laser
        $this->load->model('Articulos_model', '', true);
        $this->load->model('Tmpmovim_model', '', true);
        $this->load->model('Numeradores_model', '', true);
        $this->load->model('Cuenta_model', '', true);
        $this->load->model('Facencab_model', '', true);
        $this->load->model('cuenta/Cuenta_model', '', true);
    }

    function presupuesto()
    {
        //busco datos del previo
        $presupuesto = $this->Tmpmovim_model->getDatosUltimo($this->puesto);
        if (!$presupuesto) {
            $fecha = new DateTime();
            $data['fecha'] = $fecha->format('d-m-Y');
            $data['puesto'] = $this->puesto;
            $numerador = $this->Facencab_model->getMaxId() + 1;
            $data['id_tmpencab'] = $numerador;
            $data['idCuenta'] = 1;
            $data['tipcom_id'] = 1;
            $data['nombreCuenta'] = $this->Cuenta_model->getNombre(1);
            $data['condVtaId'] = 0;
        } else {
            $fecha = new DateTime();
            $data['fecha'] = $fecha->format('d-m-Y');
            $data['puesto'] = $this->puesto;
            $numerador = $presupuesto->idencab_tmpmov;
            $data['id_tmpencab'] = $numerador;
            $data['idCuenta'] = $presupuesto->cuentaid_tmpmov;
            $data['tipcom_id'] = ($presupuesto->cuentaid_tmpmov == 1) ? 1 : 2;
            $data['nombreCuenta'] = $this->Cuenta_model->getNombre($presupuesto->cuentaid_tmpmov);
            $data['condVtaId'] = $presupuesto->ctacte_tmpmov;
        }
        Template::set($data);
        Template::render();
    }

    function addArticulo()
    {
        $cantidad = $this->input->post('cantidad');
        $codigobarra = $this->input->post('codigobarra');
        $id_tmpencab = $this->input->post('id_tmpencab');
        $puesto = $this->input->post('puesto');
        $cuenta = $this->input->post('cuenta');
        /*****************************************
         * analizo si viene con cantidad incluida *
         *****************************************/
        $tmp_arti = explode('*', $codigobarra);
        $tmp_precio = 0;
        switch (count($tmp_arti)) {
            case 2:
                $cantidad = $tmp_arti[0];
                $codigobarra = $tmp_arti[1];
                break;
            case 3:
                $cantidad = $tmp_arti[0];
                $tmp_precio = $tmp_arti[1];
                $codigobarra = $tmp_arti[2];
                break;
            default:
                $codigobarra = $codigobarra;
                break;
        };
        /*******************************************
         * busco el articulo y la info que necesito *
         *******************************************/
        $articulo = $this->Articulos_model->getDatosPresupuesto($codigobarra);

        /*********************************
         * analizo la accion es descuento *
         **********************************/
        $tamano = strlen($codigobarra) - 2;
        $primerDigito = substr($codigobarra, 1, $tamano);
        $codigobarra = ($primerDigito == "-") ? substr($codigobarra, 0) : $codigobarra;

        if ($primerDigito == "-") {
            $accion = "del";
        } else {
            if (count($articulo) < 1 && trim($codigobarra) != "") {
                $accion = "nada";
            } else {
                $accion = "add";
            }
        };
        $data['existe'] = (count($articulo) < 1 && trim($codigobarra) != "") ? false : true;
        if ($tmp_precio > 0) {
            $precio = $tmp_precio;
        } else {
            if (isset($articulo->precio)) {
                $precio = floatval($articulo->precio);
            } else {
                $precio = 0;
            };
        };
        /*********************************************
         * agrego o quito del comprobante el articulo *
         *********************************************/
        if ($accion == "add" && trim($codigobarra) != '') {
            $resultado = $this->Tmpmovim_model->agregoAlComprobante($id_tmpencab, $codigobarra, $cantidad, $precio, $puesto, $cuenta);
        };
        $data['valorCero'] = ($precio == 0 && trim($codigobarra) != '' && $data['existe']) ? true : false;
        $data['puesto'] = $puesto;
        $data['id_tmpencab'] = $id_tmpencab;
        $fecha = new DateTime();
        $data['fechoy'] = $fecha->format('d/m/Y');
        $data['totales'] = $this->Tmpmovim_model->getTotales($id_tmpencab, $puesto);
        $data['Articulos'] = $this->Tmpmovim_model->getArticulos($id_tmpencab, $puesto);
        if (!$data['Articulos']) {
            $data['idCuenta'] = 1;
            $data['nombreCuenta'] = $this->Cuenta_model->getNombre(1);
            $data['condVta'] = ($this->Cuenta_model->getCtacte(1) == 1) ? "Cta Cte" : "Contado";
        } else {
            $data['idCuenta'] = $data['Articulos'][0]->cuenta;
            $data['nombreCuenta'] = $this->Cuenta_model->getNombre($data['Articulos'][0]->cuenta);
            $data['condVta'] = ($this->Cuenta_model->getCtacte($data['Articulos'][0]->cuenta) == 1) ? "Cta Cte" : "Contado";
        };
        $data['codigobarra'] = $codigobarra;
        if (!$data['existe']) {
            $this->Articulos_model->agregoLog($codigobarra, 'pos/factura/presupuesto');
        }
        $this->load->view('pos/factura/presupuestoDetalle', $data);
    }

    function delArticulo()
    {
        $id_tmpmov = $this->input->post('codmov');
        $id_tmpencab = $this->Tmpmovim_model->delArticulo($id_tmpmov);
        $puesto = $this->puesto;
        $data['Articulos'] = $this->Tmpmovim_model->getArticulos($id_tmpencab, $puesto);
        $data['valorCero'] = false;
        $data['existe'] = true;
        $data['puesto'] = $puesto;
        $data['id_tmpencab'] = $id_tmpencab;
        $fecha = new DateTime();
        $data['fechoy'] = $fecha->format('d/m/Y');
        $data['totales'] = $this->Tmpmovim_model->getTotales($id_tmpencab, $puesto);
        $data['Articulos'] = $this->Tmpmovim_model->getArticulos($id_tmpencab, $puesto);
        $data['idCuenta'] = 1;
        $data['tipcom_id'] = 1;
        $data['nombreCuenta'] = $this->Cuenta_model->getNombre(1);
        $data['condVtaId'] = 0;
        $data['condVta'] = ($this->Cuenta_model->getCtacte(1) == 1) ? "Cta Cte" : "Contado";
        $this->load->view('pos/factura/presupuestoDetalle', $data);
    }

    function cancelo()
    {
        $id_tmpencab = $this->input->post('id_tmpencab');
        $puesto = $this->input->post('puesto');
        $this->Tmpmovim_model->vacio($id_tmpencab, $puesto);
        $data['Articulos'] = $this->Tmpmovim_model->getArticulos($id_tmpencab, $puesto);
        $data['valorCero'] = false;
        $data['existe'] = true;
        $data['puesto'] = $puesto;
        $data['id_tmpencab'] = $id_tmpencab;
        $fecha = new DateTime();
        $data['fechoy'] = $fecha->format('d/m/Y');
        $data['totales'] = $this->Tmpmovim_model->getTotales($id_tmpencab, $puesto);
        $data['Articulos'] = $this->Tmpmovim_model->getArticulos($id_tmpencab, $puesto);
        $data['idCuenta'] = 1;
        $data['tipcom_id'] = 1;
        $data['nombreCuenta'] = $this->Cuenta_model->getNombre(1);
        $data['condVtaId'] = 0;
        $data['condVta'] = ($this->Cuenta_model->getCtacte(1) == 1) ? "Cta Cte" : "Contado";
        $this->load->view('pos/factura/presupuestoDetalle', $data);
    }

    function cambioCuenta()
    {
        $puesto = $this->input->post('puesto');
        $id_tmpencab = $this->input->post('id_tmpencab');
        $cuenta = $this->input->post('cuenta');
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        $this->Tmpmovim_model->cambioCuenta($puesto, $id_tmpencab, $cuenta, $cliente->ctacte);
        //Template::render();
    }

    function printTicket($puesto, $idencab, $tipcom_id, $vale)
    {
        //$this->output->enable_profiler(true);
        //preparo el comprobante a imprimir
        $items = $this->Tmpmovim_model->itemsComprobante($puesto, $idencab);
        $total = $this->Tmpmovim_model->totalComprobante($puesto, $idencab);
        $cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        /*
         * documentos = tipcom_id
         * posibles resultados
         * 1 ticket comun
         * 2 factura comun
         * 6 dnf para cuenta corriente sin factura
         */
        //genero el archivo
        switch ($tipcom_id) {
            case 1:
                $nom_archiv = $this->_imprimeTicket($puesto, $idencab, $items, $total);
                $data['file'] = $nom_archiv;
                $data['puesto'] = $puesto;
                $data['idencab'] = $idencab;
                $data['cuenta'] = $cuenta;
                $data['tipcom_id'] = 1;
                $data['DNF'] = $vale;
                $data['accion'] = 'printTicketDo';
                $data['Imprimo'] = 'Ticket';
                break;
            case 2:
                $data['file'] = $this->_imprimeFactura($puesto, $idencab, $items, $total, $cliente);
                $data['puesto'] = $puesto;
                $data['idencab'] = $idencab;
                $data['cuenta'] = $cuenta;
                $data['tipcom_id'] = 2;
                $data['DNF'] = $vale;
                $data['accion'] = 'printFacturaDo';
                $data['Imprimo'] = 'Factura';
                break;
            case 6:
                $ptorem = 90 + $puesto;
                $numrem = $this->Numeradores_model->getNextRemito($ptorem);
                $firma = ($vale == 0) ? false : true;
                $detalle = 1;
                if ($this->PrinterRemito == 1) {
                    $data['file'] = $this->_imprimeDNF($ptorem, $numrem, $puesto, $idencab, $cliente, $items, $detalle, $firma);
                } else {
                    $data['file'] = $this->_imprimeDNFLaser($ptorem, $numrem, $puesto, $idencab, $cliente, $items, false);
                };
                $data['puesto'] = $puesto;
                $data['idencab'] = $idencab;
                $data['cuenta'] = $cuenta;
                $data['tipcom_id'] = 6;
                $data['DNF'] = $vale;
                //$data['accion']    = 'printRemitoDo';
                $data['accion'] = 'printRemitoDoLaser';
                $data['Imprimo'] = 'Comprobante';
                break;
        };
        $this->load->view('pos/factura/carga', $data);
    }

    function printCtaCte($cuenta, $puesto, $numero, $importe, $idFacencab)
    {
        $this->output->enable_profiler(true);
        //preparo el comprobante a imprimir
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        $ptorem = 80 + $puesto;
        $numrem = $this->Numeradores_model->getNextCompCtaCte($ptorem);
        $numeroFac = $this->Facencab_model->getNumeroFromIdencab($idFacencab);
        //genero el archivo
        $data['file'] = $this->_imprimeDNFCtaCte($ptorem, $numrem, $puesto, $numeroFac, $cliente, $importe);
        $data['puesto'] = $puesto;
        $data['idencab'] = $idFacencab;
        $data['cuenta'] = $cuenta;
        $data['tipcom_id'] = 7; //comprobante de CtaCte
        $data['importe'] = $importe;
        $data['accion'] = 'printCtaCteDo';
        $data['Imprimo'] = 'Compr. CtaCte';
        $this->load->view('pos/factura/carga', $data);
    }

    function printCtaCteLaser($cuenta, $puesto, $numero, $importe, $idFacencab, $items)
    {
        $this->output->enable_profiler(true);
        //preparo el comprobante a imprimir
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        $ptorem = 80 + $puesto;
        $numrem = $this->Numeradores_model->getNextCompCtaCte($ptorem);
        $numeroFac = $this->Facencab_model->getNumeroFromIdencab($idFacencab);
        //genero el archivo
        $data['file'] = $this->_imprimeDNFLaser($ptorem, $numrem, 90 + $puesto, $numeroFac, $cliente, $items, true);
        $data['puesto'] = $puesto;
        $data['idencab'] = $idFacencab;
        $data['cuenta'] = $cuenta;
        $data['tipcom_id'] = 7; //comprobante de CtaCte
        $data['importe'] = $importe;
        $data['accion'] = 'printCtaCteDo';
        $data['Imprimo'] = 'Compr. CtaCte';
        $this->load->view('pos/factura/carga', $data);
    }

    function printTicketDo()
    {
        $this->load->library('hasar');
        $puesto = $this->input->post('puesto');
        $idencab = $this->input->post('idencab');
        $cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $tipcom_id = $this->input->post('tipcom');
        $DNF = $this->input->post('DNF');
        $estado = ($DNF == 1) ? 9 : 1;
        $this->hasar->setPuesto($puesto);
        $this->hasar->nombres($this->input->post('file'));
        $respuesta = $this->hasar->RespuestaFull();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $numero = $this->hasar->last_print;
        $items = $this->Tmpmovim_model->itemsComprobante($puesto, $idencab);
        $letra = "T";
        $ivamax = 0;
        $ivamin = 0;
        foreach ($items as $item) {
            $datosMovim[] = array(
                'tipcomid_movim' => $tipcom_id,
                'puesto_movim' => $puesto,
                'numero_movim' => $numero,
                'letra_movim' => $letra,
                'id_articulo' => $item->id_articulo,
                'codigobarra_movim' => $item->codigobarra,
                'cantidad_movim' => $item->cantidad,
                'preciovta_movim' => $item->precio,
                'tasaiva_movim' => $item->iva
            );
            if ($item->iva > 20) {
                $ivamax += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            } else {
                $ivamin += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            }
        }
        $datosEncab = array(
            'tipcom_id' => $tipcom_id,
            'puesto' => $puesto,
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $cuenta,
            'importe' => $this->hasar->importe,
            'neto' => $this->hasar->importe - $this->hasar->ivatot,
            'ivamin' => $ivamin,
            'ivamax' => $ivamax,
            'impint' => 0,
            'ingbru' => 0,
            'percep' => 0,
            'estado' => $estado
        );
        $idFacencab = $this->Facencab_model->graboComprobante($datosEncab, $datosMovim);
        if ($DNF == 1) {
            $this->printCtaCte($cuenta, $puesto, $numero, $this->hasar->importe, $idFacencab);
        };
        //$this->load->view('pos/carga');
    }

    function printFacturaDo()
    {
        $this->load->library('hasar');
        $puesto = $this->input->post('puesto');
        $idencab = $this->input->post('idencab');
        $cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $tipcom_id = $this->input->post('tipcom');
        $DNF = $this->input->post('DNF');
        $estado = ($DNF == 1) ? 9 : 1;
        $this->hasar->setPuesto($puesto);
        $this->hasar->nombres($this->input->post('file'));
        $respuesta = $this->hasar->RespuestaFull();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $numero = $this->hasar->last_print;
        $items = $this->Tmpmovim_model->itemsComprobante($puesto, $idencab);
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        $letra = ($cliente->condiva == 1) ? "A" : "B";
        $ivamax = 0;
        $ivamin = 0;
        foreach ($items as $item) {
            $datosMovim[] = array(
                'tipcomid_movim' => $tipcom_id,
                'puesto_movim' => $puesto,
                'numero_movim' => $numero,
                'letra_movim' => $letra,
                'id_articulo' => $item->id_articulo,
                'codigobarra_movim' => $item->codigobarra,
                'cantidad_movim' => $item->cantidad,
                'preciovta_movim' => $item->precio,
                'tasaiva_movim' => $item->iva
            );
            if ($item->iva > 20) {
                $ivamax += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            } else {
                $ivamin += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            }
        }
        $datosEncab = array(
            'tipcom_id' => $tipcom_id,
            'puesto' => $puesto,
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $cuenta,
            'importe' => $this->hasar->importe,
            'neto' => $this->hasar->importe - $this->hasar->ivatot,
            'ivamin' => $ivamin,
            'ivamax' => $ivamax,
            'impint' => 0,
            'ingbru' => 0,
            'percep' => 0,
            'estado' => $estado
        );
        $idFacencab = $this->Facencab_model->graboComprobante($datosEncab, $datosMovim);
        if ($DNF == 1) {
            $this->printCtaCte($cuenta, $puesto, $numero, $this->hasar->importe, $idFacencab);
        };
    }

    function printRemitoDo()
    {
        $this->load->library('hasar');
        $puesto = $this->input->post('puesto');
        $idencab = $this->input->post('idencab');
        $cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $tipcom_id = $this->input->post('tipcom');
        $DNF = $this->input->post('DNF');
        $estado = ($DNF == 1) ? 9 : 1;
        $ptorem = 90 + $puesto;
        $numero = $this->Numeradores_model->getNextRemito($ptorem);
        $this->hasar->setPuesto($puesto);
        $this->hasar->nombres($this->input->post('file'));
        $respuesta = $this->hasar->RespuestaFull();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $items = $this->Tmpmovim_model->itemsComprobante($puesto, $idencab);
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        $letra = "R";
        $ivamax = 0;
        $ivamin = 0;
        $importe = 0;
        $neto = 0;
        $negativo = ($tipcom_id == 9) ? -1 : 1;
        foreach ($items as $item) {
            $datosMovim[] = array(
                'tipcomid_movim' => $tipcom_id,
                'puesto_movim' => $ptorem,
                'numero_movim' => $numero,
                'letra_movim' => $letra,
                'id_articulo' => $item->id_articulo,
                'codigobarra_movim' => $item->codigobarra,
                'cantidad_movim' => $item->cantidad,
                'preciovta_movim' => $item->precio,
                'tasaiva_movim' => $item->iva
            );
            if ($item->iva > 20) {
                $ivamax += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            } else {
                $ivamin += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            }
            $importe += $item->precio * $item->cantidad;
            $neto += $item->precio / (1 + ($item->iva / 100)) * $item->cantidad;
        }
        $datosEncab = array(
            'tipcom_id' => $tipcom_id,
            'puesto' => $ptorem,
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $cuenta,
            'importe' => $importe,
            'neto' => $neto,
            'ivamin' => $ivamin,
            'ivamax' => $ivamax,
            'impint' => 0,
            'ingbru' => 0,
            'percep' => 0,
            'estado' => $estado
        );
        $idFacencab = $this->Facencab_model->graboComprobante($datosEncab, $datosMovim);
        $num = $this->Numeradores_model->updateRemito($ptorem, $numero + 1);
        //$this->load->view('pos/carga');
        if ($DNF == 1) {
            $this->printCtaCte($cuenta, $puesto, $numero, $importe * $negativo, $idFacencab);
        };
    }

    function printRemitoDoLaser()
    {
        $puesto = $this->input->post('puesto');
        $idencab = $this->input->post('idencab');
        $cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $tipcom_id = $this->input->post('tipcom');
        $DNF = $this->input->post('DNF');
        $estado = ($DNF == 1) ? 9 : 1;
        $ptorem = 90 + $puesto;
        $numero = $this->Numeradores_model->getNextRemito($ptorem);
        $items = $this->Tmpmovim_model->itemsComprobante($puesto, $idencab);
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        $letra = "R";
        $ivamax = 0;
        $ivamin = 0;
        $importe = 0;
        $neto = 0;
        $negativo = ($tipcom_id == 9) ? -1 : 1;
        foreach ($items as $item) {
            $datosMovim[] = array(
                'tipcomid_movim' => $tipcom_id,
                'puesto_movim' => $ptorem,
                'numero_movim' => $numero,
                'letra_movim' => $letra,
                'id_articulo' => $item->id_articulo,
                'codigobarra_movim' => $item->codigobarra,
                'cantidad_movim' => $item->cantidad,
                'preciovta_movim' => $item->precio,
                'tasaiva_movim' => $item->iva
            );
            if ($item->iva > 20) {
                $ivamax += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            } else {
                $ivamin += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            }
            $importe += $item->precio * $item->cantidad;
            $neto += $item->precio / (1 + ($item->iva / 100)) * $item->cantidad;
        }
        $datosEncab = array(
            'tipcom_id' => $tipcom_id,
            'puesto' => $ptorem,
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $cuenta,
            'importe' => $importe,
            'neto' => $neto,
            'ivamin' => $ivamin,
            'ivamax' => $ivamax,
            'impint' => 0,
            'ingbru' => 0,
            'percep' => 0,
            'estado' => $estado
        );
        $idFacencab = $this->Facencab_model->graboComprobante($datosEncab, $datosMovim);
        $num = $this->Numeradores_model->updateRemito($ptorem, $numero + 1);
        if ($DNF == 1) {
            $this->printCtaCteLaser($cuenta, $puesto, $numero, $importe * $negativo, $idFacencab, $items);
        } else {
            echo "termino";
        }
    }

    function printCtaCteDo()
    {
        $this->load->model('Ctactemovim_model', '', true);
        $puesto = $this->input->post('puesto');
        $idencab = $this->input->post('idencab');
        $cuenta = $this->input->post('cuentaAjax');
        $importe = $this->input->post('importe');
        $estado = 'P';
        $ptorem = 80 + $puesto;
        $numero = $this->Numeradores_model->getNextCompCtaCte($ptorem);
        $datosEncab = array(
            'puesto' => $ptorem,
            'numero' => $numero,
            'importe' => $importe,
            'id_cuenta' => $cuenta,
            'idencab' => $idencab,
            'estado' => $estado
        );
        $this->Ctactemovim_model->graboComprobante($datosEncab);
        $num = $this->Numeradores_model->updateCompCtaCte($ptorem, $numero + 1);
    }

    function _imprimeTicket($puesto, $idencab, $items, $total)
    {
        $this->load->library("hasar");
        $this->load->library("ticket");
        $this->ticket->setPuesto($puesto);
        $comprobante = "t";
        $nom_archiv = $comprobante . $idencab;
        $this->ticket->nombres($nom_archiv);
        $this->ticket->AbrirTicket();
        //$Cf->TextoTicket();
        $this->ticket->ItemTicket($items);
        $this->ticket->SubTotalTicket();
        $this->ticket->TotalTicket($total);
        $this->ticket->CerrarTicket();
        return $nom_archiv;
    }

    function _imprimeFactura($puesto, $idencab, $items, $total, $cliente)
    {
        $this->load->library("hasar");
        $this->load->library("df");
        $this->df->setPuesto($puesto);
        $comprobante = "f";
        $nom_archiv = $comprobante . $idencab;
        $this->df->nombres($nom_archiv);
        $tipdoc = ($cliente->tipdoc == 2) ? "C" : 2;
        $cliNom = ($cliente->datos_fac == 1) ? $cliente->nombre_facturacion : $cliente->nombre;
        $this->df->DatosCliente($cliNom, $cliente->cuit, $cliente->letra615, $tipdoc);
        $tiplet = ($cliente->condiva == 1) ? "A" : "B";
        $this->df->AbrirFactura($tiplet);
        $this->df->ItemFactura($items);
        $this->df->SubTotalFactura();
        $this->df->TotalFactura($total);
        $this->df->CerrarFactura();
        return $nom_archiv;
    }

    function _imprimeDNF($ptorem, $numrem, $puesto, $idencab, $cliente, $items, $detalle = 0, $firma = false)
    {
        $this->load->library('hasar');
        $this->load->library('cnf');
        $this->cnf->setPuesto($this->puesto);
        $comprobante = "v";
        $factura = ($detalle == 1) ? $ptorem . "-" . $numrem : $puesto . "-" . $idencab;
        $nom_archiv = $comprobante . $idencab;
        $this->cnf->nombres($nom_archiv);
        $this->cnf->AbrirDNF();
        $this->cnf->NumeroDNF($ptorem, $numrem);
        $this->cnf->ItemsDNF($items, $detalle);
        $this->cnf->CierroDNF();
        return $nom_archiv;
    }

    function _imprimeDNFCtaCte($ptorem, $numrem, $puesto, $numero, $cliente, $importe)
    {
        $this->load->library('hasar');
        $this->load->library('cnf');
        $this->cnf->setPuesto($this->puesto);
        $comprobante = "x";
        $factura = $puesto . "-" . $numero;
        $nom_archiv = $comprobante . $numero;
        $this->cnf->nombres($nom_archiv);
        $this->cnf->AbrirDNF();
        $this->cnf->NumeroDNF($ptorem, $numrem);
        $this->cnf->CtaCteDNF($factura, $cliente->codigo, $cliente->nombre);
        $this->cnf->ImporteDNF($importe);
        $this->cnf->FirmaDNF();
        $this->cnf->CierroDNF();
        return $nom_archiv;
    }

    function printTicketDoManual($puesto, $idencab, $cuenta, $file)
    {
        $this->load->library('hasar');
        $this->hasar->setPuesto($puesto);
        $this->hasar->nombres($file);
        $respuesta = $this->hasar->RespuestaFull();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        if ($cuenta == 1) {
            $tipcom_id = 1;
            $numero = $this->hasar->tkt_ultimo;
            $letra = "T";
            $estado = 1;
        } else {
            $cliente = $this->Cuenta_model->getById($cuenta);
            $tipcom_id = 2;
            $numero = $this->hasar->fac_ultimo;
            $letra = $cliente->letra;
            $estado = ($cliente->ctacte == 1) ? 9 : 1;
        }
        $numero = $this->hasar->last_print;
        $items = $this->Tmpmovim_model->itemsComprobante($puesto, $idencab);
        $ivamax = 0;
        $ivamin = 0;
        foreach ($items as $item) {
            $datosMovim[] = array(
                'puesto_movim' => $puesto,
                'numero_movim' => $numero,
                'letra_movim' => $letra,
                'id_articulo' => $item->id_articulo,
                'codigobarra_movim' => $item->codigobarra,
                'cantidad_movim' => $item->cantidad,
                'preciovta_movim' => $item->precio,
                'tasaiva_movim' => $item->iva
            );
            if ($item->iva > 20) {
                $ivamax += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            } else {
                $ivamin += ($item->precio / (1 + ($item->iva / 100))) * $item->iva / 100;
            }
        }
        $datosEncab = array(
            'tipcom_id' => $tipcom_id,
            'puesto' => $puesto,
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $cuenta,
            'importe' => $this->hasar->importe,
            'neto' => $this->hasar->importe - $this->hasar->ivatot,
            'ivamin' => $ivamin,
            'ivamax' => $ivamax,
            'impint' => 0,
            'ingbru' => 0,
            'percep' => 0,
            'estado' => $estado
        );
        $this->Facencab_model->graboComprobante($datosEncab, $datosMovim);
        $this->load->view('pos/factura/carga');
    }

    function _imprimeDNFLaser($ptorem, $numrem, $puesto, $idencab, $cliente, $items, $firma = false)
    {
        /**
         * imprime comprobante de remito por PDF
         *
         * lee los articulos que le pasan en $items, lo arma y lo imprime
         * @param integer $ptorem numero del puesto para el remito
         * @param integer $numrem numero del comprobante para el remito
         * @param integer $puesto nuemro del puesto para el comprobante si es cuenta corriente
         * @param integer $idencab numero del comprobante por si es cuenta corriente
         * @param object $cliente todos  los datos de la cuenta
         * @param object $items todos  los items que  compro el cliente
         * @param bolean $firma define si se imprime con firma o no
         * @return boolean $resultado devuelve verdadero si se envio la impresion
         */
        $this->load->library('fpdf');
        $renglon = 0;
        $hoja = 0;
        $total = 0;
        $fechoy = new DateTime();
        $fecha = $fechoy->format("d-m-Y");
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $this->fpdf->SetTopMargin(10);
        $maxLin = ($firma) ? 17 : 20;
        $resto = count($items);
        foreach ($items as $item) {
            if ($renglon == 0) {
                //imprimo encabezado
                $this->fpdf->AddPage('P', array('100', '148'));
                $this->fpdf->SetFont('Arial', 'b', '10');
                $this->fpdf->Cell(0, 5, "Documento No Valido como Factura", 0, 1, 'C');
                $this->fpdf->Cell(70, 5, sprintf("( %s ) %s", $cliente->codigo, $cliente->nombre), 0, 0, 'L');
                $this->fpdf->Cell(30, 5, $fecha, 0, 1, 'R');

                if ($firma) {
                    $this->fpdf->Cell(50, 5, sprintf("Comp. CtaCte: %04.0f-%08.0f", $puesto, $idencab), 0, 0, 'L');
                }
                $this->fpdf->Cell(50, 5, sprintf("Rem: %04.0f-%08.0f", $ptorem, $numrem), 0, 1, 'R');
                $this->fpdf->Line(0, 25, 100, 25);
                $this->fpdf->SetFont('Arial', 'b', '8');
                $this->fpdf->SetXY(0, 25);
                $this->fpdf->Cell(10, 5, "Cant", 0, 0, 'C');
                $this->fpdf->SetXY(10, 25);
                $this->fpdf->Cell(80, 5, "Detalle", 0, 0, 'C');
                $this->fpdf->SetXY(70, 25);
                $this->fpdf->Cell(15, 5, "Unit", 0, 0, 'C');
                $this->fpdf->SetXY(85, 25);
                $this->fpdf->Cell(10, 5, "Importe", 0, 1, 'C');
                $this->fpdf->Line(0, 30, 100, 30);
                if ($hoja > 0) {
                    $linea = $renglon * 5 + 30;
                    $this->fpdf->Cell(0, 5, sprintf("Transporte --> %4.2f", $total), 0, 1, 'R');
                };
            };
            $this->fpdf->SetFont('Arial', '', '10');
            $linea = ($hoja == 0) ? $renglon * 5 + 30 : $renglon * 5 + 35;
            $this->fpdf->SetXY(0, $linea);
            $this->fpdf->Cell(10, 5, $item->cantidad, 0, 0, 'L');
            $this->fpdf->SetXY(10, $linea);
            $this->fpdf->Cell(80, 5, substr($item->detalle, 0, 27), 0, 0, 'L');
            $this->fpdf->SetXY(70, $linea);
            $this->fpdf->Cell(10, 5, $item->precio, 0, 0, 'R');
            $this->fpdf->SetXY(85, $linea);
            $this->fpdf->Cell(10, 5, sprintf("%4.2f", $item->precio * $item->cantidad), 0, 1, 'R');
            $total += ($item->cantidad * $item->precio);
            $renglon++;
            $resto--;
            if ($renglon > $maxLin) {
                //termino comprobante parcial
                if ($resto > 0) {
                    $this->fpdf->SetFont('Arial', 'b', '10');
                    $this->fpdf->Line(0, $linea + 5, 100, $linea + 5);
                    $this->fpdf->Cell(0, 5, sprintf("Transporte --> %4.2f", $total), 0, 1, 'R');
                    if ($firma) {
                        $this->fpdf->Line(20, $linea + 20, 80, $linea + 20);
                        $this->fpdf->SetXY(0, $linea + 22);
                        $this->fpdf->Cell(0, 5, "Firma del Cliente", 0, 1, 'C');
                    };
                }
                $renglon = 0;
                $hoja++;
            };
        };
        $this->fpdf->SetFont('Arial', 'b', '10');
        $this->fpdf->Line(0, $linea + 5, 100, $linea + 5);
        $this->fpdf->Cell(0, 5, sprintf("Total --> %4.2f", $total), 0, 1, 'R');
        if ($firma) {
            $this->fpdf->Line(20, $linea + 20, 80, $linea + 20);
            $this->fpdf->SetXY(0, $linea + 22);
            $this->fpdf->Cell(0, 5, "Firma del Cliente", 0, 1, 'C');
        };
        $nombre = "/var/www/fiscal/" . PUESTO . "/pdf/ticket.pdf";
        $this->fpdf->Output($nombre, 'F');
        $cmd = sprintf("lp -o media=Custom.100x148mm %s -d %s", $nombre, PRREMITO);
        shell_exec($cmd);
        return $nombre;
    }
}
