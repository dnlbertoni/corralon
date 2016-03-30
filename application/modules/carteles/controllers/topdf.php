<?php

class Topdf extends MY_Controller
{
    private $Printer = "laser03";

    function __construct()
    {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->model('Articulos_model', '', true);
    }

    function navidad()
    {
        foreach ($_POST as $key => $valor) {
            if ($key != 'Imprimir')
                $codigos[] = $valor;
        };
        if (isset($codigos)) {
            $this->fpdf->Open();
            $this->fpdf->SetMargins(0, 0, 0);
            $this->fpdf->SetAutoPageBreak(true);
            $this->fpdf->SetDrawColor(128);
            $this->fpdf->SetTopMargin(2);
            $campana = 1;
            foreach ($codigos as $valor) {
                $articulo = $this->Articulos_model->getDatosBasicos($valor);
                if ($campana == 1) {
                    $this->fpdf->AddPage('L', 'A4');
                    $desplaz = 0;
                } else {
                    $desplaz = 136;
                };

                $x = 62;
                $y = 90;
                $this->fpdf->Image('assets/img/logo.png', $x + $desplaz, $y, 60);

                $x = 0;
                $y = 0;
                $this->fpdf->Image('assets/img/campanaHueca.gif', $x + $desplaz, $y, 164);

                $Y = 162;
                $X = 40;

                $this->fpdf->SetFont('Times', '', 100);
                $this->fpdf->SetXY($X + $desplaz, $Y);
                $this->fpdf->Cell(50, 10, sprintf("$%2.2f", $articulo->precio), 0, 0, 'J');

                $X = 45;
                $Y = 125;

                $this->fpdf->SetFont('Times', '', 28);
                $this->fpdf->SetXY($X + $desplaz, $Y);
                $this->fpdf->Cell(164, 10, substr($articulo->descripcion, 0, 14), 0, 0, 'J');
                $this->fpdf->SetXY($X + $desplaz - 5, $Y + 10);
                $this->fpdf->Cell(164, 10, substr($articulo->descripcion, 14, 16), 0, 0, 'J');
                $campana *= -1;
            }
            //$this->fpdf-Cuadricula();
            $file = TMP . 'navidad.pdf';
            $this->fpdf->Output($file, 'I');
            $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
            shell_exec($cmd);
            $cmd = sprintf("rm -f %s", $file);
            shell_exec($cmd);
        }
        redirect('carteles/', 'location', 301);
    }

    function CartelesPrecios()
    {
        foreach ($_POST as $key => $valor) {
            if ($valor == 'p') {
                $codigos[] = $key;
            } else {
                $grabar[] = $key;
            }
        }
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $this->fpdf->SetTopMargin(2);
        $this->fpdf->AddPage('P', array('100', '148'));
        $ancho = 50;
        $alto = 33;
        $dato = 0;
        $col = 0;
        $row = 0;
        $fecha = new DateTime();
        foreach ($codigos as $valor) {
            $x = $col * $ancho;
            $y = $row * $alto + 5;
            $articulo = $this->Articulos_model->getDatosBasicos($valor);
            $this->fpdf->Rect($x, $y, $ancho, $alto, '');
            $this->fpdf->SetFont('Times', '', 10);
            $this->fpdf->SetXY($x + 1, $y + 6);
            $this->fpdf->Image('assets/img/logo.png', $x + 1, $y + 18, 15);
            $this->fpdf->Cell($ancho - 2, 0, substr($articulo->descripcion, 0, 22), 0, 0, 'L');
            $this->fpdf->SetXY($x + 1, $y + 10);
            $this->fpdf->Cell($ancho - 2, 0, substr($articulo->descripcion, 22, 22), 0, 0, 'L');
            $this->fpdf->SetFont('Times', '', 36);
            $this->fpdf->SetXY($x + 1, $y + 22);
            $this->fpdf->Cell($ancho - 2, 0, "$" . $articulo->precio, 0, 0, 'R');
            $this->fpdf->SetFont('Times', '', 8);
            $this->fpdf->SetXY($x + 1, $y + 30);
            $this->fpdf->Cell($ancho / 2, 0, $articulo->codigobarra, 0, 0, 'L');
            $this->fpdf->Cell($ancho / 2, 0, $fecha->format('d/m/Y'), 0, 0, 'R');
            $dato++;
            $col++;
            if ($col > 1) {
                $col = 0;
                $row++;
            };
            if ($row > 3) {
                $row = 0;
                $this->fpdf->AddPage('P', array('100', '148'));
            };
        };
        $actualizoPrecios = $this->Articulos_model->GraboImpresionPrecios($codigos);
        if (isset($grabar)) {
            $actualizoGrabar = $this->Articulos_model->GraboImpresionPrecios($grabar);
        } else {
            $actualizoGrabar = true;
        }
        //echo $actualizo;
        //$actualizoPrecios=true;
        //$actualizoGrabar=true;
        if ($actualizoPrecios && $actualizoGrabar) {
            $file = TMP . "cartel.pdf";
            $this->fpdf->Output($file, 'F');
            $cmd = sprintf("lp -o media=Custom.100x148mm %s -d %s", $file, $this->Printer);
            shell_exec($cmd);
            $cmd = sprintf("rm -f  %s", $file);
            shell_exec($cmd);
        };
        redirect('carteles/', 'location', 301);
    }

