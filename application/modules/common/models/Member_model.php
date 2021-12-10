<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-07-01
 */

class Member_model extends MY_Model
{
    public $_table              = __TABLE_PREFIX__ . "member";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Function get_count_all
     *
     * 지정 조건으로 데이터 카운트
     *
     * @param array $whereData 조회조건
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-14
     * @access
     */
    public function get_count_all($whereData = array())
    {
        $whereSql = "WHERE (1) ";
        $whereSql .= " AND status = '" . $whereData['status'] . "' ";
        $whereSql .= " AND " . $whereData['date_type'] . " BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach ( $whereData as $key => $val ):
            if ( $val != "" ):
                switch ( $key ):
                    case "sch_str" :
                        if ( $whereData['sch_advisor'] == "Y" ): //라인 조회일 경우
                            if ( $whereData['sch_key'] == "hp_s" ):
                                $whereSql .= " AND A.recommend_seq IN ( SELECT seq FROM {$this->_table} WHERE hp_s = '" .$this->secure->encrypt($val). "' ) ";
                            else:
                                $whereSql .= " AND A.recommend_seq IN ( SELECT seq FROM {$this->_table} WHERE nickname = '" .$val. "' ) ";
                            endif;
                        else:   //일반 조회일 경우
                            if ( $whereData['sch_key'] == "hp_s" ):
                                $whereSql .= " AND A.hp_s = '" .$this->secure->encrypt($val). "' ";
                            elseif ( $whereData['sch_key'] == "login_id_s" ):
                                $whereSql .= " AND A.login_id_s = '" . $this->secure->encrypt($val) . "' ";
                            else:
                                $whereSql .= " AND A.nickname = '" .$val. "' ";
                            endif;
                        endif;
                        break;
                    case "sch_auto_bill" :
                        switch ( $val ):
                            case "Y" : $whereSql .= " AND B.bill_key IS NOT NULL AND B.bill_key <> '' "; break;
                            case "N" : $whereSql .= " AND B.bill_key IS NULL "; break;
                            case "D" : $whereSql .= " AND B.bill_key = '' "; break;
                        endswitch;
                        break;
                    case "status" :
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                    case "sch_advisor" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$val}'";
                        break;
                endswitch;
            endif;
        endforeach;

