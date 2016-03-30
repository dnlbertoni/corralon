<?php

/**
 * Created by PhpStorm.
 * User: dnl
 * Date: 28/02/2015
 * Time: 02:44 PM
 */
class Printers
{
    private $impresoras = array();

    function __construct()
    {
        $this->set();
    }

    function set()
    {
        exec('lpstat -s', $datos);
        array_shift($datos);
        foreach ($datos as $d) {
            $aux = explode(':', $d);
            $dat = explode(' ', $aux[0]);
            $this->impresoras[] = $dat[2];
        }
    }

    function get()
    {
        return $this->impresoras;
    }
}