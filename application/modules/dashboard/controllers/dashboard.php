<?php

/**
 * Created by PhpStorm.
 * User: dnl
 * Date: 21/06/14
 * Time: 10:36
 */
class Dashboard extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        Template::render();
    }
} 