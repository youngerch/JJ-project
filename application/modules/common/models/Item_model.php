<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-07-01
 */

/**
 * Class Item_model
 *
 * 상품 마스터 정보
 *
 * Created on 2021-06-11
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Item_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "item";
    public $_member_subscription_table = __TABLE_PREFIX__ . "member_subscription";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        $whereSql .= " AND ". $whereData['sch_key'] ." LIKE '%" . $value ."%' ";
                        break;
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}' ";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
			SELECT
			  	A.*
			FROM
				{$this->_table} A            
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
//        echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_count_all($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        $whereSql .= " AND ". $whereData['sch_key'] ." LIKE '%" . $value ."%' ";
                        break;
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}' ";
                        break;
                } // End switch
            } // End if
        } // End foreach

        $sql = "
			select
			  	count(*) as cnt
			from
				{$this->_table} as A
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }
}