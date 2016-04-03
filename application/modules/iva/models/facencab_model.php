<?php

class Facencab_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable ( 'fac_facencab' );
    }

    function ListadoSumaPeriodos($libro)
    {
        $this->db->select('periva');
        $this->db->select('count(tipcom_id) AS cantidad');
        $this->db->select('sum(importe) AS total');
        $this->db->select('sum(ivamin + ivamax) AS totiva');
        $this->db->from($this->getTable());
        $this->db->join ( 'cfg_tipcom', 'cfg_tipcom.id=fac_facencab.tipcom_id', 'inner' );
        $this->db->where ( 'fac_facencab.periva IS NOT NULL', null, false );
        $this->db->where ( 'cfg_tipcom.libroiva', $libro );
        $this->db->group_by('periva');
        $this->db->order_by('periva', 'DESC');
        $q = $this->db->get();
        return $q->result();
    }

    function ListadoFacturasPeriodoCerrar($libro, $periodo)
    {
        $ano = intval($periodo / 100);
        $mes = $periodo - ($ano * 100);
        if ($mes < 4) {
            $mes += 12;
            $ano--;
        }
        $mes -= 3;
        $perdes = ($ano * 100) + $mes;
        $this->db->select ( 'fac_facencab.id, cfg_tipcom.abreviatura as tipcomp, fac_facencab.letra, puesto, numero, cuenta.nombre as razonSocial, importe, ivapass, periva ' );
        $this->db->select('date_format(fecha, "%d-%m-%Y") as fecha', false);
        $this->db->select('(ivamin+ivamax+percep) as ivatot', false);
        $this->db->select ( 'if( fac_facencab.tipcom_id=2 AND puesto=3, 0, 1) AS suma', false );
        $this->db->from($this->getTable());
        $this->db->join ( 'cfg_tipcom', 'cfg_tipcom.id=fac_facencab.tipcom_id', 'inner' );
        $this->db->join('cuenta', 'cuenta_id = cuenta.id', 'inner');
        if (isset($periodo)) {
            $condicion = "( fac_facencab.periva = " . $periodo . " OR fac_facencab.periva = 0) ";
            $this->db->where($condicion, null, false);
        };
        $this->db->where ( 'cfg_tipcom.libroiva', $libro );
        $this->db->where('date_format(fecha, "%Y%m") <= ', $periodo, false);
        $this->db->where('date_format(fecha, "%Y%m") >= ', $perdes, false);
        $this->db->where ( 'fac_facencab.estado <>', 2 );
        $this->db->order_by ( 'fac_facencab.fecha' );
        $this->db->order_by('tipcom_id');
        $q = $this->db->get();
        //echo $this->db->last_query();
        return $q->result();
    }

    function ListadoFacturasPeriodo($libro, $periodo)
    {
        $this->db->select ( 'fac_facencab.id, cfg_tipcom.abreviatura as tipcomp, fac_facencab.letra, puesto, numero, cuenta.nombre as razonSocial, importe, ivapass, periva ' );
        $this->db->select('date_format(fecha, "%d-%m-%Y") as fecha', false);
        $this->db->select('(ivamin+ivamax+percep) as ivatot', false);
        $this->db->select ( 'if( fac_facencab.tipcom_id=2 AND puesto=3, 0, 1) AS suma', false );
        $this->db->from($this->getTable());
        $this->db->join ( 'cfg_tipcom', 'cfg_tipcom.id=fac_facencab.tipcom_id', 'inner' );
        $this->db->join('cuenta', 'cuenta_id = cuenta.id', 'inner');
        $this->db->where ( 'fac_facencab.periva ', $periodo );
        $this->db->where ( 'cfg_tipcom.libroiva', $libro );
        $this->db->order_by('fecha');
        $this->db->order_by('tipcom_id');
        //echo $this->db->_compile_select();
        $q = $this->db->get();
        return $q->result();
    }

    function LibroIVA($libro, $periodo)
    {
        $this->db->select('date_format(fecha, "%d-%m-%Y") as fecha', false);
        $this->db->select ( 'CONCAT( cfg_tipcom.abreviatura, " ", fac_facencab.letra, " ",  puesto,"-", numero) as comprobante', false );
        $this->db->select(' cuenta.nombre as razonSocial, cuenta.cuit as Cuit', false);
        $this->db->select('importe, neto, ivamin, ivamax, ingbru, impint, percep');
        $this->db->select ( 'if( fac_facencab.tipcom_id=2 AND puesto=3, 0, 1) AS suma', false );
        $this->db->from($this->getTable());
        $this->db->join ( 'cfg_tipcom', 'cfg_tipcom.id=fac_facencab.tipcom_id', 'inner' );
        $this->db->join('cuenta', 'cuenta_id = cuenta.id', 'inner');
        $this->db->where ( 'fac_facencab.periva ', $periodo );
        $this->db->where ( 'cfg_tipcom.libroiva', $libro );
        $this->db->order_by ( 'fac_facencab.fecha' );
        $this->db->order_by('tipcom_id');
        $this->db->order_by('puesto');
        $this->db->order_by('numero');
        //echo $this->db->_compile_select();
        $q = $this->db->get();
        return $q->result();
    }

    function ListadoPercepciones($periodo)
    {
        $this->db->select('cuenta.nombre as razonSocial');
        $this->db->select('cuenta.cuit as cuit');
        $this->db->select_sum('importe', 'importe');
        $this->db->select_sum('neto', 'neto');
        $this->db->select_sum('ivamin', 'ivamin');
        $this->db->select_sum('ivamax', 'ivamax');
        $this->db->select_sum('ingbru', 'ingbru');
        $this->db->select_sum('impint', 'impint');
        $this->db->select_sum('percep', 'percep');
        $this->db->from($this->getTable());
        $this->db->join ( 'cfg_tipcom', 'cfg_tipcom.id=fac_facencab.tipcom_id', 'inner' );
        $this->db->join('cuenta', 'cuenta_id = cuenta.id', 'inner');
        $this->db->where ( 'fac_facencab.periva ', $periodo );
        $this->db->where ( 'cfg_tipcom.libroiva', 2 );
        $this->db->group_by('cuit');
        $this->db->order_by('razonSocial');
        //echo $this->db->_compile_select();
        $q = $this->db->get();
        return $q->result();
    }

    function ActualizoPeriva($periodo, $fac_id)
    {
        $this->db->trans_begin();

        //activo solo las facturas que estan seleccionadas
        foreach ($fac_id as $key => $valor) {
            $this->db->set('ivapass', $valor);
            $this->db->set('periva', ($valor == 0) ? 0 : $periodo);
            $this->db->where('id', $key);
            $this->db->update($this->getTable());
            //echo $this->db->last_query();
        }
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            $estado = true;
        } else {
            $this->db->trans_rollback();
            $estado = false;
        };
        return $estado;
    }

    function verificoCierreZ($numero)
    {
        $this->db->from($this->getTable());
        $this->db->where('numero', $numero);
        $this->db->where('tipcom_id', 4);
        $this->db->where('puesto', 3);
        $this->db->where('letra', 'Z');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function PeriodosToDropDown()
    {
        $this->db->distinct();
        $this->db->select('periva');
        $this->db->from($this->getTable());
        $this->db->where('periva IS NOT NULL', null, false);
        $this->db->where('periva !=', 0);
        $this->db->order_by('periva', 'desc');
        $q = $this->db->get()->result();
        foreach ($q as $item) {
            $linea [$item->periva] = $item->periva;
        };
        return (object)$linea;
    }

    function save($datos)
    {
        $this->db->insert($this->getTable(), $datos);
        $q = $this->db->insert_id();
        return $q;
    }

    function getRegistro($id)
    {
        $this->db->from($this->getTable());
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    function getCierreZ($numero)
    {
        $this->db->from($this->getTable());
        $this->db->where('numero', $numero);
        $this->db->where('tipcom_id', 4);
        $this->db->where('puesto', 3);
        $this->db->where('letra', 'Z');
        return $this->db->get()->row();
    }

    function existeClave($clave = array())
    {
        $this->db->from($this->getTable());
        foreach ($clave as $key => $value) {
            $this->db->where($key, $value);
        };
        $q = $this->db->get()->result();
        if (count($q) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
