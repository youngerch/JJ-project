<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-28
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Faq_content_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "faq_content";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}