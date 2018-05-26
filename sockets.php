<?php
$sp = chr(28);
$conexion = fsockopen ('192.168.10.104', 1600);
if ($conexion) {

	$parametro = chr(42);
	$comando = $parametro;
	fwrite($conexion,$comando);
	$respuesta = fgets ($conexion, 10);
	echo "Comando: ".$comando."<br>";
	echo "Respuesta: ".$respuesta."<br>";

	$parametro = chr(98);
	$comando = $parametro.$sp."Santiago del Castillo".$sp."30271639868".$sp."C".$sp."C".$sp."Juncal 259 Olivos";
	fwrite($conexion,$comando);
	$respuesta = fgets ($conexion, 10);
	echo "Comando: ".$comando."<br>";
	echo "Respuesta: ".$respuesta."<br>";


	$parametro = chr(64);
	$comando = $parametro.$sp."T".$sp."T";
	fwrite($conexion,$comando);
	$respuesta = fgets ($conexion, 10);
	echo "Comando: ".$comando."<br>";
	echo "Respuesta: ".$respuesta."<br>";


	$parametro = chr(66);
	$comando = $parametro.$sp."Fotocopias BN".$sp."1.00".$sp."0.10".$sp."21.00".$sp."1.00".$sp."0.00".$sp."0".$sp."T";
	fwrite($conexion,$comando);
	$respuesta = fgets ($conexion, 10);
	echo "Comando: ".$comando."<br>";
	echo "Respuesta: ".$respuesta."<br>";

	$parametro = chr(67);
	$comando = $parametro.$sp."P".$sp."x".$sp."0";
	fwrite($conexion,$comando);
	$respuesta = fgets ($conexion, 10);
	echo "Comando: ".$comando."<br>";
	echo "Respuesta: ".$respuesta."<br>";

	$parametro = chr(68);
	$comando = $parametro.$sp."Total Venta:".$sp."1.00".$sp."C".$sp."0".$sp."MiTarjetaNumero";
	fwrite($conexion,$comando);
	$respuesta = fgets ($conexion, 10);
	echo "Comando: ".$comando."<br>";
	echo "Respuesta: ".$respuesta."<br>";

	$parametro = chr(69);
	$comando = $parametro;
	fwrite($conexion,$comando);
	$respuesta = fgets ($conexion, 10);
	echo "Comando: ".html_entity_decode($comando)."<br>";
	echo "Respuesta: ".$respuesta."<br>";

	fclose ($conexion);
}

?>
