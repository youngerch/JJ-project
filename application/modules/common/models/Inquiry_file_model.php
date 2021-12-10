<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-08-18
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Inquiry_file_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "inquiry_file";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}