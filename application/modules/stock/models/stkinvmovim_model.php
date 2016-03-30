<?php

/**
 * Created by PhpStorm.
 * User: dnl
 * Date: 22/05/14
 * Time: 16:32
 */
class Stkinvmovim_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('stk_invmovim');
    }

    function agregarAlConteo($id_articulo, $CB, $descripcion, $cantidad, $unidades, $cantidadBultos, $cantidadXbultos, $deposito, $inventario)
    {
        $this->db->set('id_inventario', $inventario);
        $this->db->set('id_articulo', $id_articulo);
        $this->db->set('codigobarra', $CB);
        $this->db->set('descripcion', $descripcion);
        $this->db->set('cantidad', $cantidad);
        $this->db->set('unidades_sueltas', $unidades);
        $this->db->set('cant_bultos', $cantidadBultos);
        $this->db->set('unidades_bulto', $cantidadXbultos);
        $this->db->set('ubicacion', $deposito);
        $this->db->insert($this->getTable());
        return $this->db->insert_id();
    }

    function getFromDeposito($deposito = 'ALL', $inventario)
    {
        if ($deposito != 'ALL') {
            $this->db->where('ubicacion', $deposito);
        };
        $this->db->where('id_inventario', $inventario);
        $this->db->from($this->getTable());
        $this->db->order_by('fecha', 'DESC');
        return $this->db->get()->result();
    }

    function modificaCantidad($id, $campo, $cantidad)
    {
        $query = " UPDATE " . $this->getTable() . " SET $campo = $campo + $cantidad, cantidad = (cant_bultos * unidades_bulto)+unidades_sueltas WHERE id  = $id ";
        $this->db->query($query);
        $this->db->select($campo);
        $this->db->select('cantidad');
        $this->db->where('id', $id);
        $this->db->from($this->getTable());
        return $this->db->get()->row();
    }
} 