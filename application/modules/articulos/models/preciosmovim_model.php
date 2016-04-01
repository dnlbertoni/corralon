<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of preciosmovim_model
 *
 * @author dnl
 */
class Preciosmovim_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('tbl_preciosmovim');
    }

    function creoTablaPrimeraVez()
    {
        $this->db->select('id_articulo as id');
        $this->db->select('preciovta_articulo AS precio');
        $this->db->select('fechamodif_articulo AS fecha');
        $this->db->select('fechaprint as impreso');
        $this->db->from('stk_articulos');
        $articulos = $this->db->get()->result();
        foreach ($articulos as $articulo) {
            $datos = array('id_articulo' => $articulo->id,
                'fecha' => $articulo->fecha,
                'precio' => $articulo->precio,
                'impreso' => 1
            );
            if ($articulo->precio > 0) {
                $this->add($datos);
            }
        }
    }

    function ultimosPrecios()
    {
        $this->db->select('id_articulo');
        $this->db->select('max(fecha) as fecha', FALSE);
        $this->db->from($this->getTable());
        $this->db->group_by('id_articulo');
        return $this->db->get()->result();
    }

    function precioFecha($id, $fecha)
    {
        $this->db->select('precio');
        $this->db->from($this->getTable());
        $this->db->where('id_articulo', $id);
        $this->db->where('fecha', $fecha);
        return $this->db->get()->row()->precio;
    }
}

