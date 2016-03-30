<?php

/**
 * Description of ofertas_model
 *
 * @author dnl
 */
class Ofertas_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('oferencab');
    }

    function datosIndex()
    {
        $this->db->select('oferencab.id');
        $this->db->select('oferencab.descripcion AS detalle');
        $this->db->select('inicio');
        $this->db->select('final');
        $this->db->select('COUNT(id_articulo) AS cantidad', FALSE);
        $this->db->select('oferencab.estado');
        $this->db->from('ofermovim');
        $this->db->join('oferencab', 'oferencab.id=ofermovim.id_oferta', 'inner');
        $this->db->where('ofermovim.estado', 1);
        $this->db->group_by('id_oferta');
        return $this->db->get()->result();
    }
}