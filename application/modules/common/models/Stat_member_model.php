<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-11
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Stat_member_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "stat_member";

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

    public function get_sum_by_year($date_start, $date_end)
    {
        $sql = "
            SELECT
                SUBSTR(stat_date, 1, 4) AS st_date,
                IFNULL(SUM(cafe_member_join), 0) AS cafe_member_join_sum,
                IFNULL(SUM(cafe_member_inactive), 0) AS cafe_member_inactive_sum,
                IFNULL(SUM(cafe_member_leave), 0) AS cafe_member_leave_sum,
                IFNULL(SUM(angel_member_join), 0) AS angel_member_join_sum,
                IFNULL(SUM(angel_member_inactive), 0) AS angel_member_inactive_sum,
                IFNULL(SUM(angel_member_leave), 0) AS angel_member_leave_sum
            FROM {$this->_table}
            WHERE SUBSTR(stat_date, 1, 4) BETWEEN ? AND ?
            GROUP BY SUBSTR(stat_date, 1, 4)
            ORDER BY SUBSTR(stat_date, 1, 4) DESC
        ";
        $query = $this->db->query($sql, array($date_start, $date_end));
        $result = $query->result();

        return $result;
    }

    public function get_sum_by_month($date_start, $date_end)
    {
        $sql = "
            SELECT
                SUBSTR(stat_date, 1, 7) AS st_date,
                IFNULL(SUM(cafe_member_join), 0) AS cafe_member_join_sum,
                IFNULL(SUM(cafe_member_inactive), 0) AS cafe_member_inactive_sum,
                IFNULL(SUM(cafe_member_leave), 0) AS cafe_member_leave_sum,
                IFNULL(SUM(angel_member_join), 0) AS angel_member_join_sum,
                IFNULL(SUM(angel_member_inactive), 0) AS angel_member_inactive_sum,
                IFNULL(SUM(angel_member_leave), 0) AS angel_member_leave_sum,
                IFNULL(SUM(cafe_member), 0) AS cafe_member_sum,
                IFNULL(SUM(angel_member), 0) AS angel_member_sum,
                IFNULL(SUM(angel_member_normal), 0) AS angel_member_normal_sum,
                IFNULL(SUM(angel_member_yearly), 0) AS angel_member_yearly_sum,
                IFNULL(SUM(angel_member_regular), 0) AS angel_member_regular_sum,
                IFNULL(SUM(angel_member_semi), 0) AS angel_member_semi_sum,
                IFNULL(SUM(angel_member_expired), 0) AS angel_member_expired_sum
            FROM {$this->_table}
            WHERE SUBSTR(stat_date, 1, 7) BETWEEN ? AND ?
            GROUP BY SUBSTR(stat_date, 1, 7)
            ORDER BY SUBSTR(stat_date, 1, 7) DESC
        ";
        $query = $this->db->query($sql, array($date_start, $date_end));
        $result = $query->result();

        return $result;
    }

    public function get_lastday_by_month($date_start, $date_end)
    {
        $sql = "
            SELECT
                *
            FROM {$this->_table}
            WHERE stat_date IN (
                SELECT 
                    MAX(stat_date) as max_stat_date
                FROM {$this->_table}
                WHERE SUBSTR(stat_date, 1, 7) BETWEEN ? AND ?
                GROUP BY SUBSTR(stat_date, 1, 7)    
            )
            ORDER BY SUBSTR(stat_date, 1, 7) DESC
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($date_start, $date_end));
        $result = $query->result();

        return $result;
    }

    public function get_sum_by_latest($check_date)
    {
        $sql = "
            SELECT 
                IFNULL(SUM(cafe_member_join), 0) AS cafe_member_join_sum,
                IFNULL(SUM(cafe_member_inactive), 0) AS cafe_member_inactive_sum,
                IFNULL(SUM(cafe_member_leave), 0) AS cafe_member_leave_sum,
                IFNULL(SUM(cafe_member), 0) AS cafe_member_sum,
                IFNULL(SUM(angel_member_join), 0) AS angel_member_join_sum,
                IFNULL(SUM(angel_member_inactive), 0) AS angel_member_inactive_sum,
                IFNULL(SUM(angel_member_leave), 0) AS angel_member_leave_sum,
                IFNULL(SUM(angel_member), 0) AS angel_member_sum,
                IFNULL(SUM(angel_member_normal), 0) AS angel_member_normal_sum,
                IFNULL(SUM(angel_member_yearly), 0) AS angel_member_yearly_sum,
                IFNULL(SUM(angel_member_regular), 0) AS angel_member_regular_sum,
                IFNULL(SUM(angel_member_semi), 0) AS angel_member_semi_sum,
                IFNULL(SUM(angel_member_expired), 0) AS angel_member_expired_sum
            FROM {$this->_table}
            WHERE stat_date < ?
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($check_date));
        $result = $query->result();

        return $result;
    }
}