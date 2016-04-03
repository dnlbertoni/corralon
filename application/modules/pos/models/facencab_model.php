<?php

class Facencab_model extends MY_Model {

    var $tablaMovim = "fac_facmovim";
    var $tablaTmp = "tmp_movimientos";

    function __construct()
    {
        parent::__construct();
        $this->setTable ( "fac_facencab" );
    }

    function getMaxId()
    {
        $this->db->select_max('id');
        $this->db->from ( $this->getTable () );
        return $this->db->get()->row()->id;
    }

    function graboComprobante($datosEncab, $datosMovim)
    {
        $this->db->trans_begin();
        //grabo facencab
        $this->db->set('fecha', 'NOW()', FALSE);
        $this->db->set('periva', 'DATE_FORMAT(NOW(),"%Y%m")', FALSE);
        $this->db->insert ( $this->getTable (), $datosEncab );
        $idencab = $this->db->insert_id();
        //grabo facmovim
        foreach ($datosMovim as $movimiento) {
            $this->db->set('fecha_movim', 'NOW()', FALSE);
            $this->db->set('idencab', $idencab);
            $this->db->insert($this->tablaMovim, $movimiento);
        }
        //borro el temporal
        if ($datosEncab['tipcom_id'] == 6 || $datosEncab['tipcom_id'] == 9) {
            $puesto = $datosEncab['puesto'] - 90;
        } else {
            $puesto = $datosEncab['puesto'];
        }
        $this->db->where('puesto_tmpmov', $puesto);
        $this->db->delete($this->tablaTmp);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $idencab;
        }
    }

    function save($datos)
    {
        $this->db->insert($this->tabla, $datos);
        $q = $this->db->insert_id();
        return $q;
    }

    function getRegistro ( $id ) {
        $this->db->_reset_select();
        $this->db->from ( $this->getTable () );
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    function getCierreZ ( $numero ) {
        $this->db->_reset_select();
        $this->db->from ( $this->getTable () );
        $this->db->where('numero', $numero);
        $this->db->where('tipcom_id', 4);
        $this->db->where('puesto', 3);
        $this->db->where('letra', 'Z');
        return $this->db->get()->row();
    }

    function getNumeroFromIdencab ( $id ) {
        $this->db->select('numero');
        $this->db->from($this->getTable());
        $this->db->where('id', $id);
        return $this->db->get()->row()->numero;
    }

}
