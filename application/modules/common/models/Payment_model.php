<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-09-02
 */

/**
 * Class Payment_model
 *
 * 결제정보
 *
 * Created on 2021-06-14
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Payment_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "payment";
    public $_member_table       = __TABLE_PREFIX__ . "member";
    public $_order_table        = __TABLE_PREFIX__ . "order";
    public $primary_key         = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Function get_count_all
     *
     * 지정 조건으로 데이터 카운트
     *
     * @param array $whereData
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-14
     * @access
     */
    public function get_count_all($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND ( ";
        $whereSql .= "  A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' OR ";
        $whereSql .= "  A.cancel_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";
        $whereSql .= " ) ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        switch ($whereData['sch_key']) {
                            case "goodsName" :
                                $whereSql .= " AND A.goodsName LIKE '%" . $value . "%' ";
                                break;
                            case "nickname" :
                                $whereSql .= " AND C.nickname LIKE '%" . $value . "%' ";
                                break;
                            case "hp" :
                                $whereSql .= " AND C.hp_s = '" . $this->secure->encrypt($value) . "' ";
                                break;
                            default:
                                $whereSql .= " AND A." . $whereData['sch_key'] . " = '" . $value . "' ";
                                break;
                        } // End switch
                        break;
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    case "payment_type" :
                        if ($value == "C") {
                            $whereSql .= " AND A.goodsName = '쿠폰선물' ";
                        } else {
                            $whereSql .= " AND B.{$key} = '{$value}'";
                        } // End if
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}'";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
			SELECT
                count(A.seq) as cnt
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_order_table} AS B ON A.seq = B.payment_seq
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }

    /**
     * Function get_list_all
     *
     * 지정 조건으로 데이터 조회
     *
     * @param $from_record 조회시작 ( 0 ~
     * @param $rows
     * @param array $whereData
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-14
     * @access
     */
    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND ( ";
        $whereSql .= "  A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' OR ";
        $whereSql .= "  A.cancel_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";
        $whereSql .= " ) ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        switch ($whereData['sch_key']) {
                            case "goodsName" :
                                $whereSql .= " AND A.goodsName LIKE '%" . $value . "%' ";
                                break;
                            case "nickname" :
                                $whereSql .= " AND C.nickname LIKE '%" . $value . "%' ";
                                break;
                            case "hp" :
                                $whereSql .= " AND C.hp_s = '" . $this->secure->encrypt($value) . "' ";
                                break;
                            default:
                                $whereSql .= " AND A." . $whereData['sch_key'] . " = '" . $value . "' ";
                                break;
                        } // End switch
                        break;
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    case "payment_type" :
                        if ($value == "C") {
                            $whereSql .= " AND A.goodsName = '쿠폰선물' ";
                        } else {
                            $whereSql .= " AND B.{$key} = '{$value}'";
                        } // End if
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}'";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
			SELECT
                A.*, 
                B.payment_type,
                C.login_id_s, C.nickname, C.international, C.hp_s
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_order_table} AS B ON A.seq = B.payment_seq
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }


    public function get_payment_summary($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND ( ";
        $whereSql .= "  A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' OR ";
        $whereSql .= "  A.cancel_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";
        $whereSql .= " ) ";

        foreach($whereData as $key => $value):
            if($value != ""):
                switch($key):
                    case "sch_str" :
                        switch ( $whereData['sch_key'] ):
                            case "goodsName" :
                                $whereSql .= " AND A.goodsName LIKE '%".$value."%' "; break;
                            case "nickname" :
                                $whereSql .= " AND C.nickname LIKE '%".$value."%' "; break;
                            case "hp" :
                                $whereSql .= " AND C.hp_s = '".$this->secure->encrypt($value)."' "; break;
                            default:
                                $whereSql .= " AND A.".$whereData['sch_key']." = '".$value."' "; break;
                        endswitch;
                        break;
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    case "payment_type" :
                        if ( $value == "C" ) :
                            $whereSql .= " AND A.goodsName = '쿠폰선물' ";
                        else:
                            $whereSql .= " AND B.{$key} = '{$value}'";
                        endif;
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}'";
                        break;
                endswitch;
            endif;
        endforeach;

        $sql = "
            SELECT
                IFNULL(SUM(CASE WHEN A.is_cancel = 'N' THEN A.amount ELSE 0 END), 0) AS payment_amount,
                IFNULL(SUM(CASE WHEN A.is_cancel = 'N' THEN 1 ELSE 0 END), 0) AS payment_count,
                IFNULL(SUM(CASE WHEN A.is_cancel = 'Y' THEN A.amount ELSE 0 END), 0) AS cancel_amount,
                IFNULL(SUM(CASE WHEN A.is_cancel = 'Y' THEN 1 ELSE 0 END), 0) AS cancel_count
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_order_table} AS B ON A.seq = B.payment_seq
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq              
            {$whereSql}
        ";
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ) :
            return $row;
        else:
            return false;
        endif;
    }

    //기간내 총 매출액을 조회.
    public function get_payment_sum_amount($startDate, $endDate)
    {
        $sql = "
            SELECT 
                IFNULL(SUM(amount), 0) AS sum_amount
            FROM {$this->_table}
            WHERE reg_date BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'
            AND is_cancel = '0'
        ";

        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->sum_amount;
    }

    //추천실적별 매출액을 조회 - 15% 지급
    public function get_recommend_sum_amount($startDate, $endDate)
    {
        $sql = "
            SELECT
                AA.recommend_seq, COUNT(member_seq) as member_count, SUM(sum_amount) AS total_amount,
                BB.seq, BB.nickname, BB.grade, BB.expire_date
            FROM (
                SELECT 
                    A.member_seq, A.sum_amount, B.nickname, B.recommend_seq
                FROM 
                ( 
                    SELECT 
                        member_seq, SUM(amount) AS sum_amount
                    FROM {$this->_table} WHERE reg_date BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'
                    AND is_cancel = '0'
                    GROUP BY member_seq
                ) AS A
                LEFT JOIN {$this->_member_table} AS B ON A.member_seq = B.seq
            ) AS AA
            LEFT JOIN tbl_member AS BB ON AA.recommend_seq = BB.seq            
            GROUP BY AA.recommend_seq
            ORDER BY AA.recommend_seq
        ";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    //
    public function verify_members($checkDT)
    {
        $sql = "
            SELECT
                member_seq, 
                SUM(IFNULL(game_coin, 0))
            FROM {$this->_table} 
            WHERE reg_date >= ?
            GROUP BY member_seq
        ";
        $query = $this->db->query($sql, array($checkDT));
        $result = $query->result();

        return $result;
    }

    //
    public function duplications_verify($checkDT)
    {
        $sql = "
            SELECT
                member_seq,
                tid,
                COUNT(tid) AS count_tid
            FROM {$this->_table}
            WHERE reg_date >= ?
            GROUP BY tid
            HAVING COUNT(tid) > 1
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($checkDT));
        $result = $query->result();

        return $result;
    }
}