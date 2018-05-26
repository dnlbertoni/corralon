<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Articulos_model extends My_Model {
    public function __construct()
    {
        parent::__construct();
        $this->setTable('stk_articulos');
    }

    function getAll ( $estado = false, $paginado = false, $pagina = false ) {
        $estado = ( $estado ) ? $estado : "ALL";
        $this->db->join('stk_subrubros','stk_subrubros.id_subrubro=stk_articulos.id_subrubro','inner');
        $this->db->join('stk_rubros','stk_rubros.id_rubro=stk_subrubros.id_rubro','inner');
        if ( $estado != 'ALL' ) {
            $this->db->where ( 'estado', $estado );
        };
        return $this->db->get ( $this->getTable (), $paginado, $pagina )->result ();
    }

    public function getArticulos()
    {
        $this->db->select('id_articulo                    as id', FALSE);
        $this->db->select('codigobarra_articulo           as codigobarra');
        $this->db->select('descripcion_articulo           as detalle', FALSE);
        $this->db->select('stk_articulos.id_subrubro      as idsubrubro', FALSE);
        $this->db->select('stk_articulos.id_marca         as idsubmarca', FALSE);
        $this->db->select('descripcion_subrubro           as subrubroNombre', FALSE);
        $this->db->select('stk_rubros.id_rubro            as idrubro', FALSE);
        $this->db->select('stk_rubros.descripcion_rubro   as rubroNombre', FALSE);
        $this->db->select('stk_marcas.id_marca            as idmarca', FALSE);
        $this->db->select('stk_marcas.detalle_marca       as marcaNombre', FALSE);
        $this->db->select('stk_submarcas.id_submarca      as id_submarca', FALSE);
        $this->db->select('stk_submarcas.detalle_submarca as submarcaNombre', FALSE);
        $this->db->from($this->getTable());
        $this->db->join('stk_submarcas', 'stk_submarcas.id_submarca = stk_articulos.id_marca', 'left');
        $this->db->join('stk_marcas', 'stk_marcas.id_marca = stk_submarcas.id_marca', 'left');
        $this->db->join ( 'stk_subrubros', 'stk_subrubros.id_subrubro = stk_articulos.id_subrubro', 'left' );
        $this->db->join ( 'stk_rubros', 'stk_rubros.id_rubro = stk_subrubros.id_rubro', 'left' );
        $this->db->order_by('idrubro', 'asc');
        $this->db->order_by('idsubrubro', 'asc');
        return $this->db->get()->result();
    }

    public function getArticulo($CB)
    {
        $this->db->select('id_articulo                    as id', FALSE);
        $this->db->select('codigobarra_articulo           as codigobarra');
        $this->db->select('descripcion_articulo           as detalle', FALSE);
        $this->db->select('stk_articulos.id_subrubro      as idsubrubro', FALSE);
        $this->db->select('stk_articulos.id_marca         as idsubmarca', FALSE);
        $this->db->select('descripcion_subrubro           as subrubroNombre', FALSE);
        $this->db->select('stk_rubros.id_rubro            as idrubro', FALSE);
        $this->db->select('stk_rubros.descripcion_rubro   as rubroNombre', FALSE);
        $this->db->select('stk_marcas.id_marca            as idmarca', FALSE);
        $this->db->select('stk_marcas.detalle_marca       as marcaNombre', FALSE);
        $this->db->select('stk_submarcas.id_submarca      as id_submarca', FALSE);
        $this->db->select('stk_submarcas.detalle_submarca as submarcaNombre', FALSE);
        $this->db->from($this->getTable());
        $this->db->join('stk_submarcas', 'stk_submarcas.id_submarca = stk_articulos.id_marca', 'left');
        $this->db->join('stk_marcas', 'stk_marcas.id_marca = stk_submarcas.id_marca', 'left');
        $this->db->join ( 'stk_subrubros', 'stk_subrubros.id_subrubro = stk_articulos.id_subrubro', 'left' );
        $this->db->join ( 'stk_rubros', 'stk_rubros.id_rubro = stk_subrubros.id_rubro', 'left' );
        $this->db->where('codigobarra_articulo', $CB);
        return $this->db->get()->row();
    }

    function getDatosInventario($CB)
    {
        $this->db->select('id_articulo');
        $this->db->select('descripcion_articulo as nombre');
        $this->db->select('codigobarra_articulo as codigobarra');
        $this->db->select('if(cantxbulto_articulo is null, 0, cantxbulto_articulo) as cantidadBulto', false);
        $this->db->select('if( cantxbulto_subrubro is null, 0, cantxbulto_subrubro) as cantidadBultoSub', false);
        $this->db->from($this->getTable());
        $this->db->join ( 'stk_subrubros', 'stk_articulos.id_subrubro = stk_subrubros.id_subrubro', 'left' );
        $this->db->where('codigobarra_articulo', $CB);
        return $this->db->get()->row();
    }

    function actualizoCantidadBultos($id, $cantidadXbultos)
    {
        $this->db->set('CANTXBULTO_ARTICULO', $cantidadXbultos);
        $this->db->where('ID_ARTICULO', $id);
        $this->db->update($this->getTable());
        return true;
    }

    function actualizoPrecio($id, $precio)
    {
        $this->db->set('PRECIO_ARTICULO', $precio);
        $this->db->where('ID_ARTICULO', $id);
        $this->db->update($this->getTable());
        return true;
    }

    public function getBusquedaAjax ( $valor ) {
        //$valor = "%" . $valor ."%";
        $this->db->select ( 'art.ID_ARTICULO as id' );
        $this->db->select ( 'art.DESCRIPCION_ARTICULO as nombre' );
        $this->db->select ( 'sub.DESCRIPCION_SUBRUBRO as subrubro' );
        $this->db->select ( 'marca.DETALLE_SUBMARCA as submarca' );
        $this->db->select ( 'art.PRECIO_ARTICULO as precio' );
        $this->db->from ( $this->getTable () . ' as art' );
        $this->db->join ( 'stk_subrubros as sub', 'sub.ID_SUBRUBRO=art.ID_SUBRUBRO', 'inner' );
        $this->db->join ( 'stk_submarcas as marca', 'marca.ID_SUBMARCA=art.ID_SUBMARCA', 'inner' );
        $this->db->like ( 'art.DESCRIPCION_ARTICULO', $valor );
        $this->db->or_like ( 'sub.DESCRIPCION_SUBRUBRO', $valor );
        $this->db->or_like ( 'marca.DETALLE_SUBMARCA', $valor );
        $this->db->order_by ( 'subrubro' );
        $this->db->order_by ( 'submarca' );
        $this->db->order_by ( 'nombre' );
        //echo $this->db->_compile_select();
        return $this->db->get ()->result ();
    }
}

/* End of file articulos_model.php */
/* Location: ./application/models/articulos_model.php */
