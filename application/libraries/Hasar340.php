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

    function __construct () {
        parent::__construct ();
        $this->puertoTCP ( 1600 );
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


    function Conectar () {
        $this->setConexion ( fsockopen ( $this->getHost (), $this->getPuertoTCP () ) );
        return $this->getConexion ();
    }

    function EnvioComando ( $comando ) {
        fwrite ( $this->getConexion (), $comando );
        $respuesta = fgets ( $this->getConexion (), 10 );
        return $respuesta;
    }

    function LeoResultado () {

    }

    function Desconecto () {
        return fclose ( $this->getConexion () );
    }
}