    function CartelesVinos()
    {
        foreach ($_POST as $key => $valor) {
            if ($valor == 'p') {
                $codigos[] = $key;
            } else {
                $grabar[] = $key;
            }
        }
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $this->fpdf->SetTopMargin(2);
        $this->fpdf->AddPage();
        $ancho = 85;
        $alto = 40;
        $dato = 0;
        $col = 0;
        $row = 0;
        $fecha = new DateTime();
        foreach ($codigos as $valor) {
            $x = $col * $ancho;
            $y = ($row * $alto) + 7;
            $articulo = $this->Articulos_model->getDatosBasicos($valor);
            $this->fpdf->Rect($x, $y, $ancho, $alto, '');
            $this->fpdf->SetFont('Times', '', 12);
            $this->fpdf->SetXY($x + 1, $y + 4);
            $this->fpdf->Image('assets/img/logo.png', $x + 1, $y + 17, 25);
            $this->fpdf->Cell($ancho - 2, 0, substr($articulo->descripcion, 0, 30), 0, 0, 'C');
            $this->fpdf->SetXY($x + 1, $y + 10);
            $this->fpdf->Cell($ancho - 2, 0, substr($articulo->descripcion, 30, 30), 0, 0, 'C');
            $this->fpdf->SetFont('Times', '', 60);
            $this->fpdf->SetXY($x + 1, $y + 25);
            $this->fpdf->Cell($ancho - 2, 0, "$" . $articulo->precio, 0, 0, 'R');
            $this->fpdf->SetFont('Times', '', 8);
            $this->fpdf->SetXY($x + 1, $y + 35);
            $this->fpdf->Cell($ancho - 2, 0, $fecha->format('d/m/Y'), 0, 0, 'R');
            $dato++;
            $col++;
            if ($col > 1) {
                $col = 0;
                $row++;
            };
            if ($row > 6) {
                $row = 0;
                $this->fpdf->AddPage();
            };
        };
        $actualizo = $this->Articulos_model->GraboImpresionPrecios($codigos);
        //echo $actualizo;
        //$actualizo=true;
        if ($actualizo) {
            $file = TMP . "cartel.pdf";
            $this->fpdf->Output($file, 'F');
            $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
            shell_exec($cmd);
            $cmd = sprintf("rm -f  %s", $file);
            shell_exec($cmd);
        };
        redirect('carteles/', 'location', 301);
    }

