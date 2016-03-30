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
        $this->setTable('facmovim');
    }

    function getByCodigobarra($CB)
    {
        $this->db->select('DATE_FORMAT(facencab.fecha,"%d/%m/%Y") AS fecha', FALSE);
        $this->db->from($this->getTable());
        $this->db->join('facencab', 'facencab.id=facmovim.idencab', 'inner');
        $this->db->where('codigobarra_movim', $CB);
        $this->db->order_by('facencab.fecha', 'DESC');
        return $this->db->get()->result();
    }
}