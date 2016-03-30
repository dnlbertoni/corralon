<?php

class Cuenta_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('cuenta');
    }

    function getNombre($id)
    {
        if ($id != 0) {
            $this->db->from($this->getTable());
            $this->db->where('id', $id);
            return $this->db->get()->row()->nombre;
        } else {
            return false;
        }
    }

    function listadoFiltrado($tipo = false, $ctacte = false)
    {
        $this->db->select('id');
        $this->db->select('nombre');
        $this->db->from($this->getTable());
        if ($tipo)
            $this->db->where('tipo', $tipo);
        if ($ctacte)
            $this->db->where('ctacte', $ctacte);
        $this->db->order_by('nombre');
        return $this->db->get()->result();
    }
}
