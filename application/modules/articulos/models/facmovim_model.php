<?php

/**
 * Description of facmovim_model
 *
 * @author dnl
 */
class facmovim_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable ( 'fac_facmovim' );
    }

    function getByCodigobarra($CB)
    {
        $this->db->select ( 'DATE_FORMAT(fac_facencab.fecha,"%d/%m/%Y") AS fecha', FALSE );
        $this->db->from($this->getTable());
        $this->db->join ( 'fac_facencab', 'fac_facencab.id=fac_facmovim.idencab', 'inner' );
        $this->db->where('codigobarra_movim', $CB);
        $this->db->order_by ( 'fac_facencab.fecha', 'DESC' );
        return $this->db->get()->result();
    }
}