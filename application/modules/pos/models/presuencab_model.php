<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 03/04/2016
 * Time: 06:35 PM
 */
class Presuencab_model extends MY_Model {
    function __construct () {
        parent::__construct ();
        $this->setTable ( 'fac_presuencab' );
    }
}