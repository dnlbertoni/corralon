<?php

class Cuenta_model extends MY_Model{
    var $tablaCondiva = "cfg_condiva";

    function __construct(){
        parent::__construct();
        $this->setTable('cuenta');
    }

    function muestroTodos($limite = 0)
    {
        $this->db->select('id, nombre, cuit');
        $this->db->order_by('nombre', 'asc');
        if ($limite > 0) {
            $this->db->limit($limite);
        }
        $q = $this->db->get($this->getTable());
        return $q->result();
    }

    function buscoCuit($cuit = 0)
    {
        $this->db->select('id, nombre, cuit');
        $this->db->where('cuit', $cuit);
        $q = $this->db->get($this->getTable());
        return $q->result();
    }

    function ListadoFiltradoNombre($valor)
    {
        //$this->db->_reset_select();
        $this->db->select('id, nombre, cuit, letra');
        $search = '%' . $valor . '%';
        $this->db->from($this->getTable());
        $this->db->like ( 'nombre', $search );
        $q = $this->db->get();
        return $q->result();
        //return $this->db->_compile_select();
    }

    function save($datos)
    {
        $this->db->insert($this->getTable(), $datos);
        return $this->db->insert_id();
    }

    function getNombre($id)
    {
        $this->db->select('nombre');
        $this->db->from($this->getTable());
        $this->db->where('id', $id);
        return $this->db->get()->row()->nombre;
    }

    function toDropDown ( $campoId, $campoNombre, $tipo ) {
        $this->db->select ( $campoId );
        $this->db->select ( $campoNombre );
        $this->db->from ( $this->getTable () );
        $this->db->where ( 'tipo', $tipo );
        $this->db->order_by ( $campoNombre );
        $query = $this->db->get ();
        $datos = array ( 'S' => "Seleccione..." );
        foreach ( $query->result () as $item ) {
            $datos[$item->{$campoId}] = $item->{$campoNombre};
        }
        return $datos;
    }

    function getByIdComprobante ( $id ) {
        $this->db->select ( 'cuenta.id                 AS codigo' );
        $this->db->select ( 'cuenta.nombre             AS nombre' );
        $this->db->select ( 'cuenta.datos_fac          AS datos_fac' );
        $this->db->select ( 'cuenta.direccion          AS direccion' );
        $this->db->select ( 'cuenta.nombre_facturacion AS nombre_facturacion' );
        $this->db->select ( 'cuenta.cuit               AS cuit' );
        $this->db->select ( 'cuenta.condiva_id         AS condiva' );
        $this->db->select ( 'cuenta.tipdoc             AS tipdoc' );
        $this->db->select ( 'cuenta.ctacte             AS ctacte' );
        $this->db->select ( 'cfg_condiva.letra615      AS letra615' );
        $this->db->from ( $this->getTable () );
        $this->db->join ( $this->tablaCondiva, 'condiva_id = cfg_condiva.id', 'inner' );
        $this->db->where ( 'cuenta.id', $id );
        $this->db->limit ( 1 );
        return $this->db->get ()->row ();
    }
}
