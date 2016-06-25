<?php

/**
 * Description of caja
 *
 * @author dnl
 * @property Cajaencab_model $Cajaencab_model
 * @property Facencab_model $Facencab_model
 * @property Presuencab_model $Presuencab_model
 * @property Cuenta_model $Cuenta_model
 */
class Caja extends Admin_Controller {

    function __construct () {
        parent::__construct();
        $this->load->model ( 'Cajaencab_model' );
        $this->load->model ( "Presuencab_model" );
        $this->load->model ( "Cuenta_model" );
    }

    function index () {
        Template::render();
    }

    function open () {
        $data['puesto'] = $this->getPuesto ();
        $data['caja'] = $this->Cajaencab_model->getCajaPuesto ( $this->getPuesto (), 1 );
        $data['fecha'] = $this->getFecha ();
        Template::set ( $data );
        Template::render ();
    }

    function openDo () { }

    function facturar () {
        $data['presupuestos'] = $this->Presuencab_model->getPendientes ();
        Template::set ( $data );
        Template::render ();
    }

    function imprimir ( $formato, $idPresupuesto ) {
        $pressupuesto = $this->Presuencab_model->getById ( $idPresupuesto );
        $presumovim = $this->Presuencab_model->getComprobante ( $idPresupuesto );
        //$this->output->enable_profiler(true);
        //preparo el comprobante a imprimir
        $total = $this->Presuencab_model->getTotales ( $idPresupuesto );
        $cliente = $this->Cuenta_model->getById ( $pressupuesto->cuenta_id );
        $items = $this->Presuencab_model->getArticulos ( $idPresupuesto );
        $vale = false;
        switch ( $formato ) {
            case "pdf":
                $tipcom_id = 6;
                break;
            case "controlador":
                $tipcom_id = 2;
                break;
            default:
                $tipcom_id = 2;
                break;
        };
        switch ( $tipcom_id ) {
            case 1:
                $nom_archiv = $this->_imprimeTicket ( $this->getPuesto (), $idPresupuesto, $items, $total );
                $data['file'] = $nom_archiv;
                $data['puesto'] = $this->getPuesto ();
                $data['idencab'] = $idPresupuesto;
                $data['cuenta'] = $pressupuesto->cuenta_id;
                $data['tipcom_id'] = 1;
                $data['DNF'] = $vale;
                $data['accion'] = 'printTicketDo';
                $data['Imprimo'] = 'Ticket';
                break;
            case 2:
                $data['file'] = $this->_imprimeFactura ( $this->getPuesto (), $idPresupuesto, $items, $total, $cliente );
                $data['puesto'] = $this->getPuesto ();
                $data['idencab'] = $idPresupuesto;
                $data['cuenta'] = $pressupuesto->cuenta_id;
                $data['tipcom_id'] = 2;
                $data['DNF'] = $vale;
                $data['accion'] = 'printFacturaDo';
                $data['Imprimo'] = 'Factura';
                break;
            case 6:
                $ptorem = 90 + $this->getPuesto ();
                $numrem = $this->Numeradores_model->getNextRemito ( $ptorem );
                $firma = ( $vale == 0 ) ? false : true;
                $detalle = true;
                if ( $this->PrinterRemito == 1 ) {
                    $data['file'] = $this->_imprimeDNF ( $ptorem, $numrem, $this->getPuesto (), $idPresupuesto, $cliente, $items, $detalle, $firma );
                } else {
                    $data['file'] = $this->_imprimeDNFLaser ( $ptorem, $numrem, $this->getPuesto (), $idPresupuesto, $cliente, $items, false );
                };
                $data['puesto'] = $this->getPuesto ();
                $data['idencab'] = $idPresupuesto;
                $data['cuenta'] = $pressupuesto->cuenta_id;
                $data['tipcom_id'] = 6;
                $data['DNF'] = $vale;
                $data['accion'] = 'printRemitoDoLaser';
                $data['Imprimo'] = 'Comprobante';
                break;
        };
    }

