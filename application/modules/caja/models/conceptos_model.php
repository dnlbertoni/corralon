<?php

/**
 * Description of conceptos_model
 *
 * @author ultra
 */
class Conceptos_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('conceptos');
    }
}
