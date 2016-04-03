<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 02/04/2016
 * Time: 01:01 PM
 */
class Cajaencab_model extends MY_Model {
    function __construct () {
        parent::__construct ();
        $this->setTable ( 'caja_encab' );
    }

    public function getCajaPuesto ( $puesto = false, $estado = false ) {
        if ( $puesto ) {
            $this->db->from ( $this->getTable () );
            $this->db->where ( 'puesto', $puesto );
            if ( $estado ) {
                $this->db->where ( 'estado', $estado );
            }
            $resultado = $this->db->get ();
            switch ( $resultado->num_rows () ) {
                case 0:
                    return false;
                    break;
                case 1:
                    return $resultado->row ();
                    break;
                default:
                    return $resultado->result ();
                    break;
            }
        } else {
            return "No existe ningun puesto asociado";
        }
    }
}