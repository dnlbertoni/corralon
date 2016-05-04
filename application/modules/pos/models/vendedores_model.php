<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 30/04/2016
 * Time: 04:25 PM
 */
class Vendedores_model extends MY_Model {

    function __construct () {
        parent::__construct ();
        $this->setTable ( 'cfg_vendedores' );
    }

}