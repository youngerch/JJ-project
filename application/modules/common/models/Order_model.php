<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-09-02
 */

/**
 * Class Order_model
 *
 * 주문정보
 *
 * Created on 2021-06-11
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Order_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "order";
    public $_order_item_table   = __TABLE_PREFIX__ . "order_item";
    public $_member_table       = __TABLE_PREFIX__ . "member";
    public $_item_table         = __TABLE_PREFIX__ . "item";            // 상품 정보
    public $_item_country_table = __TABLE_PREFIX__ . "item_country";    // 배송국가별 상품 정보
    public $_item_content_table = __TABLE_PREFIX__ . "item_content";    // 언어별 상품 정보
    public $_payment_table      = __TABLE_PREFIX__ . "payment";
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
     * @param array $whereData 지정조건 ( Array )
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-11
     * @access
     */
    public function get_count_all($whereData = array()){

        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND B.".$whereData['date_type']." BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "order_status" :
                        //$whereSql .= " AND A.order_status IN ( '" . str_replace(",", "','", $value) ."' ) ";
                        $whereSql .= " AND ( ";
                        for ($s = 0; $s < count($value); $s++) {
                            if ($s == 0) {
                                $whereSql .= " LOCATE(" . $value[$s] . ", A.order_item_status ) > 0 ";
                            } else {
                                $whereSql .= " OR LOCATE(" . $value[$s] . ", A.order_item_status ) > 0 ";
                            } // End if
                        } // End for
                        $whereSql .= " ) ";
                        break;
                    case "grade" :
                        $whereSql .= " AND C.grade = '" . $whereData['grade'] . "' ";
                        break;
                    case "sch_str" :
                        switch ($whereData['sch_key']) {
                            case "login_id_s" :
                                $whereSql .= " AND C.login_id_s = '" . $this->secure->encrypt($value) . "' ";
                                break;
                            case "hp_s" :
                                $whereSql .= " AND C.hp_s = '" . $this->secure->encrypt($value) . "' ";
                                break;
                            case "nickname" :
                                $whereSql .= " AND C.nickname LIKE '%" . $value . "%' ";
                                break;
                            case "item_name" :
                                $whereSql .= " AND D.item_name LIKE '%" . $value . "%' ";
                                break;
                            case "item_cd" :
                                $whereSql .= " AND B.item_cd = '" . $value . "' ";
                                break;
                            case "order_cd" :
                                $whereSql .= " AND A.order_cd = '" . $value . "' ";
                                break;
                            case "delivery_number" :
                                $whereSql .= " AND B.delivery_number = '" . $value . "' ";
                                break;
                        } // End switch
                        break;
                    case "tab_status" :
                        $whereSql .= " AND LOCATE(" . $value . ", A.order_item_status ) > 0 ";
                        break;
                    case "coin_type" :
                        $whereSql .= " AND Z.coin_type = '" . $value . "' ";
                        break;
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
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
            LEFT JOIN {$this->_order_item_table} AS B ON A.seq = B.order_seq AND B.is_exchange = 'N'
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq                
            LEFT JOIN {$this->_item_table} AS D ON B.item_cd = D.item_cd            
            LEFT JOIN {$this->_payment_table} AS Z ON A.payment_seq = Z.seq
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
     * @param $rows 조회건수
     * @param array $whereData 조회조건
     * @param string $order_field 정렬조건
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-11
     * @access
     */
    public function get_list_all($from_record, $rows, $whereData = array(), $order_field = "reg_desc")
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND B.".$whereData['date_type']." BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "order_status" :
                        $whereSql .= " AND ( ";
                        for ($s = 0; $s < count($value); $s++) {
                            if ($s == 0) {
                                $whereSql .= " LOCATE(" . $value[$s] . ", A.order_item_status ) > 0 ";
                            } else {
                                $whereSql .= " OR LOCATE(" . $value[$s] . ", A.order_item_status ) > 0 ";
                            } // End if
                        } // End for
                        $whereSql .= " ) ";
                        break;
                    case "grade" :
                        $whereSql .= " AND C.grade = '" . $whereData['grade'] . "' ";
                        break;
                    case "sch_str" :
                        switch ($whereData['sch_key']) {
                            case "login_id_s" :
                                $whereSql .= " AND C.login_id_s = '" . $this->secure->encrypt($value) . "' ";
                                break;
                            case "hp_s" :
                                $whereSql .= " AND C.hp_s = '" . $this->secure->encrypt($value) . "' ";
                                break;
                            case "nickname" :
                                $whereSql .= " AND C.nickname LIKE '%" . $value . "%' ";
                                break;
                            case "item_name" :
                                $whereSql .= " AND D.item_name LIKE '%" . $value . "%' ";
                                break;
                            case "item_cd" :
                                $whereSql .= " AND B.item_cd = '" . $value . "' ";
                                break;
                            case "order_cd" :
                                $whereSql .= " AND A.order_cd = '" . $value . "' ";
                                break;
                            case "delivery_number" :
                                $whereSql .= " AND B.delivery_number = '" . $value . "' ";
                                break;
                        } // End switch
                        break;
                    case "tab_status" :
                        $whereSql .= " AND LOCATE(" . $value . ", A.order_item_status ) > 0 ";
                        break;
                    case "coin_type" :
                        $whereSql .= " AND F.coin_type = '" . $value . "' ";
                        break;
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}'";
                        break;
                } // End switch
            } // End if
        } // End foreach

        //정렬 조건
        $order_by = "B.reg_date DESC";
        switch ($order_field) {
            case "reg_desc" :
                $order_by = "B.reg_date DESC";
                break;
            case "reg_asc" :
                $order_by = "B.reg_date ASC";
                break;
            case "payment_desc" :
                $order_by = "B.payment_date DESC";
                break;
            case "payment_asc" :
                $order_by = "B.payment_date ASC";
                break;
            case "ready_desc" :
                $order_by = "B.ready_date DESC";
                break;
            case "ready_asc" :
                $order_by = "B.ready_date ASC";
                break;
            case "ing_desc" :
                $order_by = "B.delivery_ing_date DESC";
                break;
            case "ing_asc" :
                $order_by = "B.delivery_ing_date ASC";
                break;
            case "end_desc" :
                $order_by = "B.delivery_end_date DESC";
                break;
            case "end_asc" :
                $order_by = "B.delivery_end_date ASC";
                break;
            case "hold_desc" :
                $order_by = "B.delivery_hold_date DESC";
                break;
            case "hold_asc" :
                $order_by = "B.delivery_hold_date ASC";
                break;
            case "refund_req_desc" :
                $order_by = "B.refund_req_date DESC";
                break;
            case "refund_req_asc" :
                $order_by = "B.refund_req_date ASC";
                break;
            case "refund_res_desc" :
                $order_by = "B.refund_res_date DESC";
                break;
            case "refund_res_asc" :
                $order_by = "B.refund_res_date ASC";
                break;
            case "cancel_req_desc" :
                $order_by = "B.cancel_req_date DESC";
                break;
            case "cancel_req_asc" :
                $order_by = "B.cancel_req_date ASC";
                break;
            case "cancel_res_desc" :
                $order_by = "B.cancel_end_date DESC";
                break;
            case "cancel_res_asc" :
                $order_by = "B.cancel_end_date ASC";
                break;
            case "item_name_desc" :
                $order_by = "D.item_name DESC, A.order_cd DESC";
                break;
            case "item_name_asc" :
                $order_by = "D.item_name ASC, A.order_cd DESC";
                break;
        } // End switch

        $sql = "
			SELECT
                A.*,
                B.item_count, B.sale_price, B.seq as item_seq,
			    B.item_status, B.ready_date, B.ready_ip, B.ready_admin_seq, B.delivery_ing_date, B.delivery_ing_ip, B.delivery_ing_admin_seq, B.delivery_end_date, B.delivery_end_ip, B.delivery_end_admin_seq,
                B.delivery_hold_date, B.delivery_hold_ip, B.delivery_hold_admin_seq, B.delivery_company, B.delivery_number, 
                B.exchange_req_date, B.exchange_req_ip, B.exchange_req_admin_seq, B.exchange_res_date, B.exchange_res_ip, B.exchange_res_admin_seq, 
                B.refund_req_date, B.refund_req_ip, B.refund_req_admin_seq, B.refund_res_date, B.refund_res_ip, B.refund_res_admin_seq, 
                B.cancel_req_date, B.cancel_req_ip, B.cancel_req_admin_seq, B.cancel_res_date, B.cancel_res_ip, B.cancel_res_admin_seq,
                C.login_id_s, C.international, C.hp_s, C.nickname, C.seq as member_seq, C.email_s,
			    D.item_name, D.unit_count, D.unit_name, D.stock_cd, D.release_count,                
			    Z.tid, Z.pg_name, Z.paymethod
            FROM {$this->_table} AS A 
            LEFT JOIN {$this->_order_item_table} AS B ON A.seq = B.order_seq AND B.is_exchange = 'N'
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq                
            LEFT JOIN {$this->_item_table} AS D ON B.item_cd = D.item_cd            
            LEFT JOIN {$this->_payment_table} AS Z ON A.payment_seq = Z.seq
			".$whereSql."
			order by {$order_by}
			limit ? , ?
		";
