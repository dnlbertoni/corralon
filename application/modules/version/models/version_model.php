<?php

class Version_model extends Model
{
    var $tabla = "veriones";

    function __construct()
    {
        parent::__construct();
    }

    function getAll()
    {
        $this->db->from($this->tabla);
        return $this->db->get()->result();
    }
}
