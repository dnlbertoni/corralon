<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 03/04/2016
 * Time: 06:35 PM
 */
class Presuencab_model extends MY_Model {
    var $tablaMovim = 'fac_presumovim';
    var $tablaTmp = "tmp_movimientos";
    function __construct () {
        parent::__construct ();
        $this->setTable ( 'fac_presuencab' );
    }

    function getPendientes () {
        return $this->getAll ( "P" );
    }

    function graboComprobante ( $datosEncab, $datosMovim ) {
        $this->db->trans_begin();
        //grabo facencab
        $this->db->set('fecha', 'NOW()', FALSE);
        $this->db->insert ( $this->getTable (), $datosEncab );
        $idencab = $this->db->insert_id();
        //grabo presumovim
        foreach ($datosMovim as $movimiento) {
            $this->db->set('fecha_movim', 'NOW()', FALSE);
            $this->db->set('idencab', $idencab);
            $this->db->insert($this->tablaMovim, $movimiento);
        }
        //borro el temporal
        $puesto = $datosEncab['puesto'];
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

}