//        echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }


    public function get_not_receive_order($item_cd)
    {
        $sql = "
            SELECT 
                A.*
            FROM {$this->_table} AS A 
            LEFT JOIN {$this->_order_item_table} AS B ON A.seq = B.order_seq
            WHERE 1 = 1
            AND A.order_type = '2'
            AND A.order_status = '0'
            AND B.item_cd = ?
        ";
        $query = $this->db->query($sql, array($item_cd));
        $result = $query->result();

        return $result;
    }

    public function get_count_status_by_itemcd($item_cd, $status, $search_date)
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.item_cd = '".$item_cd."' ";
        $whereSql .= " AND B.order_status = '".$status."' ";

        switch( $status ):
            case "0" :
                $whereSql .= " AND B.order_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";
                break;
            case "1" :
                $whereSql .= " AND B.receive_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";
                break;
            case "5" :
            case "6" :
            case "7" :
                $whereSql .= " AND B.cancel_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";
                break;
        endswitch;

        $sql = "
			SELECT
                count(A.seq) as cnt
            FROM {$this->_order_item_table} AS A 
            LEFT JOIN {$this->_table} AS B ON A.order_seq = B.seq            
			".$whereSql."
		";
        //echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row->cnt;
        else:
            return 0;
        endif;
    }

    public function get_sum_order_point($item_cd, $search_date)
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.item_cd = '".$item_cd."' ";
        $whereSql .= " AND B.order_status = '0' ";
        $whereSql .= " AND B.order_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";

        $sql = "
			SELECT
                IFNULL(SUM(A.use_point), 0) AS sum_point
            FROM {$this->_order_item_table} AS A 
            LEFT JOIN {$this->_table} AS B ON A.order_seq = B.seq            
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row->sum_point;
        else:
            return 0;
        endif;
    }

    public function get_sum_cancel_point($item_cd, $search_date)
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.item_cd = '".$item_cd."' ";
        $whereSql .= " AND B.order_status IN ( '5', '6', '7' ) ";
        $whereSql .= " AND B.cancel_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";

        $sql = "
			SELECT
                IFNULL(SUM(A.use_point), 0) AS sum_point
            FROM {$this->_order_item_table} AS A 
            LEFT JOIN {$this->_table} AS B ON A.order_seq = B.seq            
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row->sum_point;
        else:
            return 0;
        endif;
    }

    public function get_count_status_by_receive_shopcd($shop_cd, $status, $search_date)
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.receive_shop_cd = '".$shop_cd."' ";
        $whereSql .= " AND A.order_status = '".$status."' ";

        switch( $status ):
            case "0" : // 미수령
                $whereSql .= " AND A.order_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";
                break;
            case "1" :  // 수령
                $whereSql .= " AND A.receive_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";
                break;
            case "6" :  // 지점취소
                $whereSql .= " AND A.cancel_date BETWEEN '".$search_date." 00:00:00' AND '".$search_date." 23:59:59' ";
                break;
        endswitch;

        $sql = "
			SELECT
                count(A.seq) as cnt
            FROM {$this->_table} AS A
			".$whereSql."
		";
        //echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row->cnt;
        else:
            return 0;
        endif;
    }

    public function get_order_by_seqs($order_seqs, $order_by_field = 'reg_desc')
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.seq IN (".$order_seqs.") ";

        //정렬 조건
        $order_by = "B.reg_date DESC";
        switch ($order_by_field) :
            case "reg_desc" : $order_by = "B.reg_date DESC"; break;
            case "reg_asc" : $order_by = "B.reg_date ASC"; break;
            case "payment_desc" : $order_by = "B.payment_date DESC"; break;
            case "payment_asc" : $order_by = "B.payment_date ASC"; break;
            case "ready_desc" : $order_by = "B.ready_date DESC"; break;
            case "ready_asc" : $order_by = "B.ready_date ASC"; break;
            case "ing_desc" : $order_by = "B.delivery_ing_date DESC"; break;
            case "ing_asc" : $order_by = "B.delivery_ing_date ASC"; break;
            case "end_desc" : $order_by = "B.delivery_end_date DESC"; break;
            case "end_asc" : $order_by = "B.delivery_end_date ASC"; break;
            case "hold_desc" : $order_by = "B.delivery_hold_date DESC"; break;
            case "hold_asc" : $order_by = "B.delivery_hold_date ASC"; break;
            case "refund_req_desc" : $order_by = "B.refund_req_date DESC"; break;
            case "refund_req_asc" : $order_by = "B.refund_req_date ASC"; break;
            case "refund_res_desc" : $order_by = "B.refund_res_date DESC"; break;
            case "refund_res_asc" : $order_by = "B.refund_res_date ASC"; break;
            case "cancel_req_desc" : $order_by = "B.cancel_req_date DESC"; break;
            case "cancel_req_asc" : $order_by = "B.cancel_req_date ASC"; break;
            case "cancel_res_desc" : $order_by = "B.cancel_end_date DESC"; break;
            case "cancel_res_asc" : $order_by = "B.cancel_end_date ASC"; break;
            case "item_name_desc" : $order_by = "E.item_name DESC, A.order_cd DESC"; break;
            case "item_name_asc" : $order_by = "E.item_name ASC, A.order_cd DESC"; break;
        endswitch;

        $sql = "
            SELECT
                A.*,
                C.international, C.hp_s, C.nickname, C.seq as member_seq, C.email_s
            FROM {$this->_table} AS A 
            LEFT JOIN {$this->_order_item_table} AS B ON A.seq = B.order_seq AND B.is_exchange = '0'
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq            
            LEFT JOIN {$this->_item_table} AS E ON B.item_cd = E.item_cd
            LEFT JOIN {$this->_payment_table} AS F ON A.payment_seq = F.seq
            ".$whereSql."
            order by {$order_by}           
        ";
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    /**
     * Function get_order_info
     *
     * 주문 마스터 정보 상세 조회
     *
     * @param $order_seq 주문 마스터 순번
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-11
     * @access
     */
    public function get_order_info($order_seq)
    {
        $sql = "
            SELECT
                A.*,
                C.login_id_s, C.international, C.hp_s, C.nickname, C.seq as member_seq, C.email_s
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq
            WHERE 1 = 1
            AND A.seq = ?                        
        ";
        $query = $this->db->query($sql, array($order_seq));
        $row = $query->row();

        return $row;
    }

    public function get_order_detail_by_order_cd($order_cd)
    {
        $sql = "
            SELECT
                A.*,
                B.pg_name, B.paymethod, B.amount,
                C.international, C.hp_s, C.nickname, C.seq as member_seq, C.email_s
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_payment_table} AS B ON A.order_cd = B.order_cd
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq
            WHERE 1 = 1
            AND A.order_cd = ?                        
        ";
        $query = $this->db->query($sql, array($order_cd));
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    //혜택 5. 소급 적용을 위한 쿼리
    public function get_profit_targets()
    {
        //SEQ 1 ( 개발자 ), 705 ( 마스터 ) 계정 제외
        $sql = "
            SELECT 
                A.member_seq, C.nickname, C.recommend_seq            
            FROM tbl_order AS A
            LEFT JOIN tbl_payment AS B ON A.payment_seq = B.seq
            LEFT JOIN tbl_member AS C ON A.member_seq = C.seq AND C.seq NOT IN ( '1', '705' )        
            WHERE A.payment_type = 'R'
            AND B.is_cancel = '0'
            AND C.recommend_seq > '0'
            ORDER BY C.recommend_seq ASC, A.member_seq ASC
        ";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    //
    public function get_count_by_status($whereData = array()){

        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND B.".$whereData['date_type']." BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        foreach($whereData as $key => $value):
            if($value != ""):
                switch($key):
                    case "order_status" :
                        //$whereSql .= " AND A.order_status IN ( '" . str_replace(",", "','", $value) ."' ) ";
                        $whereSql .= " AND ( ";
                        for ( $s = 0; $s < count($value); $s++ ):
                            if ( $s == 0 ):
                                $whereSql .= " LOCATE(".$value[$s].", A.order_item_status ) > 0 ";
                            else:
                                $whereSql .= " OR LOCATE(".$value[$s].", A.order_item_status ) > 0 ";
                            endif;
                        endfor;
                        $whereSql .= " ) ";
                        break;
                    case "grade" :
                        $whereSql .= " AND C.grade = '".$whereData['grade']."' ";
                        break;
                    case "sch_str" :
                        switch ( $whereData['sch_key'] ):
                            case "hp_s" :
                                $whereSql .= " AND C.hp_s = '".$this->secure->encrypt($value)."' ";
                                break;
                            case "nickname" :
                                $whereSql .= " AND C.nickname LIKE '%".$value."%' ";
                                break;
                            case "item_name" :
                                $whereSql .= " AND E.item_name LIKE '%".$value."%' ";
                                break;
                            case "item_cd" :
                                $whereSql .= " AND B.item_cd = '".$value."' ";
                                break;
                            case "order_cd" :
                                $whereSql .= " AND A.order_cd = '".$value."' ";
                                break;
                            case "delivery_number" :
                                $whereSql .= " AND B.delivery_number = '".$value."' ";
                                break;
                        endswitch;
                        break;
                    case "tab_status" :
                        //$whereSql .= " AND LOCATE(".$value.", A.order_item_status ) > 0 ";
                        break;
                    case "coin_type" :
                        $whereSql .= " AND F.coin_type = '".$value."' ";
                        break;
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}'";
                        break;
                endswitch;
            endif;
        endforeach;

        //[10]미입금,[15]결제완료,[20]상품준비중,[21]배송중,[25]배송완료,[29]배송보류,[30]교환요청,[35]교환완료,[40]환불요청,[45]환불완료],[50]취소요청,[55]취소완료
        $sql = "
			SELECT		    		
			    COUNT(A.seq) AS Cnt,	    
                SUM(CASE WHEN LOCATE('10', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_10,
                SUM(CASE WHEN LOCATE('15', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_15,
                SUM(CASE WHEN LOCATE('20', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_20,
                SUM(CASE WHEN LOCATE('21', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_21,
                SUM(CASE WHEN LOCATE('25', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_25,
                SUM(CASE WHEN LOCATE('29', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_29,
                SUM(CASE WHEN LOCATE('30', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_30,
                SUM(CASE WHEN LOCATE('35', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_35,
                SUM(CASE WHEN LOCATE('40', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_40,
                SUM(CASE WHEN LOCATE('45', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_45,
                SUM(CASE WHEN LOCATE('50', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_50,
                SUM(CASE WHEN LOCATE('55', A.order_item_status) > 0 THEN 1 ELSE 0 END) AS status_55			    
			FROM {$this->_table} AS A
            LEFT JOIN {$this->_order_item_table} AS B ON A.seq = B.order_seq AND B.is_exchange = 'N'
            LEFT JOIN {$this->_member_table} AS C ON A.member_seq = C.seq            
            LEFT JOIN {$this->_item_table} AS E ON B.item_cd = E.item_cd
            LEFT JOIN {$this->_payment_table} AS F ON A.payment_seq = F.seq			
			".$whereSql."			
		";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }
}