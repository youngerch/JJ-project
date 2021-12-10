<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Inspection_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "inspection";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

} 