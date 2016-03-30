<?php

class Marcas_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable("stk_marcas");
    }

    function ListaSelect($campoId = "id_marca", $campoNombre = "detalle_marca")
    {
        return $this->toDropDown($campoId, $campoNombre);
    }

    function getNombre($idEmpresa)
    {
        $q = $this->getById($idEmpresa);
        return ($q) ? $q->DETALLE_MARCA : '';
    }
}
