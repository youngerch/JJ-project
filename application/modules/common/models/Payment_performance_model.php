<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-12-02
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_performance_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "payment_performance";
    public $_member_table       = __TABLE_PREFIX__ . "member";
    public $primary_key         = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_count_all($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";

        foreach($whereData as $key => $value):
            if($value != ""):
                switch($key):
                    case "sch_yymm" :
                        $whereSql .= " AND A.review_date = '" .$value. "' ";
                        break;
                    case "sch_str" :
                        if ( $whereData['sch_key'] == "hp_s" ):
                            $whereSql .= " AND B.hp_s = '" .$this->secure->encrypt($value). "' ";
                        else:
                            $whereSql .= " AND B.nickname = '" .$value. "' ";
                        endif;
                        break;
                    case "sch_grade" :
                        $whereSql .= " AND B.grade = '".$value."' ";
                        break;
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$value}'";
                        break;
                endswitch;
            endif;
        endforeach;

        $sql = "
			SELECT
                count(A.seq) as cnt
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_member_table} AS B ON A.member_seq = B.seq            
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
                switch($key):
                    case "sch_yymm" :
                        $whereSql .= " AND A.review_date = '" .$value. "' ";
                        break;
                    case "sch_str" :
                        if ( $whereData['sch_key'] == "hp_s" ):
                            $whereSql .= " AND B.hp_s = '" .$this->secure->encrypt($value). "' ";
                        else:
                            $whereSql .= " AND B.nickname = '" .$value. "' ";
                        endif;
                        break;
                    case "sch_grade" :
                        $whereSql .= " AND A.grade = '".$value."' ";
                        break;
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$value}'";
                        break;
                endswitch;
            endif;
        endforeach;

        $sql = "
			SELECT
                A.*,                
                B.international, B.hp_s, B.nickname, B.email_s
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_member_table} AS B ON A.member_seq = B.seq
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_performance_summary($review_date)
    {
        $sql = "
                SELECT
                    IFNULL(SUM(A.recommend_amount), 0) AS total_amount,
                    IFNULL(SUM(A.recommend_sales), 0) AS total_sales,
                    IFNULL(SUM(CASE WHEN grade IN ( '4', '5' ) THEN A.recommend_sales ELSE 0 END), 0) AS deposit_sales
                FROM {$this->_table} AS A                        
                WHERE 1 = 1
                AND review_date = ?
		";
        $query = $this->db->query($sql, $review_date);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    public function get_deposit_targets($review_date)
    {
        $sql = "
            SELECT
                *
            FROM {$this->_table}
            WHERE 1 = 1
            AND review_date = ?
            AND grade IN ( '4', '5' )
            AND status = 0
            AND deposit_date IS NULL
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($review_date));
        $result = $query->result();

        return $result;
    }
}