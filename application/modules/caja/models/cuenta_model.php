<?php

    /**
     * Class Cuenta_model
     */
    class Cuenta_model extends MY_Model{
    var $tablaCondiva = "cfg_condiva";
    /**
     * Cuenta_model constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->setTable('cuenta');
    }
    
    function getByIdComprobante ( $id ) {
        $this->db->select('cuenta.id                 AS codigo');
        $this->db->select('cuenta.nombre             AS nombre');
        $this->db->select('cuenta.datos_fac          AS datos_fac');
        $this->db->select('cuenta.nombre_facturacion AS nombre_facturacion');
        $this->db->select('cuenta.cuit               AS cuit');
        $this->db->select('cuenta.condiva_id         AS condiva');
        $this->db->select('cuenta.direccion          AS direccion');
        $this->db->select('cuenta.tipdoc             AS tipdoc');
        $this->db->select('cuenta.ctacte             AS ctacte');
        $this->db->select ( 'cfg_condiva.letra615          AS letra615' );
        $this->db->from ( $this->getTable () );
        $this->db->join ( $this->tablaCondiva, 'condiva_id = cfg_condiva.id', 'inner' );
        $this->db->where('cuenta.id', $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }
    /**
     * @param $id
     *
     * @return bool
     */
    function getNombre( $id)
    {
        if ($id != 0) {
            $this->db->from($this->getTable());
            $this->db->where('id', $id);
            return $this->db->get()->row()->nombre;
        } else {
            return false;
        }
    }

    /**
     * @param int    $doc
     * @param        $nombre
     * @param string $direccion
     *
     * @return bool
     */
    function agregoConsumidorNominal( $doc=0, $nombre, $direccion="."){
        $cuenta_data = array(
            'condiva_id' => 3,
            'nombre' => $nombre,
            'datos_fac' => 0,
            'nombre_facturacion' => $nombre,
            'cuit' => $doc,
            'tipdoc' => 1,
            'direccion' => $direccion,
            'telefono' => '',
            'celular' => '',
            'email' => '',
            'tipo' => 99,
            'estado' => 1,
            'ctacte' => 0,
            'letra' => 'B');
        $this->db->insert($this->getTable(), $cuenta_data);
        $id= $this->db->insert_id();
        if($id>0){
            return $id;
        }else{
            return false;
        }
    }


}
