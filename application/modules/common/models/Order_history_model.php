<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-09-03
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Order_history_model extends MY_Model
{
    public $_table          = __TABLE_PREFIX__ . "order_history";
    public $primary_key     = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}