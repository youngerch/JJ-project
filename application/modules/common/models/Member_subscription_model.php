<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Member_subscription_model extends MY_Model
{
    public $_table          = __TABLE_PREFIX__ . "member_subscription";
    public $_member_table   = __TABLE_PREFIX__ . "member";
    public $primary_key     = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param date $checkDate : 지정 결제일
     * 지정 결제일에 결제를 진행해야 하는 회원
     */
    public function get_member_autobill($checkDate)
    {
        $sql = "
            SELECT 
                A.*,
                B.international, B.hp_s, B.nickname
            FROM {$this->_table} as A
            LEFT JOIN {$this->_member_table} as B ON A.member_seq = B.seq
            WHERE A.member_seq NOT IN ( '1' ) 
            AND A.bill_key <> ''
            AND A.bill_key IS NOT NULL
            AND A.next_date = ?
            AND B.status = '1'
            AND B.grade = '5'
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($checkDate));
        $result = $query->result();

        return $result;
    }

    public function get_member_by_itemcd($itemcd)
    {
        $checkDate = Date('Y-m-d');

        $sql = "
            SELECT 
                A.*,
                B.international, B.hp_s, B.nickname
            FROM {$this->_table} as A
            LEFT JOIN {$this->_member_table} as B ON A.member_seq = B.seq
            WHERE A.member_seq NOT IN ( '1' ) 
            AND A.bill_key <> ''
            AND A.bill_key IS NOT NULL
            AND A.item_cd = ?
            AND A.next_date > ?
            AND B.status = '1'
            AND B.grade = '5'
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($itemcd, $checkDate));
        $result = $query->result();

        return $result;
    }

    public function get_count_by_itemcd($itemcd)
    {
        $sql = "
            SELECT 
                count(A.seq) as cnt
            FROM {$this->_table} as A
            LEFT JOIN {$this->_member_table} as B ON A.member_seq = B.seq
            WHERE A.bill_key <> ''
            AND A.bill_key IS NOT NULL
            AND A.item_cd = ?
            AND B.status = '1'            
        ";
        //echo $sql;
        $query = $this->db->query($sql, array($itemcd));
        $row = $query->row();

        return $row->cnt;
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


}