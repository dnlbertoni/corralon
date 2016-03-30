<?php

/**
 * Description of usermenu_model
 *
 * @author ultra
 */
class Usermenu_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('user_menu');
    }

    function getBarraMenu($usuario = 0)
    {
        $this->db->_reset_select();
        $this->db->select("user_menu.id   as id");
        $this->db->select("modulos.nombre as nombreModulo");
        $this->db->select("menu.nombre    as nombreMenu");
        $this->db->select("modulos.clase  as claseModulo");
        $this->db->select("menu.clase     as claseMenu");
        $this->db->select("menu.link      as link");
        $this->db->select("menu.target    as target");
        $this->db->from($this->getTable());
        $this->db->join("menu", "user_menu.menu_id = menu.id", "inner");
        $this->db->join("modulos", "menu.modulo_id    = modulos.id", "left");
        return $this->db->get()->results();
    }
}
