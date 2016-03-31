<?php

class Numeradores_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('cfg_numerador');
    }

    function getNextRecibo($puesto)
    {
        $this->db->select("numero AS numero ", false);
        $this->db->from($this->getTable());
        $this->db->where('puesto', $puesto);
        $this->db->where('tipcom_id', 5);
        return $this->db->get()->row()->numero;
    }

    function updateRecibo($puesto, $numero)
    {
        $this->db->set('numero', $numero);
        $this->db->where('tipcom_id', 5);
        $this->db->where('puesto', $puesto);
        $this->db->update($this->getTable());
        return true;
    }

    function getNextCompCtaCte($puesto)
    {
        $this->db->select($this->principal . " AS numero ", false);
        $this->db->from($this->tabla);
        $this->db->where('puesto', $puesto);
        $this->db->where('tipcom_id', 7);
        return $this->db->get()->row()->numero;
    }

    function updateCompCtaCte($puesto, $numero)
    {
        $this->db->set('numero', $numero);
        $this->db->where('tipcom_id', 7);
        $this->db->where('puesto', $puesto);
        $this->db->update($this->tabla);
        return true;
    }
}
