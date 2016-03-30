<?php

class Topdf extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->model('Cuenta_model', '', true);
    }

    function listado($tipo = false, $ctacte = false)
    {
        $cuentas = $this->Cuenta_model->listadoFiltrado($tipo, $ctacte);
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->AddPage();
        $y = 5;
        $x = 5;
        $band = 0;
        //encabezado Tabla
        $this->fpdf->SetFont('Times', '', 12);
        $this->fpdf->SetXY($x, $y + $band);
        $this->fpdf->Cell(20, 7, 'Codigo', 1, 0, 'C');
        $this->fpdf->Cell(80, 7, 'Cliente', 1, 0, 'C');
        $this->fpdf->Cell(20, 7, 'Codigo', 1, 0, 'C');
        $this->fpdf->Cell(80, 7, 'Cliente', 1, 0, 'C');
        $band += 7;
        $next = 100;
        foreach ($cuentas as $mov) {
            $next = ($next == 0) ? 100 : 0;
            $this->fpdf->SetXY($x + $next, $y + $band);
            $this->fpdf->Cell(20, 7, $mov->id, 1, 0, 'C');
            $this->fpdf->Cell(80, 7, $mov->nombre, 1, 0, 'J');
            if ($band < 250) {
                if ($next == 100) {
                    $band += 7;
                };
            } else {
                $this->fpdf->AddPage();
                $band = 0;
            }
        }
        $file = TMP . 'listado.pdf';
        $this->fpdf->Output($file, 'F');
        $cmd = sprintf("lp %s -d %s", $file, PRCARTEL);
        shell_exec($cmd);
        $cmd = sprintf("rm -f %s", $file);
        shell_exec($cmd);
        redirect('cuenta/', 'location', 301);
        //Template::render();
    }

    function _Encabezado($titulo = '', $concatCuenta = '', $fecdes = '', $fechas = '', $nro = 0)
    {
        $this->fpdf->Image('rsc/img/logo.png', 0, 0, 80);
        $this->fpdf->SetXY(70, 5);
        $this->fpdf->SetFont('Courier', 'B', 24);
        $this->fpdf->Cell(0, 10, $titulo, 0, 1, 'C');
        $this->fpdf->SetXY(80, 15);
        $this->fpdf->SetFont('Courier', 'B', 16);
        $this->fpdf->Cell(110, 10, $concatCuenta, 0, 1, 'L');
        $this->fpdf->SetXY(80, 25);
        $this->fpdf->SetFont('Courier', '', 12);
        $this->fpdf->Cell(110, 10, "Desde: " . $fecdes, 0, 1, 'L');
        $this->fpdf->SetXY(80, 35);
        $this->fpdf->Cell(110, 10, "Hasta: " . $fechas, 0, 1, 'L');
        $this->fpdf->SetXY(150, 25);
        $this->fpdf->Cell(30, 10, "NRO de Liquidacion: " . $nro, 0, 1, 'R');

    }
}
