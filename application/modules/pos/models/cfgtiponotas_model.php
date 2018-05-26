<?php

/**
 * Created by PhpStorm.
 * User: sistemas
 * Date: 14/04/2017
 * Time: 12:44 AM
 */
class Cfgtiponotas_model extends MY_Model {
    function __construct () {
        parent::__construct ();
        $this->setTable('cfg_tiponotas');
    }
}