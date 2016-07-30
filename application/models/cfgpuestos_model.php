<?php

/**
 * Created by PhpStorm.
 * User: dnl
 * Date: 03/11/2014
 * Time: 02:02 PM
 */
class Cfgpuestos_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('cfg_puestos');
    }

    function getConfig($ip)
    {
        $this->db->from($this->getTable());
        $this->db->where('ip', $ip);
        return $this->db->get()->row();
    }

    function getPuesto ( $ip ) {
        $this->db->select('puesto_cf as puesto');
        $this->db->from($this->getTable());
        $this->db->where('ip', $ip);
        $r = $this->db->get ();
        switch ( $r->num_rows ) {
            case 0:
                return $this->getDefault ( 'puesto_cf' );
                break;
            case 1:
                return $r->row ()->puesto;
                break;
            default:
                return $r->result ();
                break;
        }
    }

    function getPuestoCnf ( $ip ) {
        $this->db->select ( 'puesto_cnf as puesto' );
        $this->db->from ( $this->getTable () );
        $this->db->where ( 'ip', $ip );
        $r = $this->db->get ();
        switch ( $r->num_rows ) {
            case 0:
                return $this->getDefault ( 'puesto_cnf' );
                break;
            case 1:
                return $r->row ()->puesto;
                break;
            default:
                return $r->result ();
                break;
        }
    }

    function getRutaPuesto ( $ip ) {
        $this->db->select ( 'puerto_cf as puesto' );
        $this->db->from ( $this->getTable () );
        $this->db->where ( 'ip', $ip );
        $r = $this->db->get ();
        switch ( $r->num_rows ) {
            case 0:
                return $this->getDefault ( 'puerto_cf' );
                break;
            case 1:
                return $r->row ()->puesto;
                break;
            default:
                return $r->result ();
                break;
        }
    }

    function getImpresora ( $ip ) {
        $this->db->select ( 'impresora as impresora' );
        $this->db->from ( $this->getTable () );
        $this->db->where ( 'ip', $ip );
        $r = $this->db->get ();
        switch ( $r->num_rows ) {
            case 0:
                return $this->getDefault ( 'impresora' );
                break;
            case 1:
                return $r->row ()->impresora;
                break;
            default:
                return $r->result ();
                break;
        }
    }

    private function getDefault ( $campo ) {
        $this->db->select ( $campo . ' as puesto' );
        $this->db->from ( $this->getTable () );
        $this->db->where ( 'ip', '0.0.0.0' );
        return $this->db->get ()->row ()->puesto;
    }

}