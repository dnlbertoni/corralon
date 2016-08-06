<?php

/**
 * Description of caja
 *
 * @author dnl
 * @property Cajaencab_model $Cajaencab_model
 * @property Facencab_model $Facencab_model
 * @property Presuencab_model $Presuencab_model
 * @property Cuenta_model $Cuenta_model
 * @property Numeradores_model $Numeradores_model
 * @property Hasar340 $Hasar340
 */
class Caja extends Admin_Controller {
    var $PrinterRemito;
    function __construct () {
        parent::__construct();
        $this->load->model ( 'Cajaencab_model' );
        $this->load->model ( "Presuencab_model" );
        $this->load->model ( "Cuenta_model" );
        $this->load->model ( "Facencab_model" );
        $this->PrinterRemito = 2; //por laser
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

    function close () {
        $data['puesto'] = $this->getPuesto ();
        $data['caja'] = $this->Cajaencab_model->getCajaPuesto ( $this->getPuesto (), 1 );
        $data['fecha'] = $this->getFecha ();
        Template::set ( $data );
        Template::render ();
    }

    function cierreJournal () {
        $cierre = new Hasar();
        //$cierre->setRuta ( "/var/www/html/corralon/assets/tmp/fiscal" );
        $cierre->setRuta ( 'c:/xampp/htdocs/corralon/assets/tmp/fiscal' );

        $tipo = $this->input->post ( 'tipo' );
        $cierre->setPuesto ( intval ( $this->getPuesto () ) );
        $comprobante = "cierre";
        $nom_archiv = $comprobante . $tipo;
        $cierre->nombres ( $nom_archiv );
        $respuesta = $cierre->CierreJournal ( $tipo );
        if ( $tipo == 'Z' ) {
            $total = floatval ( $cierre->importe_cierre );
            $neto = floatval ( $cierre->importe_cierre ) - floatval ( $this->hasar->iva_cierre );
            $porcentaje = 0.90;
            $ivamax = ( $neto * $porcentaje * 0.21 );
            $ivamin = ( $neto * ( 1 - $porcentaje ) * 0.105 );
            $diff = floatval ( $cierre->iva_cierre ) - ( $ivamax + $ivamin );
            $porcDiff = $diff / floatval ( $this->hasar->iva_cierre );
            $vez = 0;
            while ( $vez != 30 ) {
                $porcentaje += $porcDiff;
                $ivamax = round ( ( $neto * $porcentaje * 0.21 ), 2 );
                $ivamin = round ( ( $neto * ( 1 - $porcentaje ) * 0.105 ), 2 );
                $diff = floatval ( $cierre->iva_cierre ) - ( $ivamax + $ivamin );
                $porcDiff = ( $diff / floatval ( $cierre->iva_cierre ) );
                //echo $diff ,"<br />";
                $vez++;
            };
            // compilo del objeto de carga
            $fechoy = getdate ();
            $periva = $fechoy['year'] * 100 + $fechoy['mon'];
            $periva = intval ( $periva );
            $fecha = $fechoy['year'] . '-' . $fechoy['mon'] . '-' . $fechoy['mday'];
            $datos = array ( 'tipcom_id' => 4,
                'puesto' => 3,
                'numero' => $cierre->numero_cierre,
                'letra' => 'Z',
                'fecha' => $fecha,
                'cuenta_id' => 1,
                'importe' => $cierre->importe_cierre,
                'neto' => $neto,
                'ivamin' => $ivamin,
                'ivamax' => $ivamax,
                'ingbru' => 0,
                'impint' => $cierre->impint_cierre,
                'percep' => 0,
                'periva' => $periva,
                'estado' => 1 );
            $data['datos'] = $datos;
            $id = $this->Facencab_model->save ( $datos );
            $data ['fac'] = $this->Facencab_model->getRegistro ( $id );
            $data ['tipcom_nombre'] = $this->Tipcom_model->getNombre ( 4 );
            //Assets::add_js ( 'pos/muestroZ' );
            Template::set ( $data );
            Template::set_view ( 'pos/muestroZ' );
            Template::render ();
        } else {
            Template::redirect ( 'caja' );
        };
    }

    function anular ( $idEncab ) {
        $this->Presuencab_model->setAnulado ( $idEncab );
        echo json_encode ( array ( "mensaje" => "OK" ) );
    }

    function facturar () {
        $fecha = new DateTime();
        $data['presupuestos'] = $this->Presuencab_model->getPendientes ( $fecha->format ( "Y-m-d" ) );
        $data['facturados'] = $this->Presuencab_model->getFacturados ( $fecha->format ( "Y-m-d" ) );
        $data['fecha'] = $fecha;
        Template::set ( $data );
        Template::render ();
    }

    function imprimir ( $formato, $idPresupuesto ) {
        $this->load->model ( 'Numeradores_model' );
        $presupuesto = $this->Presuencab_model->getById ( $idPresupuesto );
        $this->output->enable_profiler ( false );
        //preparo el comprobante a imprimir
        $total = $this->Presuencab_model->getTotales ( $idPresupuesto )->Total;
        $cliente = $this->Cuenta_model->getByIdComprobante ( $presupuesto->cuenta_id );
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
            case 2:
                $data['file'] = $this->_imprimeFactura ( $this->getPuesto (), $idPresupuesto, $items, $total, $cliente );
                $data['idencab'] = $idPresupuesto;
                $data['tipcom_id'] = 2;
                $data['DNF'] = $vale;
                $data['accion'] = 'printFacturaDo';
                $data['Imprimo'] = 'Factura';
                break;
            case 6:
                $ptorem = $this->getPuestoCnf ();
                $numrem = $this->Numeradores_model->getNextRemito ( $ptorem, true );
                $firma = ( $vale == 0 ) ? false : true;
                $detalle = true;
                if ( $this->PrinterRemito == 1 ) {
                    $data['file'] = $this->_imprimeDNF ( $ptorem, $numrem, $this->getPuesto (), $idPresupuesto, $cliente, $items, $detalle, $firma );
                } else {
                    $data['file'] = $this->_imprimeDNFLaser ( $ptorem, $numrem, $this->getPuesto (), $idPresupuesto, $cliente, $items, false );
                };
                $data['idencab'] = $idPresupuesto;
                $data['tipcom_id'] = 6;
                $data['numero'] = $numrem;
                $data['DNF'] = $vale;
                $data['accion'] = 'printRemitoDoLaser';
                $data['Imprimo'] = 'Comprobante';
                break;
        };
        $data['tabla'] = $this->Presuencab_model->getTable ();
        //header('Content-Type: application/json');
        //echo json_encode($data);
        $this->load->view ( 'caja/carga', $data );
    }

