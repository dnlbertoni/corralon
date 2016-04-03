<?php

/**
 * Description of menues_model
 *
 * @author dnl
 */
class Menues_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('cfg_menu');
    }

    function getAll($estado = 'ALL')
    {
        $this->db->select('cfg_menu.id as id');
        $this->db->select('cfg_menu.id_modulo as modulo');
        $this->db->select ( 'cfg_menu.orden as orden' );
        $this->db->select('cfg_menu.nombre as nombre');
        $this->db->select('IF(ISNULL(cfg_menu.clase),"",cfg_menu.clase) as clase', FALSE);
        $this->db->select('cfg_menu.link as link');
        $this->db->select('cfg_menu.target as target');
        $this->db->select('cfg_modulos.nombre as nombreModulo');
        $this->db->from($this->getTable());
        $this->db->join('cfg_modulos', 'cfg_menu.id_modulo=cfg_modulos.id', 'inner');
        if ($estado != 'ALL') {
            $this->db->where('cfg_menu.estado', $estado);
        }
        $this->db->order_by('modulo');
        $this->db->order_by ( 'orden' );
        return $this->db->get()->result();
    }
}
