<?php
/**
 * Created by cs.kim@ablex.co.kr on 2021-03-19
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Order_temporary_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "order_temporary";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
} 