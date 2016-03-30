<?php

/**
 * Description of caja
 *
 * @author dnl
 */
class Caja extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        Template::set_theme('moderno/');

    }

    function index()
    {
        Template::render();
    }

}
