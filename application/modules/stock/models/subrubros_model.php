<?php

class Subrubros_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable ( "stk_subrubros" );
    }

    function getAll ( $estado = false, $paginado = false, $pagina = false ) {
        $estado = ( $estado ) ? $estado : "ALL";
        if ( $estado != 'ALL' ) {
            $this->db->where ( 'estado', $estado );
        };
        return $this->db->get ( $this->getTable (), $paginado, $pagina )->result ();
    }
    function ListaSelect($campoId = "id_subrubro", $campoNombre = "descripcion_subrubro")
    {
        return $this->toDropDown($campoId, $campoNombre);
    }

    function ListaSelectDependiente($campoId = "id_subrubro", $campoNombre = "descripcion_subrubro", $campoRelacion = "id_rubro")
    {
        return $this->toDropDown_avanzado($campoId, $campoNombre, $campoRelacion);
    }

    function getFromRubro($id)
    {
        $this->db->select('id_subrubro as id');
        $this->db->select('descripcion_subrubro as nombre');
        $this->db->from($this->getTable());
        $this->db->where('id_rubro', $id);
        $this->db->order_by('nombre');
        return $this->db->get()->result();
    }

    function getAllConRubros()
    {
        $this->db->select('ID_SUBRUBRO');
        $this->db->select('DESCRIPCION_SUBRUBRO');
        $this->db->select('DESCRIPCION_RUBRO AS rubro');
        $this->db->select('ALIAS_SUBRUBRO');
        $this->db->from($this->getTable());
        $this->db->join ( "stk_rubros", "stk_subrubros.id_rubro = stk_rubros.id_rubro", "inner" );
        $this->db->order_by ( 'stk_subrubros.ID_RUBRO' );
        $this->db->order_by('ALIAS_SUBRUBRO');
        return $this->db->get()->result();
    }

    function getAllConArticulos ( $orden = false, $paginado = false, $pagina = false ) {
        $this->db->select ( 'stk_subrubros.ID_SUBRUBRO AS ID_SUBRUBRO' );
        $this->db->select('DESCRIPCION_SUBRUBRO');
        $this->db->select('DESCRIPCION_RUBRO AS rubro');
        $this->db->select('ALIAS_SUBRUBRO');
        $this->db->select('COUNT(id_articulo) AS articulos', FALSE);
        $this->db->select('SUM(IF(wizard=1,1,0)) AS Warticulos', FALSE);
        $this->db->select ( 'ESTADO_SUBRUBRO as estado' );
        //$this->db->from('stk_articulos');
        $this->db->join ( "stk_subrubros", "stk_subrubros.id_subrubro = stk_articulos.id_subrubro", "right" );
        $this->db->join ( "stk_rubros", "stk_subrubros.id_rubro = stk_rubros.id_rubro", "right" );
        $this->db->group_by('stk_articulos.id_subrubro');
        if ( $orden == "articulos" ) {
            $this->db->order_by ( $orden, 'DESC' );
        } else {
            $this->db->order_by ( 'descripcion_rubro' );
            $this->db->order_by ( 'alias_subrubro' );
        }
        return $this->db->get ( 'stk_articulos', $paginado, $pagina )->result ();
    }

    function getAlias($id)
    {
        $this->db->select("ALIAS_SUBRUBRO AS alias");
        $this->db->from($this->getTable());
        $this->db->where($this->getPrimaryKey(), $id);
        return $this->db->get()->row()->alias;
    }

    function getNombre($id)
    {
        $this->db->select("DESCRIPCION_SUBRUBRO AS alias");
        $this->db->from($this->getTable());
        $this->db->where($this->getPrimaryKey(), $id);
        return $this->db->get()->row()->alias;
    }

    function buscoNombre($valor)
    {
        $this->db->select('id_subrubro as id');
        $this->db->select('descripcion_subrubro as nombre');
        $this->db->select('descripcion_rubro as rubro');
        $this->db->from($this->getTable());
        $this->db->join ( 'stk_rubros', 'stk_rubros.id_rubro = stk_subrubros.id_rubro', 'inner' );
        $this->db->like('descripcion_subrubro', $valor);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function getArticulosFromSubrubro($id = false)
    {
        $this->db->select('id_articulo AS id');
        $this->db->select('codigobarra_articulo AS cb');
        $this->db->select('DESCRIPCION_articulo AS nombre');
        $this->db->select('CONCAT(detalle_submarca, " ( ", detalle_marca, " ) ") AS marca', FALSE);
        $this->db->select('wizard AS w');
        $this->db->from('stk_articulos');
        $this->db->join ( "stk_subrubros", "stk_subrubros.id_subrubro = stk_articulos.id_subrubro", "inner" );
        $this->db->join("stk_submarcas", "stk_submarcas.id_submarca = stk_articulos.id_marca", "inner");
        $this->db->join("stk_marcas", "stk_submarcas.id_marca    = stk_marcas.id_marca", "inner");
        $this->db->where('stk_articulos.id_subrubro', $id);
        $this->db->order_by('nombre');
        return $this->db->get()->result();
    }

    function actualizoCantidadBultos($id, $cantidadXbultos)
    {
        $this->db->set('CANTXBULTO_SUBRUBRO', $cantidadXbultos);
        $this->db->where('ID_SUBRUBRO', $id);
        $this->db->update($this->getTable());
        return true;
    }
}
