<?php

class Articulos_model extends MY_Model
{
    var $tabla = "stk_articulos";

    function __construct()
    {
        parent::__construct();
        $this->setTable('stk_articulos');
    }

    function getDatosPresupuesto($codigobarra)
    {
        if (trim($codigobarra) != '') {
            $this->db->select('codigobarra_articulo as id');
            $this->db->select('descripcion_articulo as nombre');
            $this->db->select ( 'precio_articulo as precio' );
            $this->db->select('tasaiva_articulo as iva');
            $this->db->select('estado_articulo as estado');
            $this->db->from($this->tabla);
            $this->db->where('codigobarra_articulo', $codigobarra);
            $this->db->limit(1);
            return $this->db->get()->row();
        } else {
            return false;
        }
    }

    public function agregoLog($codigobarra, $modulo, $error = "NULL")
    {
        $this->db->set('codigobarra', $codigobarra);
        $this->db->set('modulo', $modulo);
        $this->db->set('error', $error);
        $this->db->set('fecha', 'NOW()', FALSE);
        $this->db->set('estado', SUSPENDIDO);
        $this->db->insert('stk_articulos_tmp');
        return $this->db->insert_id();
    }
}