<?php

/**
 * Class Billing
 *
 * @property Tmpfacencab_model $Tmpfacencab_model
 * @property Tmpmovim_model $Tmpmovim_model
 * @property Tmpfpagos_model $Tmpfpagos_model
 * @property Hasar $Hasar
 * @property Cuenta_model $Cuenta_model
 * @property Cajamovim_model $Cajamovim_model
 */
class Billing extends Admin_Controller{
    var $PrinterDestino;

    function __construct()
    {
        parent::__construct();
        $this->PrinterDestino = 2; // 1 controlador 2 laser
        $this->load->model('Articulos_model', '', true);
        $this->load->model('Tmpmovim_model', '', true);
        $this->load->model('Tmpfacencab_model');
        $this->load->model('Tmpfpagos_model');
        $this->load->model('Numeradores_model', '', true);
        $this->load->model('Cuenta_model');
        $this->load->model('Facencab_model');
        $this->load->model('Fpagos_model');
        $this->load->model('Ctactemovim_model');
    }

    function presupuesto()
    {
        //busco datos del previo
        $presuEncab = $this->Tmpfacencab_model->getDatosUltimo($this->getPuesto());
        $fecha = new DateTime();
        $data['fechoy'] = $fecha->format('d/m/Y');
        if (!$presuEncab) { //sino existe creo uno en blanco
            $data['puesto'] = $this->getPuesto();
            $data['numero'] = $this->Facencab_model->getMaxId() + 1;
            $data['idCuenta'] = 1;
            $data['tipcom_id'] = 1;
            $data['nombreCuenta'] = $this->Cuenta_model->getNombre(1);
            //creo el presupuesto
            $numeroTemporal = $this->Tmpfacencab_model->inicializo($this->getPuesto(), $data['numero'], $data['tipcom_id'], $data['idCuenta']);
            //creo la forma de pago en efectivo con 0
            $this->Tmpfpagos_model->inicializo($numeroTemporal);
        } else { //levanto los datos del presupuesto vigente para el puesto
            $data['puesto'] = $presuEncab->puesto;
            $data['numero'] = $presuEncab->numero;
            $data['idCuenta'] = $presuEncab->cuenta_id;
            $data['tipcom_id'] = ($presuEncab->cuenta_id == 1) ? 1 : 2;
            $data['nombreCuenta'] = $this->Cuenta_model->getNombre($presuEncab->cuenta_id);
            $numeroTemporal = $presuEncab->id;
        }
        $data['presuEncab'] = $this->Tmpfacencab_model->getComprobante($numeroTemporal);
        $data['totales'] = $this->Tmpmovim_model->getTotales($numeroTemporal);
        $data['Articulos'] = $this->Tmpmovim_model->getArticulos($numeroTemporal);
        $data['tmpfacencab_id'] = $numeroTemporal;
        $data['fpagos'] = $this->Tmpfpagos_model->getPagosComprobante($numeroTemporal);
        $data['total'] = 0;
        $data['paginaMuestroFpagos'] = "'" . base_url() . "index.php/pos/billing/muestroFpagos/" . $numeroTemporal . "'";
        $data['paginaCambioComprob'] = "'" . base_url() . "pos/billing/cambioTipoComprobante/'";
        $data['mediosDePagos'] = $this->Fpagos_model->getAll();
        $data['tiposMdP'] = array('EFECTIVO' => array('label' => 'success', 'icon' => 'fa-money'),
            'CTACTE' => array('label' => 'primary', 'icon' => 'fa-users'),
            'DEBITO' => array('label' => 'primary', 'icon' => 'fa-credit-card'),
            'TARJETA' => array('label' => 'warning', 'icon' => 'fa-credit-card'),
            'CHEQUE' => array('label' => 'danger', 'icon' => 'fa-suitcase'));
        Template::set($data);
        Template::render();
    }

    function muestroFpagos($tmpfacencab_id)
    {
        $this->output->enable_profiler(true);
        $fpagos = $this->Tmpfpagos_model->getPagosComprobante($tmpfacencab_id);
        $jsonString = json_encode($fpagos);
        header('Content-Type: application/json');
        echo $jsonString;
    }

