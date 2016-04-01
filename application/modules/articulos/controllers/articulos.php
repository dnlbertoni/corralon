<?php

/*
 * Controlador cabecera del modulo articulos
 * FEATURES:
 *          - Articulo nuevo
 *          - Borrar articulo
 *          - Ver/Editar detalle de ficha de articulo
 *          - busque de articulos por:
 *                                    - Descripcion
 *                                    - Marca
 *                                    - Rubro
 *                                    - Global ->
 *                                    - Avanzada ->
 *          - muestro precio
 *          - cambio  precio
 *          - generar nombre standard
 *          - cambio mediante proceso de lotes
 *          - estadisticas y rankings
 *@TODO: Unificar el Template
 */

class Articulos extends MY_Controller
{
    private $wizard = true;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Articulos_model');
        $this->load->model('Rubros_model', '', TRUE);
        $this->load->model('Subrubros_model', '', TRUE);
        $this->load->model('Marcas_model', '', TRUE);
        $this->load->model('Submarcas_model', '', TRUE);
        Template::set('title', 'Modulo Articulos');
    }

    function index()
    {
        //panel de tareas
        $datos['tareas'][] = array('articulos/ofertas/', 'Ofertas');
        $datos['tareas'][] = array('articulos/rubros/', 'Rubros');
        $datos['tareas'][] = array('articulos/subrubros/', 'Subrubros');
        $datos['tareas'][] = array('articulos/marcas/', 'Marcas');
        $datos['tareas'][] = array('articulos/submarcas/', 'Submarcas');
        $datos['tareas'][] = array('articulos/estadisticas', 'Estadisticas');
        $datos['tareas'][] = array('articulos/subirListaAS', 'Lista Precios Jhonson');
        $datos['tareas'][] = array('articulos/subirListaLS', 'Lista Precios Sugeridos');
        Template::set($datos);
        Template::set_block('tareas', 'tareas'); // panel de tareas

        $articulos = $this->Articulos_model->getAllMod(50);
        $data['progressbarDetalle'] = $this->Articulos_model->nivelNormalizacionDetalle();
        $data['articulos'] = $articulos;
        $data['rubrosSel'] = $this->Rubros_model->ListaSelect();
        $data['subrubrosSel'] = $this->Subrubros_model->ListaSelect();
        $data['marcasSel'] = $this->Marcas_model->ListaSelect();
        $data['submarcasSel'] = $this->Submarcas_model->ListaSelect();
        $data['subrubrosDep'] = $this->Subrubros_model->ListaSelectDependiente();
        $data['submarcasDep'] = $this->Submarcas_model->ListaSelectDependiente();
        $data['accionCodigobarra'] = ($this->wizard) ? 'articulos/wizard/index' : 'articulos/ver';
        Assets::add_js('ui-tableFilter');
        Template::set($data);
        Template::render();
    }

    function buscoDescripcion()
    {
        $articulos = $this->Articulos_model->buscoNombre($this->input->post('nombreTXT'));
        $data['vacio'] = (!$articulos) ? true : false;
        $data['articulos'] = $articulos;
        $data['total'] = count($articulos);
        $this->load->view('articulos/listadoAjax', $data);
    }

    function busquedaAvanzada()
    {
        //$this->output->enable_profiler(TRUE);
        $submarca = ($this->input->post('submarca') == 'null') ? false : $this->input->post('submarca');
        $subrubro = ($this->input->post('subrubro') == 'null') ? false : $this->input->post('subrubro');
        $articulos = $this->Articulos_model->searchAvanzada($submarca, $subrubro);
        $data['vacio'] = (!$articulos) ? true : false;
        $data['articulos'] = $articulos;
        $data['total'] = count($articulos);
        $this->load->view('articulos/listadoAjax', $data);
    }

    function busquedaGlobal()
    {
        //$this->output->enable_profiler(TRUE);
        $marca = ($this->input->post('marca') == 'S') ? false : $this->input->post('marca');
        $rubro = ($this->input->post('rubro') == 'S') ? false : $this->input->post('rubro');
        $articulos = $this->Articulos_model->searchGlobal($marca, $rubro);
        $data['vacio'] = (!$articulos) ? true : false;
        $data['articulos'] = $articulos;
        $data['total'] = count($articulos);
        $this->load->view('articulos/listadoAjax', $data);
    }

    function busquedaGobalMarca()
    {
        //$this->output->enable_profiler(TRUE);
        $marca = ($this->input->post('marca') == 'S') ? false : $this->input->post('marca');
        $articulos = $this->Articulos_model->searchGlobal($marca);
        $data['vacio'] = (!$articulos) ? true : false;
        $data['articulos'] = $articulos;
        $data['total'] = count($articulos);
        $this->load->view('articulos/listadoAjax', $data);
    }

    function buscoPorMarca()
    {
        $articulos = $this->Articulos_model->buscoPorMarca($this->input->post('submarca'));
        $data['vacio'] = (!$articulos) ? true : false;
        $data['articulos'] = $articulos;
        $data['total'] = count($articulos);
        $this->load->view('articulos/listadoAjax', $data);
    }

    function buscoPorRubro()
    {
        $articulos = $this->Articulos_model->buscoPorRubro($this->input->post('subrubro'));
        $data['vacio'] = (!$articulos) ? true : false;
        $data['articulos'] = $articulos;
        $data['total'] = count($articulos);
        $this->load->view('articulos/listadoAjax', $data);
    }

    function ver($id = false)
    {
        if (trim($this->input->post('codigobarra')) != '' || $id != false) {
            if (trim($this->input->post('codigobarra')) != '') {
                $articulo = $this->Articulos_model->getByCodigobarra($this->input->post('codigobarra'));
            } else {
                $articulo = $this->Articulos_model->getById($id);
            }
            if ($articulo) {
                $data['Articulo'] = $articulo;
                $data['new'] = FALSE;
                $data['subrubroSel'] = $this->Subrubros_model->ListaSelect();
                $data['nombreSubrubro'] = $this->Subrubros_model->getNombre($articulo->ID_SUBRUBRO);
                $data['marcaSel'] = $this->Submarcas_model->ListaSelect();
                $data['nombreSubmarca'] = $this->Submarcas_model->getNombre($articulo->ID_MARCA);
                $data['accion'] = 'articulos/update';
                $data['primaryKey'] = array($this->Articulos_model->primaryKey, "CODIGOBARRA_ARTICULO");
                Assets::add_js('articulos/ver');
                Template::set($data);
                Template::render();;
            } else {
                //  redirect('articulos/wizard/index/0/'.$this->input->post('codigobarra'), 'location',301);
                $this->agregar($this->input->post('codigobarra'));
            }
        } else {
            Template::redirect('articulos');
        };
    }

    function update($masivo)
    {
        $datos = array();
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(Grabar)|^(ID_ARTICULO)|^(FECHAMODIF_ARTICULO)/', $key)) {
                $datos[$key] = $valor;
            };
        };
        $this->Articulos_model->updateMod($this->input->post('ID_ARTICULO'), $datos);
        if ($masivo) {
            Template::redirect('articulos/wizard/masivo');
        } else {
            Template::redirect('articulos/');
        }
    }

    function borrar($id, $wizard = false)
    {
        $this->Articulos_model->borrar($id);
        if ($wizard) {
            Template::redirect('articulos/wizard/masivo');
        } else {
            Template::redirect('articulos');
        }
    }

    function agregar($codigobarra)
    {
        $articulos = $this->Articulos_model->Inicializar();
        $articulos->CODIGOBARRA_ARTICULO = $codigobarra;
        $articulos->ID_SUBRUBRO = 44;
        $articulos->ID_MARCA = 0;
        $articulos->ID_PROVEEDOR = 1;
        $articulos->TASAIVA_ARTICULO = 21;
        $articulos->ESTADO_ARTICULO = 1;
        $data['Articulo'] = $articulos;
        $data['new'] = TRUE;
        $data['subrubroSel'] = $this->Subrubros_model->ListaSelect();
        $data['nombreSubrubro'] = $this->Subrubros_model->getNombre($articulos->ID_SUBRUBRO);
        $data['marcaSel'] = $this->Submarcas_model->ListaSelect();
        $data['nombreSubmarca'] = $this->Submarcas_model->getNombre($articulos->ID_MARCA);
        $data['accion'] = 'articulos/agregarDo';
        $data['primaryKey'] = array($this->Articulos_model->primaryKey, "CODIGOBARRA_ARTICULO");
        Assets::add_js('articulos/ver');
        Template::set($data);
        Template::set_view('articulos/ver');
        Template::render();
    }

    function agregarDo()
    {
        $datos = array();
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(Grabar)|^(ID_ARTICULO)|^(FECHACREACION_ARTICULO)/', $key)) {
                $datos[$key] = $valor;
            };
        };
        $datos['DESCRIPCION_ARTICULO'] = strtoupper($datos['DESCRIPCION_ARTICULO']);
        $this->Articulos_model->agregar($this->input->post('CODIGOBARRA_ARTICULO'), $datos);
        Template::redirect('articulos');
    }

    function precioAjax()
    {
        $this->output->enable_profiler(FALSE);
        $this->load->view('muestroPrecio');
    }

    function precioAjaxDo()
    {
        $this->output->enable_profiler(FALSE);
        $data['articulo'] = $this->Articulos_model->getDatosPrecio($this->input->post('codigobarra'));
        $data['codigobarra'] = $this->input->post('codigobarra');
        $this->load->view('muestroPrecioAjax', $data);
    }

    function cambioPrecioAjax()
    {
        $articulo = $this->Articulos_model->getDatosBasicos($this->input->post('id'));
        $data['articulo'] = $articulo;
        $data['idArt'] = $this->input->post('id');
        $this->load->view('articulos/cambioPrecio', $data);
    }

    function cambioPrecioDo()
    {
        $this->Articulos_model->actualizoPrecio($this->input->post('id'), $this->input->post('precio'));
    }

    function generoNombre()
    {
        $this->output->enable_profiler(false);
        $producto = $this->Subrubros_model->getAlias($this->input->post('subrubro'));
        if ($this->input->post('submarca') > 0)
            $marca = $this->Submarcas_model->getAlias($this->input->post('submarca'));
        else
            $marca = "";
        $distincion = strtoupper(trim($this->input->post('especif')));
        $medida = strtoupper(trim($this->input->post('medida')));
        $nombre = $producto;
        $nombre .= " " . $marca;
        $nombre .= ($distincion != '') ? " " . $distincion : " ";
        $nombre .= ($medida != '') ? " x" . $medida : " ";
        echo $nombre;
    }

    function cambioMuchos()
    {
        $datos = array();
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(accion)/', $key)) {
                $datos[] = $this->Articulos_model->getByIdFull($valor);
            };
        };
        $data['articulos'] = (object)$datos;
        switch ($this->input->post('accion')) {
            case "marca":
                $accion = "ID_MARCA";
                $url = 'submarcas';
                $funcion = 'searchAjax';
                $vista = 'articulos/cambioMuchos';
                $titulo = "'>> MARCAS <<'";
                break;
            case "rubro":
                $accion = "ID_SUBRUBRO";
                $url = 'subrubros';
                $funcion = 'searchAjax';
                $vista = 'articulos/cambioMuchos';
                $titulo = "'>> RUBROS <<'";
                break;
            case "precio":
                $accion = "PRECIOVTA_ARTICULO";
                $url = 'precios';
                $funcion = 'masivoAjax';
                $vista = 'articulos/precios/cambioMuchos';
                $titulo = "'>> PRECIOS <<'";
                break;
        };
        $data['accion'] = $accion;
        $data['urlBuscoAjax'] = sprintf("'%sindex.php/articulos/%s/%s/%s'", base_url(), $url, $funcion, 'resultadoAjax');
        $data['titulo'] = $titulo;
        Template::set($data);
        Template::set_view($vista);
        Template::render();
    }

    function cambioMuchosDo($precios = false)
    {
        if ($this->input->post('ID_MARCA')) {
            $newfield = 'ID_MARCA';
        } else {
            if ($this->input->post('ID_SUBRUBRO')) {
                $newfield = 'ID_SUBRUBRO';
            } else {
                $newfield = '';
            }
        };
        $datos = array();
        foreach ($_POST as $key => $valor) {
            if (!preg_match('/^(ID_MARCA)|^(ID_SUBRUBRO)|^(precio_)/', $key)) {
                $datos[] = $valor;
            };
        };
        if (!$precios) {
            $dataAdd = array($newfield => $this->input->post($newfield));
            foreach ($datos as $id) {
                $this->Articulos_model->update($dataAdd, $id);
            };
        } else {
            if ($precios == "precios") {
                foreach ($datos as $id) {
                    $aux = "precio_" . $id;
                    $precio = $this->input->post($aux);
                    if ($id > 0 && $this->input->post($aux))
                        $this->Articulos_model->actualizoPrecio($id, $precio);
                };
            }
        }
        Template::redirect('articulos');
    }

    function borrarLote()
    {
        foreach ($_POST as $key => $valor) {
            if (is_numeric($valor)) {
                $datos[] = $valor;
            };
        };
        foreach ($datos as $id) {
            $this->Articulos_model->borrar($id);
        };
        //Template::render();
        Template::redirect('articulos');
    }

    function normalizacion()
    {
        $data['todosLosArticulos'] = $this->Articulos_model->totalRegistros();
        $estadisticas = $this->Articulos_model->datosNormalizacion();
        $data['estadisticas'] = (object)$estadisticas;
        Template::set($data);
        Template::render();
    }

    function estadisticas()
    {
        $estados = $this->Articulos_model->getRubrosMarcasAgrupadas();
        $activos = $this->Articulos_model->getActivos();
        $generica = $this->Articulos_model->getConsulta("SELECT COUNT(id_articulo) AS cantidad FROM stk_articulos WHERE id_marca = 0");
        $data['faltanGenericas'] = $generica->cantidad;
        $data['estados'] = $estados;
        $total = 0;
        foreach ($estados as $numero) {
            $total += $numero->cantidad;
        }
        $data['total'] = $total;
        $data['activos'] = $activos;
        $data['suspendidos'] = $total - $activos;
        $data['normalizacion'] = $this->Articulos_model->datosNormalizacion();
        Template::set($data);
        Template::render();
    }

    function rankings($tipo = "importe")
    {
        switch ($tipo) {
            case "importe":
                $numeros = $this->Articulos_model->getRankingRubrosMarcas(true);
                break;
            case "cantidad":
                $numeros = $this->Articulos_model->getRankingRubrosMarcas(false);
                break;
            case "marcas":
                $numeros = $this->Articulos_model->getRubrosMarcasAgrupadas();
                break;
        }
        $data['numeros'] = $numeros;
        Template::set($data);
        Template::set_view('agrupadas');
        Template::render();
    }

    function subirListaAS()
    {
        $error = array('error' => '');
        Template::set($error);
        Template::render();
    }

    function subirListaASDo()
    {
        $config['upload_path'] = TMP;
        $config['allowed_types'] = 'csv|txt';
        $config['max_size'] = '20480';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            Template::set($error);
            Template::set_view('articulos/subirListaAS');
        } else {
            $archivo = $this->upload->data();
            $this->load->library('Getcsv');
            $productosAS = $this->getcsv->set_file_path($archivo['full_path'], ";")->get_array();
            $x = 0;
            $coef = 70;
            $data['coef'] = $coef;
            $coef = ($coef / 100) + 1;
            foreach ($productosAS as $prod) {
                if ($this->getDatosArticuloCSV($prod['BARRAS'])) {
                    $productos[$x]['BARRAS'] = $prod['BARRAS'];
                    $productos[$x]['PRODUCTO'] = $prod['PRODUCTO'];
                    //$productos[$x]['RUBRO']=$prod['RUBRO'];
                    $productos[$x]['COSTO'] = round((float)$prod['COSTO'], 2);
                    $productos[$x]['PRECIO'] = round($productos[$x]['COSTO'] * $coef, 2);
                    $productos[$x]['detalle_db'] = $this->getDatosArticuloCSV($prod['BARRAS']);
                } else {
                    $nuevos[$x]['BARRAS'] = $prod['BARRAS'];
                    $nuevos[$x]['PRODUCTO'] = $prod['PRODUCTO'];
                    //$productos[$x]['RUBRO']=$prod['RUBRO'];
                    $nuevos[$x]['COSTO'] = round((float)$prod['COSTO'], 2);
                    $nuevos[$x]['PRECIO'] = round($nuevos[$x]['COSTO'] * $coef, 2);
                }
                $x++;
            }
            $data['productos'] = $productos;
            $data['nuevos'] = $nuevos;
            //$data['selSubrubros']=$this->Subrubros_model->toDropDown('id_subrubro','descripcion_subrubro');
            Template::set($data);
            Template::set_view('articulos/archivoAS');
        }
        Template::render();
    }

    function graboDesdeCSV()
    {
        $precio = $this->input->post('costo') * 1.7;
        $this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'preciocosto_articulo', $this->input->post('costo'));
        $this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'preciovta_articulo', $precio);
        echo "Grabacion Ok";
    }

    /* la serenisima
     * modificacion de lista de precios
     */
    function subirListaLS()
    {
        $error = array('error' => '');
        Template::set($error);
        Template::render();
    }

    function subirListaLSDo()
    {
        $config['upload_path'] = TMP;
        $config['allowed_types'] = 'csv|txt';
        $config['max_size'] = '20480';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            Template::set($error);
            Template::set_view('articulos/subirListaLS');
        } else {
            $archivo = $this->upload->data();
            $this->load->library('Getcsv');
            $productosLS = $this->getcsv->set_file_path($archivo['full_path'], ";")->get_array();
            $x = 0;
            foreach ($productosLS as $prod) {
                if ($this->getDatosArticuloCSV($prod['BARRAS'])) {
                    $productos[$x]['BARRAS'] = $prod['BARRAS'];
                    $productos[$x]['PRODUCTO'] = $prod['PRODUCTO'];
                    $productos[$x]['COSTO'] = round((float)$prod['COSTO'], 2);
                    $productos[$x]['PRECIO'] = round((float)$prod['PRECIO'], 2);
                    $productos[$x]['markup'] = round((($productos[$x]['PRECIO'] / $productos[$x]['COSTO']) - 1) * 100, 2);
                    $productos[$x]['detalle_db'] = $this->getDatosArticuloCSV($prod['BARRAS']);
                } else {
                    $nuevos[$x]['BARRAS'] = $prod['BARRAS'];
                    $nuevos[$x]['PRODUCTO'] = $prod['PRODUCTO'];
                    $nuevos[$x]['COSTO'] = round((float)$prod['COSTO'], 2);
                    $nuevos[$x]['PRECIO'] = round((float)$prod['PRECIO'], 2);
                    $nuevos[$x]['markup'] = round((($nuevos[$x]['PRECIO'] / $nuevos[$x]['COSTO']) - 1) * 100, 2);
                }
                $x++;
            }
            $data['productos'] = $productos;
            $data['nuevos'] = $nuevos;
            //$data['selSubrubros']=$this->Subrubros_model->toDropDown('id_subrubro','descripcion_subrubro');
            Template::set($data);
            Template::set_view('articulos/archivoLS');
        }
        Template::render();
    }

    function getDatosArticuloCSV($CB)
    {
        //$this->output->enable_profiler(false);
        $arti = $this->Articulos_model->getByCodigobarraCsv($CB);
        if ($arti) {
            $datos['descripcion'] = $arti->descripcion;
            $datos['costo'] = $arti->costo;
            $datos['precio'] = $arti->precio;
            $datos['markup'] = $arti->markup;
            $datos['fechamodif'] = $arti->fecha;
            return $datos;
        } else {
            return false;
        }
    }

    function graboDesdeLSCSV()
    {
        $this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'preciocosto_articulo', $this->input->post('costo'));
        $this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'preciovta_articulo', $this->input->post('precio'));
        $this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'markup_articulo', $this->input->post('markup'));
        echo "Grabacion Ok";
    }

    function insertoDesdeLSCSV()
    {
        $this->output->enable_profiler('true');
        $datos = array('codigobarra_articulo' => $this->input->post('codigobarra'),
            'DESCRIPCION_ARTICULO' => $this->input->post('detalle'),
            'preciovta_articulo' => $this->input->post('precio'),
            'preciocosto_articulo' => $this->input->post('costo'),
            'markup_articulo' => $this->input->post('markup')
        );
        $id = $this->Articulos_model->add($datos);
        //$this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'preciocosto_articulo', $this->input->post('costo'));
        //$this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'preciovta_articulo', $this->input->post('precio'));
        //$this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'markup_articulo', $this->input->post('markup'));
        echo "Grabacion Ok - Id asignado :" . $id;
    }

    function setRubro()
    {
        $estado = $this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'id_subrubro', $this->input->post('id_subrubro'));
    }

    function setMarca()
    {
        $estado = $this->Articulos_model->updateArticulo($this->input->post('codigobarra'), 'id_submarca', $this->input->post('id_submarca'));
    }

    function chekeoPrecios()
    {
        $this->load->model('Preciosmovim_model');
        $preciosMovim = $this->Preciosmovim_model->ultimosPrecios();
        foreach ($preciosMovim as $nuevo) {
            $precio = $this->Preciosmovim_model->precioFecha($nuevo->id_articulo, $nuevo->fecha);
            $articulo = $this->Articulos_model->getDatosBasicos($nuevo->id_articulo);
            if ($articulo) {
                //if($articulo->precio < $precio){
                $this->Articulos_model->actualizoPrecio($nuevo->id_articulo, $precio, ($precio / 1.7));
                echo "<p>" . $articulo->descripcion . "<span> " . $articulo->precio . "</span><span> " . $precio . "</span></p>";
                //}
            }
        }
    }
}


/*
 * Location: controllers/articulos.php
 */
