<?php

class Tmpfacencab_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable("tmp_facencab");
    }

    function inicializo($puesto, $numero, $tipcom, $cuenta)
    {
        $this->db->set('tipcom_id', $tipcom);
        $this->db->set('puesto', $puesto);
        $this->db->set('numero', $numero);
        $this->db->set('cuenta_id', $cuenta);
        $this->db->insert($this->getTable());
        return $this->db->insert_id();
    }

    function save($datos)
    {
        $this->db->insert($this->getTable(), $datos);
        $q = $this->db->insert_id();
        return $q;
    }

    function vacio($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->getTable());
        return true;
    }

    function getDatosUltimo($puesto)
    {
        $this->db->select_max('id');
        $this->db->from($this->getTable());
        $this->db->where('puesto', $puesto);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $idencab = $q->row()->id;
            //$this->db->_reset_select();
            $this->db->from($this->getTable());
            $this->db->where('puesto', $puesto);
            $this->db->where('id', $idencab);
            $this->db->limit(1);
            return $this->db->get()->row();
        } else {
            return false;
        };
    }

    function getComprobante($id)
    {
        $this->db->select('puesto');
        $this->db->select('numero');
        $this->db->select('tipcom_id');
        $this->db->select('cuenta_id');
        $this->db->select('cuenta.nombre as cuenta_nombre', false);
        $this->db->from($this->getTable());
        $this->db->join('cuenta', 'cuenta.id=tmp_facencab.cuenta_id', 'inner');
        $this->db->where('tmp_facencab.id', $id);
        return $this->db->get()->row();
    }

    function updateTotales($id, $importe)
    {
        $this->db->set('importe', $importe);
        $this->db->where('id', $id);
        $this->db->update($this->getTable());
        $this->db->_reset_select();
    }

    function cambioCuenta($id, $cuenta_id)
    {
        $this->db->set('cuenta_id', $cuenta_id);
        $this->db->where('id', $id);
        $this->db->update($this->getTable());
        return $id;
    }

    function cambioComprobante($id, $tipcom_id)
    {
        $this->db->set('tipcom_id', $tipcom_id);
        $this->db->where('id', $id);
        $this->db->update($this->getTable());
        return $id;
    }
}