    function printFacturaDo () {
        //print_r($_POST);
        $this->load->library ( 'hasar' );
        $idencab = $this->input->post ( 'idencab' );
        $tipcom_id = $this->input->post ( 'tipcom' );
        $DNF = $this->input->post ( 'DNF' );
        $estado = ( $DNF == 1 ) ? 9 : 1;
        $hasar = new Hasar();
        $hasar->setRuta ( $this->getRutaPuesto () );
        $hasar->setPuesto ( $this->getPuesto () );
        $hasar->nombres ( $this->input->post ( 'file' ) );
        $respuesta = $hasar->RespuestaFull ();
        //print_r($respuesta);
        //$respEstado = $hasar->Estado();
        $numero = $hasar->last_print;
        //echo $numero;
        $comprobantes = $this->Presuencab_model->getComprobante ( $idencab );
        $comprobante = $comprobantes[0];
        $items = $this->Presuencab_model->getArticulos ( $idencab );
        //echo $comprobante->cuenta_id;
        $cliente = $this->Cuenta_model->getByIdComprobante ( $comprobante->cuenta_id );
        $letra = ( $cliente->condiva == 1 ) ? "A" : "B";
        $ivamax = 0;
        $ivamin = 0;
        $datosMovim = array ();
        foreach ( $items as $item ) {
            $datosMovim[] = array (
                'tipcomid_movim' => $tipcom_id,
                'puesto_movim' => $this->getPuesto (),
                'numero_movim' => $numero,
                'letra_movim' => $letra,
                'id_articulo' => $item->id_articulo,
                'codigobarra_movim' => $item->Codigobarra,
                'cantidad_movim' => $item->Cantidad,
                'preciovta_movim' => $item->Precio,
                'tasaiva_movim' => $item->Tasa
            );
            if ( $item->Tasa > 20 ) {
                $ivamax += ( $item->Precio / ( 1 + ( $item->Tasa / 100 ) ) ) * $item->Tasa / 100;
            } else {
                $ivamin += ( $item->Precio / ( 1 + ( $item->Tasa / 100 ) ) ) * $item->Tasa / 100;
            }
        }
        $datosEncab = array (
            'tipcom_id' => $tipcom_id,
            'puesto' => $this->getPuesto (),
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $comprobante->cuenta_id,
            'importe' => $hasar->importe,
            'neto' => $hasar->importe - $hasar->ivatot,
            'ivamin' => $ivamin,
            'ivamax' => $ivamax,
            'impint' => 0,
            'ingbru' => 0,
            'percep' => 0,
            'estado' => $estado
        );
        $idFacencab = $this->Facencab_model->graboComprobante ( $datosEncab, $datosMovim );
        $this->Presuencab_model->setFacturado ( $idencab, $idFacencab );
        if ( $DNF == 1 ) {
            $this->printCtaCte ( $comprobante->cuenta_id, $this->getPuesto (), $numero, $hasar->importe, $idFacencab );
        };
        $resultado['mensaje'] = "OK";
        echo json_encode ( $resultado );
    }

