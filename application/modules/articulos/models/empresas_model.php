<?php

class Empresas_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('stk_empresas');
    }

    function getById($idEmpresa)
    {
        $this->db->_reset_select();
        $this->db->from($this->getTable());
        $this->db->where('id', $idEmpresa);
        $q = $this->db->get()->row();
        if (count($q) > 0) {
            return $q;
        } else {
            return false;
        }
    }

    function getEmpresasFromMarca($idMarca)
    {
        $this->db->select('id');
        $this->db->from($this->getTable());
        $this->db->where('id_marca', $idMarca);
        return $this->db->get()->result();
    }

    function getMarcasNombre()
    {
        $this->db->select('stk_empresas.id as id');
        $this->db->select('stk_marcas.id_marca as marca');
        $this->db->select('detalle_marca as nombre');
        $this->db->select('COUNT(id_submarca) as cantidad');
        $this->db->select('COUNT(id_articulo) as cantart');
        $this->db->from('tbl_articulos');
        $this->db->join('stk_submarcas', 'tbl_articulos.id_marca=stk_submarcas.id_marca', 'inner');
        $this->db->join('stk_marcas', 'stk_marcas.id_marca=stk_submarcas.id_marca', 'inner');
        $this->db->join('stk_empresas', 'stk_marcas.id_marca = stk_empresas.id_marca', 'inner');
        $this->db->group_by('stk_submarcas.id_marca');
        $this->db->order_by('cantidad', 'DESC');
        return $this->db->get()->result();
    }

    function genericosAll()
    {
        $this->db->select('stk_empresas.id_marca as id');
        $this->db->select('detalle_marca  as nombre');
        $this->db->select_sum('if(tbl_articulos.id_marca=0,1,0)', 'genericos', false);
        $this->db->select_sum('if(tbl_articulos.id_marca=0,0,1)', 'asignados', false);
        $this->db->select('count(id_articulo) as totales', false);
        $this->db->select('(sum(if(tbl_articulos.id_marca=0,1,0))/count(id_articulo)*100) as tasa', false);
        $this->db->from('tbl_articulos');
        $this->db->join($this->getTable(), 'tbl_articulos.empresa=stk_empresas.id', 'inner');
        $this->db->join('stk_marcas', 'stk_empresas.id_marca = stk_marcas.id_marca', 'inner');
        $this->db->group_by('stk_empresas.id_marca');
        $this->db->having('tasa > ', 0);
        $this->db->order_by('tasa', 'DESC');
        $this->db->order_by('genericos', 'DESC');
        return $this->db->get()->result();
    }

    function getRubros($idEmpresa)
    {
        $this->db->distinct();
        $this->db->select('tbl_articulos.id_subrubro as subrubroId');
        $this->db->select('tbl_subrubros.id_rubro  as rubroId');
        $this->db->select('descripcion_rubro as rubroNombre');
        $this->db->select('descripcion_subrubro as subrubroNombre');
        $this->db->from('tbl_articulos');
        $this->db->join('tbl_subrubros', 'tbl_articulos.id_subrubro = tbl_subrubros.id_subrubro', 'inner');
        $this->db->join('tbl_rubros', 'tbl_subrubros.id_rubro    = tbl_rubros.id_rubro', 'inner');
        $this->db->join('stk_submarcas', 'tbl_articulos.id_marca    = stk_submarcas.id_submarca', 'inner');
        $this->db->where('stk_submarcas.id_marca', $idEmpresa);
        $this->db->order_by('rubroId');
        return $this->db->get()->result();
    }

    function getMarcas($idEmpresa)
    {
        //obtengo empresas relacionadas
        $this->db->distinct();
        $this->db->select('id_marca');
        $this->db->from($this->getTable());
        $this->db->where('id', $idEmpresa);
        $marcas = $this->db->get()->result();
        $this->db->_reset_select();

        $this->db->distinct();
        $this->db->select('tbl_articulos.id_marca as submarcaId');
        $this->db->select('stk_submarcas.id_marca  as marcaId');
        $this->db->select('detalle_marca as marcaNombre');
        $this->db->select('detalle_submarca as submarcaNombre');
        $this->db->from('tbl_articulos');
        $this->db->join('stk_submarcas', 'tbl_articulos.id_marca    = stk_submarcas.id_submarca', 'inner');
        $this->db->join('stk_marcas', 'stk_marcas.id_marca       = stk_submarcas.id_marca', 'inner');
        foreach ($marcas as $m) {
            $this->db->or_where('stk_submarcas.id_marca', $m->id_marca);
        }
        $this->db->order_by('marcaId');
        return $this->db->get()->result();
    }

    function getMarcasFromCodigobarra($idEmpresa)
    {
        //$this->db->distinct();
        $this->db->select('COUNT(tbl_articulos.id_marca) as cantidad', false);
        $this->db->select('COUNT(tbl_articulos.id_marca) / COUNT(tbl_articulos.id_articulo)*100 as aciertoSubmarca', false);
        $this->db->select('COUNT(tbl_articulos.id_marca) / COUNT(tbl_articulos.id_articulo)*100 as aciertoMarca', false);
        $this->db->select('tbl_articulos.id_marca as submarcaId');
        $this->db->select('detalle_submarca as submarcaNombre');
        $this->db->select('stk_submarcas.id_marca  as marcaId');
        $this->db->select('detalle_marca as marcaNombre');
        $this->db->from('tbl_articulos');
        $this->db->join('stk_submarcas', 'tbl_articulos.id_marca    = stk_submarcas.id_submarca', 'inner');
        $this->db->join('stk_marcas', 'stk_marcas.id_marca       = stk_submarcas.id_marca', 'inner');
        $this->db->where('tbl_articulos.empresa', $idEmpresa);
        $this->db->group_by('tbl_articulos.id_marca');
        $this->db->order_by('marcaNombre', 'ASC');
        $this->db->order_by('cantidad', 'DESC');
        return $this->db->get()->result();
    }

    function getRubrosFromCodigobarra($idEmpresa)
    {
        $this->db->select('COUNT(tbl_articulos.id_subrubro) as cantidad', false);
        $this->db->select('COUNT(tbl_articulos.id_subrubro) / COUNT(tbl_articulos.id_articulo)*100 as aciertoSubrubro', false);
        $this->db->select('COUNT(tbl_articulos.id_subrubro) / COUNT(tbl_articulos.id_articulo)*100 as aciertoRubro', false);
        $this->db->select('tbl_articulos.id_subrubro as subrubroId');
        $this->db->select('descripcion_subrubro as subrubroNombre');
        $this->db->select('tbl_subrubros.id_rubro  as rubroId');
        $this->db->select('descripcion_rubro as rubroNombre');
        $this->db->from('tbl_articulos');
        $this->db->join('tbl_subrubros', 'tbl_articulos.id_subrubro    = tbl_subrubros.id_subrubro', 'inner');
        $this->db->join('tbl_rubros', 'tbl_rubros.id_rubro          = tbl_subrubros.id_rubro', 'inner');
        $this->db->where('tbl_articulos.empresa', $idEmpresa);
        $this->db->group_by('tbl_articulos.id_subrubro');
        $this->db->order_by('rubroNombre', 'ASC');
        $this->db->order_by('cantidad', 'DESC');
        return $this->db->get()->result();
    }

    function getAllConMarcasExcluidas($idEmpresa)
    {
        $query = "SELECT  stk_marcas.ID_MARCA as marcaId,
                      ID_SUBMARCA as submarcaId,
                      DETALLE_SUBMARCA as submarcaNombre,
                      DETALLE_MARCA AS marcaNombre
              FROM (stk_submarcas)
              INNER JOIN stk_marcas ON stk_submarcas.id_marca = stk_marcas.id_marca
              WHERE id_submarca NOT IN(
                                  SELECT tbl_articulos.id_marca as submarcaId
                                  FROM (tbl_articulos)
                                  INNER JOIN stk_submarcas ON tbl_articulos.id_marca    = stk_submarcas.id_submarca
                                  INNER JOIN stk_marcas ON stk_marcas.id_marca       = stk_submarcas.id_marca
                                  WHERE `tbl_articulos`.`empresa` = '$idEmpresa'
                                )
              ORDER BY stk_marcas.DETALLE_MARCA, DETALLE_SUBMARCA ";
        $datos = $this->db->query($query);
        return $datos->result();
    }

    function getAllConRubrosExcluidos($idEmpresa)
    {
        $query = "SELECT  tbl_rubros.ID_RUBRO as rubroId,
                      ID_SUBRUBRO as subrubroId,
                      DESCRIPCION_SUBRUBRO as subrubroNombre,
                      DESCRIPCION_RUBRO AS rubroNombre
              FROM (tbl_subrubros)
              INNER JOIN tbl_rubros ON tbl_subrubros.id_rubro = tbl_rubros.id_rubro
              WHERE id_subrubro NOT IN(
                                  SELECT tbl_articulos.id_subrubro as subrubroId
                                  FROM (tbl_articulos)
                                  INNER JOIN tbl_subrubros ON tbl_articulos.id_subrubro = tbl_subrubros.id_subrubro
                                  INNER JOIN tbl_rubros    ON tbl_rubros.id_rubro       = tbl_subrubros.id_rubro
                                  WHERE `tbl_articulos`.`empresa` = '$idEmpresa'
                                )
              ORDER BY tbl_rubros.DESCRIPCION_RUBRO, DESCRIPCION_SUBRUBRO ";
        $datos = $this->db->query($query);
        return $datos->result();
    }
}
