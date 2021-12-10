<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-10-01
 */

class Item_alarm_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "item_alarm";
    public $_item_table = __TABLE_PREFIX__ . "item";
    public $_member_table = __TABLE_PREFIX__ . "member";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_all($from_record, $rows, $whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A." . $whereData['date_type'] . " BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        switch ( $whereData['sch_key'] ) {
                            case "login_id" :
                                $whereSql .= " AND B.login_id_s = '" .$this->secure->encrypt($value). "' ";
                                break;
                            case "hp" :
                                $whereSql .= " AND B.hp_s = '" .$this->secure->encrypt($value). "' ";
                                break;
                            default :
                                $whereSql .= " AND B.".$whereData['sch_key']." LIKE '%" . $value ."%' ";
                                break;
                        }
                        break;
                    case "sch_key" :
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
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
			  	A.*,
			    B.login_id_s, B.nickname, B.international, B.hp_s, B.join_date, B.login_date,
			    C.seq AS item_seq, C.item_name, C.unit_count, C.unit_name, C.sales_status
			FROM
				{$this->_table} AS A
				LEFT JOIN {$this->_member_table} AS B ON A.member_seq = B.seq
				LEFT JOIN {$this->_item_table} AS C ON A.item_cd = C.item_cd        
			".$whereSql."
			ORDER BY A.seq DESC
			LIMIT ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_count_all($whereData = array())
    {
        $whereSql = " WHERE 1 = 1 ";
        $whereSql .= " AND A." . $whereData['date_type'] . " BETWEEN '" . $whereData['date_start'] . " 00:00:00' AND '" . $whereData['date_end'] . " 23:59:59' ";

        foreach ($whereData as $key => $value) {
            if ($value != "") {
                switch ($key) {
                    case "sch_str" :
                        switch ( $whereData['sch_key'] ) {
                            case "login_id" :
                                $whereSql .= " AND B.login_id_s = '" .$this->secure->encrypt($value). "' ";
                                break;
                            case "hp" :
                                $whereSql .= " AND B.hp_s = '" .$this->secure->encrypt($value). "' ";
                                break;
                            default :
                                $whereSql .= " AND B.".$whereData['sch_key']." LIKE '%" . $value ."%' ";
                                break;
                        }
                        break;
                    case "sch_key" :
                    case "date_type" :
                    case "date_start" :
                    case "date_end" :
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
			  	COUNT(*) AS cnt
			FROM
				{$this->_table} AS A
				LEFT JOIN {$this->_member_table} AS B ON A.member_seq = B.seq
				LEFT JOIN {$this->_item_table} AS C ON A.item_cd = C.item_cd      
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }
}