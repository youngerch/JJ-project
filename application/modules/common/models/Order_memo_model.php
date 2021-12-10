<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-09-03
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Order_memo_model extends MY_Model
{
    public $_table          = __TABLE_PREFIX__ . "order_memo";
    public $_admin_table    = __TABLE_PREFIX__ . "admin";
    public $primary_key     = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}