    function cartelesGrandes()
    {
        foreach ($_POST as $key => $valor) {
            if ($valor == 'p') {
                $codigos[] = $key;
            } else {
                $grabar[] = $key;
            }
        }
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $this->fpdf->SetTopMargin(2);
        $this->fpdf->AddPage('P', array('100', '148'));
        $ancho = 100;
        $alto = 66;
        $dato = 0;
        $col = 0;
        $row = 0;
        $fecha = new DateTime();
        foreach ($codigos as $valor) {
            $x = $col * $ancho + 5;
            $y = ($row * $alto) + 7;
            $articulo = $this->Articulos_model->getDatosBasicos($valor);
            $this->fpdf->Rect($x, $y, $ancho, $alto, '');
            $this->fpdf->SetFont('Times', '', $ancho / 5);
            $this->fpdf->SetXY($x + 1, $y + 4);
            $this->fpdf->Image('assets/img/logo.png', $x + 1, $y + ($alto / 1.5), $alto / 2);
            $this->fpdf->Cell($ancho - 2, 0, substr($articulo->descripcion, 0, 20), 0, 0, 'C');
            $this->fpdf->SetXY($x + 1, $y + 10);
            $this->fpdf->Cell($ancho - 2, 0, substr($articulo->descripcion, 20, 20), 0, 0, 'C');
            $this->fpdf->SetFont('Times', '', 80);
            $this->fpdf->SetXY($x + 1, $y + ($alto / 2));
            $this->fpdf->Cell($ancho - 10, 0, "$" . $articulo->precio, 0, 0, 'R');
            $this->fpdf->SetFont('Times', '', $ancho / 10);
            $this->fpdf->SetXY($x + 1, $y + $alto - ($alto / 10));
            $this->fpdf->Cell($ancho - 10, 0, $fecha->format('d/m/Y'), 0, 0, 'R');
            $dato++;
            $col++;
            if ($col > 0) {
                $col = 0;
                $row++;
            };
            if ($row > 1) {
                $row = 0;
                $this->fpdf->AddPage('P', array('100', '148'));
            };
        };
        //$actualizo =$this->Articulos_model->GraboImpresionPrecios($codigos);
        //echo $actualizo;
        $actualizo = true;
        if ($actualizo) {
            $file = TMP . "cartel.pdf";
            $this->fpdf->Output($file, 'F');
            $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
            shell_exec($cmd);
            $cmd = sprintf("rm -f  %s", $file);
            shell_exec($cmd);
        };
        redirect('carteles/', 'location', 301);
    }

