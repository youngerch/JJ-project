<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-06-17
 */

/**
 * Class Admin_model
 *
 * 운영자 정보
 *
 * Created on 2021-06-14
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Admin_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "admin";
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
        $whereSql = "where 1 = 1 ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        $whereSql .= " and (A.name like '%" . $value . "%' or A.email like '%" . $value . "%')";
                        break;
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
    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = "where 1 = 1 ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        $whereSql .= " and (A.name like '%" . $value . "%' or A.email like '%" . $value . "%')";
                        break;
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
			select
			  	A.*
			from
				{$this->_table} A	
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_name(){

        $sql = "
			select
			  	A.seq, A.name
			from
				{$this->_table} A	
			order by A.seq desc
		";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
}