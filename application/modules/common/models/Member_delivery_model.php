<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Member_delivery_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "member_delivery";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_latest_delivery($member_seq)
    {
        $sql = "
            SELECT 
                *
            FROM {$this->_table}
            WHERE 1 = 1
            AND member_seq = ?
            ORDER BY seq DESC
            LIMIT 1
        ";
        $query = $this->db->query($sql, array($member_seq));
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }
}