<?php

/**
 * Created by PhpStorm.
 * User: dnlbe
 * Date: 6/6/2016
 * Time: 9:49 PM
 * @property $Hasar340 Hasar340
 */
class Factura extends CI_Controller{
    function __construct(){
        parent::__construct();
    }
    function index(){
        Template::render();
    }
    function emitir(){
        $this->load->library("Hasar340");
        $factura = new Hasar340("192.168.10.104");
        /*
        foreach ($_POST as $key => $value) {
            $aux = explode("_", $key);
            if ($aux[0] == "producto") {
                $aux2 = $aux[1];
                $producto[][$aux2] = $value;
            }
        }
        */
        $producto[0]['detalle']='articulo 1';
        $producto[0]['cantidad']=1;
        $producto[0]['precio']=23.45;
        $producto[0]['iva']=21;
        $productos = (object)$producto;
        $factura->Conecto();
        $factura->DatosCliente('daniel',26866703,'C',2,'gualeguay 4797');
        $factura->AbrirFactura('B');
        $factura->ItemsFactura($productos);
        $total = $factura->SubTotalFactura();
        $factura->TotalFactura($total);
        $factura->CerrarFactura();
    }
}