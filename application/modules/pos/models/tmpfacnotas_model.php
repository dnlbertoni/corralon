<?php

/**
 * Created by PhpStorm.
 * User: sistemas
 * Date: 13/04/2017
 * Time: 09:22 PM
 */
class Tmpfacnotas_model extends MY_Model {
    public function __construct () {
        parent::__construct ();
        $this->setTable('tmp_facnotas');
    }

}