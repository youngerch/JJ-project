<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-11
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Sms_model extends MY_Model
{
    public $_table          = __TABLE_PREFIX__ . "sms";
    public $_member_table   = __TABLE_PREFIX__ . "member";
    public $_admin_table    = __TABLE_PREFIX__ . "admin";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_all($from_record, $rows, $whereData = array()){

        $whereSql = " WHERE A.reg_date BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach($whereData as $key => $value):
            if ( $value != "" ):
                switch ( $key ):
                    case "sch_str" :
                        switch ( $whereData['sch_key'] ):
                            case "hp_s" :
                                $whereSql .= " AND A.to_phone = '".$this->secure->encrypt($value)."' ";
                                break;
                            case "nickname" :
                                $whereSql .= " AND B.nickname LIKE '%{$value}%' ";
                                break;
                            case "admin_seq" :
                                $whereSql .= " AND C.name LIKE '%{$value}%' ";
                                break;
                            default :
                                $whereSql .= " AND A." . $whereData['sch_key'] . " LIKE '%{$value}%' ";
                                break;
                        endswitch;
                        break;
                    case "sch_key" :
                    case "date_start" :
                    case "date_end" :
                        //PASS
                        break;
                    default:
                        $whereSql .= " AND {$key} = '{$value}' ";
                        break;
                endswitch;
            endif;
        endforeach;

        $sql = "
			SELECT
			  	A.*, 
                IFNULL(B.nickname, A.to_name) AS receiver,
                IFNULL(C.name, '시스템') AS sender
			FROM {$this->_table} AS A
			LEFT OUTER JOIN {$this->_member_table} AS B ON A.to_phone = B.hp_s
			LEFT JOIN {$this->_admin_table} AS C ON A.admin_seq = C.seq
			".$whereSql."
			ORDER BY A.seq DESC
			LIMIT ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_count_all($whereData = array()){

        $whereSql = " WHERE A.reg_date BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach($whereData as $key => $value):
            if ( $value != "" ):
                switch ( $key ):
                    case "sch_str" :
                        switch ( $whereData['sch_key'] ):
                            case "hp_s" :
                                $whereSql .= " AND A.to_phone = '".$this->secure->encrypt($value)."' ";
                                break;
                            case "nickname" :
                                $whereSql .= " AND B.nickname LIKE '%{$value}%' ";
                                break;
                            case "admin_seq" :
                                $whereSql .= " AND C.name LIKE '%{$value}%' ";
                                break;
                            default :
                                $whereSql .= " AND A." . $whereData['sch_key'] . " LIKE '%{$value}%' ";
                                break;
                        endswitch;
                        break;
                    case "sch_key" :
                    case "date_start" :
                    case "date_end" :
                        //PASS
                        break;
                    default:
                        $whereSql .= " AND {$key} = '{$value}' ";
                        break;
                endswitch;
            endif;
        endforeach;

        $sql = "
            SELECT
			  	COUNT(A.seq) AS cnt
			FROM {$this->_table} AS A
			LEFT JOIN {$this->_member_table} AS B ON A.member_seq = B.seq
			LEFT JOIN {$this->_admin_table} AS C ON A.admin_seq = C.seq
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }

}