    function printCtaCte ( $cuenta, $puesto, $numero, $importe, $idFacencab ) {
        $this->output->enable_profiler ( true );
        //preparo el comprobante a imprimir
        $cliente = $this->Cuenta_model->getByIdComprobante ( $cuenta );
        $ptorem = 80 + $puesto;
        $numrem = $this->Numeradores_model->getNextCompCtaCte ( $ptorem );
        $numeroFac = $this->Facencab_model->getNumeroFromIdencab ( $idFacencab );
        //genero el archivo
        $data['file'] = $this->_imprimeDNFCtaCte ( $ptorem, $numrem, $puesto, $numeroFac, $cliente, $importe );
        $data['puesto'] = $puesto;
        $data['idencab'] = $idFacencab;
        $data['cuenta'] = $cuenta;
        $data['tipcom_id'] = 7; //comprobante de CtaCte
        $data['importe'] = $importe;
        $data['accion'] = 'printCtaCteDo';
        $data['Imprimo'] = 'Compr. CtaCte';
        $this->load->view ( 'pos/factura/carga', $data );
    }

    function printCtaCteLaser ( $cuenta, $puesto, $numero, $importe, $idFacencab, $items ) {
        $this->output->enable_profiler ( true );
        //preparo el comprobante a imprimir
        $cliente = $this->Cuenta_model->getByIdComprobante ( $cuenta );
        $ptorem = 80 + $puesto;
        $numrem = $this->Numeradores_model->getNextCompCtaCte ( $ptorem );
        $numeroFac = $this->Facencab_model->getNumeroFromIdencab ( $idFacencab );
        //genero el archivo
        $data['file'] = $this->_imprimeDNFLaser ( $ptorem, $numrem, 90 + $puesto, $numeroFac, $cliente, $items, true );
        $data['puesto'] = $puesto;
        $data['idencab'] = $idFacencab;
        $data['cuenta'] = $cuenta;
        $data['tipcom_id'] = 7; //comprobante de CtaCte
        $data['importe'] = $importe;
        $data['accion'] = 'printCtaCteDo';
        $data['Imprimo'] = 'Compr. CtaCte';
        $this->load->view ( 'pos/factura/carga', $data );
    }

