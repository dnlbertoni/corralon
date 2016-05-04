<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        Template::set_theme('citrus/');
        $this->load->model('Articulos_model');
    }

    public function index () {
        Template::render();
    }

    public function imprimirListado()
    {
        $articulos = $this->Articulos_model->getArticulos();
        $this->output->enable_profiler(true);
        $this->load->library('fpdf');
        $this->fpdf->Open();
        $this->fpdf->AddPage();
        $this->fpdf->SetFillColor('0');
        $this->fpdf->SetDrawColor('0');
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetFont('Times', '', 12);
        $rubroAux = false;
        $subrubroAux = false;
        foreach ($articulos as $arti) {
            if ($rubroAux != $arti->idrubro) {
                $this->fpdf->Cell(210, 8, $arti->rubroNombre, 1, 1, 'C');
                $rubroAux = $arti->idrubro;
            };
            if ($subrubroAux != $arti->idsubrubro) {
                $this->fpdf->Cell(210, 8, $arti->subrubroNombre, 1, 1, 'C');
                $subrubroAux = $arti->idsubrubro;
            };
            $this->fpdf->Cell(10, 8, $arti->id, 1, 0);
            $this->fpdf->Cell(30, 8, $arti->codigobarra, 1, 0);
            $this->fpdf->Cell(100, 8, $arti->detalle, 1, 0);
            $this->fpdf->Cell(40, 8, $arti->submarcaNombre, 1, 0);
            $this->fpdf->Cell(10, 8, '', 1, 0);
            $this->fpdf->Cell(10, 8, '', 1, 0);
            $this->fpdf->Cell(10, 8, '', 1, 1);
        }
        $this->fpdf->Output('prueba', 'I');
    }

}

/* End of file  */
/* Location: ./application/modules/stock/controllers/ */