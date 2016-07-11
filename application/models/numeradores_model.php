<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Numeradores_model extends MY_Model {
    var $tabla = "cfg_numerador";
    var $principal = "numero";
    var $primaryKey = "id";

    function __construct () {
        parent::__construct ();
        $this->setTable ( 'cfg_numerador' );
    }

    function getById ( $id ) {
        $this->db->select ( $this->principal . " AS numero ", false );
        $this->db->from ( $this->tabla );
        $this->db->where ( $this->primaryKey, $id );
        return $this->db->get ()->row ();
    }

    function getNextRemito ( $puesto, $actualizo = false ) {
        $this->db->select ( $this->principal . " AS numero ", false );
        $this->db->from ( $this->tabla );
        $this->db->where ( 'puesto', $puesto );
        $this->db->where ( 'tipcom_id', 6 );
        $numero = $this->db->get ()->row ()->numero;
        if ( $actualizo ) {
            return $this->updateRemito ( $puesto, $numero );
        } else {
            return $numero;
        }
    }

    function updateRemito ( $puesto, $numero ) {
        $numero++;
        $this->db->set ( 'numero', $numero );
        $this->db->where ( 'tipcom_id', 6 );
        $this->db->where ( 'puesto', $puesto );
        $this->db->update ( $this->tabla );
        return $numero;
    }

    function getNextCompCtaCte ( $puesto, $actualizo = false ) {
        $this->db->select ( $this->principal . " AS numero ", false );
        $this->db->from ( $this->tabla );
        $this->db->where ( 'puesto', $puesto );
        $this->db->where ( 'tipcom_id', 7 );
        $numero = $this->db->get ()->row ()->numero;
        if ( $actualizo ) {
            return $this->updateCompCtaCte ( $puesto, $numero );
        } else {
            return $numero;
        }
    }

    function updateCompCtaCte ( $puesto, $numero ) {
        $numero++;
        $this->db->set ( 'numero', $numero );
        $this->db->where ( 'tipcom_id', 7 );
        $this->db->where ( 'puesto', $puesto );
        $this->db->update ( $this->tabla );
        return $numero;
    }

    function getNextPresupuesto ( $puesto ) {
        $this->db->select ( $this->principal . " AS numero ", false );
        $this->db->from ( $this->getTable () );
        $this->db->where ( 'puesto', $puesto );
        $this->db->where ( 'tipcom_id', 18 );
        return $this->db->get ()->row ()->numero;
    }

    function updatePresupuesto ( $puesto, $numero ) {
        $this->db->set ( 'numero', $numero );
        $this->db->where ( 'tipcom_id', 18 );
        $this->db->where ( 'puesto', $puesto );
        $this->db->update ( $this->getTable () );
        //die();
        return true;
    }
}
