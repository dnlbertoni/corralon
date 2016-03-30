<?php

/**
 * Description of modulos_model
 *
 * @author dnl
 */
class Modulos_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('cfg_modulos');
    }

    function getAll($estado = 'ALL')
    {
        $this->db->select('id as id');
        $this->db->select('nombre as nombre');
        $this->db->select('IF(ISNULL(clase),"",clase) as clase', FALSE);
        $this->db->select('IF(ISNULL(modo_texto),"",modo_texto) as modo_texto', FALSE);
        $this->db->from($this->getTable());
        if ($estado != 'ALL') {
            $this->db->where('estado', $estado);
        }
        $this->db->order_by('nombre');
        return $this->db->get()->result();
    }
}
