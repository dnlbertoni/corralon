<?php

class Articulos_model extends MY_Model {
    var $tabla = array("name" => "stk_articulos",
        "id" => "id_articulo",
        "nombre" => "descripcion_articulo",
        "precio" => "preciovta_articulo",
        "codigobarra" => "codigobarra_articulo"
    );
    var $primaryKey = "ID_ARTICULO";

    function __construct () {
        parent::__construct ();
        $this->tabla = (object)$this->tabla;
        $this->setTable($this->tabla->name);
    }

    function Inicializar($full = false)
    {
        $campos = $this->db->field_data($this->tabla->name);
        $datos = array();
        foreach ($campos as $campo) {
            if ($campo->name != "FECHAMODIF_ARTICULO") {
                if ($campo->name != "FECHACREACION_ARTICULO") {
                    if ($campo->type == "int" || $campo->type == "real") {
                        $datos[$campo->name] = 0;
                    } else {
                        $datos[$campo->name] = '';
                    };
                };
            }
        }
        $datos['ID_SUBRUBRO'] = 44;
        $datos['ID_MARCA'] = 0;
        $datos['TASAIVA_ARTICULO'] = 21;
        $datos['ESTADO_ARTICULO'] = 1;
        $datos['wizard'] = 0;
        if ($full) {
            $datos['ID_RUBRO'] = 0;
            $datos['DESCRIPCION_RUBRO'] = '';
            $datos['DESCRIPCION_SUBRUBRO'] = '';
            $datos['ID_SUBMARCA'] = 0;
            $datos['DETALLE_MARCA'] = '';
            $datos['DETALLE_SUBMARCA'] = '';
        }
        return (object)$datos;
    }

