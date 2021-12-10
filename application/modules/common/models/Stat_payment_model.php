<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-11
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Stat_payment_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "stat_payment";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_count_all($whereData = array()){

        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.stat_date BETWEEN '".$whereData['date_start']."' AND '".$whereData['date_end']."' ";

        $sql = "
			SELECT
                count(A.seq) as cnt
            FROM {$this->_table} AS A 
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }

    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A.stat_date BETWEEN '".$whereData['date_start']."' AND '".$whereData['date_end']."' ";

        $sql = "
			SELECT
                A.*
            FROM {$this->_table} AS A
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_list_by_year($date_start, $date_end)
    {
        /*
        $sql = "
            SELECT
                SUBSTR(stat_date, 1, 4) AS st_date,
                IFNULL(SUM(innopay_card_payment), 0) AS innopay_card_payment_sum,
                IFNULL(SUM(innopay_card_cancel), 0) AS innopay_card_cancel_sum,
                IFNULL(SUM(danal_card_payment), 0) AS danal_card_payment_sum,
                IFNULL(SUM(danal_card_cancel), 0) AS danal_card_cancel_sum,
                IFNULL(SUM(danal_teledit_payment), 0) AS danal_teledit_payment_sum,
                IFNULL(SUM(danal_teledit_cancel), 0) AS danal_teledit_cancel_sum,
                IFNULL(SUM(krt_coin_deposit), 0) AS krt_coin_deposit_sum,
                IFNULL(SUM(krt_coin_withdraw), 0) AS krt_coin_withdraw_sum,
                IFNULL(SUM(krt_coin_withdraw_fee), 0) AS krt_coin_withdraw_fee_sum,
                IFNULL(SUM(game_coin_deposit), 0) AS game_coin_deposit_sum,
                IFNULL(SUM(game_coin_withdraw), 0) AS game_coin_withdraw_sum,
                IFNULL(SUM(game_coin_withdraw_fee), 0) AS game_coin_withdraw_fee_sum,
                IFNULL(SUM(ciel_coin_deposit), 0) AS ciel_coin_deposit_sum,
                IFNULL(SUM(ciel_coin_withdraw), 0) AS ciel_coin_withdraw_sum,
                IFNULL(SUM(ciel_coin_withdraw_fee), 0) AS ciel_coin_withdraw_fee_sum,
                IFNULL(SUM(icf_coin_deposit), 0) AS icf_coin_deposit_sum,
                IFNULL(SUM(icf_coin_withdraw), 0) AS icf_coin_withdraw_sum,
                IFNULL(SUM(icf_coin_withdraw_fee), 0) AS icf_coin_withdraw_fee_sum,
                IFNULL(SUM(point_deposit), 0) AS point_deposit_sum,
                IFNULL(SUM(point_withdraw), 0) AS point_withdraw_sum
            FROM tbl_stat_payment
            WHERE SUBSTR(stat_date, 1, 4) BETWEEN ? AND ?
            GROUP BY SUBSTR(stat_date, 1, 4)
            ORDER BY SUBSTR(stat_date, 1, 4) DESC
        ";
        */
        $sql = "
            SELECT
                SUBSTR(stat_date, 1, 4) AS st_date,
                IFNULL(SUM(innopay_card_payment), 0) AS innopay_card_payment_sum,
                IFNULL(SUM(innopay_card_cancel), 0) AS innopay_card_cancel_sum,
                IFNULL(SUM(danal_card_payment), 0) AS danal_card_payment_sum,
                IFNULL(SUM(danal_card_cancel), 0) AS danal_card_cancel_sum,
                IFNULL(SUM(danal_teledit_payment), 0) AS danal_teledit_payment_sum,
                IFNULL(SUM(danal_teledit_cancel), 0) AS danal_teledit_cancel_sum,
                IFNULL(SUM(icp_regular_amount), 0) AS icp_regular_amount_sum,
                IFNULL(SUM(icp_regular_count), 0) AS icp_regular_count_sum,
                IFNULL(SUM(icp_yearly_amount), 0) AS icp_yearly_amount_sum,
                IFNULL(SUM(icp_yearly_count), 0) AS icp_yearly_count_sum,
                IFNULL(SUM(icp_normal_amount), 0) AS icp_normal_amount_sum,
                IFNULL(SUM(icp_normal_count), 0) AS icp_normal_count_sum,
                IFNULL(SUM(icp_coupon_amount), 0) AS icp_coupon_amount_sum,
                IFNULL(SUM(icp_coupon_count), 0) AS icp_coupon_count_sum,
                IFNULL(SUM(icc_regular_amount), 0) AS icc_regular_amount_sum,
                IFNULL(SUM(icc_regular_count), 0) AS icc_regular_count_sum,
                IFNULL(SUM(icc_yearly_amount), 0) AS icc_yearly_amount_sum,
                IFNULL(SUM(icc_yearly_count), 0) AS icc_yearly_count_sum,
                IFNULL(SUM(icc_normal_amount), 0) AS icc_normal_amount_sum,
                IFNULL(SUM(icc_normal_count), 0) AS icc_normal_count_sum,
                IFNULL(SUM(icc_coupon_amount), 0) AS icc_coupon_amount_sum,
                IFNULL(SUM(icc_coupon_count), 0) AS icc_coupon_count_sum,
                IFNULL(SUM(dcp_regular_amount), 0) AS dcp_regular_amount_sum, 
                IFNULL(SUM(dcp_regular_count), 0) AS dcp_regular_count_sum, 
                IFNULL(SUM(dcp_yearly_amount), 0) AS dcp_yearly_amount_sum,
                IFNULL(SUM(dcp_yearly_count), 0) AS dcp_yearly_count_sum,
                IFNULL(SUM(dcp_normal_amount), 0) AS dcp_normal_amount_sum,
                IFNULL(SUM(dcp_normal_count), 0) AS dcp_normal_count_sum,
                IFNULL(SUM(dcp_coupon_amount), 0) AS dcp_coupon_amount_sum,
                IFNULL(SUM(dcp_coupon_count), 0) AS dcp_coupon_count_sum,
                IFNULL(SUM(dcc_regular_amount), 0) AS dcc_regular_amount_sum,
                IFNULL(SUM(dcc_regular_count), 0) AS dcc_regular_count_sum,
                IFNULL(SUM(dcc_yearly_amount), 0) AS dcc_yearly_amount_sum,
                IFNULL(SUM(dcc_yearly_count), 0) AS dcc_yearly_count_sum, 
                IFNULL(SUM(dcc_normal_amount), 0) AS dcc_normal_amount_sum,
                IFNULL(SUM(dcc_normal_count), 0) AS dcc_normal_count_sum,
                IFNULL(SUM(dcc_coupon_amount), 0) AS dcc_coupon_amount_sum,
                IFNULL(SUM(dcc_coupon_count), 0) AS dcc_coupon_count_sum,
                IFNULL(SUM(dtp_regular_amount), 0) AS dtp_regular_amount_sum,
                IFNULL(SUM(dtp_regular_count), 0) AS dtp_regular_count_sum,
                IFNULL(SUM(dtp_yearly_amount), 0) AS dtp_yearly_amount_sum,
                IFNULL(SUM(dtp_yearly_count), 0) AS dtp_yearly_count_sum,
                IFNULL(SUM(dtp_normal_amount), 0) AS dtp_normal_amount_sum,
                IFNULL(SUM(dtp_normal_count), 0) AS dtp_normal_count_sum,
                IFNULL(SUM(dtp_coupon_amount), 0) AS dtp_coupon_amount_sum,
                IFNULL(SUM(dtp_coupon_count), 0) AS dtp_coupon_count_sum,
                IFNULL(SUM(dtc_regular_amount), 0) AS dtc_regular_amount_sum,
                IFNULL(SUM(dtc_regular_count), 0) AS dtc_regular_count_sum,
                IFNULL(SUM(dtc_yearly_amount), 0) AS dtc_yearly_amount_sum,
                IFNULL(SUM(dtc_yearly_count), 0) AS dtc_yearly_count_sum,
                IFNULL(SUM(dtc_normal_amount), 0) AS dtc_normal_amount_sum,
                IFNULL(SUM(dtc_normal_count), 0) AS dtc_normal_count_sum,
                IFNULL(SUM(dtc_coupon_amount), 0) AS dtc_coupon_amount_sum,
                IFNULL(SUM(dtc_coupon_count), 0) AS dtc_coupon_count_sum,
                IFNULL(SUM(krt_coin_deposit), 0) AS krt_coin_deposit_sum,
                IFNULL(SUM(krt_coin_withdraw), 0) AS krt_coin_withdraw_sum,
                IFNULL(SUM(krt_coin_withdraw_fee), 0) AS krt_coin_withdraw_fee_sum,
                IFNULL(SUM(game_coin_deposit), 0) AS game_coin_deposit_sum,
                IFNULL(SUM(game_coin_withdraw), 0) AS game_coin_withdraw_sum,
                IFNULL(SUM(game_coin_withdraw_fee), 0) AS game_coin_withdraw_fee_sum,
                IFNULL(SUM(ciel_coin_deposit), 0) AS ciel_coin_deposit_sum,
                IFNULL(SUM(ciel_coin_withdraw), 0) AS ciel_coin_withdraw_sum,
                IFNULL(SUM(ciel_coin_withdraw_fee), 0) AS ciel_coin_withdraw_fee_sum,
                IFNULL(SUM(icf_coin_deposit), 0) AS icf_coin_deposit_sum,
                IFNULL(SUM(icf_coin_withdraw), 0) AS icf_coin_withdraw_sum,
                IFNULL(SUM(icf_coin_withdraw_fee), 0) AS icf_coin_withdraw_fee_sum,
                IFNULL(SUM(point_deposit), 0) AS point_deposit_sum,
                IFNULL(SUM(point_withdraw), 0) AS point_withdraw_sum
            FROM tbl_stat_payment
            WHERE SUBSTR(stat_date, 1, 4) BETWEEN ? AND ?
            GROUP BY SUBSTR(stat_date, 1, 4)
            ORDER BY SUBSTR(stat_date, 1, 4) DESC
        ";
        //var_dump($sql);
        //var_dump(array($date_start, $date_end));
        $query = $this->db->query($sql, array($date_start, $date_end));
        $result = $query->result();

        return $result;
    }

    public function get_list_by_month($date_start, $date_end)
    {
        $sql = "
            SELECT
                SUBSTR(stat_date, 1, 7) AS st_date,
                IFNULL(SUM(innopay_card_payment), 0) AS innopay_card_payment_sum,
                IFNULL(SUM(innopay_card_cancel), 0) AS innopay_card_cancel_sum,
                IFNULL(SUM(danal_card_payment), 0) AS danal_card_payment_sum,
                IFNULL(SUM(danal_card_cancel), 0) AS danal_card_cancel_sum,
                IFNULL(SUM(danal_teledit_payment), 0) AS danal_teledit_payment_sum,
                IFNULL(SUM(danal_teledit_cancel), 0) AS danal_teledit_cancel_sum,
                IFNULL(SUM(icp_regular_amount), 0) AS icp_regular_amount_sum,
                IFNULL(SUM(icp_regular_count), 0) AS icp_regular_count_sum,
                IFNULL(SUM(icp_yearly_amount), 0) AS icp_yearly_amount_sum,
                IFNULL(SUM(icp_yearly_count), 0) AS icp_yearly_count_sum,
                IFNULL(SUM(icp_normal_amount), 0) AS icp_normal_amount_sum,
                IFNULL(SUM(icp_normal_count), 0) AS icp_normal_count_sum,
                IFNULL(SUM(icp_coupon_amount), 0) AS icp_coupon_amount_sum,
                IFNULL(SUM(icp_coupon_count), 0) AS icp_coupon_count_sum,
                IFNULL(SUM(icc_regular_amount), 0) AS icc_regular_amount_sum,
                IFNULL(SUM(icc_regular_count), 0) AS icc_regular_count_sum,
                IFNULL(SUM(icc_yearly_amount), 0) AS icc_yearly_amount_sum,
                IFNULL(SUM(icc_yearly_count), 0) AS icc_yearly_count_sum,
                IFNULL(SUM(icc_normal_amount), 0) AS icc_normal_amount_sum,
                IFNULL(SUM(icc_normal_count), 0) AS icc_normal_count_sum,
                IFNULL(SUM(icc_coupon_amount), 0) AS icc_coupon_amount_sum,
                IFNULL(SUM(icc_coupon_count), 0) AS icc_coupon_count_sum,
                IFNULL(SUM(dcp_regular_amount), 0) AS dcp_regular_amount_sum, 
                IFNULL(SUM(dcp_regular_count), 0) AS dcp_regular_count_sum, 
                IFNULL(SUM(dcp_yearly_amount), 0) AS dcp_yearly_amount_sum,
                IFNULL(SUM(dcp_yearly_count), 0) AS dcp_yearly_count_sum,
                IFNULL(SUM(dcp_normal_amount), 0) AS dcp_normal_amount_sum,
                IFNULL(SUM(dcp_normal_count), 0) AS dcp_normal_count_sum,
                IFNULL(SUM(dcp_coupon_amount), 0) AS dcp_coupon_amount_sum,
                IFNULL(SUM(dcp_coupon_count), 0) AS dcp_coupon_count_sum,
                IFNULL(SUM(dcc_regular_amount), 0) AS dcc_regular_amount_sum,
                IFNULL(SUM(dcc_regular_count), 0) AS dcc_regular_count_sum,
                IFNULL(SUM(dcc_yearly_amount), 0) AS dcc_yearly_amount_sum,
                IFNULL(SUM(dcc_yearly_count), 0) AS dcc_yearly_count_sum, 
                IFNULL(SUM(dcc_normal_amount), 0) AS dcc_normal_amount_sum,
                IFNULL(SUM(dcc_normal_count), 0) AS dcc_normal_count_sum,
                IFNULL(SUM(dcc_coupon_amount), 0) AS dcc_coupon_amount_sum,
                IFNULL(SUM(dcc_coupon_count), 0) AS dcc_coupon_count_sum,
                IFNULL(SUM(dtp_regular_amount), 0) AS dtp_regular_amount_sum,
                IFNULL(SUM(dtp_regular_count), 0) AS dtp_regular_count_sum,
                IFNULL(SUM(dtp_yearly_amount), 0) AS dtp_yearly_amount_sum,
                IFNULL(SUM(dtp_yearly_count), 0) AS dtp_yearly_count_sum,
                IFNULL(SUM(dtp_normal_amount), 0) AS dtp_normal_amount_sum,
                IFNULL(SUM(dtp_normal_count), 0) AS dtp_normal_count_sum,
                IFNULL(SUM(dtp_coupon_amount), 0) AS dtp_coupon_amount_sum,
                IFNULL(SUM(dtp_coupon_count), 0) AS dtp_coupon_count_sum,
                IFNULL(SUM(dtc_regular_amount), 0) AS dtc_regular_amount_sum,
                IFNULL(SUM(dtc_regular_count), 0) AS dtc_regular_count_sum,
                IFNULL(SUM(dtc_yearly_amount), 0) AS dtc_yearly_amount_sum,
                IFNULL(SUM(dtc_yearly_count), 0) AS dtc_yearly_count_sum,
                IFNULL(SUM(dtc_normal_amount), 0) AS dtc_normal_amount_sum,
                IFNULL(SUM(dtc_normal_count), 0) AS dtc_normal_count_sum,
                IFNULL(SUM(dtc_coupon_amount), 0) AS dtc_coupon_amount_sum,
                IFNULL(SUM(dtc_coupon_count), 0) AS dtc_coupon_count_sum,
                IFNULL(SUM(krt_coin_deposit), 0) AS krt_coin_deposit_sum,
                IFNULL(SUM(krt_coin_withdraw), 0) AS krt_coin_withdraw_sum,
                IFNULL(SUM(krt_coin_withdraw_fee), 0) AS krt_coin_withdraw_fee_sum,
                IFNULL(SUM(game_coin_deposit), 0) AS game_coin_deposit_sum,
                IFNULL(SUM(game_coin_withdraw), 0) AS game_coin_withdraw_sum,
                IFNULL(SUM(game_coin_withdraw_fee), 0) AS game_coin_withdraw_fee_sum,
                IFNULL(SUM(ciel_coin_deposit), 0) AS ciel_coin_deposit_sum,
                IFNULL(SUM(ciel_coin_withdraw), 0) AS ciel_coin_withdraw_sum,
                IFNULL(SUM(ciel_coin_withdraw_fee), 0) AS ciel_coin_withdraw_fee_sum,
                IFNULL(SUM(icf_coin_deposit), 0) AS icf_coin_deposit_sum,
                IFNULL(SUM(icf_coin_withdraw), 0) AS icf_coin_withdraw_sum,
                IFNULL(SUM(icf_coin_withdraw_fee), 0) AS icf_coin_withdraw_fee_sum,
                IFNULL(SUM(point_deposit), 0) AS point_deposit_sum,
                IFNULL(SUM(point_withdraw), 0) AS point_withdraw_sum
            FROM tbl_stat_payment
            WHERE SUBSTR(stat_date, 1, 7) BETWEEN ? AND ?
            GROUP BY SUBSTR(stat_date, 1, 7)
            ORDER BY SUBSTR(stat_date, 1, 7) DESC
        ";
        $query = $this->db->query($sql, array($date_start, $date_end));
        $result = $query->result();

        return $result;
    }
}