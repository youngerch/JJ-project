<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-09-03
 */

/**
 * Class Order_item_model
 *
 * 주문 아이템 정보
 *
 * Created on 2020-09-03
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Order_item_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "order_item";      // 주문 상품 정보
    public $_order_table        = __TABLE_PREFIX__ . "order";           // 주문 정보
    public $_item_table         = __TABLE_PREFIX__ . "item";            // 상품 정보
    public $_item_country_table = __TABLE_PREFIX__ . "item_country";    // 배송국가별 상품 정보
    public $_item_content_table = __TABLE_PREFIX__ . "item_content";    // 언어별 상품 정보
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_order_item_by_order_seq($order_seq)
    {
        $sql = "
            SELECT
                A.*, 
                C.unit_count, C.unit_name, C.stock_cd, C.release_count, C.item_name
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_order_table} AS B ON A.order_seq = B.seq
            LEFT JOIN {$this->_item_table} AS C ON A.item_cd = C.item_cd
            WHERE 1 = 1
            AND A.is_exchange = 'N'
            AND A.order_seq = ?
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($order_seq));
        $result = $query->result();

        return $result;
    }

    public function get_order_item_by_seq($seq)
    {
        $sql = "
            SELECT
                A.*, 
                B.item_name, B.unit_name, B.unit_count, B.stock_cd, B.total_warehousing_count,
                C.order_cd
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_item_table} AS B ON A.item_cd = B.item_cd
            LEFT JOIN {$this->_order_table} AS C ON A.order_seq = C.seq
            WHERE 1 = 1
            AND A.seq = ?
        ";
        $query = $this->db->query($sql, array($seq));
        $result = $query->row();

        return $result;
    }

    public function get_count_by_status($whereData = array()){

        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND B.order_type IN ( '2', '3' ) ";

        //[10]미입금,[15]결제완료,[20]상품준비중,[21]배송중,[25]배송완료,[29]배송보류,[30]교환요청,[35]교환완료,[40]환불요청,[45]환불완료],[50]취소요청,[55]취소완료
        $sql = "
			SELECT		    		
			    COUNT(A.seq) AS Cnt,	    
                SUM(CASE WHEN A.item_status = '10' THEN 1 ELSE 0 END) AS status_10,
                SUM(CASE WHEN A.item_status = '15' THEN 1 ELSE 0 END) AS status_15,
                SUM(CASE WHEN A.item_status = '20' THEN 1 ELSE 0 END) AS status_20,
                SUM(CASE WHEN A.item_status = '21' THEN 1 ELSE 0 END) AS status_21,
                SUM(CASE WHEN A.item_status = '25' THEN 1 ELSE 0 END) AS status_25,
                SUM(CASE WHEN A.item_status = '29' THEN 1 ELSE 0 END) AS status_29,
                SUM(CASE WHEN A.item_status = '30' THEN 1 ELSE 0 END) AS status_30,
                SUM(CASE WHEN A.item_status = '35' THEN 1 ELSE 0 END) AS status_35,
                SUM(CASE WHEN A.item_status = '40' THEN 1 ELSE 0 END) AS status_40,
                SUM(CASE WHEN A.item_status = '45' THEN 1 ELSE 0 END) AS status_45,
                SUM(CASE WHEN A.item_status = '50' THEN 1 ELSE 0 END) AS status_50,
                SUM(CASE WHEN A.item_status = '55' THEN 1 ELSE 0 END) AS status_55			    
			FROM {$this->_table} AS A 
            LEFT JOIN {$this->_order_table} AS B ON A.order_seq = B.seq            
			".$whereSql."			
		";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function get_order_item_by_exchange($order_seq)
    {
        $sql = "            
            SELECT
                A.*, 
                C.unit_count, C.unit_name, C.stock_cd, C.release_count, C.item_name
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_order_table} AS B ON A.order_seq = B.seq
            LEFT JOIN {$this->_item_table} AS C ON A.item_cd = C.item_cd
            WHERE 1 = 1
            AND A.is_exchange = 'Y'
            AND A.order_seq = ?
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($order_seq));
        $result = $query->result();

        return $result;
    }

    public function get_group_concat_order_item_status($order_seq)
    {
        $sql = "
            SELECT GROUP_CONCAT(item_status ORDER BY seq) AS order_item_status
            FROM {$this->_table}
            WHERE order_seq = ?
        ";
        $query = $this->db->query($sql, array($order_seq));
        $row = $query->row();

        if ( $row ):
            return $row->order_item_status;
        else:
            return false;
        endif;
    }

    //배송중인 상품 list 조회
    public function get_order_item_all($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "item_status" :
                        $whereSql .= " AND item_status = '" . $whereData['item_status'] . "' ";
                        break;
                    case "delivery_company" :
                        $whereSql .= " AND delivery_company = '" . $whereData['delivery_company'] . "' ";
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$value}'";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
            SELECT 
                A.*,
                B.item_name
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_item_table} AS B ON A.item_cd = B.item_cd
            ".$whereSql."
        ";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

}