    function printTicketDo () {
        $this->load->library ( 'hasar' );
        $puesto = $this->input->post ( 'puesto' );
        $idencab = $this->input->post ( 'idencab' );
        $cuenta = $this->Tmpmovim_model->getCuenta ( $idencab, $puesto );
        $tipcom_id = $this->input->post ( 'tipcom' );
        $DNF = $this->input->post ( 'DNF' );
        $estado = ( $DNF == 1 ) ? 9 : 1;
        $this->hasar->setPuesto ( $puesto );
        $this->hasar->nombres ( $this->input->post ( 'file' ) );
        $respuesta = $this->hasar->RespuestaFull ();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $numero = $this->hasar->last_print;
        $items = $this->Tmpmoººvim_model->itemsComprobante ( $puesto, $idencab );
        $letra = "T";
        $ivamax = 0;
        $ivamin = 0;
        foreach ( $items as $item ) {
            $datosMovim[] = array (
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
            if ( $item->iva > 20 ) {
                $ivamax += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            } else {
                $ivamin += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            }
        }
        $datosEncab = array (
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
        $idFacencab = $this->Facencab_model->graboComprobante ( $datosEncab, $datosMovim );
        if ( $DNF == 1 ) {
            $this->printCtaCte ( $cuenta, $puesto, $numero, $this->hasar->importe, $idFacencab );
        };
        //$this->load->view('pos/carga');
    }

    function printFacturaDo () {
        $this->load->library ( 'hasar' );
        $puesto = $this->input->post ( 'puesto' );
        $idencab = $this->input->post ( 'idencab' );
        $cuenta = $this->Tmpmovim_model->getCuenta ( $idencab, $puesto );
        $tipcom_id = $this->input->post ( 'tipcom' );
        $DNF = $this->input->post ( 'DNF' );
        $estado = ( $DNF == 1 ) ? 9 : 1;
        $this->hasar->setPuesto ( $puesto );
        $this->hasar->nombres ( $this->input->post ( 'file' ) );
        $respuesta = $this->hasar->RespuestaFull ();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $numero = $this->hasar->last_print;
        $items = $this->Tmpmovim_model->itemsComprobante ( $puesto, $idencab );
        $cliente = $this->Cuenta_model->getByIdComprobante ( $cuenta );
        $letra = ( $cliente->condiva == 1 ) ? "A" : "B";
        $ivamax = 0;
        $ivamin = 0;
        foreach ( $items as $item ) {
            $datosMovim[] = array (
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
            if ( $item->iva > 20 ) {
                $ivamax += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            } else {
                $ivamin += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            }
        }
        $datosEncab = array (
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
        $idFacencab = $this->Facencab_model->graboComprobante ( $datosEncab, $datosMovim );
        if ( $DNF == 1 ) {
            $this->printCtaCte ( $cuenta, $puesto, $numero, $this->hasar->importe, $idFacencab );
        };
    }

    function printRemitoDo () {
        $this->load->library ( 'hasar' );
        $puesto = $this->input->post ( 'puesto' );
        $idencab = $this->input->post ( 'idencab' );
        $cuenta = $this->Tmpmovim_model->getCuenta ( $idencab, $puesto );
        $tipcom_id = $this->input->post ( 'tipcom' );
        $DNF = $this->input->post ( 'DNF' );
        $estado = ( $DNF == 1 ) ? 9 : 1;
        $ptorem = 90 + $puesto;
        $numero = $this->Numeradores_model->getNextRemito ( $ptorem );
        $this->hasar->setPuesto ( $puesto );
        $this->hasar->nombres ( $this->input->post ( 'file' ) );
        $respuesta = $this->hasar->RespuestaFull ();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $items = $this->Tmpmovim_model->itemsComprobante ( $puesto, $idencab );
        $cliente = $this->Cuenta_model->getByIdComprobante ( $cuenta );
        $letra = "R";
        $ivamax = 0;
        $ivamin = 0;
        $importe = 0;
        $neto = 0;
        $negativo = ( $tipcom_id == 9 ) ? -1 : 1;
        foreach ( $items as $item ) {
            $datosMovim[] = array (
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
            if ( $item->iva > 20 ) {
                $ivamax += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            } else {
                $ivamin += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            }
            $importe += $item->precio * $item->cantidad;
            $neto += $item->precio / ( 1 + ( $item->iva / 100 ) ) * $item->cantidad;
        }
        $datosEncab = array (
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
        $idFacencab = $this->Facencab_model->graboComprobante ( $datosEncab, $datosMovim );
        $num = $this->Numeradores_model->updateRemito ( $ptorem, $numero + 1 );
        //$this->load->view('pos/carga');
        if ( $DNF == 1 ) {
            $this->printCtaCte ( $cuenta, $puesto, $numero, $importe * $negativo, $idFacencab );
        };
    }

    function printRemitoDoLaser () {
        $puesto = $this->input->post ( 'puesto' );
        $idencab = $this->input->post ( 'idencab' );
        $cuenta = $this->Tmpmovim_model->getCuenta ( $idencab, $puesto );
        $tipcom_id = $this->input->post ( 'tipcom' );
        $DNF = $this->input->post ( 'DNF' );
        $estado = ( $DNF == 1 ) ? 9 : 1;
        $ptorem = 90 + $puesto;
        $numero = $this->Numeradores_model->getNextRemito ( $ptorem );
        $items = $this->Tmpmovim_model->itemsComprobante ( $puesto, $idencab );
        $cliente = $this->Cuenta_model->getByIdComprobante ( $cuenta );
        $letra = "R";
        $ivamax = 0;
        $ivamin = 0;
        $importe = 0;
        $neto = 0;
        $negativo = ( $tipcom_id == 9 ) ? -1 : 1;
        foreach ( $items as $item ) {
            $datosMovim[] = array (
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
            if ( $item->iva > 20 ) {
                $ivamax += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            } else {
                $ivamin += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            }
            $importe += $item->precio * $item->cantidad;
            $neto += $item->precio / ( 1 + ( $item->iva / 100 ) ) * $item->cantidad;
        }
        $datosEncab = array (
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
        $idFacencab = $this->Facencab_model->graboComprobante ( $datosEncab, $datosMovim );
        $num = $this->Numeradores_model->updateRemito ( $ptorem, $numero + 1 );
        if ( $DNF == 1 ) {
            $this->printCtaCteLaser ( $cuenta, $puesto, $numero, $importe * $negativo, $idFacencab, $items );
        } else {
            echo "termino";
        }
    }

    function printCtaCteDo () {
        $this->load->model ( 'Ctactemovim_model', '', true );
        $puesto = $this->input->post ( 'puesto' );
        $idencab = $this->input->post ( 'idencab' );
        $cuenta = $this->input->post ( 'cuentaAjax' );
        $importe = $this->input->post ( 'importe' );
        $estado = 'P';
        $ptorem = 80 + $puesto;
        $numero = $this->Numeradores_model->getNextCompCtaCte ( $ptorem );
        $datosEncab = array (
            'puesto' => $ptorem,
            'numero' => $numero,
            'importe' => $importe,
            'id_cuenta' => $cuenta,
            'idencab' => $idencab,
            'estado' => $estado
        );
        $this->Ctactemovim_model->graboComprobante ( $datosEncab );
        $num = $this->Numeradores_model->updateCompCtaCte ( $ptorem, $numero + 1 );
    }

    function _imprimeTicket ( $puesto, $idencab, $items, $total ) {
        $this->load->library ( "hasar" );
        $this->load->library ( "ticket" );
        $this->ticket->setPuesto ( $puesto );
        $comprobante = "t";
        $nom_archiv = $comprobante . $idencab;
        $this->ticket->nombres ( $nom_archiv );
        $this->ticket->AbrirTicket ();
        //$Cf->TextoTicket();
        $this->ticket->ItemTicket ( $items );
        $this->ticket->SubTotalTicket ();
        $this->ticket->TotalTicket ( $total );
        $this->ticket->CerrarTicket ();
        return $nom_archiv;
    }

    function _imprimeFactura ( $puesto, $idencab, $items, $total, $cliente ) {
        $this->load->library ( "hasar" );
        $this->load->library ( "df" );
        $this->df->setPuesto ( $puesto );
        $comprobante = "f";
        $nom_archiv = $comprobante . $idencab;
        $this->df->nombres ( $nom_archiv );
        $tipdoc = ( $cliente->tipdoc == 2 ) ? "C" : 2;
        $cliNom = ( $cliente->datos_fac == 1 ) ? $cliente->nombre_facturacion : $cliente->nombre;
        $this->df->DatosCliente ( $cliNom, $cliente->cuit, $cliente->letra615, $tipdoc );
        $tiplet = ( $cliente->condiva == 1 ) ? "A" : "B";
        $this->df->AbrirFactura ( $tiplet );
        $this->df->ItemFactura ( $items );
        $this->df->SubTotalFactura ();
        $this->df->TotalFactura ( $total );
        $this->df->CerrarFactura ();
        return $nom_archiv;
    }

    function _imprimeDNF ( $ptorem, $numrem, $puesto, $idencab, $cliente, $items, $detalle = 0, $firma = false ) {
        $this->load->library ( 'hasar' );
        $this->load->library ( 'cnf' );
        $this->cnf->setPuesto ( $this->puesto );
        $comprobante = "v";
        $factura = ( $detalle == 1 ) ? $ptorem . "-" . $numrem : $puesto . "-" . $idencab;
        $nom_archiv = $comprobante . $idencab;
        $this->cnf->nombres ( $nom_archiv );
        $this->cnf->AbrirDNF ();
        $this->cnf->NumeroDNF ( $ptorem, $numrem );
        $this->cnf->ItemsDNF ( $items, $detalle );
        $this->cnf->CierroDNF ();
        return $nom_archiv;
    }

    function _imprimeDNFCtaCte ( $ptorem, $numrem, $puesto, $numero, $cliente, $importe ) {
        $this->load->library ( 'hasar' );
        $this->load->library ( 'cnf' );
        $this->cnf->setPuesto ( $this->puesto );
        $comprobante = "x";
        $factura = $puesto . "-" . $numero;
        $nom_archiv = $comprobante . $numero;
        $this->cnf->nombres ( $nom_archiv );
        $this->cnf->AbrirDNF ();
        $this->cnf->NumeroDNF ( $ptorem, $numrem );
        $this->cnf->CtaCteDNF ( $factura, $cliente->codigo, $cliente->nombre );
        $this->cnf->ImporteDNF ( $importe );
        $this->cnf->FirmaDNF ();
        $this->cnf->CierroDNF ();
        return $nom_archiv;
    }

    function printTicketDoManual ( $puesto, $idencab, $cuenta, $file ) {
        $this->load->library ( 'hasar' );
        $this->hasar->setPuesto ( $puesto );
        $this->hasar->nombres ( $file );
        $respuesta = $this->hasar->RespuestaFull ();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        if ( $cuenta == 1 ) {
            $tipcom_id = 1;
            $numero = $this->hasar->tkt_ultimo;
            $letra = "T";
            $estado = 1;
        } else {
            $cliente = $this->Cuenta_model->getById ( $cuenta );
            $tipcom_id = 2;
            $numero = $this->hasar->fac_ultimo;
            $letra = $cliente->letra;
            $estado = ( $cliente->ctacte == 1 ) ? 9 : 1;
        }
        $numero = $this->hasar->last_print;
        $items = $this->Tmpmovim_model->itemsComprobante ( $puesto, $idencab );
        $ivamax = 0;
        $ivamin = 0;
        foreach ( $items as $item ) {
            $datosMovim[] = array (
                'puesto_movim' => $puesto,
                'numero_movim' => $numero,
                'letra_movim' => $letra,
                'id_articulo' => $item->id_articulo,
                'codigobarra_movim' => $item->codigobarra,
                'cantidad_movim' => $item->cantidad,
                'preciovta_movim' => $item->precio,
                'tasaiva_movim' => $item->iva
            );
            if ( $item->iva > 20 ) {
                $ivamax += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            } else {
                $ivamin += ( $item->precio / ( 1 + ( $item->iva / 100 ) ) ) * $item->iva / 100;
            }
        }
        $datosEncab = array (
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
        $this->Facencab_model->graboComprobante ( $datosEncab, $datosMovim );
        $this->load->view ( 'pos/factura/carga' );
    }

    function _imprimeDNFLaser ( $ptorem, $numrem, $puesto, $idencab, $cliente, $items, $firma = false ) {
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
        $this->load->library ( 'fpdf' );
        $renglon = 0;
        $hoja = 0;
        $total = 0;
        $fechoy = new DateTime();
        $fecha = $fechoy->format ( "d-m-Y" );
        $this->fpdf->Open ();
        $this->fpdf->SetMargins ( 0, 0, 0 );
        $this->fpdf->SetAutoPageBreak ( true );
        $this->fpdf->SetDrawColor ( 128 );
        $this->fpdf->SetTopMargin ( 10 );
        $maxLin = ( $firma ) ? 17 : 20;
        $resto = count ( $items );
        foreach ( $items as $item ) {
            if ( $renglon == 0 ) {
                //imprimo encabezado
                $this->fpdf->AddPage ( 'P', array ( '100', '148' ) );
                $this->fpdf->SetFont ( 'Arial', 'b', '10' );
                $this->fpdf->Cell ( 0, 5, "Documento No Valido como Factura", 0, 1, 'C' );
                $this->fpdf->Cell ( 70, 5, sprintf ( "( %s ) %s", $cliente->codigo, $cliente->nombre ), 0, 0, 'L' );
                $this->fpdf->Cell ( 30, 5, $fecha, 0, 1, 'R' );

                if ( $firma ) {
                    $this->fpdf->Cell ( 50, 5, sprintf ( "Comp. CtaCte: %04.0f-%08.0f", $puesto, $idencab ), 0, 0, 'L' );
                }
                $this->fpdf->Cell ( 50, 5, sprintf ( "Rem: %04.0f-%08.0f", $ptorem, $numrem ), 0, 1, 'R' );
                $this->fpdf->Line ( 0, 25, 100, 25 );
                $this->fpdf->SetFont ( 'Arial', 'b', '8' );
                $this->fpdf->SetXY ( 0, 25 );
                $this->fpdf->Cell ( 10, 5, "Cant", 0, 0, 'C' );
                $this->fpdf->SetXY ( 10, 25 );
                $this->fpdf->Cell ( 80, 5, "Detalle", 0, 0, 'C' );
                $this->fpdf->SetXY ( 70, 25 );
                $this->fpdf->Cell ( 15, 5, "Unit", 0, 0, 'C' );
                $this->fpdf->SetXY ( 85, 25 );
                $this->fpdf->Cell ( 10, 5, "Importe", 0, 1, 'C' );
                $this->fpdf->Line ( 0, 30, 100, 30 );
                if ( $hoja > 0 ) {
                    $linea = $renglon * 5 + 30;
                    $this->fpdf->Cell ( 0, 5, sprintf ( "Transporte --> %4.2f", $total ), 0, 1, 'R' );
                };
            };
            $this->fpdf->SetFont ( 'Arial', '', '10' );
            $linea = ( $hoja == 0 ) ? $renglon * 5 + 30 : $renglon * 5 + 35;
            $this->fpdf->SetXY ( 0, $linea );
            $this->fpdf->Cell ( 10, 5, $item->cantidad, 0, 0, 'L' );
            $this->fpdf->SetXY ( 10, $linea );
            $this->fpdf->Cell ( 80, 5, substr ( $item->detalle, 0, 27 ), 0, 0, 'L' );
            $this->fpdf->SetXY ( 70, $linea );
            $this->fpdf->Cell ( 10, 5, $item->precio, 0, 0, 'R' );
            $this->fpdf->SetXY ( 85, $linea );
            $this->fpdf->Cell ( 10, 5, sprintf ( "%4.2f", $item->precio * $item->cantidad ), 0, 1, 'R' );
            $total += ( $item->cantidad * $item->precio );
            $renglon++;
            $resto--;
            if ( $renglon > $maxLin ) {
                //termino comprobante parcial
                if ( $resto > 0 ) {
                    $this->fpdf->SetFont ( 'Arial', 'b', '10' );
                    $this->fpdf->Line ( 0, $linea + 5, 100, $linea + 5 );
                    $this->fpdf->Cell ( 0, 5, sprintf ( "Transporte --> %4.2f", $total ), 0, 1, 'R' );
                    if ( $firma ) {
                        $this->fpdf->Line ( 20, $linea + 20, 80, $linea + 20 );
                        $this->fpdf->SetXY ( 0, $linea + 22 );
                        $this->fpdf->Cell ( 0, 5, "Firma del Cliente", 0, 1, 'C' );
                    };
                }
                $renglon = 0;
                $hoja++;
            };
        };
        $this->fpdf->SetFont ( 'Arial', 'b', '10' );
        $this->fpdf->Line ( 0, $linea + 5, 100, $linea + 5 );
        $this->fpdf->Cell ( 0, 5, sprintf ( "Total --> %4.2f", $total ), 0, 1, 'R' );
        if ( $firma ) {
            $this->fpdf->Line ( 20, $linea + 20, 80, $linea + 20 );
            $this->fpdf->SetXY ( 0, $linea + 22 );
            $this->fpdf->Cell ( 0, 5, "Firma del Cliente", 0, 1, 'C' );
        };
        $nombre = "/var/www/fiscal/" . PUESTO . "/pdf/ticket.pdf";
        $this->fpdf->Output ( $nombre, 'F' );
        $cmd = sprintf ( "lp -o media=Custom.100x148mm %s -d %s", $nombre, PRREMITO );
        shell_exec ( $cmd );
        return $nombre;
    }
}
