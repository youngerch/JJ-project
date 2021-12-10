<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Inquiry_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "inquiry";
    public $_member_table = __TABLE_PREFIX__ . "member";
    public $_category_table = __TABLE_PREFIX__ . "category";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_all($from_record, $rows, $whereData = array()){

        $whereSql = " WHERE A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        foreach($whereData as $key => $value):
            if($value != ""):
                switch ( $key ) :
                    case "search_str" :
                        $whereSql .= " AND ( A.title LIKE '%" . $value ."%' OR A.content LIKE ' ";
                        break;
                    case "sch_str" :
                        if ( $whereData['sch_key'] == "hp" ):
                            $whereSql .= " AND B.hp_s = '" . $this->secure->encrypt($value) ."' ";
                        elseif ( $whereData['sch_key'] == "nickname" ):
                            $whereSql .= " AND B.nickname = '" . $value ."' ";
                        elseif ( $whereData['sch_key'] == "id" ):
                            $whereSql .= " AND B.login_id = '" . $value ."' ";
                        endif;
                        break;
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}'";
                endswitch;
            endif;
        endforeach;

        $sql = "
			select
			  	A.*,
			  	B.international, B.hp_s, B.nickname, B.login_id_s,
			  	C.cate_name_kor
			from
				{$this->_table} A 
		    LEFT JOIN {$this->_member_table} B ON A.member_seq = B.seq	
		    LEFT JOIN {$this->_category_table} C ON A.category_cd = C.cate_code
			".$whereSql."
			order by A.seq desc
			limit ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    public function get_count_all($whereData = array()){

        $whereSql = " WHERE A.reg_date BETWEEN '".$whereData['date_start']." 00:00:00' AND '".$whereData['date_end']." 23:59:59' ";

        foreach($whereData as $key => $value):
            if($value != ""):
                switch ( $key ) :
                    case "search_str" :
                        $whereSql .= " AND ( A.title LIKE '%" . $value ."%' OR A.content LIKE ' ";
                        break;
                    case "sch_str" :
                        if ( $whereData['sch_key'] == "hp" ):
                            $whereSql .= " AND B.hp_s = '" . $this->secure->encrypt($value) ."' ";
                        elseif ( $whereData['sch_key'] == "nickname" ):
                            $whereSql .= " AND B.nickname = '" . $value ."' ";
                        elseif ( $whereData['sch_key'] == "id" ):
                            $whereSql .= " AND B.login_id = '" . $value ."' ";
                        endif;
                        break;
                    case "date_start" :
                    case "date_end" :
                    case "sch_key" :
                        //PASS
                        break;
                    default :
                        $whereSql .= " AND A.{$key} = '{$value}'";
                endswitch;
            endif;
        endforeach;

        $sql = "
			select
			  	count(*) as cnt
			from
				{$this->_table} A 
		    LEFT JOIN {$this->_member_table} B ON A.member_seq = B.seq	
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }

    public function get_count_by_status()
    {
        $sql = "
			SELECT
			  	count(*) as cnt
			FROM
				{$this->_table} A
		    WHERE status <> '9'
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }
}