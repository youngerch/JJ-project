<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-07-03
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Item_image_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "item_image";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_item_images($shop_cd, $lang_cd, $item_cd)
    {
        $sql = "
            select
                a.*,
                b.cd_name as item_img_cd_name
            from
                {$this->_table} as a 
                inner join tbl_code as b on a.item_img_cd = b.cd
            where
                a.item_cd = ?
                and b.shop_cd = ?
                and b.lang_cd = ?
        ";
        $query = $this->db->query($sql, array($item_cd, $shop_cd, $lang_cd));
        $result = $query->result();

        return $result;
    }

    public function get_item_list_img($item_cd)
    {
        $sql = "
            select
                a.*
            from
                {$this->_table} as a
            where
                a.item_cd = ?
                and item_img_cd = '18001'
                and a.display_is_pc = '1'
            order by a.orders_pc asc    
        ";
        $query = $this->db->query($sql, array($item_cd));
        $row = $query->row();
        return $row;
    }
}