    function addArticulo()
    {
        $this->output->enable_profiler(false);
        $codigobarra = $this->input->post('codigobarra');
        $tmpfacencab_id = $this->input->post('tmpfacencab_id');
        $precio = ($this->input->post('precio')) ? $this->input->post('precio') : FALSE;
        $cantidad = ($this->input->post('cantidad')) ? $this->input->post('cantidad') : 1;
        $error = TRUE;

        // busco articulo y traigo datos, precio y estado
        $articulo = $this->Articulos_model->getDatosPresupuesto($codigobarra);
        if (!$articulo) { //verifico que el articulo exista
            $error = TRUE;
            $errorTipo = 'El articulo NO EXISTE en la base de datos';
        } else {
            if ($articulo->precio == 0) { //verifico que el articulo tenga precio superior a 0
                $error = TRUE;
                $errorTipo = "El articulo no POSEE PRECIO";
            } else {
                if ($articulo->estado === SUSPENDIDO) { //verifico que el articulo no este suspendido
                    $error = TRUE;
                    $errorTipo = "El articulo esta SUSPENDIDO";
                } else { // el articulo tiene un precio aceptable
                    $articulo->precio = (!$precio) ? $articulo->precio : $precio;
                    $error = FALSE;
                    $errorTipo = '';
                };
            };
        };
        if (!$error) { // si no existen errores continuo con el proceso
            $renglon = $this->Tmpmovim_model->agregoAlComprobante($tmpfacencab_id, $codigobarra, $cantidad, $articulo->precio);//agrego al comprobante
            $renglonFinal = $this->Tmpmovim_model->getRenglon($renglon);
            //var_dump($renglon);
            $totales = $this->Tmpmovim_model->getTotales($tmpfacencab_id);//busco totales
            /* actualizo fpagos */
            $this->Tmpfpagos_model->actualizoPagos($tmpfacencab_id, $totales->Total);
            $resultado = $this->Tmpfacencab_model->updateTotales($tmpfacencab_id, $totales->Total);// actualizo totales
            $json = array('id' => $renglon, 'codigoB' => $codigobarra, 'descripcion' => $renglonFinal->nombre, 'cantidad' => sprintf("%5.2f", $renglonFinal->cantidad), 'precio' => sprintf("$%10.2f", $renglonFinal->precio), 'importe' => sprintf("$%10.2f", $renglonFinal->cantidad * $renglonFinal->precio), 'error' => $error, 'errorTipo' => $errorTipo, 'Totales' => sprintf("$%10.2f", $totales->Total), 'Bultos' => $totales->Bultos, 'Formas' => $resultado);

        } else {
            $this->Articulos_model->agregoLog($codigobarra, 'pos/billing/addArticulo', $errorTipo);
            $detalle = (isset($articulo->nombre)) ? $articulo->nombre : '';
            $json = array('codigoB' => $codigobarra, 'descripcion' => $detalle, 'error' => $error, 'errorTipo' => $errorTipo, 'precioViejo' => $precio);
        }
        $jsonString = json_encode($json);
        header('Content-Type: application/json');
        echo $jsonString;
    }

    function delArticulo($id)
    {
        $tmpfacencab_id = $this->Tmpmovim_model->delArticulo($id);
        $totales = $this->Tmpmovim_model->getTotales($tmpfacencab_id);//busco totales
        /* actualizo fpagos */
        $this->Tmpfpagos_model->actualizoPagos($tmpfacencab_id, $totales->Total);
        //$totales = $this->Tmpmovim_model->getTotales($tmpfacencab_id);//busco totales
        //$resultado = $this->Tmpfacencab_model->updateTotales($tmpfacencab_id,$totales->Total);// actualizo totales
        Template::redirect('pos/billing/presupuesto');
    }

    function cancelo()
    {
        $id = $this->input->post('tmpfacencab_id');
        $this->Tmpfpagos_model->vacio($id);
        $this->Tmpmovim_model->vacio($id);
        $this->Tmpfacencab_model->vacio($id);
    }

