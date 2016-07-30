<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 29/04/2016
 * Time: 09:13 PM
 *
 * @property Articulos_model $Articulos_model
 * @property Cuenta_model $Cuenta_model
 * @property ProveedoresArticulos_model $ProveedoresArticulos_model
 * @property Subrubros_model $Subrubros_model
 * @property Submarcas_model $Submarcas_model
 */
class Articulos extends Admin_Controller {

    function __construct () {
        parent::__construct ();
        $this->load->model ( 'Articulos_model' );
        $this->load->model ( 'Cuenta_model' );
        $this->load->model ( 'Subrubros_model' );
        $this->load->model ( 'Submarcas_model' );

    }
    function index () {
        $this->load->library ( 'pagination' ); //Cargamos la librería de paginación
        $this->load->config ( 'pagination' );
        $config['base_url'] = base_url () . 'stock/articulos/'; // parametro base de la aplicación, si tenemos un .htaccess nos evitamos el index.php
        $config['total_rows'] = $this->Articulos_model->getNumeroFilas ();//calcula el número de filas
        $config["uri_segment"] = 3;//el segmento de la paginación
        $this->pagination->initialize ( $config ); //inicializamos la paginación
        $articulos = $this->Articulos_model->getAll ( false, $this->pagination->per_page, $this->uri->segment ( 3 ) );
        Template::set ( 'articulos', $articulos );
        Template::set ( 'paginacion', $this->pagination->create_links () );
        Template::render ();
    }

    function nuevo () {
        Template::set ( 'articulo', $this->Articulos_model->getInicial () );
        Template::set ( 'accion', 'stock/articulos/nuevoDo' );
        Template::set_view ( 'stock/articulos/edit' );
        Template::render ();
    }

    function nuevoDo () {
        foreach ( $_POST as $key => $value ) {
            if ( $key != "ID_ARTICULO" ) {
                $datos[$key] = $value;
            }
        }
        $this->Articulos_model->add ( $datos );
        //Template::redirect('stock/aticulos');
    }
    function importar ( $tipo = "proveedor" ) {
        Template::set ( 'tipo', $tipo );
        Template::set ( 'proveedoresSel', $this->Cuenta_model->toDropDown ( 'id', 'nombre', 2 ) );
        Template::set_view ( 'stock/articulos/importar' );
        Template::render ();
    }
    function do_importar () {
        $this->output->enable_profiler ( false );
        $this->load->library ( 'PHPExcel' );
        $directorio = ( __DIR__ );
        $config['upload_path'] = $directorio . '/../../../../assets/tmp/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = '2048';

        $this->load->library ( 'upload', $config );

        $error = "no errores";
        if ( !$this->upload->do_upload () ) {
            $error = $this->upload->display_errors ();
        } else {
            $archivo = $this->upload->data ();
            $nombreArchivo = $archivo['full_path'];

            //creando un objeto lector y cargando el fichero
            $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
            $objPHPExcel = $objReader->load ( $nombreArchivo );
            //$objPHPExcel = PHPExcel_IOFactory::createReaderForFile($nombreArchivo);

            //iterando por el contenido de las celdas
            foreach ( $objPHPExcel->getWorksheetIterator () as $HojaTrabajo ) {
                $titulo = $HojaTrabajo->getTitle ();
                $MayorFila = $HojaTrabajo->getHighestRow ();
                $MayorColumna = $HojaTrabajo->getHighestColumn ();
                $MayorColumnaIndex = PHPExcel_Cell::columnIndexFromString ( $MayorColumna );
                $nrColumns = ord ( $MayorColumna ) - 64;
                $total_filas = $MayorFila - 1;
                $encabenzado = array ();
                for ( $fila = 1; $fila < $MayorFila + 1; $fila++ ) {
                    for ( $columna = 0; $columna < $MayorColumnaIndex; $columna++ ) {
                        $celda = $HojaTrabajo->getCellByColumnAndRow ( $columna, $fila );
                        if ( $fila == 1 ) {
                            $encabenzado[$columna] = $celda->getValue ();
                        } else {
                            $sub = $encabenzado[$columna];
                            if ( gettype ( $celda->getValue () ) == "string" ) {
                                $datos[$fila][$sub] = strtoupper ( trim ( $celda->getValue () ) );
                            } else {
                                $datos[$fila][$sub] = $celda->getValue ();
                            }
                        }
                    }
                }
                $data[0] = $datos;
            }
        }
        unlink ( $nombreArchivo );
        header ( 'Content-Type: application/json' );
        echo json_encode ( $data[0] );
    }
    function comparar () {
        $this->load->model ( "ProveedoresArticulos_model" );
        foreach ( $_POST as $key => $value ) {
            if ( $key != "idProveedor" ) {
                $subAux = explode ( '_', $key );
                $codigo = $subAux[1];
                $clave = $subAux[0];
                $data['articulosProveedor'][$codigo][$clave] = $value;
                $data['articulosProveedor'][$codigo]['accion'] = "add";
            }
        }
        $data['articulosEncontrados'] = $this->ProveedoresArticulos_model->getByProveedor ( $this->input->post ( "idProveedor" ) );
        foreach ( $data['articulosEncontrados'] as $articulosEncontrados ) {
            foreach ( $data['articulosProveedor'] as $key => $value ) {
                if ( $articulosEncontrados->codigoProveedor == $key ) {
                    $data['articulosProveedor'][$key]['accion'] = "edit";
                }
            }
        }
        $data['cuenta_id'] = $this->input->post ( 'idProveedor' );
        $data['subrubrosSel'] = $this->Subrubros_model->toDropDown ( 'id_subrubro', 'descripcion_subrubro' );
        $data['submarcasSel'] = $this->Submarcas_model->toDropDown ( 'id_submarca', 'detalle_submarca' );
        Template::set ( $data );
        Template::render ();
    }

