<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-07-01
 */

/**
 * Class Document_model
 *
 * 문서관리
 *
 * Created on 2021-07-01
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Document_model extends MY_Model
{
    public $_table      = __TABLE_PREFIX__ . "document";
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
        $whereSql = "WHERE 1 = 1 ";
        $whereSql .= " AND reg_date BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach ($whereData as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "sch_str" :
                        $whereSql .= " AND A." . $whereData['sch_key'] . " LIKE '%{$val}%' ";
                        break;
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$val}'";
                        break;
                } // End switch
            } // End if
        } // End foreach

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
        $whereSql = "WHERE 1 = 1 ";
        $whereSql .= " AND reg_date BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach ($whereData as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "sch_str" :
                        $whereSql .= " AND A." . $whereData['sch_key'] . " LIKE '%{$val}%' ";
                        break;
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$val}'";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
			SELECT
			  	A.*
			FROM {$this->_table} AS A        
			".$whereSql."
			ORDER BY A.seq DESC
			LIMIT ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }
}