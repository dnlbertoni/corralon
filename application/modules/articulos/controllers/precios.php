<?php

class Precios extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function masivoAjax()
    {
        $this->load->view('articulos/precios/masivoAjaxForm');
    }
}
