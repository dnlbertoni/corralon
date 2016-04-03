<?php

/**
 * Description of facmovim_model
 *
 * @author dnl
 */
class Facmovim_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable ( 'fac_facmovim' );
    }

    function getDetalle($idencab)
    {
        $this->db->select('cantidad_movim');
        $this->db->select ( 'fac_facmovim.id_articulo' );
        $this->db->select('descripcion_articulo');
        $this->db->select('preciovta_movim');
        $this->db->from($this->getTable());
        $this->db->join ( 'stk_articulos', 'stk_articulos.id_articulo=fac_facmovim.id_articulo', 'inner' );
        $this->db->where ( 'fac_facmovim.idencab', $idencab );
        return $this->db->get()->result();
    }
}
