<?php

/**
 * Description of cajamovim_model
 *
 * @author dnl
 */
class Cajamovim_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('caja_movim');
    }
}
