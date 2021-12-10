<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-09-23
 */

/**
 * Class Category_model
 *
 * 사이트 내 카테고리 관리
 *
 * Created on 2021-06-29
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Category_model extends MY_Model
{
    public $_table          = __TABLE_PREFIX__ . "category";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Function get_count_all
     *
     * 조건에 따른 데이터 수 조회
     *
     * @param array $whereData 검색 조건
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-30
     * @access
     */
    public function get_count_all($whereData = array())
    {
        $whereSql = " Where 1 = 1 ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "cate_code_search" :
                        $whereSql .= " AND cate_code LIKE '{$value}%' ";
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$value}%' ";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
			SELECT
			  	COUNT(seq) AS cnt
			FROM
				{$this->_table}	
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }

    /**
     * Function get_list_all
     *
     * 조건에 따른 데이터 조회
     *
     * @param array $whereData
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-30
     * @access
     */
    public function get_list_all($whereData = array())
    {
        $whereSql = " Where 1 = 1 ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "cate_code_search" :
                        $whereSql .= " AND cate_code LIKE '{$value}%' ";
                        break;
                    default :
                        $whereSql .= " AND {$key} = '{$value}' ";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
			SELECT
			  	*
			FROM {$this->_table}	
			" . $whereSql . "
			ORDER BY cate_order ASC, reg_date ASC
		";
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }


}