<?php

class Ctactemovim_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('ctacte_movim');
    }

    function graboComprobante($datos)
    {
        $this->db->trans_begin();
        //grabo facencab
        $this->db->set('fecha', 'NOW()', FALSE);
        $this->db->insert($this->getTable(), $datos);
        $idencab = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $idencab;
        }
    }
}
