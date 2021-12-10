<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-28
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Board_file_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "board_file";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}