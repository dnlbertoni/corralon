<?php

class Condiva_model extends MY_Model
{
    private $tabla = 'condiva';

    function __construct()
    {
        parent::__construct();
        $this->setTable('condiva');
    }

    function toDropDown()
    {
        //$this->db->_reset_select();
        $this->db->select('id, nombre ');
        $this->db->from($this->tabla);
        $q = $this->db->get()->result();
        foreach ($q as $item) {
            $lista[$item->id] = $item->nombre;
        };
        return (object)$lista;
    }
}
