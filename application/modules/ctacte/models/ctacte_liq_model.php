<?php

class Ctacte_liq_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('ctacte_liq');
    }

    function insertar($datos)
    {
        $this->db->set('fecha', 'NOW()', FALSE);
        $this->db->set('estado', 'P');
        $this->db->insert($this->getTable(), $datos);
        return $this->db->insert_id();
    }

    function getAllEstado($estado, $limite = false)
    {
        $this->db->select('ctacte_liq.id AS id');
        $this->db->select('cuenta.nombre AS nombre');
        $this->db->select('DATE_FORMAT(ctacte_liq.fecha, "%d/%m/%Y") as fecha', FALSE);
        $this->db->select('ctacte_liq.importe as importe');
        $this->db->from($this->getTable());
        $this->db->join('cuenta', 'cuenta.id =ctacte_liq.id_cuenta', 'inner');
        $this->db->where('ctacte_liq.estado', $estado);
        if ($limite) {
            $this->db->order_by('ctacte_liq.fecha', 'DESC');
            $this->db->limit($limite);
        } else {
            $this->db->order_by('nombre');
        }
        return $this->db->get()->result();
    }

    function cobroLiq($idLiq, $idRec)
    {
        $this->db->set('estado', 'C');
        $this->db->set('id_rec', $idRec);
        $this->db->where('id', $idLiq);
        $this->db->update($this->getTable());
        return true;
    }

    function getPeriodos($idCuenta)
    {
        $this->db->select('id');
        $this->db->select('DATE_FORMAT(fecha, "%Y%m") as periodo', false);
        $this->db->select('importe');
        $this->db->select('estado');
        $this->db->from($this->getTable());
        $this->db->where('id_cuenta', $idCuenta);
        $this->db->order_by('periodo', 'DESC');
        return $this->db->get()->result();
    }

    function promedio($idCuenta)
    {
        $this->db->select('id_cuenta');
        $this->db->select('AVG(importe) as promedio', false);
        $this->db->from($this->getTable());
        $this->db->group_by('id_cuenta');
        $this->db->having('id_cuenta', $idCuenta);
        $q = $this->db->get()->row();
        if (count($q) > 0) {
            return $q->promedio;
        } else {
            return false;
        }
    }
}
