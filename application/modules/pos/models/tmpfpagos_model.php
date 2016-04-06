<?php

/**
 * Description of tmpfpagos_model
 *
 * @author dnl
 */
class Tmpfpagos_model extends MY_Model {
    function __construct () {
        parent::__construct();
        $this->setTable('tmp_fpagos');
    }

    function inicializo ( $id ) {
        $this->db->set('tmpfacencab_id', $id);
        $this->db->set('fpagos_id', 1);
        $this->db->insert($this->getTable());
        return $this->db->insert_id();
    }

    function getPagosComprobante ( $id ) {
        $this->db->select('tmp_fpagos.id');
        $this->db->select('fpagos_id');
        $this->db->select ( 'caja_fpagos.nombre as pagoNombre' );
        $this->db->select('monto');
        $this->db->select('( 1 - ( monto /tmp_facencab.importe ) )as porcentaje');
        $this->db->from($this->getTable());
        $this->db->from('tmp_facencab');
        $this->db->join ( 'caja_fpagos', 'tmp_fpagos.fpagos_id=caja_fpagos.id', 'inner' );
        $this->db->where('tmpfacencab_id', $id);
        $this->db->where('tmp_facencab.id = tmp_fpagos.tmpfacencab_id');
        return $this->db->get()->result();
    }

    function actualizoPagos ( $tmpfacencab_id, $total ) {
        $pagos = $this->getPagosComprobante($tmpfacencab_id);
        $totalAux = 0;
        foreach ($pagos as $p) {
            $totalAux = $totalAux + $p->monto;
        }
        if ($totalAux != 0) {
            $diferencial = $total / $totalAux;
            foreach ($pagos as $p) {
                $nuevo = $p->monto * $diferencial;
                $this->db->_reset_select();
                $this->db->set('monto', $nuevo);
                $this->db->where('id', $p->id);
                $this->db->update($this->getTable());
            }
        } else {
            $this->db->_reset_select();
            $this->db->set('monto', $total);
            $this->db->where('tmpfacencab_id', $tmpfacencab_id);
            $this->db->update($this->getTable());
        }
    }

    function cambiarFpFull ( $id, $estado ) {
        $this->db->set('fpagos_id', $estado);
        $this->db->where('tmpfacencab_id', $id);
        $this->db->update($this->getTable());
        return true;
    }

    function vacio ( $id ) {
        $this->db->where('tmpfacencab_id', $id);
        $this->db->delete($this->getTable());
        return true;
    }
}
