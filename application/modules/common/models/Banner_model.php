<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Banner_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "banner";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_count_all($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";

        foreach ($whereData as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "search_str" :
                        $whereSql .= " AND A.title LIKE '%{$val}%'";
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$val}'";
                        break;
                } // End switch
            } // End switch
        } // End foreach

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


    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";

        foreach ($whereData as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "search_str" :
                        $whereSql .= " AND A.title LIKE '%{$val}%'";
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$val}'";
                        break;
                } // End switch
            } // End switch
        } // End foreach

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

    public function get_count_by_display($whereData = array()){

        $whereSql = " WHERE 1 = 1 ";

        foreach ($whereData as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "search_str" :
                        $whereSql .= " AND A.title LIKE '%{$val}%'";
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$val}'";
                        break;
                } // End switch
            } // End switch
        } // End foreach

        $sql = "
			SELECT		    		
			    COUNT(A.seq) AS Cnt,	    
			    SUM(CASE WHEN A.is_display = 1 THEN 1 ELSE 0 END) AS type_1,
			    SUM(CASE WHEN A.is_display = 9 THEN 1 ELSE 0 END) AS type_9			    
			FROM
				{$this->_table} A	
			".$whereSql."
			GROUP BY is_display
		";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }
}