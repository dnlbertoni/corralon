<?php

class Condiva_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('condiva');
    }

    function toDropDown()
    {
        $this->db->select('id, nombre ');
        $this->db->from($this->getTable());
        $q = $this->db->get()->result();
        foreach ($q as $item) {
            $lista[$item->id] = $item->nombre;
        };
        return (object)$lista;
    }
}
