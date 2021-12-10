<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Item_lib
 *
 * 상품 정보 관리 라이브러리
 *
 * Created on 2021-06-10
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Item_lib
{
    protected $_ci;
    var $_admin;

    public function __construct()
    {
        $this->_ci =& get_instance();

        $this->_ci->load->model(array(
            'common/item_model'             => 'm_item',
            'common/item_image_model'       => 'm_item_image',
            'common/item_log_model'         => 'm_item_log',
            'common/item_alarm_model'       => 'm_item_alarm',
        ));

        $this->_ci->load->library(array());

        $admin_seq = $this->_ci->admin_lib->get_session('seq');
        $this->_admin = $this->_ci->admin_lib->get_administrator($admin_seq);
    }

    /**
     * Function get_item
     *
     * 상품 정보 조회
     *
     * @param $item_seq 아이템 순번
     * @return false
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-10
     * @access
     */
    public function get_item($item_seq)
    {
        $_item = $this->_ci->m_item->get($item_seq);

        if (!isset($_item->seq)) {
            return false;
        }
        else {
            return $_item;
        } // End if
    }

    /**
     * Function get_item_by_column
     *
     * 지정된 정보(컬럼)를 이용한 관리자 정보 조회
     *
     * @param $whereData 컬럼 정보와 데이터를 가진 Array
     * @return false
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-10
     * @access
     */
    public function get_item_by_column($whereData)
    {
        $_item = $this->_ci->m_item->get_by($whereData);

        if (!isset($_item->seq)) {
            return false;
        }
        else {
            return $_item;
        } // End if
    }

    /**
     * Function set_item
     *
     * 아이템 정보 업데이트
     *
     * @param $item_seq 아이템 순번
     * @param $updateData 아이템 업데이트 정보
     * @return bool
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-10
     * @access
     */
    public function set_item($item_seq, $updateData)
    {
        $_item = $this->get_item($item_seq);

        $rlt = $this->_ci->m_item->update($item_seq, $updateData);
        if($rlt) {
            $this->item_log($item_seq, $_item);
            return true;
        }
        else {
            return false;
        } // End if
    }

    /**
     * Function set_item_log
     *
     * 아이템 정보 변경 로그
     *
     * @param $item_seq
     * @param $beforeData
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-10
     * @access
     */
    public function item_log($item_seq, $beforeData)
    {
        $afterData = $this->_ci->m_item->get($item_seq);

        $insertData = array(
            'item_seq'          => $item_seq,
            'before_item_data'  => json_encode($beforeData),
            'after_item_data'   => json_encode($afterData),
            'reg_date'          => __TIME_YMDHIS__,
            'reg_ip'            => __REMOTE_ADDR__,
            'reg_admin_seq'     => $this->_admin->seq
        );
        $this->_ci->m_item_log->insert($insertData);
    }

    /**
     * Function send_item_alarm
     *
     * 상품 알림 발송
     *
     * @param $item 상품 정보 object
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-09-30
     * @access
     */
    public function send_item_alarm($_item)
    {
        $this->_ci->load->library(array('sms_lib', 'member_lib'));

        $alarm_send = $_item->alarm_send;
        //$total_count = $this->_ci->item_alarm->get_count_all(array('item_cd'=>$item_cd, 'is_send'=>'N'));
        //$chk_count = intVal($total_count) - intVal($alarm_send);
        $lists = $this->_ci->m_item_alarm->get_list_all(0, intVal($alarm_send), array('item_cd'=>$_item->item_cd, 'is_send'=>'N'));
        foreach ( $lists as $key => $row ) {
            //SMS 발송
            $_member = $this->_ci->member_lib->get_member($row->member_seq);
            $msg = "[".__SITE_TITLE__."]\n안녕하세요. 삼보사랑입니다.\n지금 쇼핑몰로 이동하시면, 제품 구매가 가능합니다.\n좋은 제품으로 보답하겠습니다.\n감사합니다.\n\nwww.sambosarang.com\n※ 회원 모집 완료 시, 구매가 불가한 점 양해 부탁드립니다.";

            $dest = array();
            $dest[0] = [
                'to_name'   => $_member->nickname,
                'to_phone'  => $_member->hp
            ];

            $arrInData = array(
                'member_seq'    => $_member->seq,
                'sms_type'      => '1',                                 // (고정)
                'title'         => '['.__SITE_TITLE__.'] 상품 입고 알림',     // 제목
                'content'       => $msg,           					    // 발송 내용
                'to_name'       => $_member->nickname,                  // 수신자 이름
                'to_phone'      => $_member->hp_s,                      // 수신자 번호
                'dest_info'     => $dest,
                'callback_phone'=> __SITE_SMS_HP_NO__,                  // 발신자 번호
                'msg_type'      => 'LMS',                               // 문자타입(SMS - 80byte 이하, LMS - 2000byte 이하)
                'schedule_type' => '0',                                 // (고정)
                'schedule_date' => date("YmdHis"),              // 발송 시각 : YmdHis. 즉시 발송일 경우 현재 시각
                'option1'       => '',                                  // 옵션1 :: 인증번호
                'option2'       => '',                                  // 옵션2
                'option3'       => '',                                  // 옵션3
                'return_url'    => '',                                  // 발송 결과를 받는 리턴 URL
            );
            $_rlt = $this->_ci->sms_lib->send_single_message($arrInData);

            $updateItemAlarmData = array(
                'is_send'   => 'Y',
                'send_date' => __TIME_YMDHIS__
            );
            $this->_ci->m_item_alarm->update($row->seq, $updateItemAlarmData);

        } // End foreach;

        //발송 후 구매 차단 해제.
        $updateItemData = array(
            'is_alarm'  => 'N',
            'mod_date'  => __TIME_YMDHIS__,
            'mod_ip'    => __REMOTE_ADDR__
        );
        $this->set_item($_item->seq, $updateItemData);
    }
}