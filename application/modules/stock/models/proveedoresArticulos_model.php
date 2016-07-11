<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 15/05/2016
 * Time: 11:06 PM
 */
class ProveedoresArticulos_model extends MY_Model {

    function __construct () {
        parent::__construct ();
        $this->setTable ( "stk_proveedores_articulos" );
    }

    function getByProveedor ( $idPorveedor ) {
        $this->db->select ( 'a.id_articulo as id' );
        $this->db->select ( 'a.descripcion_articulo as descripcion' );
        $this->db->select ( 'a.costo_articulo as costo' );
        $this->db->select ( 'a.precio_articulo as precio' );
        $this->db->select ( 'codigo_prov as codigoProveedor' );
        $this->db->select ( 'sr.descripcion_subrubro as subrubro' );
        $this->db->select ( 'sm.detalle_submarca as submarca' );
        $this->db->from ( $this->getTable () );
        $this->db->join ( 'stk_articulos as a', 'id_articulo=a.id_articulo', 'inner' );
        $this->db->join ( 'stk_subrubros as sr', 'a.id_subrubro=sr.id_subrubro', 'inner' );
        $this->db->join ( 'stk_submarcas as sm', 'a.id_submarca=sm.id_submarca', 'inner' );
        $this->db->where ( 'cuenta_id', $idPorveedor );
        $this->db->where ( 'a.id_articulo=stk_proveedores_articulos.articulo_id', '', false );
        return $this->db->get ()->result ();
    }

    function existeRelacion ( $cuenta_id, $articulo_id ) {
        $this->db->select ( 'id' );
        $this->db->from ( $this->getTable () );
        $this->db->where ( 'cuenta_id', $cuenta_id );
        $this->db->where ( 'articulo_id', $articulo_id );
        $this->db->limit ( 1 );
        $q = $this->db->get ();
        if ( count ( $q ) > 0 ) {
            return $q->row ()->id;
        } else {
            return false;
        }
    }
}