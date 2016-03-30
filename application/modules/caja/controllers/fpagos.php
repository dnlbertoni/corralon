<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fpagos
 *
 * @author dnl
 */
class Fpagos extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Fpagos_model');
    }

    function index()
    {
        Template::render();
    }
}
