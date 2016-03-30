<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ticket extends hasar
{
    function __construct()
    {
        parent::__construct();
    }

    function AbrirTicket($tipdoc = "T")
    {  //abro docuemtno ticket
        $command = "@" . $this->fs . $tipdoc . $this->fs . "T" . "\n";
        if (file_exists($this->nombre_archivo_tmp))
            unlink($this->nombre_archivo_tmp);
        if (file_exists($this->nombre_archivo_recibir))
            unlink($this->nombre_archivo_recibir);
        $estado = $this->AbroArchivoMandar();
        if ($estado != "ERROR")
            $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function TextoTicket()
    { //Texto Fiscal documento ticket
        $command = "A" . $this->fs . "Muchas Gracias" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function ItemTicket($items)
    {//item documento ticket
        foreach ($items as $item) {
            $iva = number_format($item->iva, 2, '.', '');
            $precio = number_format($item->precio, 2, '.', '');
            $cantidad = number_format($item->cantidad, 2, '.', '');
            $command = "B" . $this->fs . substr($item->detalle, 0, 20) . $this->fs . $cantidad . $this->fs . $precio . $this->fs . $iva . $this->fs . "M" . $this->fs . '0.0' . $this->fs . $this->display . $this->fs . "T" . "\n";
            $estado = $this->EscriboArchivoMandar($command);
        }
        return $estado;
    }

    function SubTotalTicket()
    {//Subtotal documento ticket
        $command = "C" . $this->fs . "P" . $this->fs . "Subtotal" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    function TotalTicket($total)
    {//Total documento ticket
        $total = number_format($total, 2, '.', '');
        $command = "D" . $this->fs . "Efectivo" . $this->fs . $total . $this->fs . "T" . $this->fs . $this->display . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        return $estado;
    }

    //cierro documento ticket
    function CerrarTicket()
    {
        $command = "E" . "\n";
        $estado = $this->EscriboArchivoMandar($command);
        $estado = $this->CierroArchivoMandar();
        $estado = copy($this->nombre_archivo_tmp, $this->nombre_archivo_mandar);
        return $estado;
    }
}

?>