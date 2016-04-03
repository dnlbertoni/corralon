<?php

class Facencab_model extends MY_Model {
    var $tabla = "fac_facencab";
    var $tablaMovim = "fac_facmovim";
    var $tablaTmp = "tmp_movimientos";

    function __construct () {
        parent::__construct ();
        $this->setTable ( "fac_facencab" );
    }

    function graboComprobante ( $datosEncab, $datosMovim ) {
        $this->db->trans_begin ();
        //grabo facencab
        $this->db->set ( 'fecha', 'NOW()', FALSE );
        $this->db->set ( 'periva', 'DATE_FORMAT(NOW(),"%Y%m")', FALSE );
        $this->db->insert ( $this->tabla, $datosEncab );
        $idencab = $this->db->insert_id ();
        //grabo facmovim
        foreach ( $datosMovim as $movimiento ) {
            $this->db->set ( 'fecha_movim', 'NOW()', FALSE );
            $this->db->set ( 'idencab', $idencab );
            $this->db->insert ( $this->tablaMovim, $movimiento );
        }
        //borro el temporal
        if ( $datosEncab['tipcom_id'] == 6 || $datosEncab['tipcom_id'] == 9 ) {
            $puesto = $datosEncab['puesto'] - 90;
        } else {
            $puesto = $datosEncab['puesto'];
        }
        $this->db->where ( 'puesto_tmpmov', $puesto );
        $this->db->delete ( $this->tablaTmp );
        if ( $this->db->trans_status () === FALSE ) {
            $this->db->trans_rollback ();
            return false;
        } else {
            $this->db->trans_commit ();
            return $idencab;
        }
    }

    function save ( $datos ) {
        $this->db->insert ( $this->tabla, $datos );
        $q = $this->db->insert_id ();
        return $q;
    }

    function getPresupuestosPendientes ( $fecha ) {
        $this->db->select ( "fac_facencab.id as id" );
        $this->db->select ( "DATE_FORMAT(fac_facencab.fecha, '%d/%m/%Y') as fecha" );
        $this->db->select ( "CONCAT(fac_facencab.puesto,'-',fac_facencab.numero) as comprobante", false );
        $this->db->select ( "cuenta.nombre as cliente" );
        $this->db->select ( "fac_facencab.importe as importe" );
        $this->db->from ( $this->getTable () );
        $this->db->join ( "cuenta", "cuenta.id=fac_facencab.cuenta_id", "inner" );
        $this->db->where ( 'fac_facencab.tipcom_id', 18 );
        $this->db->where ( 'fac_facencab.estado', 2 ); //pendiente de facturar
        $this->db->where ( 'fac_facencab.fecha', $fecha );
        return $this->db->get ()->result ();


    }
}
