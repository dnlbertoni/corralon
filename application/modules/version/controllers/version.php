<?php

class Version extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Version_model', '', true);
    }

    function index()
    {
        $data['titulo'] = "";
        Template::render();
    }
}
