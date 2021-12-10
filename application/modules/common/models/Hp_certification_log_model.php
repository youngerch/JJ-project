<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Hp_certification_log_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "hp_certification_log";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}