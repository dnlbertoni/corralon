<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hasar
{
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

    function __construct()
    {  //metodo constructor
        $fs = chr(28);
        //$ruta_universal        =  "/var/www/beta/citrusDev/rsc/tmp/fiscal";  // codeigniter
        $ruta_universal = ABSOLUT_PATH;  // debian
        $this->ruta = $ruta_universal;
        $this->mandar = "mandar";
        $this->recibir = "recibir";
        $this->tmp = "log";
        $this->fs = $fs;
        $this->display = 0;
    }

    function setPuesto($num)
    {
        $this->puestoVta = $num;
        $this->display = ($num == 4) ? 0 : 0;
    }

    function nombres($valor = "estado")
    {
        $this->nFile = $valor;
        $nombre_archivo_tmp = "$this->ruta/$this->puestoVta/$this->tmp/$this->nFile.txt";
        $nombre_archivo_snd = "$this->ruta/$this->puestoVta/$this->mandar/$this->nFile.txt";
        $nombre_archivo_rec = "$this->ruta/$this->puestoVta/$this->recibir/$this->nFile.ans";
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
                /*
                    case "b":
                            $resp[$r] = $this->RespuestaCliente($linea[$r]);
                        break;
                 */
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
                /*
                    default:
                                $resp[$r] = $this->RespuestaAbrir($linea[$r]);
                        break;
                */
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
                $answer['detalle'] = $answer['detalle'] . $i . "-" . $StatusImpresoraError[$i] . "|";
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
                $answer['detalle'] = $answer['detalle'] . $i . "-" . $StatusFiscalError[$i] . "|";
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
}

/*
 * Libreria para controlador Hasar 615
 * Location: application/libraries/Hasar.php 
 */
