<?php

class Submarcas_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable("stk_submarcas");
    }

    function ListaSelect($campoId = "id_submarca", $campoNombre = "detalle_submarca")
    {
        return $this->toDropDown($campoId, $campoNombre);
    }

    function ListaSelectDependiente($campoId = "id_submarca", $campoNombre = "detalle_submarca", $campoRelacion = "id_marca")
    {
        return $this->toDropDown_avanzado($campoId, $campoNombre, $campoRelacion);
    }

    function getFromMarca($id)
    {
        $this->db->select('id_submarca as id');
        $this->db->select('detalle_submarca as nombre');
        $this->db->from($this->getTable());
        $this->db->where('id_marca', $id);
        $this->db->order_by('nombre');
        return $this->db->get()->result();
    }

    function getAllConArticulos()
    {
        $this->db->select('stk_submarcas.ID_SUBMARCA AS ID_SUBMARCA');
        $this->db->select('DETALLE_SUBMARCA');
        $this->db->select('DETALLE_MARCA AS marca');
        $this->db->select('ALIAS_SUBMARCA');
        $this->db->select('COUNT(id_articulo) AS articulos', FALSE);
        $this->db->select('SUM(IF(wizard=1,1,0)) AS Warticulos', FALSE);
        $this->db->from('stk_articulos');
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca ", "left");
        $this->db->join("stk_marcas", "stk_submarcas.id_marca = stk_marcas.id_marca", "left");
        $this->db->group_by('stk_articulos.id_marca');
        $this->db->order_by('detalle_marca');
        $this->db->order_by('alias_submarca');
        return $this->db->get()->result();
    }

    function getResto($sugeridos)
    {
        $this->db->select('ID_SUBMARCA');
        $this->db->select('DETALLE_SUBMARCA');
        $this->db->select('DETALLE_MARCA AS marca');
        $this->db->from($this->getTable());
        $this->db->join("stk_marcas", "stk_submarcas.id_marca = stk_marcas.id_marca", "inner");
        $this->db->order_by('stk_submarcas.ID_MARCA');
        $this->db->order_by('DETALLE_SUBMARCA');
        return $this->db->get()->result();
    }

    function getAllConMarcas()
    {
        $this->db->select('stk_marcas.ID_MARCA as marcaId');
        $this->db->select('ID_SUBMARCA as submarcaId');
        $this->db->select('DETALLE_SUBMARCA as submarcaNombre');
        $this->db->select("ALIAS_SUBMARCA  AS alias");
        $this->db->select('DETALLE_MARCA AS marcaNombre');
        $this->db->from($this->getTable());
        $this->db->join("stk_marcas", "stk_submarcas.id_marca = stk_marcas.id_marca", "left");
        $this->db->order_by('stk_submarcas.ID_MARCA');
        $this->db->order_by('DETALLE_SUBMARCA');
        return $this->db->get()->result();
    }

    function getAlias($id)
    {
        $this->db->select("ALIAS_SUBMARCA  AS alias");
        $this->db->from($this->getTable());
        $this->db->where($this->getPrimaryKey(), $id);
        return $this->db->get()->row()->alias;
    }

    function getNombre($id)
    {
        $this->db->select("DETALLE_SUBMARCA  AS alias");
        $this->db->from($this->getTable());
        $this->db->where($this->getPrimaryKey(), $id);
        return $this->db->get()->row()->alias;
    }

    function buscoNombre($valor)
    {
        $this->db->select('id_submarca as id');
        $this->db->select('detalle_submarca as nombre');
        $this->db->select('stk_marcas.id_marca as id_marca');
        $this->db->select('detalle_marca as marca');
        $this->db->from($this->getTable());
        $this->db->join('stk_marcas', 'stk_marcas.id_marca = stk_submarcas.id_marca', 'inner');
        $this->db->like('detalle_submarca', $valor);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function getArticulosFromSubmarca($id = false)
    {
        $this->db->select('id_articulo AS id');
        $this->db->select('codigobarra_articulo AS cb');
        $this->db->select('DESCRIPCION_articulo AS nombre');
        $this->db->select('CONCAT(descripcion_subrubro, " ( ", descripcion_rubro, " ) ") AS rubro', FALSE);
        $this->db->select('wizard AS w');
        $this->db->from('stk_articulos');
        $this->db->join("tbl_subrubros", "tbl_subrubros.id_subrubro = stk_articulos.id_subrubro", "inner");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "right");
        $this->db->join("stk_rubros", "tbl_subrubros.id_rubro    = stk_rubros.id_rubro", "inner");
        if ($id) {
            $this->db->where('stk_articulos.id_marca', $id);
        } else {
            $this->db->where('stk_articulos.id_marca IS NULL', '', FALSE);
        }
        $this->db->order_by('nombre');
        return $this->db->get()->result();
    }
}
