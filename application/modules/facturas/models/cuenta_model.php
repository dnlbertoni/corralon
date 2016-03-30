<?php

class Cuenta_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('cuenta');
    }

    function getProveedoresLista($campoId = "id", $campoNombre = "nombre")
    {
        $this->db->select($campoId);
        $this->db->select($campoNombre);
        $this->db->from($this->getTable());
        $this->db->where('tipo', 2);
        $this->db->order_by($campoNombre);
        $query = $this->db->get();
        $datos = array('0' => "Todos...");
        foreach ($query->result() as $item) {
            $datos[$item->{$campoId}] = $item->{$campoNombre};
        }
        return $datos;
    }
}
