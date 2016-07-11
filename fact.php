<?php
include "Hasar340.php";
$nombre = substr ( $_POST['nombre'], 0, 20 );
$numdoc = $_POST['numdoc'];
$tipo = $_POST['tipo'];
$tipdoc = $_POST['tipdoc'];
$direccion = $_POST['direccion'];
$factura = new Hasar340( "192.168.10.2" );
$total = 0;
/*
for($i=0;$i<8;$i++) {
    $producto=array();
    $sub="producto_" . $i;
    $producto['detalle']=$_POST[$sub];
    $sub="cantidad_" . $i;
    $producto['cantidad']=$_POST[$sub];
    $sub="iva_" . $i;
    $producto['iva']=$_POST[$sub];
    $sub="precio_" . $i;
    $producto['precio']=$_POST[$sub];
    $productos[]=(object)$producto;
}
*/
$nombre = "daniel bertoni";
$tipo = 'C';
$tipdoc = 2;
$numdoc = 26866703;
$direccion = 'gualeguay 4797';


$producto['detalle'] = 'articulo 1';
$producto['cantidad'] = 1;
$producto['precio'] = 1.45;
$producto['iva'] = 21;
$productos[] = (object) $producto;

$respuesta = $factura->Conecto ();
echo $respuesta['mensaje'];
if ( $respuesta['error'] ) {
    $factura->DatosCliente ( $nombre, $numdoc, $tipo, $tipdoc, $direccion );
    echo $factura->AbrirFactura ( 'B' );
    $factura->ItemsFactura ( $productos );
    $factura->SubTotalFactura ();
    $factura->TotalFactura ( $total );
    $factura->CerrarFactura ();
    $factura->Desconecto ();
} else {
    echo "no es la respuesta";
    $factura->Desconecto ();
}
