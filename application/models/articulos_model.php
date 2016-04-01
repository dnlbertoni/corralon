<?php

class Articulos_model extends MY_Model
{
    private $tabla = "stk_articulos";

    function __construct()
    {
        parent::__construct();
        $this->setTable('stk_articulos');
    }

    function getDatosBasicos($id)
    {
        $id = intval($id);
        //$this->db->_reset_select();
        $this->db->select("id_articulo AS id, descripcion_articulo AS descripcion, preciovta_articulo AS precio");
        $this->db->from($this->tabla);
        $this->db->where('id_articulo', $id);
        return $this->db->get()->row();
    }

}
