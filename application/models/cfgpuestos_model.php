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
        switch ( count ( $r ) ) {
            case 0:
                return PUESTO_DEFAULT;
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
        switch ( count ( $r ) ) {
            case 0:
                return false;
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
        switch ( count ( $r ) ) {
            case 0:
                return false;
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
        switch ( count ( $r ) ) {
            case 0:
                return 'laser';
                break;
            case 1:
                return $r->row ()->impresora;
                break;
            default:
                return $r->result ();
                break;
        }
    }

}