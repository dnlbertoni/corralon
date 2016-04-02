<?php

/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 02/04/2016
 * Time: 11:03 AM
 */
class Caja extends Admin_Controller{
    function __construct(){
        parent::__construct();
    }
    public function index(){

    }
    public function open(){
        Template::render();
    }
}