    function buscoNombre($valor)
    {
        $this->db->select($this->tabla->id . "     AS id");
        $this->db->select($this->tabla->nombre . " AS nombre");
        $this->db->select($this->tabla->precio . " AS precio");
        $this->db->select("descripcion_subrubro    AS subrubro");
        $this->db->select("detalle_submarca           AS marca");
        $this->db->select("estado_articulo         AS estado");
        $this->db->select("codigobarra_articulo    AS codigobarra");
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->from($this->tabla->name);
        $this->db->like($this->tabla->nombre, $valor);
        $this->db->limit(500);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function buscoPorRubro($valor)
    {
        $this->db->select($this->tabla->id . "     AS id");
        $this->db->select($this->tabla->nombre . " AS nombre");
        $this->db->select($this->tabla->precio . " AS precio");
        $this->db->select("descripcion_subrubro    AS subrubro");
        $this->db->select("detalle_submarca           AS marca");
        $this->db->select("codigobarra_articulo    AS codigobarra");
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->from($this->tabla->name);
        $this->db->where('stk_articulos.id_subrubro', $valor);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function buscoPorMarca($valor)
    {
        $this->db->select($this->tabla->id . "     AS id");
        $this->db->select($this->tabla->nombre . " AS nombre");
        $this->db->select($this->tabla->precio . " AS precio");
        $this->db->select($this->tabla->codigobarra . " AS codigobarra");
        $this->db->select("descripcion_subrubro    AS subrubro");
        $this->db->select("detalle_submarca           AS marca");
        $this->db->select("estado_articulo         AS estado");
        $this->db->select("codigobarra_articulo    AS codigobarra");
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->from($this->tabla->name);
        $this->db->where('stk_articulos.id_marca', $valor);
        $this->db->order_by('estado', 'DESC');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function searchAvanzada($marca, $rubro)
    {
        $this->db->select($this->tabla->id . "     AS id");
        $this->db->select($this->tabla->nombre . " AS nombre");
        $this->db->select($this->tabla->precio . " AS precio");
        $this->db->select("descripcion_subrubro    AS subrubro");
        $this->db->select("detalle_submarca        AS marca");
        $this->db->select("estado_articulo         AS estado");
        $this->db->select("codigobarra_articulo    AS codigobarra");
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->from($this->tabla->name);
        if (is_numeric($marca)) {
            $this->db->where('stk_articulos.id_marca', $marca);
        };
        if ($rubro) {
            $this->db->where('stk_articulos.id_subrubro', $rubro);
        };
        $this->db->order_by($this->tabla->nombre);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function searchGlobal($marca = false, $rubro = false)
    {
        $this->db->select($this->tabla->id . "     AS id");
        $this->db->select($this->tabla->nombre . " AS nombre");
        $this->db->select($this->tabla->precio . " AS precio");
        $this->db->select("descripcion_subrubro    AS subrubro");
        $this->db->select("detalle_submarca        AS marca");
        $this->db->select("estado_articulo         AS estado");
        $this->db->select("codigobarra_articulo    AS codigobarra");
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "inner");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "inner");
        $this->db->from($this->tabla->name);
        if ($marca) {
            $this->db->where('stk_submarcas.id_marca', $marca);
        };
        if ($rubro) {
            $this->db->where('tbl_subrubros.id_rubro', $rubro);
        };
        $this->db->order_by($this->tabla->nombre);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function getDatosBasicos($id)
    {
        $id = intval($id);
        $this->db->_reset_select();
        $this->db->select("id_articulo AS id, descripcion_articulo AS descripcion, preciovta_articulo AS precio");
        $this->db->select("preciocosto_articulo   AS costo");
        $this->db->from($this->tabla->name);
        $this->db->where($this->tabla->id, $id);
        return $this->db->get()->row();
    }

    function getAllMod($limite = 0)
    {
        $this->db->from($this->tabla->name);
        $this->db->order_by("fechamodif_articulo", "Desc");
        $this->db->limit($limite);
        return $this->db->get()->result();
    }

    function getByCodigobarra($Cb)
    {
        $this->db->from($this->tabla->name);
        $this->db->where($this->tabla->codigobarra, $Cb);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        } else {
            return FALSE;
        }
    }

    function updateMod($id, $datos)
    {
        $this->db->set("FECHAMODIF_ARTICULO", "NOW()", FALSE);
        $this->db->where($this->tabla->id, $id);
        $this->db->update($this->tabla->name, $datos);
        $datPrecio = $this->getDatosBasicos($id);
        $precio = $datPrecio->precio;
        $costo = $datPrecio->costo;
        if ($precio)
            $this->actualizoPrecio($id, $precio, $costo);
        return true;
    }

    function getDatosPrecio($codigo)
    {
        $this->db->select($this->tabla->id . "     AS id");
        $this->db->select($this->tabla->nombre . " AS descripcion");
        $this->db->select($this->tabla->precio . " AS precio");
        $this->db->from($this->tabla->name);
        $this->db->where($this->tabla->codigobarra, $codigo);
        $q = $this->db->get();
        if ($q->num_rows() > 0)
            return $q->row();
        else
            return false;
    }

    function agregar($codigobarra = false, $datos = array(), $precio = false)
    {
        //busco si ya no esta ingresado
        $this->db->set('fechacreacion_articulo ', "NOW()", false);
        $this->db->set('fechamodif_articulo ', "NOW()", false);
        $this->db->insert($this->tabla->name, $datos);
        $idArticulo = $this->db->insert_id();
        $datPrecio = $this->getDatosBasicos($idArticulo);
        $precio = $datPrecio->precio;
        if ($precio)
            $this->actualizoPrecio($idArticulo, $precio);
        return $idArticulo;
    }

    function getByIdFull($id)
    {
        $this->db->from($this->getTable());
        $this->db->join('tbl_subrubros', 'tbl_subrubros.id_subrubro = stk_articulos.id_subrubro', 'left');
        $this->db->join('stk_rubros', 'stk_rubros.id_rubro = tbl_subrubros.id_rubro', 'left');
        $this->db->join('stk_submarcas', 'stk_submarcas.id_submarca = stk_articulos.id_marca', 'left');
        $this->db->join('stk_marcas', 'stk_marcas.id_marca = stk_submarcas.id_marca', 'left');
        $this->db->where($this->getPrimaryKey(), $id);
        return $this->db->get()->row();
    }

    function datosNormalizacion()
    {
        $todos = $this->db->count_all($this->getTable());
        $this->db->_reset_select();
        $this->db->where("detalle IS NULL", "", FALSE);
        $this->db->from($this->getTable());
        $faltan = $this->db->count_all_results();
        return ($todos - $faltan) / $todos;
    }

    function getMarcasAgrupadas($subrubro = FALSE)
    {
        $this->db->select("stk_articulos.id_marca");
        $this->db->select("detalle_submarca AS submarca");
        $this->db->select("count(stk_articulos.id_marca) as cantidad", false);
        $this->db->from($this->getTable());
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->group_by("stk_articulos.id_marca");
        $this->db->order_by("cantidad", "DESC");
        if ($subrubro) {
            $this->db->where("id_subrubro", $subrubro);
        }
        return $this->db->get()->result();
    }

    function getRubrosAgrupados($submarca = FALSE)
    {
        $this->db->select("stk_articulos.id_subrubro");
        $this->db->select("descripcion_subrubro AS subrubro");
        $this->db->select("count(stk_articulos.id_subrubro) as cantidad", false);
        $this->db->from($this->getTable());
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->group_by("stk_articulos.id_subrubro");
        $this->db->order_by("cantidad", "DESC");
        if ($submarca) {
            $this->db->where("id_marca", $submarca);
        }
        return $this->db->get()->result();
    }

    function getRubrosMarcasAgrupadas()
    {
        $this->db->select("stk_articulos.id_subrubro");
        $this->db->select("descripcion_subrubro AS subrubro");
        $this->db->select("stk_articulos.id_marca");
        $this->db->select("detalle_submarca AS submarca");
        $this->db->select("count(stk_articulos.id_subrubro) as cantidad", false);
        $this->db->select("CONCAT(stk_articulos.id_subrubro, stk_articulos.id_marca) as indice", false);
        $this->db->from($this->getTable());
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->group_by("indice");
        $this->db->order_by("stk_articulos.id_subrubro", "ASC");
        $this->db->order_by("cantidad", "DESC");
        return $this->db->get()->result();
    }

    function getRankingRubrosMarcas($importe = false)
    {
        $this->db->select("stk_articulos.id_subrubro");
        $this->db->select("descripcion_subrubro AS subrubro");
        $this->db->select("stk_articulos.id_marca");
        $this->db->select("detalle_submarca AS submarca");
        if ($importe) {
            $this->db->select ( "sum(fac_facmovim.preciovta_movim * fac_facmovim.cantidad_movim) as cantidad", false );
        } else {
            $this->db->select ( "sum(fac_facmovim.cantidad_movim) as cantidad", false );
        }
        $this->db->select("CONCAT(stk_articulos.id_subrubro, stk_articulos.id_marca) as indice", false);
        $this->db->from ( "fac_facmovim" );
        $this->db->join ( "stk_articulos", "fac_facmovim.id_articulo = stk_articulos.id_articulo", "inner" );
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->group_by("indice");
        $this->db->order_by("stk_articulos.id_subrubro", "ASC");
        $this->db->order_by("cantidad", "DESC");
        return $this->db->get()->result();
    }

    function actualizoPrecio($id, $precio, $costo = false)
    {
        $this->db->set('preciovta_articulo', $precio);
        $this->db->set('estado_articulo', 1);
        $this->db->where('id_articulo', $id);
        $this->db->update($this->getTable());
        $this->db->_reset_select();
        //busco ultimo precio en la base y lo comparo
        $this->db->select('precio');
        $this->db->select('fecha');
        $this->db->from('tbl_preciosmovim');
        $this->db->where('id_articulo', $id);
        $this->db->order_by('fecha', 'DESC');
        $this->db->limit(1);
        $q = $this->db->get()->row();
        /*
        if($precio != $q->precio){ // grabo en preciosmovim
          $this->db->_reset_select();
          $this->db->set('id_articulo', $id);
          $this->db->set('fecha', 'NOW()', FALSE);
          $this->db->set('precio', $precio);
          if($costo){
            $this->db->set('costo', $costo);
          }
          $this->db->set('impreso', 0);
          $this->db->insert('tbl_preciosmovim');
        }
         *
         */
        return true;
    }

    function getActivos()
    {
        $this->db->from($this->getTable());
        $this->db->where("ESTAdO_articulo", 1);
        return $this->db->count_all_results();
    }

    function getEmpresas($limit = 0, $offset = 0)
    {
        $this->db->select('empresa');
        $this->db->select('count(id_articulo) as cantidad', FALSE);
        $this->db->from($this->getTable());
        $selectInterno = "NOT EXISTS (SELECT stk_empresas.id FROM stk_empresas WHERE stk_articulos.empresa = stk_empresas.id)";
        $this->db->where($selectInterno, '', FALSE);
        $this->db->where('empresa is not null', '', false);
        $this->db->group_by('empresa');
        if ($limit != 0) {
            $offset = ($offset) ? $offset : 0;
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('cantidad', 'DESC');
        return $this->db->get()->result();
    }

    function getArticulosFromEmpresa($idEmpresas)
    {
        $this->db->select('id_articulo          as id');
        $this->db->select('descripcion_articulo as descripcion');
        $this->db->select('codigobarra_articulo as codigobarra');
        $this->db->select('descripcion_subrubro as subrubro');
        $this->db->select('descripcion_rubro    as rubro');
        $this->db->select('detalle_submarca     as submarca');
        $this->db->select('detalle_marca        as marca');
        $this->db->select('DATE_FORMAT(fechamodif_articulo,"%d-%m-%Y") as modif', false);
        $this->db->select('estado_articulo      as estado');
        $this->db->from($this->getTable());
        $this->db->join('stk_submarcas', 'stk_submarcas.id_submarca = stk_articulos.id_marca', 'inner');
        $this->db->join('stk_marcas', 'stk_submarcas.id_marca = stk_marcas.id_marca', 'inner');
        $this->db->join('tbl_subrubros', 'tbl_subrubros.id_subrubro = stk_articulos.id_subrubro', 'inner');
        $this->db->join('stk_rubros', 'tbl_subrubros.id_rubro = stk_rubros.id_rubro', 'inner');
        foreach ($idEmpresas as $idEmpresa) {
            $this->db->or_where('stk_articulos.empresa', $idEmpresa->id);
        }
        return $this->db->get()->result();
    }

    function getByCodigobarraCsv($Cb)
    {
        $this->db->select('id_articulo          as id');
        $this->db->select('codigobarra_articulo as codigobarra');
        $this->db->select('descripcion_articulo as descripcion');
        $this->db->select('preciocosto_articulo as costo');
        $this->db->select('markup_articulo as markup');
        $this->db->select('preciovta_articulo   as precio');
        $this->db->select('stk_articulos.id_subrubro as id_subruro');
        $this->db->select('descripcion_subrubro as nombre_subrubro');
        $this->db->select('stk_articulos.id_marca as id_submarca');
        $this->db->select('detalle_submarca as nombre_submarca');
        $this->db->select('fechamodif_articulo as fecha');
        $this->db->from($this->tabla->name);
        $this->db->join('tbl_subrubros', 'tbl_subrubros.id_subrubro=stk_articulos.id_subrubro', 'inner');
        $this->db->join('stk_submarcas', 'stk_submarcas.id_submarca=stk_articulos.id_marca', 'inner');
        $this->db->where($this->tabla->codigobarra, $Cb);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        } else {
            return FALSE;
        }
    }

    function updateArticulo($CB, $campo, $valor)
    {
        $this->db->set($campo, $valor);
        $this->db->where('codigobarra_articulo', $CB);
        $this->db->update($this->getTable());
        return true;
    }

    function filtroWizard($orden)
    {
        $this->db->select($this->tabla->id . "     AS id");
        $this->db->select($this->tabla->nombre . " AS nombre");
        $this->db->select($this->tabla->precio . " AS precio");
        $this->db->select($this->tabla->codigobarra . " AS codigobarra");
        $this->db->select("descripcion_subrubro    AS subrubro");
        $this->db->select("detalle_submarca           AS marca");
        $this->db->select("estado_articulo         AS estado");
        $this->db->join("tbl_subrubros", "stk_articulos.id_subrubro = tbl_subrubros.id_subrubro", "left");
        $this->db->join("stk_submarcas", "stk_articulos.id_marca = stk_submarcas.id_submarca", "left");
        $this->db->from($this->tabla->name);
        $this->db->where('stk_articulos.wizard <', 1, FALSE);
        $this->db->or_where('stk_articulos.wizard IS NULL', '', FALSE);
        //$this->db->limit(50);
        $this->db->order_by('stk_articulos.id_marca', 'ASC');
        $this->db->order_by('estado', 'DESC');
        $this->db->order_by($orden, 'ASC');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        } else {
            return false;
        }
    }

    function getKeyWords($id_subrubro = false)
    {
        $this->db->distinct();
        $this->db->select('especificacion');
        $this->db->from($this->getTable());
        if ($id_subrubro) {
            $this->db->where('id_subrubro', $id_subrubro);
        };
        $this->db->where('especificacion IS NOT NULL', '', FALSE);
        $this->db->where('TRIM(especificacion) <>""', '', FALSE);
        $q = $this->db->get()->result();
        $palabras = array ();
        if (count($q) > 0) {
            foreach ($q as $palabra) {
                $aux = explode(' ', $palabra->especificacion);
                foreach ($aux as $a) {
                    $exclusiones = array(' ', '', 'de', 'sabor', 'la', 'a', 'y', 'el', 'del');
                    if (!in_array(strtolower($a), $exclusiones)) {
                        $palabras[] = strtoupper($a);
                    }
                }
            }
            $palabras = array_unique($palabras);
            asort($palabras);
        } else {
            $palabras = array();
        }
        return $palabras;
    }

    function getKeyUnits($id_subrubro = false)
    {
        $this->db->distinct();
        $this->db->select('medida');
        $this->db->from($this->getTable());
        if ($id_subrubro) {
            $this->db->where('id_subrubro', $id_subrubro);
        };
        $this->db->where('medida IS NOT NULL', '', FALSE);
        $this->db->where('TRIM(medida) <>""', '', FALSE);
        $q = $this->db->get()->result();
        $palabras = array ();
        if (count($q) > 0) {
            foreach ($q as $palabra) {
                $aux = explode(' ', $palabra->medida);
                foreach ($aux as $a) {
                    $palabras[] = intval($a);
                }
            }
            $palabras = array_unique($palabras);
            asort($palabras);
        } else {
            $palabras = array();
        }
        return $palabras;
    }

    function getMarkupRubro($id_subrubro = false)
    {
        $this->db->distinct();
        $this->db->select('markup_articulo as markup');
        $this->db->from($this->getTable());
        if ($id_subrubro) {
            $this->db->where('id_subrubro', $id_subrubro);
        };
        $this->db->where('markup_articulo IS NOT NULL', '', FALSE);
        $this->db->where('preciocosto_articulo > 0', '', FALSE);
        $this->db->where('markup_articulo > 0', '', FALSE);
        $q = $this->db->get()->result();
        $palabras = array ();
        if (count($q) > 0) {
            foreach ($q as $palabra) {
                $aux = explode(' ', $palabra->markup);
                foreach ($aux as $a) {
                    $palabras[] = intval($a);
                }
            }
            $palabras = array_unique($palabras);
            asort($palabras);
        } else {
            $palabras = array();
        }
        return $palabras;
    }

    function getStatusWizard()
    {
        $this->db->select('SUM(IF(wizard=1,1,0))/COUNT(id_articulo)*100 AS progreso', FALSE);
        $this->db->from($this->getTable());
        return $this->db->get()->row()->progreso;
    }
}
