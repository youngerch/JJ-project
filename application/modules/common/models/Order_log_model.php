<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-09-02
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Order_log_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "order_log";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}