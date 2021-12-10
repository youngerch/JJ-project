<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-18
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Member_error_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "member_error";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}