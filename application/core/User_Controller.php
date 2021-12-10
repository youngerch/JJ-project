<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class User_Controller
 * 사용자 최상위 컨트롤러
 */
class User_Controller extends MY_Controller
{

    var $_modules;
    var $_template;
    var $_template_popup;
    var $_template_layer;

    var $_container;
    var $_container_popup;
    var $_container_layer;

    function __construct()
    {
        parent::__construct();

        // Set container variable
        $this->_template            = $this->config->item('template_dir_main');
        $this->_template_popup      = $this->config->item('template_dir_popup');

        $this->_template_layer      = $this->config->item('template_dir_layer');
        $this->_container_layer     = $this->_template_layer . "layout.php";

        $this->_container           = $this->_template . "layout.php";
        $this->_container_popup     = $this->_template_popup . "layout.php";

        $this->_modules             = $this->config->item('modules_locations');

        // //허용된 아이피 이외는 모두 차단~
        // $allow_ip =  $this->config->item('cfg_allow_ip');
        // if(!in_array(__REMOTE_ADDR__, $allow_ip)):
        //     redirect("http://admin.jjproject.com/", 'refresh');
        //     exit;
        // endif;

        $this->load->library(array(
            'user_agent', 'admin_lib'
        ));

        log_message('debug', 'User_Controller class loaded');
    }

}