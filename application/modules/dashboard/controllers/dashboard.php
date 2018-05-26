<?php

/**
 * Created by PhpStorm.
 * User: dnl
 * Date: 21/06/14
 * Time: 10:36
 * Dashboard: Menu Inicial del sistema.
 */
class Dashboard extends Admin_Controller
{
    /**
     * Dashboard constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @todo gaficos de resumen y monitoreo
     * @todo login de usuairo y vendedores
     */
    public function index()    {
        Template::render();
    }
} 