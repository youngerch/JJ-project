<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-11
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Statistics_model extends MY_Model
{
    public $_stat_member_table  = __TABLE_PREFIX__ . "stat_member";
    public $_stat_shop_table    = __TABLE_PREFIX__ . "stat_shop";
    public $_stat_product_table = __TABLE_PREFIX__ . "stat_product";
    public $_stat_payment_table = __TABLE_PREFIX__ . "stat_payment";
    public $_stat_stargrade_table = __TABLE_PREFIX__ . "stat_stargrade";

    public $_item_table         = __TABLE_PREFIX__ . "item";
    public $_shop_table         = __TABLE_PREFIX__ . "shop";
    public $_payment_table      = __TABLE_PREFIX__ . "payment";
    public $_order_table        = __TABLE_PREFIX__ . "order";
    public $_point_table        = __TABLE_PREFIX__ . "point";
    public $_coin_table         = __TABLE_PREFIX__ . "coin";
    public $primary_key         = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_member($search_type, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.stat_date BETWEEN '" . $whereData['date_from'] . "' AND '" . $whereData['date_to'] . "' ";

        if ($search_type == "1") :
            $sql = "
                SELECT
                    *
                FROM {$this->_stat_member_table} AS A 
                " . $whereSql . "
                order by A.stat_date ASC			
            ";
        else:
            $sql = "
                SELECT
                    *
                FROM {$this->_stat_member_table} AS A 
                " . $whereSql . "
                GROUP BY SUBSTR(stat_date, 1, 7)
                ORDER BY SUBSTR(stat_date, 1, 7) ASC	
            ";
        endif;
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function set_member($insertData)
    {
        $this->db->insert($this->_stat_member_table, $insertData);

        return $this->db->insert_id();
    }

    public function get_product($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND stat_date BETWEEN '" . $whereData['date_from'] . "' AND '" . $whereData['date_to'] . "' ";

        $sql = "
            SELECT 
                B.item_name,
                B.status,
                A.*
            FROM ( 	
                SELECT
                    item_cd,
                    IFNULL(SUM(order_cnt), 0) AS order_cnt,
                    IFNULL(SUM(receive_cnt), 0) AS receive_cnt,
                    IFNULL(SUM(user_cancel_cnt), 0) AS user_cancel_cnt,
                    IFNULL(SUM(shop_cancel_cnt), 0) AS shop_cancel_cnt,
                    IFNULL(SUM(cs_cancel_cnt), 0) AS cs_cancel_cnt,
                    IFNULL(SUM(order_point), 0) AS order_point,
                    IFNULL(SUM(cancel_point), 0) AS cancel_point
                FROM {$this->_stat_product_table}
                {$whereSql}
                GROUP BY item_cd
            ) AS A
            LEFT JOIN {$this->_item_table} AS B ON A.item_cd = B.item_cd
        ";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function set_product($insertData)
    {
        $this->db->insert($this->_stat_product_table, $insertData);

        return $this->db->insert_id();
    }

    public function get_shop($search_type, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.stat_date BETWEEN '" . $whereData['date_from'] . "' AND '" . $whereData['date_to'] . "' ";

        if ($search_type == "1") :
            $sql = "
                SELECT
                    stat_date,
                    IFNULL(SUM(point_earn), 0) AS point_earn,
                    IFNULL(SUM(point_return), 0) AS point_return,
                    IFNULL(SUM(receive_cnt), 0) AS receive_cnt,
                    IFNULL(SUM(cancel_cnt), 0) AS cancel_cnt                    
                FROM {$this->_stat_shop_table} AS A 
                " . $whereSql . "
                GROUP BY A.stat_date
                ORDER BY A.stat_date ASC			
            ";
        else:
            $sql = "
                SELECT
                    SUBSTR(stat_date, 1, 7) AS stat_date,
                    IFNULL(SUM(point_earn), 0) AS point_earn,
                    IFNULL(SUM(point_return), 0) AS point_return,
                    IFNULL(SUM(receive_cnt), 0) AS receive_cnt,
                    IFNULL(SUM(cancel_cnt), 0) AS cancel_cnt  
                FROM {$this->_stat_shop_table} AS A 
                " . $whereSql . "
                GROUP BY SUBSTR(stat_date, 1, 7)
                ORDER BY SUBSTR(stat_date, 1, 7) ASC	
            ";
        endif;
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function get_by_shop($shop_cd, $search_type, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND stat_date BETWEEN '" . $whereData['date_from'] . "' AND '" . $whereData['date_to'] . "' ";

        if ( $shop_cd != "" ):
            $whereSql .= " AND shop_cd = '".$shop_cd."' ";
        endif;

        if ($search_type == "1") :
            $sql = "
                SELECT
                    A.*,
                    B.shop_name
                FROM (
                    SELECT
                        stat_date, shop_cd,
                        IFNULL(SUM(point_earn), 0) AS point_earn,
                        IFNULL(SUM(point_return), 0) AS point_return,
                        IFNULL(SUM(receive_cnt), 0) AS receive_cnt,
                        IFNULL(SUM(cancel_cnt), 0) AS cancel_cnt                    
                    FROM {$this->_stat_shop_table} 
                    " . $whereSql . "
                    GROUP BY stat_date, shop_cd
                ) AS A
                LEFT JOIN {$this->_shop_table} AS B ON A.shop_cd = B.shop_cd
                ORDER BY A.stat_date ASC, B.shop_name ASC			
            ";
        else:
            $sql = "
                SELECT
                    A.*,
                    B.shop_name
                FROM (
                    SELECT
                        SUBSTR(stat_date, 1, 7) AS stat_date,
                        shop_cd,
                        IFNULL(SUM(point_earn), 0) AS point_earn,
                        IFNULL(SUM(point_return), 0) AS point_return,
                        IFNULL(SUM(receive_cnt), 0) AS receive_cnt,
                        IFNULL(SUM(cancel_cnt), 0) AS cancel_cnt  
                    FROM {$this->_stat_shop_table} AS A 
                    " . $whereSql . "
                    GROUP BY SUBSTR(stat_date, 1, 7), shop_cd
                ) AS A
                LEFT JOIN {$this->_shop_table} AS B ON A.shop_cd = B.shop_cd    
                ORDER BY A.stat_date ASC, B.shop_name ASC
            ";
        endif;
//        echo "<pre>";
//        echo $sql;
//        echo "</pre>";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function set_shop($insertData)
    {
        $this->db->insert($this->_stat_shop_table, $insertData);

        return $this->db->insert_id();
    }

    //결제 정보
    public function get_payment($stat_date, $is_cancel)
    {
        if ( $is_cancel == "1" ):
            $sql = "
                SELECT
                    IFNULL(SUM(CASE WHEN pg_name = 'INNOPAY' AND paymethod = 'CARD' THEN amount ELSE 0 END), 0) AS innopay_card_cancel,	
                    IFNULL(SUM(CASE WHEN pg_name = 'DANAL' AND paymethod = 'CARD' THEN amount ELSE 0 END), 0) AS danal_card_cancel,	
                    IFNULL(SUM(CASE WHEN pg_name = 'DANAL' AND paymethod = 'TELEDIT' THEN amount ELSE 0 END), 0) AS danal_teledit_cancel
                FROM {$this->_payment_table}
                WHERE 1 = 1
                AND cancel_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'
                AND is_cancel = '1'
            ";
        else:
            $sql = "
                SELECT
                    IFNULL(SUM(CASE WHEN pg_name = 'INNOPAY' AND paymethod = 'CARD' THEN amount ELSE 0 END), 0) AS innopay_card_payment,	
                    IFNULL(SUM(CASE WHEN pg_name = 'DANAL' AND paymethod = 'CARD' THEN amount ELSE 0 END), 0) AS danal_card_payment,	
                    IFNULL(SUM(CASE WHEN pg_name = 'DANAL' AND paymethod = 'TELEDIT' THEN amount ELSE 0 END), 0) AS danal_teledit_payment	
                FROM {$this->_payment_table}
                WHERE 1 = 1
                AND reg_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'
                AND is_cancel = '0'
            ";
        endif;

        //echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    //결제 상세 정보
    public function get_payment_detail($stat_date, $is_cancel)
    {
        if ( $is_cancel == "1" ):
            $sql = "
                SELECT 
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_REGULAR_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_REGULAR_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_YEARLY_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_YEARLY_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_NORMAL_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_NORMAL_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_COUPON_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_COUPON_COUNT,   
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_REGULAR_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_REGULAR_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_YEARLY_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_YEARLY_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_NORMAL_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_NORMAL_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_COUPON_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_COUPON_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'R' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_REGULAR_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'R' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_REGULAR_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'Y' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_YEARLY_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'Y' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_YEARLY_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'N' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_NORMAL_AMOUNT,	
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'N' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_NORMAL_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND A.goodsName = '쿠폰선물' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_COUPON_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND A.goodsName = '쿠폰선물' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_COUPON_COUNT		
                FROM {$this->_payment_table} AS A
                LEFT OUTER JOIN {$this->_order_table} AS B ON A.order_cd = B.order_cd AND B.order_type = '3' AND B.order_payment = '5'    
                WHERE 1 = 1
                AND A.cancel_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'
                AND A.is_cancel = '1'
            ";
        else:
            $sql = "
                SELECT 
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_REGULAR_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_REGULAR_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_YEARLY_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_YEARLY_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_NORMAL_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_NORMAL_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN A.amount ELSE 0 END), 0) AS INNOPAY_CARD_COUPON_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'INNOPAY' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN 1 ELSE 0 END), 0) AS INNOPAY_CARD_COUPON_COUNT,   
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_REGULAR_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'R' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_REGULAR_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_YEARLY_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'Y' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_YEARLY_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_NORMAL_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND B.payment_type = 'N' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_NORMAL_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN A.amount ELSE 0 END), 0) AS DANAL_CARD_COUPON_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'CARD' AND A.goodsName = '쿠폰선물' THEN 1 ELSE 0 END), 0) AS DANAL_CARD_COUPON_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'R' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_REGULAR_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'R' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_REGULAR_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'Y' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_YEARLY_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'Y' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_YEARLY_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'N' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_NORMAL_AMOUNT,	
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND B.payment_type = 'N' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_NORMAL_COUNT,                    
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND A.goodsName = '쿠폰선물' THEN A.amount ELSE 0 END), 0) AS DANAL_TELEDIT_COUPON_AMOUNT,
                    IFNULL(SUM(CASE WHEN A.pg_name = 'DANAL' AND A.paymethod = 'TELEDIT' AND A.goodsName = '쿠폰선물' THEN 1 ELSE 0 END), 0) AS DANAL_TELEDIT_COUPON_COUNT		
                FROM {$this->_payment_table} AS A
                LEFT OUTER JOIN {$this->_order_table} AS B ON A.order_cd = B.order_cd AND B.order_type = '3' AND B.order_payment = '5'
                WHERE 1 = 1
                AND A.reg_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'                
            ";
        endif;

        //echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    //코인 정보
    public function get_coin($stat_date, $is_withdraw)
    {
        if ( $is_withdraw == "1" ): //출금
            // 2021.05.04 - 김창수 수정
            // 기존 출금 완료 건에 한해서만 출금 통계로 집계
            $sql = "
                SELECT
                    IFNULL(SUM(CASE WHEN coin_type = 'GAME' AND withdraw_trade_num IS NOT NULL THEN amount ELSE 0 END), 0) AS game_coin_withdraw,	
                    IFNULL(SUM(CASE WHEN coin_type = 'GAME' AND withdraw_trade_num IS NOT NULL THEN withdraw_fee ELSE 0 END), 0) AS game_coin_withdraw_fee,                       
                    IFNULL(SUM(CASE WHEN coin_type = 'ICF' AND withdraw_trade_num IS NOT NULL THEN amount ELSE 0 END), 0) AS icf_coin_withdraw,	
                    IFNULL(SUM(CASE WHEN coin_type = 'ICF' AND withdraw_trade_num IS NOT NULL THEN withdraw_fee ELSE 0 END), 0) AS icf_coin_withdraw_fee,
                    IFNULL(SUM(CASE WHEN coin_type = 'CIEL' AND withdraw_trade_num IS NOT NULL THEN amount ELSE 0 END), 0) AS ciel_coin_withdraw,	
                    IFNULL(SUM(CASE WHEN coin_type = 'CIEL' AND withdraw_trade_num IS NOT NULL THEN withdraw_fee ELSE 0 END), 0) AS ciel_coin_withdraw_fee,                       
                    IFNULL(SUM(CASE WHEN coin_type = 'KRT' AND withdraw_trade_num IS NOT NULL THEN amount ELSE 0 END), 0) AS krt_coin_withdraw,	
                    IFNULL(SUM(CASE WHEN coin_type = 'KRT' AND withdraw_trade_num IS NOT NULL THEN withdraw_fee ELSE 0 END), 0) AS krt_coin_withdraw_fee	                
                FROM {$this->_coin_table}
                WHERE 1 = 1
                AND res_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'
                AND inout_type = '9'
                AND member_seq <> '705'
                AND withdraw_trade_num IS NOT NULL
                AND coin_status = '3'
            ";
        else:   //적립
            // 2021.05.04 - 김창수 수정
            // 출금 거절을 통해서 적립된 inout_class[28]를 제외한 적립 내용만으로 집계
            $sql = "
                SELECT
                    IFNULL(SUM(CASE WHEN coin_type = 'GAME' THEN amount ELSE 0 END), 0) AS game_coin_deposit,
                    IFNULL(SUM(CASE WHEN coin_type = 'ICF' THEN amount ELSE 0 END), 0) AS icf_coin_deposit,
                    IFNULL(SUM(CASE WHEN coin_type = 'CIEL' THEN amount ELSE 0 END), 0) AS ciel_coin_deposit,
                    IFNULL(SUM(CASE WHEN coin_type = 'KRT' THEN amount ELSE 0 END), 0) AS krt_coin_deposit                    	
                FROM {$this->_coin_table}
                WHERE 1 = 1
                AND reg_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'
                AND inout_type = '1'
                AND member_seq <> '705'
                AND inout_class <> '28'
                AND coin_status = '3'
            ";
        endif;
        //echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    //포인트 정보
    public function get_point($stat_date)
    {
        $sql = "
                SELECT
                    IFNULL(SUM(CASE WHEN point_type = '1' THEN order_point ELSE 0 END), 0) AS point_deposit,
                    IFNULL(SUM(CASE WHEN point_type = '2' THEN order_point ELSE 0 END), 0) AS point_withdraw	
                FROM {$this->_point_table}
                WHERE 1 = 1
                AND reg_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'                
            ";
        //echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    //통계정보 등록
    public function set_stat_payment($insertData)
    {
        $this->db->insert($this->_stat_payment_table, $insertData);

        return $this->db->insert_id();
    }

    //스타등급 통계 등록
    public function set_stargrade($insertData)
    {
        $this->db->insert($this->_stat_stargrade_table, $insertData);

        return $this->db->insert_id();
    }

}