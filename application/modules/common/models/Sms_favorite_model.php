<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-11
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');


class Sms_favorite_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "sms_favorite";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_all($from_record, $rows, $whereData = array()){

        $whereSql = " WHERE 1 = 1 ";

        foreach($whereData as $key => $value):
            switch ( $key ):
                case "sch_str" :
                    $whereSql .= " AND " . $whereData['sch_key'] . " LIKE '%{$value}%' ";
                    break;
                case "sch_key" :
                    //PASS
                    break;
                default:
                    $whereSql .= " AND {$key} = '{$value}' ";
                    break;
            endswitch;
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
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_count_all($whereData = array()){

        $whereSql = " WHERE 1 = 1 ";

        foreach($whereData as $key => $value):
            switch ( $key ):
                case "sch_str" :
                    $whereSql .= " AND " . $whereData['sch_key'] . " LIKE '%{$value}%' ";
                    break;
                case "sch_key" :
                    //PASS
                    break;
                default:
                    $whereSql .= " AND {$key} = '{$value}' ";
                    break;
            endswitch;
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