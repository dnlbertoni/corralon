<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fpagos_model
 *
 * @author dnl
 */
class Fpagos_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('fpagos');
    }
}