    function oferta($tamano = 1)
    {
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(fecha)|^(Imprimir)/', $key)) {
                $codigos[] = $valor;
            };
        };
        /*
        foreach($codigos as $codigo){
          $articulo = $this->Articulos_model->GetDatosBasicos($codigo);
        }
        Template::render();
        */
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $x = 0;
        $y = 0;
        if ($tamano == 1 && count($codigos) > 0) {
            foreach ($codigos as $valor) {
                $this->fpdf->AddPage('L', 'A4');
                $articulo = $this->Articulos_model->getDatosBasicos($valor);
                $this->fpdf->Image('assets/img/logo.png', $x, $y, 100);
                $this->fpdf->SetFont('Times', '', 120);
                $this->fpdf->SetXY($x + 100, $y + 25);
                $this->fpdf->Cell(190, 0, "OFERTA", 0, 0, 'C');
                $this->fpdf->SetXY($x, $y + 75);
                $this->fpdf->SetFont('Arial', '', 60);
                $this->fpdf->Cell(290, 0, strtoupper(substr($articulo->descripcion, 0, 20)), 0, 1, 'C');
                $this->fpdf->SetFont('Times', '', 200);
                $this->fpdf->SetXY($x, $y + 125);
                $this->fpdf->Cell(290, 0, sprintf("$%02.2f", $articulo->precio), 0, 0, 'C');
                $this->fpdf->SetFont('Arial', '', 12);
                $this->fpdf->SetXY($x, $y + 200);
                $this->fpdf->Cell(290, 0, sprintf("Oferta valida hasta %s", $this->input->post('fecha')), 0, 0, 'R');
            }
        };
        if ($tamano == 3 && count($codigos) > 0) {
            $this->fpdf->AddPage();
            $alto = 85;
            $pagina = 270;
            foreach ($codigos as $valor) {
                $articulo = $this->Articulos_model->getDatosBasicos($valor);
                $this->fpdf->Rect($x, $y + 5, 160, $alto, '');
                $this->fpdf->Image('assets/img/logo.png', $x, $y + 45, 80);
                $this->fpdf->SetFont('Times', '', 72);
                $this->fpdf->SetXY($x, $y + 15);
                $this->fpdf->Cell(160, 0, "OFERTA", 0, 0, 'C');
                $this->fpdf->SetXY($x, $y + 35);
                $this->fpdf->SetFont('Arial', '', 30);
                $this->fpdf->Cell(160, 0, strtoupper(substr($articulo->descripcion, 0, 20)), 0, 1, 'C');
                $this->fpdf->SetFont('Arial', '', 72);
                $this->fpdf->SetXY($x + 80, $y + 55);
                $this->fpdf->Cell(80, 0, sprintf("$%02.2f", $articulo->precio), 0, 0, 'C');
                $this->fpdf->SetFont('Arial', '', 12);
                $this->fpdf->SetXY($x, $y + 85);
                $texto = "Oferta valida hasta " . $this->input->post('fecha');
                $this->fpdf->Cell(160, 0, $texto, 0, 0, 'R');
                $y += $alto;
                if ($y > $pagina) {
                    $this->fpdf->AddPage();
                    $y = 0;
                };
            }
        };
        $file = TMP . "cartel.pdf";
        $this->fpdf->Output($file, 'F');
        $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
        shell_exec($cmd);
        $cmd = sprintf("rm -f  %s", $file);
        shell_exec($cmd);
        redirect('carteles/', 'location', 301);
    }

    function ofertaMultiple($tamano = 1)
    {
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(fecha)|^(Imprimir)|^(precio)/', $key)) {
                $codigos[] = $valor;
            };
        };
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $x = 0;
        $y = 0;
        if ($tamano == 1 && count($codigos) > 0) {
            $this->fpdf->AddPage('L', 'A4');
            $this->fpdf->Image('assets/img/logo.png', $x, $y, 100);
            $this->fpdf->SetFont('Times', '', 120);
            $this->fpdf->SetXY($x + 100, $y + 25);
            $this->fpdf->Cell(190, 0, "OFERTA", 0, 0, 'C');
            $i = 0;
            $cantidad = 1;
            foreach ($codigos as $valor) {
                $articulo = $this->Articulos_model->getDatosBasicos($valor);
                $this->fpdf->SetXY($x, $y + 60 + $i);
                $this->fpdf->SetFont('Arial', '', 35);
                $this->fpdf->Cell(290, 0, strtoupper(substr($articulo->descripcion, 0, 20)), 0, 1, 'C');
                $i += 15;
                if ($cantidad < count($codigos)) {
                    $this->fpdf->SetXY($x, $y + 60 + $i);
                    $this->fpdf->Cell(290, 0, '+', 0, 1, 'C');
                    $cantidad++;
                }
                $i += 15;
            }
            $this->fpdf->SetFont('Times', '', 200);
            $this->fpdf->SetXY($x, $y + 170);
            $this->fpdf->Cell(290, 0, sprintf("$%02.2f", $this->input->post('precio')), 0, 0, 'C');
            $this->fpdf->SetFont('Arial', '', 12);
            $this->fpdf->SetXY($x, $y + 200);
            $this->fpdf->Cell(290, 0, sprintf("Oferta valida hasta %s", $this->input->post('fecha')), 0, 0, 'R');
        };
        if ($tamano == 3 && count($codigos) > 0) {
            foreach ($codigos as $valor) {
                $this->fpdf->AddPage();
                $articulo = $this->Articulos_model->getDatosBasicos($valor);
                $this->fpdf->Rect($x, $y + 5, 160, 90, '');
                $this->fpdf->Image('assets/img/logo.png', $x, $y + 45, 80);
                $this->fpdf->SetFont('Times', '', 72);
                $this->fpdf->SetXY($x, $y + 15);
                $this->fpdf->Cell(160, 0, "OFERTA", 0, 0, 'C');
                $this->fpdf->SetXY($x, $y + 35);
                $this->fpdf->SetFont('Arial', '', 30);
                $this->fpdf->Cell(160, 0, strtoupper(substr($articulo->descripcion, 0, 20)), 0, 1, 'C');
                $this->fpdf->SetFont('Arial', '', 72);
                $this->fpdf->SetXY($x + 80, $y + 55);
                $this->fpdf->Cell(80, 0, sprintf("$%02.2f", $articulo->precio), 0, 0, 'C');
                $this->fpdf->SetFont('Arial', '', 12);
                $this->fpdf->SetXY($x, $y + 85);
                $texto = "Oferta valida hasta " . $this->input->post('fecha');
                $this->fpdf->Cell(160, 0, $texto, 0, 0, 'R');
                if ($y > 150) {
                    $this->fpdf->AddPage();
                    $y = -90;
                };
            }
        };
        $file = TMP . "cartel.pdf";
        $this->fpdf->Output($file, 'F');
        $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
        shell_exec($cmd);
        $cmd = sprintf("rm -f  %s", $file);
        shell_exec($cmd);
        redirect('carteles/', 'location', 301);
    }

    function listaDePrecios()
    {
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/(Imprimir)|(tamano)/', $key)) {
                $codigos[] = $valor;
            }
        }
        $tamano = $this->input->post('tamano');
        $fuente = $tamano * 2.25;
        $print = ($this->input->post('Imprimir') == "Imprimir") ? true : false;
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $this->fpdf->SetTopMargin(10);
        $this->fpdf->AddPage();
        $ancho = 150;
        $alto = $tamano;
        $col = 0;
        $row = 0;
        foreach ($codigos as $valor) {
            $x = $col * $ancho;
            $y = $row * $alto + 15;
            $articulo = $this->Articulos_model->getDatosBasicos($valor);
            $id = $articulo->id;
            $this->fpdf->SetFont('Times', '', $fuente / 1.5);
            $this->fpdf->SetXY($x + 10, $y);
            $this->fpdf->Cell(50, 0, $articulo->codigobarra . " - ", 0, 0, 'R');
            $this->fpdf->SetFont('Times', '', $fuente);
            $this->fpdf->SetXY($x + 60, $y);
            $this->fpdf->Cell($ancho, 0, substr($articulo->descripcion, 0, ($ancho / $tamano) * 2), 0, 0, 'L');
            $row++;
            $margen = 20;
            if ($row > (((290 - $margen) / $tamano) - 1)) {
                $row = 0;
                $this->fpdf->AddPage();
            };
        };
        $rubroNombre = $this->Articulos_model->getNombreRubro($id);
        if ($print) {
            $file = TMP . "cartel.pdf";
            $this->fpdf->Output($file, 'F');
            $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
            shell_exec($cmd);
            $cmd = sprintf("rm -f  %s", $file);
            shell_exec($cmd);
        } else {
            $file = "listadeprecios_" . $rubroNombre . ".pdf";
            $this->fpdf->Output($file, 'D');
        }
        redirect('carteles/', 'location', 301);
    }

    function cartelVerduras()
    {
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/(tamano)|^(Imprimir)/', $key)) {
                $codigos[] = $valor;
            };
        };
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $x = 0;
        $y = 0;
        if (count($codigos) > 0) {
            $this->fpdf->AddPage();
            $alto = 70;
            $pagina = 270;
            $col = 0;
            $des = 0;
            foreach ($codigos as $valor) {
                $x = $col * 100 + $des;
                $articulo = $this->Articulos_model->getDatosBasicos($valor);
                $this->fpdf->Rect($x, $y + 5, ($col * 100) + 100, $alto, '');
                $this->fpdf->Image('assets/img/logo.png', $x + 3, $y + 45, 50);
                $this->fpdf->SetFont('Times', '', 30);
                $this->fpdf->SetXY($x, $y + 15);
                $this->fpdf->Cell(100, 0, strtoupper(substr($articulo->descripcion, 0, 14)), 0, 1, 'C');
                $this->fpdf->SetXY($x, $y + 35);
                $this->fpdf->SetFont('Arial', '', 30);
                $this->fpdf->Cell(100, 0, strtoupper(substr($articulo->descripcion, 15, 14)), 0, 1, 'C');
                /*
                $this->fpdf->SetFont('Arial','' ,42);
                $this->fpdf->SetXY($x+80,$y+55);
                $this->fpdf->Cell(80, 0, sprintf("$%02.2f",$articulo->precio), 0, 0, 'C');
                 *
                 */
                $col = ($col == 0) ? 1 : 0;
                $des = ($col == 0) ? 0 : 3;
                if ($col == 0) {
                    $y += $alto;
                };
                if ($y > $pagina) {
                    $this->fpdf->AddPage();
                    $y = 0;
                };
            }
        };
        $print = ($this->input->post('Imprimir') == "Imprimir") ? true : false;
        if ($print) {
            $file = TMP . "cartel.pdf";
            $this->fpdf->Output($file, 'F');
            $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
            shell_exec($cmd);
            $cmd = sprintf("rm -f  %s", $file);
            shell_exec($cmd);
        } else {
            $file = "carteles.pdf";
            $this->fpdf->Output($file, 'D');
        }
        redirect('carteles/', 'location', 301);
    }

    function ofertaEscrita()
    {
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(fecha)|^(Imprimir)|^(copias)|^(titulo)/', $key)) {
                $lineas[] = $valor;
            };
        };
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $x = 0;
        $y = 0;
        $alto = 85;
        $pagina = 270;
        //defino copias
        $copy = 0;
        while ($copy < $this->input->post('copias')) {
            $this->fpdf->AddPage('L', 'A4');
            $articulo = $this->Articulos_model->getDatosBasicos($valor);
            $this->fpdf->Image('assets/img/logo.png', $x, $y, 100);
            $fontLineas = 240 / count($lineas);
            $fontLinea = 1200 / strlen($this->input->post('titulo'));
            $font = ($fontLineas > $fontLinea) ? $fontLinea : $fontLineas;
            $this->fpdf->SetFont('Times', '', $font);
            $this->fpdf->SetXY($x + 100, $y + 25);
            $this->fpdf->Cell(190, 0, $this->input->post('titulo'), 0, 0, 'C');
            $y += 75;
            $fontLineas = 240 / count($lineas);
            foreach ($lineas as $valor) {
                $valor = trim($valor);
                if ($y < 180) {
                    $this->fpdf->SetXY($x, $y);
                    $fontLinea = 1200 / strlen($valor);
                    $font = ($fontLineas > $fontLinea) ? $fontLinea : $fontLineas;
                    $this->fpdf->SetFont('Arial', '', $font);
                    $this->fpdf->Cell(290, 0, strtoupper(substr($valor, 0, 20)), 0, 1, 'C');
                    $y += $font / 2;
                };
            }
            $this->fpdf->SetFont('Arial', '', 12);
            $this->fpdf->SetXY($x, 200);
            $this->fpdf->Cell(290, 0, sprintf("Oferta valida hasta %s", $this->input->post('fecha')), 0, 0, 'R');
            $copy++;
        }
        $file = TMP . "cartel.pdf";
        $this->fpdf->Output($file, 'I');
        $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
        shell_exec($cmd);
        $cmd = sprintf("rm -f  %s", $file);
        shell_exec($cmd);
        redirect('carteles/', 'location', 301);
    }

    function listaDeArticulos()
    {
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/(Imprimir)|(tamano)/', $key)) {
                $codigos[] = $valor;
            }
        }
        $tamano = $this->input->post('tamano');
        $fuente = $tamano * 2.25;
        $print = ($this->input->post('Imprimir') == "Imprimir") ? true : false;
        $this->fpdf->Open();
        $this->fpdf->SetMargins(0, 0, 0);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetDrawColor(128);
        $this->fpdf->SetTopMargin(10);
        $this->fpdf->AddPage();
        $ancho = 150;
        $alto = $tamano;
        $col = 0;
        $row = 0;
        foreach ($codigos as $valor) {
            $x = $col * $ancho;
            $y = $row * $alto + 15;
            $articulo = $this->Articulos_model->getDatosBasicos($valor);
            $id = $articulo->id;
            $this->fpdf->SetFont('Times', '', $fuente / 1.5);
            $this->fpdf->SetXY($x + 10, $y);
            $this->fpdf->Cell(50, 0, $articulo->codigobarra . " - ", 0, 0, 'R');
            $this->fpdf->SetFont('Times', '', $fuente);
            $this->fpdf->SetXY($x + 60, $y);
            $this->fpdf->Cell($ancho, 0, substr($articulo->descripcion, 0, ($ancho / $tamano) * 2), 0, 0, 'L');
            $row++;
            $margen = 20;
            if ($row > (((290 - $margen) / $tamano) - 1)) {
                $row = 0;
                $this->fpdf->AddPage();
            };
        };
        $rubroNombre = $this->Articulos_model->getNombreRubro($id);
        if ($print) {
            $file = TMP . "cartel.pdf";
            $this->fpdf->Output($file, 'F');
            $cmd = sprintf("lp %s -d %s", $file, $this->Printer);
            shell_exec($cmd);
            $cmd = sprintf("rm -f  %s", $file);
            shell_exec($cmd);
        } else {
            $file = "listadeprecios_" . $rubroNombre . ".pdf";
            $this->fpdf->Output($file, 'D');
        }
        redirect('carteles/', 'location', 301);
    }
}
