<?php

class CNF extends hasar
{
    function AbrirDNF()
    {    //abro docuemtno CNF
        $command = "H" . "\n";
        if (file_exists($this->nombre_archivo_tmp))
            unlink($this->nombre_archivo_tmp);
        if (file_exists($this->nombre_archivo_recibir))
            unlink($this->nombre_archivo_recibir);
        $estado = $this->AbroArchivoMandar();
        if ($estado != "ERROR")
            $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function NumeroDNF($puesto, $numero)
    {    //imprimo numero de DNF interno
        $print = sprintf("%04.0f-%08.0f", $puesto, $numero);
        $command = "I" . $this->fs . chr(244) . $print . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "P" . $this->fs . 1 . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function CtaCteDNF($factura, $clienum, $clienom)
    {    //Encabezado CTACTE documento CNF
        $command = "I" . $this->fs . "A Imputar en la Cta Cte " . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "I" . $this->fs . chr(244) . "( " . $clienum . " ) " . substr($clienom, 0, 10) . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "I" . $this->fs . "Segun: " . $factura . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "P" . $this->fs . 2 . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function ItemsDNF($items, $detalle = 0)
    {    //item documento CNF
        // si detalle = 1 imprime sino no.
        $total = 0;
        foreach ($items as $item) {
            //$iva = number_format($item->iva, 2, '.', '');
            $importe = number_format($item->precio * $item->cantidad, 2, '.', '');
            $cantidad = number_format($item->cantidad, 2, '.', '');
            $precio = number_format($item->precio, 2, '.', '');
            $command = "I" . $this->fs . substr($item->detalle, 0, 20) . " (" . $cantidad . ")  $" . $importe . $this->fs . $this->display . "\n";
            $total = $total + ($precio * $cantidad);
            if ($detalle == 1) {
                $estado = $this->EscriboArchivoMandar($command);
            };
        }
        $command = "P" . $this->fs . 2 . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $total = sprintf("$%04.2f", $total);
        $command = "I" . $this->fs . chr(244) . "Importe " . $total . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "P" . $this->fs . 2 . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function ImporteDNF($importe)
    {    //item documento CNF
        $total = sprintf("$%04.2f", $importe);
        $command = "I" . $this->fs . chr(244) . "Importe " . $total . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "P" . $this->fs . 2 . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function FirmaDNF()
    {    //firma CTACTE documento CNF
        $command = "P" . $this->fs . 6 . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "I" . $this->fs . "------------------------------------------------" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $command = "I" . $this->fs . chr(244) . "   FIRMA CLIENTE" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function CierroDNF()
    {    //cierro documento ticket
        $command = "J" . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $estado = $this->CierroArchivoMandar();
        $estado = copy($this->nombre_archivo_tmp, $this->nombre_archivo_mandar);
        return $estado;
    }
}

?>
