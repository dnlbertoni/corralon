<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of listas
 *
 * @author dnl
 */
class Listas extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->module('layout');
    }

    function index()
    {
        $this->layout->buffer('content', 'listas/index');
        $this->layout->render();
    }
}
