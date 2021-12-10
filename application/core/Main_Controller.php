<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main_Controller extends User_Controller
{

    var $_template;
    var $_template_popup;
    var $_container_popup;
    var $_template_layer;
    var $_container_layer;

    function __construct()
    {
        parent::__construct();

        // Set container variable
        $this->_template            = $this->config->item('template_dir_main');
        $this->_container           = $this->_template . "layout.php";
        log_message('debug', 'Main_Controller class loaded');
    }

}