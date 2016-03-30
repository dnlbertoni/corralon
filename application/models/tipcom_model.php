<?php

class Tipcom_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('tipcom');
    }

    function getNombre($id = 0)
    {
        //$this->db->_reset_select();
        if ($id != 0) {
            $this->db->select('descripcion AS nombre');
            $this->db->from('tipcom');
            $this->db->where('id', $id);
            $this->db->limit(1);
            $q = $this->db->get();
            $qu = $q->row();
            $query = $qu->nombre;
        } else {
            $query = "No existe el Comprobante";
        }
        return $query;
    }

    function getConceptoCaja($id)
    {
        if ($id != 0) {
            $this->db->select('concepto_id AS concepto');
            $this->db->from('tipcom');
            $this->db->where('id', $id);
            $this->db->limit(1);
            $query = $this->db->get()->row()->concepto;
        } else {
            $query = 0;
        }
        return $query;
    }
}