    function cambioCuenta($tmpfacencab_id, $cuenta_id)
    {
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta_id);
        $this->Tmpfacencab_model->cambioCuenta($tmpfacencab_id, $cuenta_id);
        /* si es ctecte asumir ctacte com oforma de pago */
        if ($cliente->ctacte == 1) {
            $this->Tmpfpagos_model->cambiarFpFull($tmpfacencab_id, 9);
        } else {
            $this->Tmpfpagos_model->cambiarFpFull($tmpfacencab_id, 1);
        }
        Template::redirect('pos/billing/presupuesto');
    }

    public function cambioTipoComprobante()
    {
        $this->output->enable_profiler(false);
        $id = $this->input->get('id');
        $tipcom_id = intval($this->input->get('tipo'));
        $this->Tmpfacencab_model->cambioComprobante($id, $tipcom_id);
        switch ($tipcom_id) {
            case 1:
                $tipo['nombre'] = 'Ticket';
                break;
            case 2:
                $tipo['nombre'] = 'Factura';
                break;
            case 6:
                $tipo['nombre'] = 'Remito';
                break;
        };
        $jsonString = json_encode($tipo);
        header('Content-Type: application/json');
        echo $jsonString;
        //Template::redirect('pos/billing/presupuesto');
    }

    function cambioCondicion(){
        $puesto = $this->input->post('puesto');
        $id_tmpencab = $this->input->post('id_tmpencab');
        $cuenta = $this->input->post('condVtaId');
        $cliente = $this->Cuenta_model->getByIdComprobante($cuenta);
        $this->Tmpmovim_model->cambioCuenta($puesto, $id_tmpencab, $cuenta, $cliente->ctacte);
        //Template::render();
    }

    function emitoComprobante(){
        $this->output->enable_profiler(false);
        $this->load->library('hasar');
        $tmpfacenab_id = $this->input->post('tmpfacencab');
        //leo comporobante completo
        $comprobante = $this->Tmpfacencab_model->getById($tmpfacenab_id);
        $renglones = $this->Tmpmovim_model->itemsComprobante($tmpfacenab_id);
        $cliente = $this->Cuenta_model->getByIdComprobante($comprobante->cuenta_id);
        $total = $this->Tmpmovim_model->totalComprobante($comprobante->id);
        $fpagos = $this->Tmpfpagos_model->getPagosComprobante($tmpfacenab_id);
        $ctacte = false;
        foreach ($fpagos as $fp) {
            if ($fp->fpagos_id == 9) {
                $ctacte = true;
                break;
            }
        }
        /*
       * documentos = tipcom_id
       * posibles resultados
       * 1 ticket
       * 2 factura
       * 6 dnf
       */
        //imprimo comprobante
        switch ($comprobante->tipcom_id) {
            case 1:
                $archivo = $this->_imprimeTicket($this->getPuesto(), $comprobante->id, $renglones, $total, false);

                $this->hasar->setPuesto($this->getPuesto());
                $this->hasar->nombres($archivo);
                $respuesta = $this->hasar->RespuestaFull();
                //$respEstado = $this->hasar->Estado();
                $comprobante->numero = $this->hasar->last_print;
                $comprobante->importe = $this->hasar->importe;
                $ivatot = $this->hasar->importe;
                $letra = 'T';

                break;
            case 2:
                $archivo = $this->_imprimeFactura($this->getPuesto(), $comprobante->id, $renglones, $total, $cliente);

                $this->hasar->setPuesto($this->getPuesto());
                $this->hasar->nombres($archivo);
                $respuesta = $this->hasar->RespuestaFull();
                //$respEstado = $this->hasar->Estado();
                $comprobante->numero = $this->hasar->last_print;
                $comprobante->importe = $this->hasar->importe;
                $ivatot = $this->hasar->importe;

                $letra = 'T'; //ver cliente

                break;
            case 6:
                $ptorem = 90 + $comprobante->puesto;
                $numrem = $this->Numeradores_model->getNextRemito($ptorem);
                $ivatot = 0;
                if ($this->PrinterDestino == 1) {
                    $archivo = $this->_imprimeDNF($ptorem, $numrem, $this->getPuesto(), $comprobante->id, $cliente, $renglones, 1, false);

                    $this->hasar->setPuesto($this->getPuesto());
                    $this->hasar->nombres($archivo);
                    $respuesta = $this->hasar->RespuestaFull();
                    //$respEstado = $this->hasar->Estado();

                } else {
                    $archivo = $this->_imprimeDNFLaser($ptorem, $numrem, $comprobante->puesto, $comprobante->id, $cliente, $renglones, false);
                };
                $letra = 'R';
                break;
        }
        /*
         * grabo comprobante
         */
        $ivamax = 0;
        $ivamin = 0;
        foreach ($renglones as $item) {
            $datosMovim[] = array(
                'tipcomid_movim' => $comprobante->tipcom_id,
                'puesto_movim' => $comprobante->puesto,
                'numero_movim' => $comprobante->numero,
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
            'tipcom_id' => $comprobante->tipcom_id,
            'puesto' => $comprobante->puesto,
            'numero' => $comprobante->numero,
            'letra' => $letra,
            'cuenta_id' => $comprobante->cuenta_id,
            'importe' => $comprobante->importe,
            'neto' => $comprobante->importe - $ivatot,
            'ivamin' => $ivamin,
            'ivamax' => $ivamax,
            'impint' => 0,
            'ingbru' => 0,
            'percep' => 0,
            'estado' => ($ctacte) ? 9 : 1
        );
        $idFacencab = $this->Facencab_model->graboComprobante($datosEncab, $datosMovim);
        /**
         * GRABO MOVIEIMTNO DE CAJA
         */
        $cajaOK = $this->_graboCaja($idFacencab, $comprobante->tipcom_id, $fpagos);
        /**
         * IMPRIMO MOVIMEINTO DE CTACTE
         */
        if ($ctacte) {
            //preparo el comprobante a imprimir
            $ptorem = 80 + $comprobante->puesto;
            $numrem = $this->Numeradores_model->getNextCompCtaCte($ptorem);
            $numeroFac = $comprobante->numero;
            //genero el archivo
            $this->_imprimeDNFLaser($ptorem, $numrem, 90 + $comprobante->puesto, $numeroFac, $cliente, $renglones, true);
            $estado = 'P';
            $ptorem = 80 + $comprobante->puesto;
            $numero = $this->Numeradores_model->getNextCompCtaCte($ptorem);
            $datosEncab = array(
                'puesto' => $ptorem,
                'numero' => $numero,
                'importe' => $comprobante->importe,
                'id_cuenta' => $comprobante->cuenta_id,
                'idencab' => $idFacencab,
                'estado' => $estado
            );
            $this->Ctactemovim_model->graboComprobante($datosEncab);
            $num = $this->Numeradores_model->updateCompCtaCte($ptorem, $numero + 1);
        }
        /*
         * limpio los temporales
         */
        $this->Tmpfpagos_model->vacio($comprobante->id);
        $this->Tmpmovim_model->vacio($comprobante->id);
        $this->Tmpfacencab_model->vacio($comprobante->id);
        /*
         * libero la pagina
         */
        echo json_encode('ok');
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
        $this->cnf->setPuesto($this->getPuesto());
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
        $this->cnf->setPuesto($this->getPuesto());
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
        $fecha = $fechoy->format("d/m/Y H:m");
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
                $this->fpdf->Cell(70, 5, sprintf("( %s ) %s", $cliente->codigo, $cliente->nombre), 0, 1, 'L');

                if ($firma) {
                    $this->fpdf->Cell(50, 5, sprintf("Comp. CtaCte: %04.0f-%08.0f", $puesto, $idencab), 0, 0, 'L');
                }
                $this->fpdf->Cell(50, 5, sprintf("Rem: %04.0f-%08.0f", $ptorem, $numrem), 0, 0, 'L');
                $this->fpdf->Cell(50, 5, $fecha, 0, 1, 'R');
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
            $this->fpdf->SetFont('Arial', '', '8');
            $linea = ($hoja == 0) ? $renglon * 4 + 30 : $renglon * 4 + 35;
            $this->fpdf->SetXY(0, $linea);
            $this->fpdf->Cell(10, 5, $item->cantidad, 0, 0, 'L');
            $this->fpdf->SetXY(10, $linea);
            $this->fpdf->Cell(80, 5, substr($item->detalle, 0, 30), 0, 0, 'L');
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
        $nombre = ABSOLUT_PATH . '/' . $this->getPuesto() . "/pdf/ticket.pdf";
        $this->fpdf->Output($nombre, 'F');
        $cmd = sprintf("lp -o media=Custom.100x148mm %s -d %s", $nombre, PRREMITO);
        shell_exec($cmd);
        return $nombre;
    }

    private function _graboCaja($idFacencab, $tipcom, $fpagos)
    {
        $this->load->model('Tipcom_model');
        $this->load->model('Cajamovim_model');
        $concepto = $this->Tipcom_model->getConceptoCaja($tipcom);
        foreach ($fpagos as $fp) {
            $datosCaja = array(
                'caja_id' => 1,
                'concepto_id' => $concepto,
                'facencab_id' => $idFacencab,
                'fpago_id' => $fp->fpagos_id,
                'importe' => $fp->monto
            );
            $idCajmovim = $this->Cajamovim_model->add($datosCaja);
        }
        return $idCajmovim;
    }
}