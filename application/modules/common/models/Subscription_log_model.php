<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Subscription_log_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "subscription_log";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_count_all($whereData = array()){

        $whereSql = " WHERE 1 = 1 ";

        foreach($whereData as $key => $value):
            if($value != ""):
                if ($key == "search_key"):
                    //PASS
                elseif ($key == "search_str"):
                    $whereSql .= " AND ". $whereData['search_key'] ." LIKE '%" . $value ."%' ";
                elseif ($key == "search_year"):
                    $whereSql .= " AND SUBSTR(A.reg_date, 1, 4) =  '{$value}'";
                else:
                    $whereSql .= " AND A.{$key} = '{$value}'";
                endif;
            endif;
        endforeach;

        $sql = "
			select
			  	count(*) as cnt
			from
				{$this->_table} as A
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }

    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";

        foreach($whereData as $key => $value):
            if($value != ""):
                if ($key == "search_key"):
                    //PASS
                elseif ($key == "search_str"):
                    $whereSql .= " AND ". $whereData['search_key'] ." LIKE '%" . $value ."%' ";
                elseif ($key == "search_year"):
                    $whereSql .= " AND SUBSTR(A.reg_date, 1, 4) =  '{$value}'";
                else:
                    $whereSql .= " AND A.{$key} = '{$value}'";
                endif;
            endif;
        endforeach;

        $sql = "
			select
			  	A.*
			from
				{$this->_table} A
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

}