<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-06-17
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Admin_login_log_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "admin_login_log";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}