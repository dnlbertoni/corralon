<?php

class Cuenta_model extends MY_Model{

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
        $this->db->like('nombre', $valor);
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
}
