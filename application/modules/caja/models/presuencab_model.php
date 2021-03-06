<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 03/04/2016
 * Time: 06:35 PM
 */
class Presuencab_model extends MY_Model {
    var $tablaMovim = 'fac_presumovim';
    var $tablaTmp = "tmp_movimientos";

    function __construct () {
        parent::__construct ();
        $this->setTable ( 'fac_presuencab' );
    }

    function getPendientes ( $fecha ) {
        $this->db->select ( "fac_presuencab.id" );
        $this->db->select ( "fecha" );
        $this->db->select ( "v.nombre as vendedor" );
        $this->db->select ( "CONCAT(puesto,'-',numero) as comprobante" );
        $this->db->select ( "cuenta.nombre as cliente" );
        $this->db->select ( "importe" );
        //$this->db->select("vendedor");
        $this->db->from ( $this->getTable () );
        $this->db->join ( "cuenta", "cuenta.id=cuenta_id", "inner" );
        $this->db->join ( "cfg_vendedores as v", "v.id=vendedor_id", "inner" );
        $this->db->where ( 'fac_presuencab.estado', 'P' );
        if ( $fecha ) {
            $this->db->where ( 'fac_presuencab.fecha <=', $fecha );
        }
        return $this->db->get ()->result ();
    }

    function getFacturados ( $fecha = false ) {
        $this->db->select ( "fac_presuencab.id" );
        $this->db->select ( "fecha" );
        $this->db->select ( "v.nombre as vendedor" );
        $this->db->select ( "CONCAT(puesto,'-',numero) as comprobante" );
        $this->db->select ( "cuenta.nombre as cliente" );
        $this->db->select ( "importe" );
        //$this->db->select("vendedor");
        $this->db->from ( $this->getTable () );
        $this->db->join ( "cuenta", "cuenta.id=cuenta_id", "inner" );
        $this->db->join ( "cfg_vendedores as v", "v.id=vendedor_id", "inner" );
        $this->db->where ( 'fac_presuencab.estado', 'F' );
        if ( $fecha ) {
            $this->db->where ( 'fac_presuencab.fecha', $fecha );
        }
        return $this->db->get ()->result ();
    }

    function graboComprobante ( $datosEncab, $datosMovim ) {
        $this->db->trans_begin ();
        //grabo facencab
        $this->db->set ( 'fecha', 'NOW()', FALSE );
        $this->db->insert ( $this->getTable (), $datosEncab );
        $idencab = $this->db->insert_id ();
        //grabo presumovim
        foreach ( $datosMovim as $movimiento ) {
            $this->db->set ( 'fecha_movim', 'NOW()', FALSE );
            $this->db->set ( 'idencab', $idencab );
            $this->db->insert ( $this->tablaMovim, $movimiento );
        }
        //borro el temporal
        $puesto = $datosEncab['puesto'];
        $this->db->where ( 'puesto_tmpmov', $puesto );
        $this->db->delete ( $this->tablaTmp );
        if ( $this->db->trans_status () === FALSE ) {
            $this->db->trans_rollback ();
            return false;
        } else {
            $this->db->trans_commit ();
            return $idencab;
        }
    }

    function save ( $datos ) {
        $this->db->insert ( $this->getTable (), $datos );
        $q = $this->db->insert_id ();
        return $q;
    }

    function getComprobante ( $id ) {
        $this->db->from ( $this->tablaMovim );
        $this->db->join ( $this->getTable (), "fac_presuencab.id = fac_presumovim.idencab", "inner" );
        $this->db->where ( 'idencab', $id );
        //echo $this->db->_compile_select();
        return $this->db->get ()->result ();
    }

    function getTotales ( $id ) {
        $this->db->select ( 'SUM(cantidad_movim * preciovta_movim) AS Total', false );
        $this->db->select ( 'COUNT(codigobarra_movim) AS Bultos', false );
        $this->db->from ( $this->tablaMovim );
        $this->db->where ( 'idencab', $id );
        return $this->db->get ()->row ();
    }

    function getArticulos ( $id ) {
        $this->db->select ( 'id_articulo' );
        $this->db->select ( 'codigobarra_movim AS Codigobarra' );
        $this->db->select ( 'descripcion_movim AS Nombre' );
        $this->db->select ( 'cantidad_movim AS Cantidad' );
        $this->db->select ( 'preciovta_movim AS Precio' );
        $this->db->select ( 'tasaiva_movim AS Tasa' );
        $this->db->select ( '(cantidad_movim * preciovta_movim ) AS Importe', false );
        $this->db->select ( 'id As codmov' );
        $this->db->from ( $this->tablaMovim );
        $this->db->where ( 'idencab', $id );
        $this->db->order_by ( 'id', 'DESC' );
        $q = $this->db->get ();
        if ( $q->num_rows () > 0 ) {
            return $q->result ();
        } else {
            return false;
        }
    }

    function setFacturado ( $idPresu, $idFacencab ) {
        $this->db->set ( 'facencab_id', $idFacencab );
        $this->db->set ( 'estado', 'F' );
        $this->db->where ( 'id', $idPresu );
        return $this->db->update ( $this->getTable () );
    }

    function setAnulado ( $idPresu ) {
        $this->db->set ( 'facencab_id', 'NULL' );
        $this->db->set ( 'estado', 'A' );
        $this->db->where ( 'id', $idPresu );
        return $this->db->update ( $this->getTable () );
    }

}