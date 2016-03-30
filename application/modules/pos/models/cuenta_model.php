<?php

class Cuenta_model extends MY_Model
{
    var $tabla = "cuenta";
    var $tablaCondiva = "condiva";

    function __construct()
    {
        parent::__construct();
        $this->setTable('cuenta');
    }

    function getByIdComprobante($id)
    {
        $this->db->select('cuenta.id                 AS codigo');
        $this->db->select('cuenta.nombre             AS nombre');
        $this->db->select('cuenta.datos_fac          AS datos_fac');
        $this->db->select('cuenta.nombre_facturacion AS nombre_facturacion');
        $this->db->select('cuenta.cuit               AS cuit');
        $this->db->select('cuenta.condiva_id         AS condiva');
        $this->db->select('cuenta.tipdoc             AS tipdoc');
        $this->db->select('cuenta.ctacte             AS ctacte');
        $this->db->select('condiva.letra615          AS letra615');
        $this->db->from($this->tabla);
        $this->db->join($this->tablaCondiva, 'condiva_id = condiva.id', 'inner');
        $this->db->where('cuenta.id', $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    function getNombre($id)
    {
        if ($id != 0) {
            $this->db->from($this->tabla);
            $this->db->where('id', $id);
            return $this->db->get()->row()->nombre;
        } else {
            return false;
        }
    }

    function getCtacte($id)
    {
        if ($id != 0) {
            $this->db->from($this->tabla);
            $this->db->where('id', $id);
            return $this->db->get()->row()->ctacte;
        } else {
            return false;
        }
    }

}