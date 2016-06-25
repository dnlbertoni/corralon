<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 14/04/2016
 * Time: 09:14 PM
 */
class Hasar340 {
    var $host;
    var $puertoTCP;
    var $Conexion;
    var $sp;

    function __construct ( $ip = false ) {
        if ( $ip ) {
            $this->setHost ( $ip );
        }
        $this->setPuertoTCP ( 1600 );
        $this->sp = chr ( 28 );
    }

    /**
     * @return mixed
     */
    public function getConexion () {
        return $this->Conexion;
    }

    /**
     * @param mixed $Conexion
     */
    public function setConexion ( $Conexion ) {
        $this->Conexion = $Conexion;
    }

    /**
     * @return mixed
     */
    public function getHost () {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost ( $host ) {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getPuertoTCP () {
        return $this->puertoTCP;
    }

    /**
     * @param mixed $puertoTCP
     */
    public function setPuertoTCP ( $puertoTCP ) {
        $this->puertoTCP = $puertoTCP;
    }

    function Conecto () {
        $coneccion = fsockopen ( $this->getHost (), $this->getPuertoTCP (), $error, $errorTexto );
        $this->setConexion ( $coneccion );
        $respuesta['error'] = ( $error != 0 ) ? false : true;
        $respuesta['mensaje'] = $errorTexto;
        return $respuesta;
    }

    function EnvioComando ( $comando ) {
        fwrite ( $this->getConexion (), $comando );
        $respuesta = fgets ( $this->getConexion () );
        return $respuesta;
    }

    function LeoResultado () {

    }

    function CierreJournal ( $tipo ) {
        $comando = chr ( 57 ) . $this->sp . $tipo;
        $this->EnvioComando ( $comando );
    }

    function Desconecto () {
        return fclose ( $this->getConexion () );
    }

    function DatosCliente ( $nombre, $doc, $respiva, $tipdoc, $direccion ) {  //Datos del cliente
        // nombre = nombre cliente | $doc = cuit o dni o 0 si no es nada |
        // $respiva = "I" si es incripto  "C" si es consumidor final "E" si es excento "M" monotributo

        $nombre = substr ( $nombre, 0, 30 );
        $respiva = strtoupper ( $respiva );
        $commando = chr ( 98 ) . $this->sp . $nombre . $this->sp . $doc . $this->sp . $respiva . $this->sp . $tipdoc . $this->sp . $direccion;
        return $this->EnvioComando ( $commando );
    }

    function AbrirFactura ( $tipo ) {
        $commando = chr ( 64 ) . $this->sp . $tipo . $this->sp . "S";
        return $this->EnvioComando ( $commando );
    }

    function ItemsFactura ( $items ) {
        foreach ( $items as $item ) {
            $iva = number_format ( $item->iva, 2, '.', '' );
            $precio = number_format ( $item->precio, 2, '.', '' );
            $cantidad = number_format ( $item->cantidad, 2, '.', '' );
            $commando = chr ( 66 ) . $this->sp . substr ( $item->detalle, 0, 50 ) . $this->sp . $cantidad . $this->sp . $precio . $this->sp . $iva . $this->sp . "M" . $this->sp . '0.0' . $this->sp . 0 . $this->sp . "T";
            $respuesta = $this->EnvioComando ( $commando );
        }
        return true;
    }

    function SubTotalFactura () {    //Subtotal documento ticket
        $command = chr ( 67 ) . $this->sp . "P" . $this->sp . "Subtotal" . $this->sp . 0;
        return $this->EnvioComando ( $command );
    }

    function TotalFactura ( $total ) {    //Total documento factura
        $total = number_format ( $total, 2, '.', '' );
        $command = chr ( 68 ) . $this->sp . "Efectivo" . $this->sp . $total . $this->sp . "T";
        return $this->EnvioComando ( $command );
    }

    function CerrarFactura () {    //cierro documento factura
        $command = chr ( 69 ) . $this->sp . 2;
        return $this->EnvioComando ( $command );
    }
}