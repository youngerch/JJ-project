<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Board_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "board";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_all($from_record, $rows, $whereData = array()){

        $whereSql = " WHERE A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        if ( $whereData['is_reserve'] != "" )
            $whereSql .= " AND A.is_reserve = '" . $whereData['is_reserve'] ."' ";

        if ( $whereData['is_display'] != "" )
            $whereSql .= " AND A.is_display = '" . $whereData['is_display'] ."' ";

        if ( $whereData['search_str'] != "" )
            $whereSql .= " AND A.title LIKE '%" . $whereData['search_str'] ."%' ";

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

    public function get_count_all($whereData = array()){

        $whereSql = " WHERE A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        if ( $whereData['is_reserve'] != "" )
            $whereSql .= " AND A.is_reserve = '" . $whereData['is_reserve'] ."' ";

        if ( $whereData['is_display'] != "" )
            $whereSql .= " AND A.is_display = '" . $whereData['is_display'] ."' ";

        if ( $whereData['search_str'] != "" )
            $whereSql .= " AND A.title LIKE '%" . $whereData['search_str'] ."%' ";

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

    public function get_count_by_site_type($whereData = array()){

        $whereSql = " WHERE A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        if ( $whereData['is_reserve'] != "" )
            $whereSql .= " AND A.is_reserve = '" . $whereData['is_reserve'] ."' ";

        if ( $whereData['is_display'] != "" )
            $whereSql .= " AND A.is_display = '" . $whereData['is_display'] ."' ";

        if ( $whereData['search_str'] != "" )
            $whereSql .= " AND A.title LIKE '%" . $whereData['search_str'] ."%' ";

        $sql = "
			SELECT
			    COUNT(A.seq) AS cnt,
			    SUM(CASE WHEN A.site_type = 9 THEN 1 ELSE 0 END) AS type_all,
			    SUM(CASE WHEN A.site_type = 1 THEN 1 ELSE 0 END) AS type_1,
			    SUM(CASE WHEN A.site_type = 2 THEN 1 ELSE 0 END) AS type_2			    
			FROM
				{$this->_table} A	
			".$whereSql."
			GROUP BY site_type
		";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }
}