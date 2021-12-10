<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-12-02
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_performance_summary_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "payment_performance_summary";
    public $primary_key         = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}