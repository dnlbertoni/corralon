<?php

/**
 * Description of facencab_model
 *
 * @author dnl
 */
class Facencab_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('facencab');
    }

    function getAnosCTACTE()
    {
        $this->db->distinct();
        $this->db->select('DATE_FORMAT(fecha, "%Y") as ano', false);
        $this->db->from($this->getTable());
        $this->db->where('estado', 9);
        $q = $this->db->get()->result();
        if (count($q) > 0) {
            foreach ($q as $anio) {
                $dato[$anio->ano] = $anio->ano;
            }
            return $dato;
        } else {
            return false;
        }
    }

    function getTotalesCTACTE($periodo)
    {
        $this->db->select('DATE_FORMAT(fecha, "%Y%m") as periodo', false);
        $this->db->select('cuenta_id');
        $this->db->select('cuenta.nombre as cliente');
        $this->db->select('SUM(importe) as total');
        $this->db->select('count(facencab.id) as compras');
        $this->db->from($this->getTable());
        $this->db->join('cuenta', 'cuenta.id=facencab.cuenta_id', 'right');
        $this->db->where('DATE_FORMAT(fecha, "%Y%m") = ' . $periodo, '', false);
        $this->db->where('facencab.estado', 9);
        $this->db->group_by('cuenta_id');
        $this->db->order_by('cliente');
        return $this->db->get()->result();
    }
}
