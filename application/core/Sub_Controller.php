<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sub_Controller extends User_Controller
{
    var $_template;

    var $_template_popup;
    var $_container_popup;

    var $_template_layer;
    var $_container_layer;

    var $_admin;
    var $_page_title;

    var $_inquiry_count;
    var $_coin_withdraw_count;

    var $_menu_item;

    function __construct()
    {
        parent::__construct();
        // Set container variable
        $this->_template  = $this->config->item('template_dir_sub');
        $this->_container = $this->_template . "layout.php";
        log_message('debug', 'Sub_Controller class loaded');

        if ($this->admin_lib->is_login()) {

            $this->load->model(array(
                'common/inquiry_model'          => 'm_inquiry',
                'common/admin_permission_model' => 'm_admin_permission',
            ));

            $admin_seq = $this->admin_lib->get_session('seq');
            $this->_admin = $this->admin_lib->get_administrator($admin_seq);
            $permission_seq = $this->admin_lib->get_administrator($admin_seq)->permission_seq;
            $this->_accessible_arr = explode('|', $this->m_admin_permission->get($permission_seq)->accessible_menu);

            $this->_inquiry_count = $this->m_inquiry->get_count_by_status();

        }
        else {
            redirect('/main/auth', 'refresh');
        } // End if

        $this->_page_title = "메인";
    }

}