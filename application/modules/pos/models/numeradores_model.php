<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Numeradores_model extends MY_Model
{
    var $tabla = "numerador";
    var $principal = "numero";
    var $primaryKey = "id";

    function __construct()
    {
        parent::__construct();
        $this->setTable('numerador');
    }

    function getById($id)
    {
        $this->db->select($this->principal . " AS numero ", false);
        $this->db->from($this->tabla);
        $this->db->where($this->primaryKey, $id);
        return $this->db->get()->row();
    }

    function getNextRemito($puesto)
    {
        $this->db->select($this->principal . " AS numero ", false);
        $this->db->from($this->tabla);
        $this->db->where('puesto', $puesto);
        $this->db->where('tipcom_id', 6);
        return $this->db->get()->row()->numero;
    }

    function updateRemito($puesto, $numero)
    {
        $this->db->set('numero', $numero);
        $this->db->where('tipcom_id', 6);
        $this->db->where('puesto', $puesto);
        $this->db->update($this->tabla);
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
