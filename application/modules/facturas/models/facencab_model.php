<?php

class Facencab_model extends MY_Model
{
    private $tabla = "facencab";

    function __construct()
    {
        parent::__construct();
        $this->setTable('facencab');
    }

    function ActualizoPeriva($periodo, $fac_id)
    {
        $this->db->_reset_select();
        $this->db->trans_begin();

        //activo solo las facturas que estan seleccionadas
        foreach ($fac_id as $key => $valor) {
            $this->db->set('ivapass', $valor);
            $this->db->set('periva', ($valor == 0) ? 0 : $periodo);
            $this->db->where('id', $key);
            $this->db->update($this->tabla);
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
        $this->db->_reset_select();
        $this->db->from($this->tabla);
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
        $this->db->_reset_select();
        $this->db->distinct();
        $this->db->select('periva');
        $this->db->from($this->tabla);
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
        $this->db->insert($this->tabla, $datos);
        $q = $this->db->insert_id();
        return $q;
    }

    function getRegistro($id)
    {
        $this->db->_reset_select();
        $this->db->from($this->tabla);
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    function getCierreZ($numero)
    {
        $this->db->_reset_select();
        $this->db->from($this->tabla);
        $this->db->where('numero', $numero);
        $this->db->where('tipcom_id', 4);
        $this->db->where('puesto', 3);
        $this->db->where('letra', 'Z');
        return $this->db->get()->row();
    }

    function getCuentasFechas($cuenta_id = false, $desde = false, $hasta = false, $limite = false)
    {
        $this->db->select('facencab.id as id');
        $this->db->select('nombre ');
        $this->db->select('DATE_FORMAT(fecha, "%d-%m-%Y") as fecha');
        $this->db->select('CONCAT( tipcom.abreviatura, " ", facencab.letra, " ",  puesto,"-", numero) as comprobante', FALSE);
        $this->db->select('importe');
        $this->db->select('periva');
        $this->db->from($this->getTable());
        $this->db->join('cuenta', 'cuenta_id=cuenta.id', 'left');
        $this->db->join('tipcom', 'tipcom.id=facencab.tipcom_id', 'inner');
        if ($cuenta_id != '') {
            $this->db->where('cuenta_id', $cuenta_id);
        }
        if ($desde == "" && $hasta != "") {
            $this->db->where('DATE_FORMAT(fecha, "%Y-%m-%d") <= "' . $hasta . '"', '', FALSE);
        }
        if ($desde != "" && $hasta == "") {
            $this->db->where('DATE_FORMAT(fecha, "%Y-%m-%d") >= "' . $desde . '"', '', FALSE);
        }
        if ($desde != "" && $hasta != "") {
            $this->db->where('DATE_FORMAT(fecha, "%Y-%m-%d") BETWEEN "' . $desde . '" AND "' . $hasta . '"', '', FALSE);
        }
        $this->db->order_by('facencab.fecha', 'DESC');
        $this->db->order_by('comprobante', 'ASC');
        if ($limite) {
            $this->db->limit($limite);
        }
        return $this->db->get()->result();
    }
}