    function printRemitoDo () {
        $this->load->library ( 'hasar' );
        $hasar = new Hasar();
        $puesto = $this->getPuesto ();
        $idencab = $this->input->post ( 'idencab' );
        $tipcom_id = $this->input->post ( 'tipcom' );
        $DNF = $this->input->post ( 'DNF' );
        $estado = ( $DNF == 1 ) ? 9 : 1;
        $ptorem = $this->getPuestoCnf ();
        $comprobante = $this->Presuencab_model->getComprobante ( $idencab );
        $numero = $comprobante->numero;
        $hasar->setPuesto ( $puesto );
        $hasar->nombres ( $this->input->post ( 'file' ) );
        $respuesta = $this->hasar->RespuestaFull ();
        //$respEstado = $this->hasar->Estado();
        //$cuenta = $this->Tmpmovim_model->getCuenta($idencab, $puesto);
        $items = $this->Presuencab_model->getArticulos ( $idencab );
        $cliente = $this->Cuenta_model->getByIdComprobante ( $comprobante->cuenta_id );
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
                'codigobarra_movim' => $item->Codigobarra,
                'cantidad_movim' => $item->Cantidad,
                'preciovta_movim' => $item->Precio,
                'tasaiva_movim' => $item->Tasa
            );
            if ( $item->Tasa > 20 ) {
                $ivamax += ( $item->Precio / ( 1 + ( $item->Tasa / 100 ) ) ) * $item->Tasa / 100;
            } else {
                $ivamin += ( $item->Precio / ( 1 + ( $item->Tasa / 100 ) ) ) * $item->Tasa / 100;
            }
            $importe += $item->Precio * $item->Cantidad;
            $neto += $item->Precio / ( 1 + ( $item->Tasa / 100 ) ) * $item->Cantidad;
        }
        $datosEncab = array (
            'tipcom_id' => $tipcom_id,
            'puesto' => $ptorem,
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $comprobante->cuenta_id,
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
        $this->Presuencab_model->setFacturado ( $idencab, $idFacencab );
        //$this->load->view('pos/carga');
        if ( $DNF == 1 ) {
            $this->printCtaCte ( $comprobante->cuenta_id, $puesto, $numero, $importe * $negativo, $idFacencab );
        };
        $estado['mensaje'] = "OK";
        echo json_encode ( $estado );
    }

    function printRemitoDoLaser () {
        $idencab = $this->input->post ( 'idencab' );
        $tipcom_id = $this->input->post ( 'tipcom' );
        $DNF = $this->input->post ( 'DNF' );
        $estado = ( $DNF == 1 ) ? 9 : 1;
        $ptorem = $this->getPuestoCnf ();
        $comprobante = $this->Presuencab_model->getById ( $idencab );
        $numero = $comprobante->numero;
        $items = $this->Presuencab_model->getComprobante ( $idencab );
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
                'codigobarra_movim' => $item->codigobarra_movim,
                'cantidad_movim' => $item->cantidad_movim,
                'preciovta_movim' => $item->preciovta_movim,
                'tasaiva_movim' => $item->tasaiva_movim
            );
            if ( $item->tasaiva_movim > 20 ) {
                $ivamax += ( $item->preciovta_movim / ( 1 + ( $item->tasaiva_movim / 100 ) ) ) * $item->tasaiva_movim / 100;
            } else {
                $ivamin += ( $item->preciovta_movim / ( 1 + ( $item->tasaiva_movim / 100 ) ) ) * $item->tasaiva_movim / 100;
            }
            $importe += $item->preciovta_movim * $item->cantidad_movim;
            $neto += $item->preciovta_movim / ( 1 + ( $item->tasaiva_movim / 100 ) ) * $item->cantidad_movim;
        }
        $datosEncab = array (
            'tipcom_id' => $tipcom_id,
            'puesto' => $ptorem,
            'numero' => $numero,
            'letra' => $letra,
            'cuenta_id' => $comprobante->cuenta_id,
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
        $this->Presuencab_model->setFacturado ( $idencab, $idFacencab );
        if ( $DNF == 1 ) {
            $this->printCtaCteLaser ( $comprobante->cuenta_id, $this->getPuesto (), $numero, $importe * $negativo, $idFacencab, $items );
        } else {
            $estado['mensaje'] = "OK";
        }
        echo json_encode ( $estado );
    }

    function printCtaCte ( $cuenta, $puesto, $numero, $importe, $idFacencab ) {
        $this->output->enable_profiler ( false );
        //preparo el comprobante a imprimir
        $cliente = $this->Cuenta_model->getByIdComprobante ( $cuenta );
        $ptorem = 80 + $puesto;
        $numrem = $this->Numeradores_model->getNextCompCtaCte ( $ptorem, true );
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
        $this->load->view ( 'caja/carga', $data );
    }

    function printCtaCteLaser ( $cuenta, $puesto, $numero, $importe, $idFacencab, $items ) {
        $this->output->enable_profiler ( false );
        //preparo el comprobante a imprimir
        $cliente = $this->Cuenta_model->getByIdComprobante ( $cuenta );
        $ptorem = 80 + $puesto;
        $numrem = $this->Numeradores_model->getNextCompCtaCte ( $ptorem, true );
        $numeroFac = $this->Facencab_model->getNumeroFromIdencab ( $idFacencab );
        //genero el archivo
        $data['file'] = $this->_imprimeDNFLaser ( $ptorem, $numrem, $this->getPuestoCnf (), $numeroFac, $cliente, $items, true );
        $data['puesto'] = $puesto;
        $data['idencab'] = $idFacencab;
        $data['cuenta'] = $cuenta;
        $data['tipcom_id'] = 7; //comprobante de CtaCte
        $data['importe'] = $importe;
        $data['accion'] = 'printCtaCteDo';
        $data['Imprimo'] = 'Compr. CtaCte';
        $this->load->view ( 'caja/carga', $data );
    }

    function printCtaCteDo () {
        $this->load->model ( 'Ctactemovim_model', '', true );
        $puesto = $this->getPuesto ();
        $idencab = $this->input->post ( 'idencab' );
        $comprobante = $this->Facencab_model->getById ( $idencab );
        $cuenta = $comprobante->cuenta_id;
        $importe = $comprobante->importe;
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
        //$num = $this->Numeradores_model->updateCompCtaCte ( $ptorem, $numero + 1 );
    }


    function _imprimeFactura ( $puesto, $idencab, $items, $total, $cliente ) {
        $this->load->library ( "hasar" );
        $this->load->library ( "df330" );

        // $this->df330->setRuta ( 'c:/xampp/htdocs/corralon/assets/tmp/fiscal');
        $this->df330->setRuta ( $this->getRutaPuesto () );

        $this->df330->setPuesto ( $puesto );
        $comprobante = "f";
        $nom_archiv = $comprobante . $idencab;
        $this->df330->nombres ( $nom_archiv );
        $tipdoc = ( $cliente->tipdoc == 2 ) ? "C" : 2;
        $cliNom = ( $cliente->datos_fac == 1 ) ? $cliente->nombre_facturacion : $cliente->nombre;
        $this->df330->DatosCliente ( $cliNom, $cliente->cuit, $cliente->letra615, $tipdoc, $cliente->direccion );
        $tiplet = ( $cliente->condiva == 1 ) ? "A" : "B";
        $this->df330->AbrirFactura ( $tiplet );
        $this->df330->ItemFactura ( $items );
        $this->df330->SubTotalFactura ();
        $this->df330->TotalFactura ( $total );
        $this->df330->CerrarFactura ();
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

    function _imprimeDNFLaser ( $ptorem, $numrem, $puesto, $idencab, $cliente, $items, $firma = false, $imprime = false ) {
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
            $this->fpdf->Cell ( 10, 5, $item->Cantidad, 0, 0, 'L' );
            $this->fpdf->SetXY ( 10, $linea );
            $this->fpdf->Cell ( 80, 5, substr ( $item->Nombre, 0, 27 ), 0, 0, 'L' );
            $this->fpdf->SetXY ( 70, $linea );
            $this->fpdf->Cell ( 10, 5, $item->Precio, 0, 0, 'R' );
            $this->fpdf->SetXY ( 85, $linea );
            $this->fpdf->Cell ( 10, 5, sprintf ( "%4.2f", $item->Precio * $item->Cantidad ), 0, 1, 'R' );
            $total += ( $item->Cantidad * $item->Precio );
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
        $pathPDF = "/var/www/html/corralon/assets/tmp/fiscal/%s/pdf/%04.0f-%08.0f.pdf";
        $pathPDF = "%s/%s/pdf/%04.0f-%08.0f.pdf";
        $nombre = sprintf ( $pathPDF, $this->getRutaPuesto (), intval ( $this->getPuesto () ), $ptorem, $numrem );
        $this->fpdf->Output ( $nombre, 'F' );
        if ( $imprime ) {
            $cmd = sprintf ( "lp -o media=Custom.100x148mm %s -d %s", $nombre, $this->getImpresora () );
            shell_exec ( $cmd );
        }
        return $nombre;
    }
}
