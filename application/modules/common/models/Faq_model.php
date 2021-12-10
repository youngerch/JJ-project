<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Faq_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "faq";
    public $_category_table = __TABLE_PREFIX__ . "category";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_all($from_record, $rows, $whereData = array()){

        //$whereSql = " WHERE A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";
        $whereSql = " WHERE 1 = 1 ";

        foreach($whereData as $key => $value):
            if($value != ""):
                if ($key == "search_key" || $key == "date_start" || $key == "date_end"):
                    //PASS
                elseif ($key == "search_str"):
                    $whereSql .= " AND A.title LIKE '%" . $value ."%' ";
                else:
                    $whereSql .= " AND A.{$key} = '{$value}'";
                endif;
            endif;
        endforeach;

        $sql = "
			select
			  	A.*,
			  	B.cate_name_kor
			from
				{$this->_table} A
			left join {$this->_category_table} B on A.category_cd = B.cate_code		
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_count_all($whereData = array()){

        //$whereSql = " WHERE A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";
        $whereSql = " WHERE 1 = 1 ";

        foreach($whereData as $key => $value):
            if($value != ""):
                if ($key == "search_key" || $key == "date_start" || $key == "date_end"):
                    //PASS
                elseif ($key == "search_str"):
                    $whereSql .= " AND A.title LIKE '%" . $value ."%' ";
                else:
                    $whereSql .= " AND A.{$key} = '{$value}'";
                endif;
            endif;
        endforeach;

        $sql = "
			select
			  	count(*) as cnt
			from
				{$this->_table} A	
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }
}