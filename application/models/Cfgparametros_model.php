<?php

/**
 * Created by PhpStorm.
 * User: dnlbe
 * Date: 31/3/2016
 * Time: 9:55 AM
 */

class Cfgparametros_model extends MY_Model{
    function __construct(){
        parent::__construct();
        $this->setTable('cfg_parametros');
    }
    public function setParametro($id=false,$nombre=false,$valor=false){
        if($id){
            $this->db->set('valor', $valor);
            $this->db->from($this->getTable());
            $this->db->where('id',$id);
            $this->db->update();
            return true;
        }else {
            if ($nombre) {
                $this->db->set('nombre', $nombre);
                $this->db->set('valor', $valor);
                $this->db->from($this->getTable());
                $this->db->insert();
                return $this->db->insert_id();
            } else {
                return "Falta parametro de Valor";
            }
        }
    }

    public function getParametroNombre($nombre){
        $this->db->select('valor');
        $this->db->from($this->getTable());
        $this->db->where('nombre',$nombre);
        return $this->db->get()->row()->valor;
    }
    public function getParametroId($id){
        $this->db->from($this->getTable());
        $this->db->where('id',$id);
        return $this->db->get()->row();
    }
    public function getNombreEmpresa(){
        return $this->getParametroNombre('nombreEmpresa');
    }
}