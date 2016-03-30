<?php

class Cuenta_model extends MY_Model
{
    private $tabla = 'cuenta';

    function __construct()
    {
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
        $q = $this->db->get($this->tabla);
        return $q->result();
    }

    function buscoCuit($cuit = 0)
    {
        $this->db->select('id, nombre, cuit');
        $this->db->where('cuit', $cuit);
        $q = $this->db->get($this->tabla);
        return $q->result();
    }

    function ListadoFiltradoNombre($valor)
    {
        //$this->db->_reset_select();
        $this->db->select('id, nombre, cuit, letra');
        $search = '%' . $valor . '%';
        $this->db->from($this->tabla);
        $this->db->like('nombre', $valor);
        $q = $this->db->get();
        return $q->result();
        //return $this->db->_compile_select();
    }

    function save($datos)
    {
        $this->db->insert($this->tabla, $datos);
        return $this->db->insert_id();
    }

    function getNombre($id)
    {
        $this->db->select('nombre');
        $this->db->from($this->getTable());
        $this->db->where('id', $id);
        return $this->db->get()->row()->nombre;
    }
}
