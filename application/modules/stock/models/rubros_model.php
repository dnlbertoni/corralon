<?php

class Rubros_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable("stk_rubros");
    }

    function ListaSelect($campoId = "id_rubro", $campoNombre = "descripcion_rubro")
    {
        return $this->toDropDown($campoId, $campoNombre);
    }

    function getNombre($id)
    {
        $this->db->select("descripcion_rubro AS nombre");
        $this->db->from($this->getTable());
        $this->db->where($this->getPrimaryKey(), $id);
        return $this->db->get()->row()->nombre;
    }

    function getAll ( $estado = false, $paginado = false, $pagina = false ) {
        $estado = ( $estado ) ? $estado : "ALL";
        if ( $estado != 'ALL' ) {
            $this->db->where ( 'estado', $estado );
        };
        return $this->db->get ( $this->getTable (), $paginado, $pagina )->result ();
    }

    function getRubrosConArticulos () {
        $this->db->select ( 'id_rubro' );

        $this->db->join ( 'stk_rubros as r', 'r.id_rubro=s.id_rubro', 'left' );
    }
}
