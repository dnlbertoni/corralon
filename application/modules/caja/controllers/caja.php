<?php

/**
 * Description of caja
 *
 * @author dnl
 */
class Caja extends Admin_Controller {

    function __construct () {
        parent::__construct();

    }

    function index () {
        Template::render();
    }

    function open () {
        $data['puesto'] = $this->getPuesto ();
        Template::set ( $data );
        Template::render ();
    }
}
