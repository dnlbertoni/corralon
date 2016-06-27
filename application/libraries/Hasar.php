<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hasar {
    //variables
    var $fs;
    var $puestoVta;
    var $ruta;
    var $mandar;
    var $recibir;
    var $tmp;
    var $nFile;
    var $archivo;
    var $nombre_archivo;
    var $nombre_archivo_tmp;
    var $comando;
    var $estadoArchivo;
    var $display;
    /*
    * datos del estado de la impresora fiscal en general
    */
    var $Estado;
    // datos del ultimo comprobante emitido
    var $tipo;
    var $tkt_ultimo;
    var $fac_ultimo;
    var $last_print;
    var $bultos;
    var $importe;
    var $pagado;
    var $ivatot;
    var $ivanor;
    var $impint;
    var $vuelto;
    var $estado;
    // datos de los cierres Journal
    var $fecha_cierre;
    var $numero_cierre;
    var $tkt_cierre;
    var $fac_cierre;
    var $importe_cierre;
    var $iva_cierre;
    var $impint_cierre;
    var $df_can_cierre;
    var $dnf_em_cierre;
    var $df_emi_cierre;

    function __construct ( $ruta = false )
    {  //metodo constructor
        $fs = chr(28);
        if ( $ruta ) {
            $this->setRuta ( $ruta );
        } else {
            //$ruta_universal        =  "/var/www/beta/citrusDev/rsc/tmp/fiscal";  // codeigniter
            $ruta_universal = ABSOLUT_PATH;  // debian
            $this->setRuta ( $ruta_universal );
        }
        $this->mandar = "mandar";
        $this->recibir = "recibir";
        $this->tmp = "log";
        $this->fs = $fs;
        $this->display = 0;
    }

    /**
     * @return string
     */
    public function getRuta () {
        return $this->ruta;
    }

    /**
     * @param string $ruta
     */
    public function setRuta ( $ruta ) {
        $this->ruta = $ruta;
    }


    function setPuesto($num)
    {
        $this->puestoVta = intval ( $num );
        $this->display = ($num == 4) ? 0 : 0;
    }

    function nombres($valor = "estado")
    {
        $this->nFile = $valor;
        $ruta = $this->getRuta ();
        $nombre_archivo_tmp = "$ruta/$this->puestoVta/$this->tmp/$this->nFile.txt";
        $nombre_archivo_snd = "$ruta/$this->puestoVta/$this->mandar/$this->nFile.txt";
        $nombre_archivo_rec = "$ruta/$this->puestoVta/$this->recibir/$this->nFile.ans";
        $this->nombre_archivo_tmp = $nombre_archivo_tmp;
        $this->nombre_archivo_mandar = $nombre_archivo_snd;
        $this->nombre_archivo_recibir = $nombre_archivo_rec;
        $this->nombre_archivo = $nombre_archivo_tmp;
    }

    /***********************************************
     ********** Escritura archivo en el server *****
     **********************************************/
    function AbroArchivoMandar()
    {
        $this->archivo = fopen($this->nombre_archivo_tmp, "w+") or die("ERROR $this->nombre_archivo_tmp");
        return $this->archivo;
    }

    function CierroArchivoMandar()
    {
        $resultado = fclose($this->archivo);
        return $resultado;
    }

    function EscriboArchivoMandar($valor)
    {
        $resultado = fputs($this->archivo, $valor);
        return $resultado;
    }

    /***********************************************
     *********Lectura archivo de respuesta *********
     **********************************************/
    function AbroArchivoRecibir()
    {
        $this->archivo = fopen($this->nombre_archivo_recibir, "r") or die("ERROR $this->nombre_archivo_recibir");
        return $this->archivo;
    }

    function CierroArchivoRecibir()
    {
        $resultado = fclose($this->archivo);
        return $resultado;
    }

    function LeoArchivoRecibir()
    {
        $resultado = fgets($this->archivo);
        return $resultado;
    }

    /***********************************************
     *********** ENVIO DEL COMPROBANTE *************
     **********************************************/
    function Estado()
    {
        $this->nombres("estado");
        if (file_exists($this->nombre_archivo_tmp))
            unlink($this->nombre_archivo_tmp);
        if (file_exists($this->nombre_archivo_recibir))
            unlink($this->nombre_archivo_recibir);
        $command = "*" . "\n";
        $estado = $this->AbroArchivoMandar();
        if ($estado != "ERROR")
            $estado = $this->EscriboArchivoMandar($command);
        $estado = $this->CierroArchivoMandar();
        $estado = copy($this->nombre_archivo_tmp, $this->nombre_archivo_mandar);
        $band = 1000;
        while (!(file_exists($this->nombre_archivo_recibir))) {
            if ($band == 1000) {
                //echo "Esperando Respuesa Fiscal, por favor espere...<br />";
                sleep(2);
                $band = 0;
            } else {
                $band++;
            };
        }
        $estado = $this->AbroArchivoRecibir();
        while (!(feof($this->archivo))) {
            $linea[] = fgets($this->archivo);
        }
        $estado = fclose($this->archivo);
        $this->estadoArchivo = $estado;
        $respuesta = $this->RespuestaEstado($linea[0]);
        return $respuesta;
    }

    //abro docuemtno Fiscal
    function AbrirDocumentoFiscal($tipdoc)
    {
        $command = "@" . $this->fs . $tipdoc . $this->fs . "T" . "\n";
        if (file_exists($this->nombre_archivo_tmp))
            unlink($this->nombre_archivo_tmp);
        if (file_exists($this->nombre_archivo_recibir))
            unlink($this->nombre_archivo_recibir);
        $estado = $this->AbroArchivoMandar();
        if ($estado != "ERROR")
            $estado = $this->EscriboArchivoMandar($command);
        $estado = $this->CierroArchivoMandar();
        $estado = copy($this->nombre_archivo_tmp, $this->nombre_archivo_mandar);
        $band = 10000;
        while (!(file_exists($this->nombre_archivo_recibir))) {
            if ($band == 10000) {
                //echo "Esperando Respuesa Fiscal, por favor espere...<br />";
                sleep(3);
                $band = 0;
            } else {
                $band++;
            };
        }
        $estado = $this->AbroArchivoRecibir();
        while (!(feof($this->archivo))) {
            $linea[] = fgets($this->archivo);
        }
        $estado = fclose($this->archivo);
        $this->estadoArchivo = $estado;
        $respuesta = $this->RespAbroDocumentoFiscal($linea[0]);
        return $respuesta;
    }

    // cierro docuemtno fiscal
    function CierroDocumentoFiscal()
    {
        $command = "E" . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $estado = $this->CierroArchivoMandar();
        $estado = copy($this->nombre_archivo_tmp, $this->nombre_archivo_mandar);
        return $estado;
    }

    function CierreJournal($dato)
    {
        $this->nombres("cierre");

        if (file_exists($this->nombre_archivo_tmp))
            unlink($this->nombre_archivo_tmp);
        if (file_exists($this->nombre_archivo_recibir))
            unlink($this->nombre_archivo_recibir);
        $dato = strtoupper($dato);
        $command = "9" . chr(28) . $dato;
        $estado = $this->AbroArchivoMandar();
        if ($estado != "ERROR")
            $estado = $this->EscriboArchivoMandar($command);
        $estado = $this->CierroArchivoMandar();
        $estado = copy($this->nombre_archivo_tmp, $this->nombre_archivo_mandar);

        $band = 10000;
        while (!(file_exists($this->nombre_archivo_recibir))) {
            if ($band == 10000) {
                //echo "Esperando Respuesa Fiscal, por favor espere...<br />";
                sleep(2);
                $band = 0;
            } else {
                $band++;
            };
        }
        $estado = $this->AbroArchivoRecibir();
        while (!(feof($this->archivo))) {
            $linea[] = fgets($this->archivo);
        }
        $estado = fclose($this->archivo);
        $this->estadoArchivo = $estado;
        $respuesta = $this->RespuestaCierreJournal($linea[0]);
        return $respuesta;
    }

    function GetDailyReport($dato, $tipo)
    {
        $this->nombres("zviejo");
        if (file_exists($this->nombre_archivo_tmp))
            unlink($this->nombre_archivo_tmp);
        if (file_exists($this->nombre_archivo_recibir))
            unlink($this->nombre_archivo_recibir);
        $command = "<" . $this->fs . $dato . $this->fs . $tipo . "\n";
        $estado = $this->AbroArchivoMandar();
        if ($estado != "ERROR")
            $estado = $this->EscriboArchivoMandar($command);
        $estado = $this->CierroArchivoMandar();
        $estado = copy($this->nombre_archivo_tmp, $this->nombre_archivo_mandar);
        $band = 10000;
        while (!(file_exists($this->nombre_archivo_recibir))) {
            if ($band == 10000) {
                //echo "Esperando Respuesa Fiscal, por favor espere...<br />";
                sleep(2);
                $band = 0;
            } else {
                $band++;
            };
        }
        $estado = $this->AbroArchivoRecibir();
        while (!(feof($this->archivo))) {
            $linea[] = fgets($this->archivo);
        }
        $estado = fclose($this->archivo);
        $this->estadoArchivo = $estado;
        $respuesta = $this->RespuestaGetDailyReport($linea[0]);
        return $respuesta;
    }

    /***********************************************
     ********* RECEPCION DEL COMPROBANTE ***********
     **********************************************/
    function RespuestaEstado($linea)
    {
        $campos = explode("|", $linea);
        // primer sector estado impresora
        $estado_impre = $this->StatusImpresora($campos[1]);
        // segundo sector estado fiscal
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        // Nro Ultimo ticket/ ticket-factura B/C emitido
        $this->tkt_ultimo = $campos[3];
        // tercer sector estado auxiliar
        // $estado_auxi=$this->StatusAuxiliar($campos[4]);
        //Nro ultimo tique-factura "A" emitido
        $this->fac_ultimo = $campos[5];
        /********* solo valido para 715 y 441 *********
         * // cuarto sector estado documento
         * $respu=$this->StatusDocumento($campos[6]);
         * $estado['estado'][3]=$respu['nombre'];
         * $estado['detalle'][3]=$respu['detalle'];
         * // N� �ltimo tique-nota de cr�dito B/C emitido
         * $this->ncb_ultimo=$campos[7];
         * // N� �ltimo tique-nota de cr�dito A emitido
         * $this->nca_ultimo=$campos[8];
         */
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        return $estado;
    }

    function RespuestaAbrir($linea)
    {
        $campos = explode("|", $linea);
        //print_r($campos);
        $estado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        // solo valido para 715 y 441
        //$dato[0]=$campos[3];
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        return $estado;
    }

    function RespuestaTexto($linea)
    {
        $campos = explode("|", $linea);
        $etado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        return $estado;
    }

    function RespuestaItem($linea)
    {
        $campos = explode("|", $linea);
        $estado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        return $estado;
    }

    function RespuestaSubtotal($linea)
    {
        $campos = explode("|", $linea);
        $estado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        $this->bultos = $campos[3];
        $this->importe = $campos[4];
        $this->ivatot = $campos[5];
        $this->pagado = $campos[6];
        $this->ivanor = $campos[7];
//		$this->imptint = $campos[8];
        return $estado;
    }

    function RespuestaTotal($linea)
    {
        $campos = explode("|", $linea);
        $estado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        $this->vuelto = $campos[3];
        return $estado;
    }

    function RespuestaCerrar($linea)
    {
        $campos = explode("|", $linea);
        $estado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        $this->last_print = $campos[3];
        return $estado;
    }

    function RespuestaCierreJournal($linea)
    {
        $campos = explode("|", $linea);
        $estado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        $estado['estado'] = "OK";
        $estado['detalle'] = "Ninguno";
        if ($estado_impre['estado'] == "Error") {
            $estado['estado'] = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        };
        if ($estado_fiscal['estado'] == "Error") {
            $estado['estado'] = "Error Fiscal";
            $estado['detalle'] .= $estado_fiscal['detalle'];
        };
        $this->numero_cierre = intval($campos[3]);
        $this->df_can_cierre = intval($campos[4]);
        $this->dnf_em_cierre = intval($campos[6]);
        $this->df_emi_cierre = intval($campos[7]);
        $this->tkt_cierre = intval($campos[9]);
        $this->fac_cierre = intval($campos[10]);
        $this->importe_cierre = floatval($campos[11]);
        $this->iva_cierre = floatval($campos[12]);
        $this->impint_cierre = floatval($campos[13]);
        return $estado;
    }

    function RespuestaGetDailyReport($linea)
    {
        $campos = explode("|", $linea);
        $estado_impre = $this->StatusImpresora($campos[1]);
        $estado_fiscal = $this->StatusFiscal($campos[2]);
        $this->Estado = "OK";
        $estado['detalle'] = "Ninguno";
        /*
         * fuerzo estado ok
         */
        $estado_impre['estado'] = "OK";
        /*
         * ojo que esta forzado a andar siempre
         */

        if ($estado_impre['estado'] == "Error") {
            $this->Estado = "Error Impresora";
            $estado['detalle'] .= $estado_impre['detalle'];
        } else {
            if ($estado_fiscal['estado'] == "Error") {
                $this->Estado = "Error Fiscal";
                $estado['detalle'] .= $estado_fiscal['detalle'];
            } else {
                $this->Estado = "OK";
                $this->fecha_cierre = substr($campos[3], 4, 2) . "/" . substr($campos[3], 2, 2) . "/20" . substr($campos[3], 0, 2);
                $this->numero_cierre = intval($campos[4]);
                $this->tkt_cierre = intval($campos[5]);
                $this->fac_cierre = intval($campos[6]);
                $this->importe_cierre = floatval($campos[7]);
                $this->iva_cierre = floatval($campos[8]);
                $this->impint_cierre = floatval($campos[9]);
            }
        };
        return $estado;
    }

    function RespuestaFull()
    {
        $resp = array();
        $band = 1000;
        while (!(file_exists($this->nombre_archivo_recibir))) {
            if ($band == 1000) {
                //echo "Esperando Respuesa Fiscal, por favor espere...<br />";
                sleep(2);
                $band = 0;
            } else {
                $band++;
            };
        }
        $estado = $this->AbroArchivoRecibir();
        while (!(feof($this->archivo))) {
            $linea[] = fgets($this->archivo);
        }
        $estado = fclose($this->archivo);
        for ($r = 0; $r < count($linea); $r++) {
            $aux = explode("|", $linea[$r]);
            switch ($aux[0]) {
                case "*":
                    $resp[$r] = $this->RespuestaEstado($linea[$r]);
                    break;
                case "b":
                    $resp[$r] = $this->RespuestaCliente ( $linea[$r] );
                    break;
                case "A":
                    $resp[$r] = $this->RespuestaTexto($linea[$r]);
                    break;
                case "B":
                    $resp[$r] = $this->RespuestaItem($linea[$r]);
                    break;
                case "C":
                    $resp[$r] = $this->RespuestaSubtotal($linea[$r]);
                    break;
                case "D":
                    $resp[$r] = $this->RespuestaTotal($linea[$r]);
                    break;
                case "E":
                    $resp[$r] = $this->RespuestaCerrar($linea[$r]);
                    break;
                case "@":
                    $resp[$r] = $this->RespuestaAbrir($linea[$r]);
                    break;
                case "9":
                    $resp[$r] = $this->RespuestaCierreJournal($linea[$r]);
                    break;
                default:
                    $resp[$r] = $this->RespuestaAbrir ( $linea[$r] );
                    break;
            };
        }
        return $resp;
    }
    /************************************************/
    /************ descompresion de Status ***********/
    /************************************************/
    function StatusImpresora($linea)
    {
        include("errores_hasar.php");
        $bits = str_split($linea);
        $error_nible = 0;
        $answer['estado'] = "OK";
        $answer['detalle'] = "";
        for ($i = 0; $i < 16; $i++) {
            if ($i == 8)
                $i++;
            if ($bits[$i] == 1) {
                $error_nible++;
                echo $i . "<br/>";
                $answer['detalle'] = $answer['detalle'] . $i . "-" . $this->decodeError ( $i, "Impresora" )->error . "|";
            };
            if ($i == 3 && $error_nible > 0) {
                $answer['estado'] = "Error";
                $error_nible = 0;
            };
            if ($i == 7 && $error_nible > 0) {
                $answer['estado'] = "Error";
                $error_nible = 0;
            };
        }
        return $answer;
    }

    function StatusFiscal($linea)
    {
        include("errores_hasar.php");
        //	echo $linea, "<br />";
        $bits = str_split($linea);
        $error_nible = 0;
        $answer['estado'] = "OK";
        $answer['detalle'] = "";
        for ($i = 0; $i < 16; $i++) {
            if ($i == 5)
                $i = $i + 2;
            if (intval($bits[$i]) == 1) {
                $error_nible++;
                $answer['detalle'] = $answer['detalle'] . $i . "-" . $this->decodeError ( $i, "Controlador" )->erro . "|";
            }
            if ($i == 7 && $error_nible > 0) {
                $answer['estado'] = "Error";
                $error_nible = 0;
            };
            if ($i == 15 && $error_nible > 0) {
                $answer['estado'] = "Error";
                $error_nible = 0;
            };
        }
        return $answer;
    }

    function StatusAuxiliar($linea)
    {
        $bits = str_split($linea);
        $error_nible = 0;
        $answer['estado'] = "OK";
        $answer['detalle'] = "";
        for ($i = 0; $i < 16; $i++) {
            if (intval($bits[$i]) == 1) {
                $error_nible++;
                $answer['detalle'] = $answer['detalle'] . $i . "|";
            }
            if ($i == 8 && $error_nible > 0) {
                $answer['estado'] = "Error";
                $error_nible = 0;
            };
            if ($i == 15 && $error_nible > 0) {
                $answer['estado'] = "Error";
                $error_nible = 0;
            };
        }
        return $answer;
    }

    function decodeError ( $errorNro, $tipo ) {
        /***********************************************
         *********   VECTORES ERRORES    ***********
         **********************************************/

        /***************************/
        // Errores de la Impresora //
        /***************************/
        $StatusImpresoraError[0] = "";
        $StatusImpresoraDetalle[0] = "";
        $StatusImpresoraError[1] = "";
        $StatusImpresoraDetalle[1] = "";
        $StatusImpresoraError[2] = "";
        $StatusImpresoraDetalle[2] = "";
        $StatusImpresoraError[3] = "Error de impresora";
        $StatusImpresoraDetalle[3] = "Se ha interrumpido la conexi�n entre el controlador fiscal y la impresora.";
        $StatusImpresoraError[4] = "Impresora offline";
        $StatusImpresoraDetalle[4] = "La impresora no ha logrado comunicarse dentro del per�odo de tiempo establecido.";
        $StatusImpresoraError[5] = "Falta papel del diario";
        $StatusImpresoraDetalle[5] = "El sensor de papel del diario ha detectado falta de papel.";
        $StatusImpresoraError[6] = "Falta papel de tickets";
        $StatusImpresoraDetalle[6] = "El sensor de papel de tickets ha detectado falta de papel.";
        $StatusImpresoraError[7] = "Buffer de impresora lleno";
        $StatusImpresoraDetalle[7] = "El controlador fiscal convierte los comandos enviados por un host en comandos fiscales, y los almacena en un buffer antes de enviarlos a la impresora fiscal. Cuando el buffer se aproxima a su capacidad m�xima, este bit se activa. Cualquier comando que se env�e cuando este bit est� en 1 no se ejecuta y debe ser reenviado
por el host.";
        $StatusImpresoraError[8] = "Buffer de impresora vac�o";
        $StatusImpresoraDetalle[8] = "Este bit se activa cuando el buffer mencionado en el punto anterior se encuentra vac�o. Es una indicaci�n al host de que todos los comandos fueron enviados a la impresora fiscal.";
        $StatusImpresoraError[9] = "Tapa de impresora abierta.";
        $StatusImpresoraDetalle[9] = "";
        $StatusImpresoraError[15] = "Caj�n de dinero cerrado o ausente.";
        $StatusImpresoraDetalle[15] = "";
        $StatusImpresoraError[16] = "OR l�gico de los bits 2-5, 8 y 14.";
        $StatusImpresoraDetalle[16] = "Este bit se encuentra en 1 siempre que alguno de los bits del 2 al 5, el bit 8 o el 14 se encuentre en 1.";

        /***************************/
// Errores del Controlador //
        /***************************/
        $StatusFiscalError  [0] = "Error en chequeo de memoria fiscal.";
        $StatusFiscalDetalle[0] = "Al encenderse la impresora se produjo un error en el checksum. La impresora no funcionar�.";
        $StatusFiscalError  [1] = "Error en chequeo de memoria de trabajo";
        $StatusFiscalDetalle[1] = "Al encenderse la impresora se produjo un error en el checksum. La impresora no funcionar�.";
        $StatusFiscalError  [2] = "Carga de bater�a baja";
        $StatusFiscalDetalle[2] = "La carga de la bater�a de respaldo de la memoria de trabajo se encuentra baja.";
        $StatusFiscalError  [3] = "Comando desconocido";
        $StatusFiscalDetalle[3] = "El comando recibido no fue reconocido.";
        $StatusFiscalError  [4] = "Datos no v�lidos en un campo";
        $StatusFiscalDetalle[4] = "Uno de los campos del comando recibido tiene datos no v�lidos por ejemplo, datos	no num�ricos en un campo num�rico).";
        $StatusFiscalError  [5] = "Comando no v�lido para el estado fiscal actual";
        $StatusFiscalDetalle[5] = "Se ha recibido un comando que no es v�lido en el estado actual del controlador (por ejemplo, abrir un recibo no-fiscal cuando se encuentra abierto un recibo fiscal).	Nota: cuando se ha producido un cambio no v�lido de c�digo de IVA, tanto el bit 4 como el 5 tendr�n valor 1.";
        $StatusFiscalError  [6] = "Desborde del Total";
        $StatusFiscalDetalle[6] = "El acumulador de una transacci�n, del total diario o del IVA se desbordar� a ra�z de	un comando recibido. El comando no es ejecutado. Este bit debe ser monitoreado por el host para emitir un aviso de error.";
        $StatusFiscalError  [7] = "Memoria fiscal llena, bloqueada o dada de baja";
        $StatusFiscalDetalle[7] = "En caso de que la memoria fiscal est� llena, bloqueada o dada de baja, no se	permite abrir un comprobante fiscal.";
        $StatusFiscalError  [8] = "Memoria fiscal a punto de llenarse";
        $StatusFiscalDetalle[8] = "La memoria fiscal tiene 30 o menos registros libres.	Este bit debe ser monitoreado por el host para emitir el correspondiente aviso.";
        $StatusFiscalError  [9] = "Terminal fiscal certificada";
        $StatusFiscalDetalle[9] = "Indica que la impresora ha sido inicializada.";
        $StatusFiscalError  [10] = "Terminal fiscal fiscalizada";
        $StatusFiscalDetalle[10] = "Indica que la impresora ha sido inicializada.";
        $StatusFiscalError  [11] = "Error en ingreso de fecha";
        $StatusFiscalDetalle[11] = "Se ha ingresado una fecha no v�lida. Para volver al bit a 0 debe ingresarse una fecha v�lida.";
        $StatusFiscalError  [12] = "Documento fiscal abierto";
        $StatusFiscalDetalle[12] = "Este bit se encuentra en 1 siempre que un documento fiscal se encuentra abierto.";
        $StatusFiscalError  [13] = "Documento abierto";
        $StatusFiscalDetalle[13] = "Este bit se encuentra en 1 siempre que un documento (fiscal, no fiscal o no fiscal homologado) se encuentra abierto.";
        $StatusFiscalError  [15] = "OR l�gico de los bits 0 a 8.";
        $StatusFiscalDetalle[15] = "Este bit se encuentra en 1 siempre que alguno de los bits mencionados se encuentre en 1.";

        /**
         *  Errores del Estado Auxiliar
         **/
        $StatusAuxiliarError[1] = "Memoria fiscal no inicializada.";
        $StatusAuxiliarError[2] = "No hay ning�n comprobante abierto.";
        $StatusAuxiliarError[3] = "Un comprobante fiscal se encuentra abierto. Venta habilitada.";
        $StatusAuxiliarError[4] = "Comprobante fiscal abierto. Se acaba de imprimir un texto fiscal.";
        $StatusAuxiliarError[5] = "Un comprobante no fiscal se encuentra abierto.";
        $StatusAuxiliarError[6] = "Comprobante fiscal abierto. Se realiz� al menos un pago.";
        $StatusAuxiliarError[7] = "Comprobante fiscal abierto. Se sald� el monto.";
        $StatusAuxiliarError[8] = "Comprobante fiscal abierto. Se realiz� una percepci�n.";
        $StatusAuxiliarError[9] = "El controlador ha sido dado de baja.";
        $StatusAuxiliarError[10] = "Comprobante fiscal abierto. Se realiz� un descuento / recargo general.";
        $StatusAuxiliarError[11] = "Comp. fiscal abierto. Se realiz� una bonificaci�n / recargo / devoluci�n envases.";
        $StatusAuxiliarError[13] = "Una nota de cr�dito se encuentra abierta. Cr�dito (venta) habilitado.";
        $StatusAuxiliarError[14] = "Nota de cr�dito se encuentra abierta. Se realiz� una bonificaci�n / recargo / devoluci�n envases.";
        $StatusAuxiliarError[15] = "Nota de cr�dito se encuentra abierta. Se realiz� un descuento / recargo general.";
        $StatusAuxiliarError[16] = "Nota de cr�dito se encuentra abierta. Se realiz� una percepci�n.";
        $StatusAuxiliarError[17] = "Nota de cr�dito se encuentra abierta. Se acaba de imprimir un texto fiscal.			";
        switch ( $tipo ) {
            case "Impresora":
                $error['error'] = $StatusImpresoraError[$errorNro];
                $error['detalle'] = $StatusImpresoraDetalle[$errorNro];
                break;
            case "Controlador":
                $error['error'] = $StatusFiscalError[$errorNro];
                $error['detalle'] = $StatusFiscalDetalle[$errorNro];
                break;
            case "Auxiliar":
                $error['error'] = $StatusAuxiliarError[$errorNro];
                $error['detalle'] = $StatusAuxiliarError[$errorNro];
                break;
        }
        return (object) $error;
    }
}

/*
 * Libreria para controlador Hasar 615
 * Location: application/libraries/Hasar.php 
 */
