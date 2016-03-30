<?php

class Tipcom_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('tipcom');
    }

    function getNombre($id)
    {
        return $this->getById($id)->descripcion;
    }
}
