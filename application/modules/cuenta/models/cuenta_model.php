<?php

class Cuenta_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('cuenta');
    }

    function getNombre($id)
    {
        if ($id != 0) {
            $this->db->from($this->getTable());
            $this->db->where('id', $id);
            return $this->db->get()->row()->nombre;
        } else {
            return false;
        }
    }

    function getIndex($tipo)
    {
        $this->db->select('id, nombre, cuit');
        $this->db->select('if(tipo=1,"Clie","Prov") as tipo', false);
        $this->db->from($this->getTable());
        $this->db->order_by('nombre', 'asc');
        if ($tipo) {
            $this->db->where('tipo', $tipo);
        }
        return $this->db->get()->result();
    }

    function listadoFiltrado($tipo = false, $ctacte = false)
    {
        $this->db->select('id');
        $this->db->select('nombre');
        $this->db->from($this->getTable());
        if ($tipo)
            $this->db->where('tipo', $tipo);
        if ($ctacte)
            $this->db->where('ctacte', $ctacte);
        $this->db->order_by('nombre');
        return $this->db->get()->result();
    }

    function buscoCuit($cuit = 0)
    {
        $this->db->select('id, nombre, cuit');
        $this->db->where('cuit', $cuit);
        $q = $this->db->get($this->getTable());
        return $q->result();
    }

    function ListadoFiltradoNombre($valor, $tipo = 0)
    {
        //$this->db->_reset_select();
        $this->db->select ( 'id, nombre, cuit, letra, ctacte, tipo' );
        $search = '%' . $valor . '%';
        $this->db->from($this->getTable());
        $this->db->like('nombre', $valor);
        if ($tipo != 0) {
            $this->db->where('tipo', $tipo);
        };
        return $this->db->get()->result();
    }

    function save($datos)
    {
        $this->db->insert($this->getTable(), $datos);
        return $this->db->insert_id();
    }

    function StaticsSimples ( $id ) {
        $this->db->select('count(tipcom_id) as cantidad ', false);
        $this->db->select('sum(importe) as importe', false);
        $this->db->from ( 'fac_facencab' );
        $this->db->where('cuenta_id', $id);
        return $this->db->get()->row();
    }
}
