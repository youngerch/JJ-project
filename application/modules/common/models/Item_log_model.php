<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-07-13
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Item_log_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "item_log";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}