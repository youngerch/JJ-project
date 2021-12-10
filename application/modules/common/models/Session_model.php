<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');
class Session_model extends MY_Model
{
    public $_table = 'ci_sessions';
    public $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }
}