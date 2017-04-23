<?php

class Df330 extends hasar {
    function DatosCliente($nombre, $doc, $respiva, $tipdoc, $direccion = "")
    {//Datos del cliente
        // nombre = nombre cliente | $doc = cuit o dni o 0 si no es nada |
        // $respiva = "I" si es incripto  "C" si es consumidor final "E" si es excento "M" monotributo

        $nombre = substr ( $nombre, 0, 30 );
        $respiva = strtoupper ( $respiva );
        $command = "b" . $this->fs . $nombre . $this->fs . $doc . $this->fs . $respiva . $this->fs . $tipdoc . $this->fs . $direccion . "\n";
        if ( file_exists ( $this->nombre_archivo_tmp ) )
            unlink ( $this->nombre_archivo_tmp );
        /*
        if ( file_exists ( $this->nombre_archivo_recibir ) )
            unlink ( $this->nombre_archivo_recibir );
        */
        $estado = $this->AbroArchivoMandar ();
        if ( $estado != "ERROR" )
            $estado = $this->EscriboArchivoMandar ( $command );
        return $estado;
    }

    function AbrirFactura ( $tipdoc ) { //abro docuemnto factura tipdoc = "A" o "B"
        $command = "@" . $this->fs . $tipdoc . $this->fs . "S" . "\n";
        $estado = $this->EscriboArchivoMandar ( $command );
        return $estado;
    }

    function TextoFactura () {    //Texto Fiscal documento factura
        $command = "A" . $this->fs . "Muchas Gracias" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar ( $command );
        return $estado;
    }

    function ItemFactura ( $items ) {//item documento factura
        foreach ( $items as $item ) {
            $iva = number_format ( $item->Tasa, 2, '.', '' );
            $precio = number_format ( $item->Precio, 2, '.', '' );
            $cantidad = number_format ( $item->Cantidad, 2, '.', '' );
            $command = "B" . $this->fs . substr ( $item->Nombre, 0, 50 ) . $this->fs . $cantidad . $this->fs . $precio . $this->fs . $iva . $this->fs . "M" . $this->fs . '0.0' . $this->fs . $this->display . $this->fs . "T" . "\n";
            $estado = $this->EscriboArchivoMandar ( $command );
        }
        return $estado;
    }

    function SubTotalFactura () {    //Subtotal documento ticket
        $command = "C" . $this->fs . "P" . $this->fs . "Subtotal" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar ( $command );
        return $estado;
    }

    function TotalFactura ( $total ) {    //Total documento factura
        $total = number_format ( $total, 2, '.', '' );
        $command = "D" . $this->fs . "Efectivo" . $this->fs . $total . $this->fs . "T" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar ( $command );
        return $estado;
    }

    function CerrarFactura () {    //cierro documento factura
        $command = "E" . "\n";
        $estado = $this->EscriboArchivoMandar ( $command );
        $estado = $this->CierroArchivoMandar ();
        $estado = copy ( $this->nombre_archivo_tmp, $this->nombre_archivo_mandar );
        return $estado;
    }
}

?>