        $sql = "
			SELECT
			  	count(A.seq) AS cnt
			FROM {$this->_table} AS A
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
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-15
     * @access
     */
    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = "WHERE (1) ";
        $whereSql .= " AND status = '" . $whereData['status'] . "' ";
        $whereSql .= " AND " . $whereData['date_type'] . " BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach ( $whereData as $key => $val ):
            if ( $val != "" ):
                switch ( $key ):
                    case "sch_str" :
                        if ( $whereData['sch_advisor'] == "Y" ): //라인 조회일 경우
                            if ( $whereData['sch_key'] == "hp_s" ):
                                $whereSql .= " AND A.recommend_seq IN ( SELECT seq FROM {$this->_table} WHERE hp_s = '" .$this->secure->encrypt($val). "' ) ";
                            else:
                                $whereSql .= " AND A.recommend_seq IN ( SELECT seq FROM {$this->_table} WHERE nickname = '" .$val. "' ) ";
                            endif;
                        else:   //일반 조회일 경우
                            if ( $whereData['sch_key'] == "hp_s" ):
                                $whereSql .= " AND A.hp_s = '" .$this->secure->encrypt($val). "' ";
                            elseif ( $whereData['sch_key'] == "login_id_s" ):
                                $whereSql .= " AND A.login_id_s = '" . $this->secure->encrypt($val) . "' ";
                            else:
                                $whereSql .= " AND A.nickname = '" .$val. "' ";
                            endif;
                        endif;
                        break;
                    case "sch_auto_bill" :
                        switch ( $val ):
                            case "Y" : $whereSql .= " AND B.bill_key IS NOT NULL AND B.bill_key <> '' "; break;
                            case "N" : $whereSql .= " AND B.bill_key IS NULL "; break;
                            case "D" : $whereSql .= " AND B.bill_key = '' "; break;
                        endswitch;
                        break;
                    case "status" :
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                    case "sch_advisor" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$val}'";
                        break;
                endswitch;
            endif;
        endforeach;

        $sql = "
			SELECT
			  	A.*
			FROM {$this->_table} AS A
            LEFT OUTER JOIN {$this->_table_subscription} AS B ON A.seq = B.member_seq
			".$whereSql."
			ORDER BY A.seq DESC
			limit ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    /**
     * Function get_count_by_grade
     *
     * 멤버십 분류별 카운티
     *
     * @param array $whereData 조회 조건
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2020-11-24
     * @access
     */
    public function get_count_by_grade($whereData = array())
    {
        $whereSql = "WHERE (1) ";
        $whereSql .= " AND status = '" . $whereData['status'] . "' ";
        $whereSql .= " AND " . $whereData['date_type'] . " BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach ($whereData as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "sch_str" :
                        if ($whereData['sch_advisor'] == "Y") { //라인 조회일 경우
                            if ($whereData['sch_key'] == "hp_s") {
                                $whereSql .= " AND A.recommend_seq IN ( SELECT seq FROM {$this->_table} WHERE hp_s = '" . $this->secure->encrypt($val) . "' ) ";
                            } else {
                                $whereSql .= " AND A.recommend_seq IN ( SELECT seq FROM {$this->_table} WHERE nickname = '" . $val . "' ) ";
                            } // End if
                        } else { //일반 조회일 경우
                            if ($whereData['sch_key'] == "hp_s") {
                                $whereSql .= " AND A.hp_s = '" . $this->secure->encrypt($val) . "' ";
                            } else if ( $whereData['sch_key'] == "login_id_s" ) {
                                $whereSql .= " AND A.login_id_s = '" . $this->secure->encrypt($val) . "' ";
                            } else {
                                $whereSql .= " AND A.nickname = '" . $val . "' ";
                            } // End if
                        } // End if
                        break;
                    case "sch_auto_bill" :
                        switch ($val) {
                            case "Y" :
                                $whereSql .= " AND B.bill_key IS NOT NULL ";
                                break;
                            case "N" :
                                $whereSql .= " AND B.bill_key IS NULL ";
                                break;
                            case "D" :
                                $whereSql .= " AND B.bill_key = '' ";
                                break;
                        } // End switch
                        break;
                    case "status" :
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                    case "sch_advisor" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$val}'";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
            SELECT
			    COUNT(A.seq) AS cnt,
			    SUM(CASE WHEN A.grade = '0' THEN 1 ELSE 0 END) AS cafe_member,        -- 북카페회원 
			    SUM(CASE WHEN A.grade = '1' THEN 1 ELSE 0 END) AS angel_normal,       -- 엔젤 일반회원   
			    SUM(CASE WHEN A.grade = '4' THEN 1 ELSE 0 END) AS angel_yearly,       -- 엔젤 연회원
                SUM(CASE WHEN A.grade = '5' THEN 1 ELSE 0 END) AS angel_regular,      -- 엔젤 정회원
                SUM(CASE WHEN A.grade = '6' THEN 1 ELSE 0 END) AS angel_associate,    -- 엔젤 준회원 
                SUM(CASE WHEN A.grade = '9' THEN 1 ELSE 0 END) AS angel_timeover      -- 엔젤 만료회원   
			FROM {$this->_table} AS A
            LEFT OUTER JOIN {$this->_table_subscription} AS B ON A.seq = B.member_seq
			".$whereSql."
        ";
        //echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row;
    }

    /**
     * 테이블 업데이트를 위해 락을 건다.
     * @param $trade_id
     * @return mixed
     */
    public function get_data_rock($seq)
    {
        $sql = "
            SELECT
                *
            FROM
                {$this->_table}
            WHERE
                seq = ?
            FOR UPDATE    
        ";
        $query = $this->db->query($sql, $seq);
        $result = $query->row();

        return $result;
    }

    /**
     * 자격기간이 지난 회원 정보 조회
     * - 등급 확인 조정용 , 정회원과 준회원 기분
     *
     */
    public function get_member_with_subscription()
    {
        $sql = "
            SELECT
                A.seq, A.grade,
                B.bill_key, B.next_date, A.expire_date
            FROM {$this->_table} AS A
            LEFT JOIN {$this->_table_subscription} AS B ON A.seq = B.member_seq
            WHERE A.grade IN ( '5', '6' )
            AND A.status = '1'
        ";
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    /**
     * 엔젤멤버십 통계
     */
    public function get_member_statistics($stat_date = "")
    {
        $sql = "
        SELECT
            IFNULL(SUM(CASE WHEN is_membership = '0' THEN 1 ELSE 0 END), 0) AS cafe_member,
            IFNULL(SUM(CASE WHEN is_membership = '1' THEN 1 ELSE 0 END), 0) AS angel_member,
            IFNULL(SUM(CASE WHEN is_membership = '1' AND grade = '1' THEN 1 ELSE 0 END), 0) AS angel_member_normal,
            IFNULL(SUM(CASE WHEN is_membership = '1' AND grade = '4' THEN 1 ELSE 0 END), 0) AS angel_member_yearly,            
            IFNULL(SUM(CASE WHEN is_membership = '1' AND grade = '5' THEN 1 ELSE 0 END), 0) AS angel_member_regular,
            IFNULL(SUM(CASE WHEN is_membership = '1' AND grade = '6' THEN 1 ELSE 0 END), 0) AS angel_member_semi,
            IFNULL(SUM(CASE WHEN is_membership = '1' AND grade = '9' THEN 1 ELSE 0 END), 0) AS angel_member_expired
        FROM {$this->_table}
        WHERE `status` <> '9'        
        ";

        if ( $stat_date != "" ) $sql .= " AND join_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59' ";

        //AND seq NOT IN ( '1' )
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    /**
     * 상태(신규, 휴면, 탈퇴)별 회원통계
     */
    public function get_member_statistics_by_status($stat_date, $status)
    {
        $sql = "";
        switch ( $status ):
            case "1" :  //신규가입
                $sql = "
                    SELECT
                        IFNULL(SUM(CASE WHEN is_membership = '0' THEN 1 ELSE 0 END), 0) AS cafe_member,
                        IFNULL(SUM(CASE WHEN is_membership = '1' THEN 1 ELSE 0 END), 0) AS angel_member
                    FROM {$this->_table}
                    WHERE `status` = '1'
                    AND join_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'                    
                ";
                break;
            case "5" :  //휴면회원
                $sql = "
                    SELECT
                        IFNULL(SUM(CASE WHEN is_membership = '0' THEN 1 ELSE 0 END), 0) AS cafe_member,
                        IFNULL(SUM(CASE WHEN is_membership = '1' THEN 1 ELSE 0 END), 0) AS angel_member
                    FROM {$this->_table}
                    WHERE `status` = '5'
                    AND mod_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'                    
                ";
                break;
            case "9" :  //탈퇴회원
                $sql = "
                    SELECT
                        IFNULL(SUM(CASE WHEN is_membership = '0' THEN 1 ELSE 0 END), 0) AS cafe_member,
                        IFNULL(SUM(CASE WHEN is_membership = '1' THEN 1 ELSE 0 END), 0) AS angel_member
                    FROM {$this->_table}
                    WHERE `status` = '9'
                    AND leave_date BETWEEN '{$stat_date} 00:00:00' AND '{$stat_date} 23:59:59'                    
                ";
                break;
        endswitch;

        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }

    /**
     * 스타등급통계
     */
    public function get_member_statistics_by_stargrade()
    {
        $sql = "
            SELECT
                IFNULL(SUM(CASE WHEN star_grade > 0 THEN 1 ELSE 0 END), 0) AS star_grade_total,
                IFNULL(SUM(CASE WHEN star_grade = '1' THEN 1 ELSE 0 END), 0) AS star_grade_1,
                IFNULL(SUM(CASE WHEN star_grade = '2' THEN 1 ELSE 0 END), 0) AS star_grade_2,
                IFNULL(SUM(CASE WHEN star_grade = '3' THEN 1 ELSE 0 END), 0) AS star_grade_3,
                IFNULL(SUM(CASE WHEN star_grade = '4' THEN 1 ELSE 0 END), 0) AS star_grade_4,
                IFNULL(SUM(CASE WHEN star_grade = '5' THEN 1 ELSE 0 END), 0) AS star_grade_5,
                IFNULL(SUM(CASE WHEN star_grade = '6' THEN 1 ELSE 0 END), 0) AS star_grade_6,
                IFNULL(SUM(CASE WHEN star_grade = '7' THEN 1 ELSE 0 END), 0) AS star_grade_7,
                IFNULL(SUM(CASE WHEN star_grade = '8' THEN 1 ELSE 0 END), 0) AS star_grade_8,
                IFNULL(SUM(CASE WHEN star_grade = '9' THEN 1 ELSE 0 END), 0) AS star_grade_9,
                IFNULL(SUM(CASE WHEN star_grade = '10' THEN 1 ELSE 0 END), 0) AS star_grade_10
            FROM {$this->_table}
            WHERE `status` <> '9'
            AND star_grade > 0            
        ";
        $query = $this->db->query($sql);
        $row = $query->row();

        if ( $row ):
            return $row;
        else:
            return false;
        endif;
    }
}