    function agregarDeLote () {
        $this->output->enable_profiler ( false );
        $this->load->model ( 'ProveedoresArticulos_model' );
        $datos = array ( 'DESCRIPCION_ARTICULO' => $this->input->get ( 'descripcion' ),
            'COSTO_ARTICULO' => $this->input->get ( 'costo' ),
            'MARKUP_ARTICULO' => $this->input->get ( 'markup' ),
            'PRECIO_ARTICULO' => $this->input->get ( 'precio' ),
            'TASAIVA_ARTICULO' => 21,
            'ID_SUBRUBRO' => $this->input->get ( 'subrubro' ),
            'ID_SUBMARCA' => $this->input->get ( 'submarca' ),
            'SERVICIO_ARTICULO' => 0,
            'ESTADO_ARTICULO' => 1,
        );
        $idArticulo = $this->Articulos_model->add ( $datos );
        $this->Articulos_model->update ( array ( 'CODIGOBARRA_ARTICULO' => $idArticulo, "PRECIO_ARTICULO" => $this->input->get ( 'precio' ) ), $idArticulo );
        if ( $this->input->get ( 'codigoProveedor' ) != '' && $this->input->get ( 'codigoProveedor' ) != 'null' ) {
            $datosProveedor = array (
                'cuenta_id' => $this->input->get ( 'cuenta_id' ),
                'articulo_id' => $idArticulo,
                'codigo_prov' => $this->input->get ( 'codigoProveedor' ),
                'estado' => 1
            );
            $resultado = $this->ProveedoresArticulos_model->add ( $datosProveedor );
        }
        header ( 'Content-Type: application/json' );
        echo json_encode ( array ( 'estado' => 'Procesado' ) );
    }

    function modificarDeLote () {
        $datos = array (
            'COSTO_ARTICULO' => $this->input->get ( 'costo' ),
            'MARKUP_ARTICULO' => $this->input->get ( 'markup' ),
            'PRECIO_ARTICULO' => $this->input->get ( 'precio' ),
        );
        $idArticulo = $this->input->get ( 'id_articulo' );
        $resultado = $this->Articulos_model->add ( $datos );
        if ( $this->input->get ( 'codigoProveedor' ) != '' ) {
            $datosProveedor = array (
                'cuenta_id' => $this->input->get ( 'cuenta_id' ),
                'articulo_id' => $idArticulo,
                'codigo_prov' => $this->input->get ( 'codigoProveedor' ),
                'estado' => 1
            );
            $relacion = $this->ProveedoresArticulos_model->existeRelacion ( $this->input->get ( 'cuenta_id' ), $idArticulo );
            if ( $relacion ) {
                $resultado = $this->ProveedoresArticulos_model->update ( $datosProveedor, $relacion );
            } else {
                $resultado = $this->ProveedoresArticulos_model->add ( $datosProveedor );
            }
        }
        header ( 'Content-Type: application/json' );
        echo json_encode ( array ( 'estado' => 'Procesado' ) );
    }

    function busquedaAjax () {
        $this->output->enable_profiler ( false );
        $this->load->view ( 'pos/presupuestos/busquedaArticulo' );
    }
    function searchAjax () {
        $this->output->enable_profiler ( false );
        $resultados = $this->Articulos_model->getBusquedaAjax ( $this->input->post ( 'valor' ) );
        echo json_encode ( $resultados );
    }

    function consultaJson () {
        $this->output->enable_profiler ( false );
        $id = $this->input->post ( 'id' );
        $articulo = $this->Articulos_model->getById ( $id );
        header ( 'Content-Type: application/json' );
        echo json_encode ( $articulo );
    }
}