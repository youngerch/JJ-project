<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Member_subscription_log_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "member_subscription_log";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}