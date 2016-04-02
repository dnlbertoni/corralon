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
} 