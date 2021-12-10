<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-09-01
 */

/**
 * Class Item
 *
 * 상품 관리
 *
 * Created on 2021-06-17
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Item extends Sub_Controller
{
    protected $_module;
    protected $_rows = 10;

    function __construct()
    {
        parent::__construct();

        if (!in_array("item", $this->_accessible_arr)) {
            $this->alert->error("권한이 없습니다.", __SITE_TITLE__, "/");
        }

        $this->_module = "item";
        $this->_menu_item = 'item';

        $this->load->model(array(
            'common/item_model'                 => 'm_item',
            'common/item_image_model'           => 'm_item_image',
            'common/item_alarm_model'           => 'm_item_alarm',
            'common/member_model'               => 'm_member',
            'common/member_subscription_model'  => 'm_member_subscription',
            'common/order_model'                => 'm_order',
            'common/order_item_model'           => 'm_order_item',
        ));

        $this->load->library(array(
            'item_lib', 'order_lib', 'member_lib', 'sms_lib'
        ));
    }

    /**
     * Function index
     *
     * 인덱스
     * 상품목록으로 전환처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-17
     * @access
     */
    public function index()
    {
        $this->lists();
    }

    /**
     * Function lists
     *
     * 상품 목록
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-17
     * @access
     */
    public function lists()
    {
        $current_page = $this->input->get('current_page') ? $this->input->get('current_page') : 1;
        $from_record = ($current_page - 1) * $this->_rows;

        $is_display = $this->input->get('is_display');
        $sales_status = $this->input->get('sales_status');
        $sch_key = $this->input->get('sch_key') ? $this->input->get('sch_key') : "item_name";
        $sch_str = $this->input->get('sch_str') ? urldecode($this->input->get('sch_str')) : "";

        $whereData = array(
            'is_display' => $is_display,
            'sales_status' => $sales_status,
            'sch_key' => $sch_key,
            'sch_str' => $sch_str,
        );
        $lists = $this->m_item->get_list_all($from_record, $this->_rows, $whereData);
        $totalCnt = $this->m_item->get_count_all($whereData);
        $totalPage = ceil($totalCnt / $this->_rows);

        $list = array();
        $i = 0;
        foreach ($lists as $key => $row) {
            $row->no = $totalCnt - $from_record - $i;

            $list[$i] = $row;
            $i++;
        } // End if

        $_page = "item_list";
        $data = array(
            'is_display'    => $is_display,
            'sales_status'  => $sales_status,
            'sch_key'       => $sch_key,
            'sch_str'       => urldecode($sch_str),
            'lists'         => arrayToObject($list),
            'currentPage'   => intVal($current_page),
            'perPage'       => intVal($this->_rows),
            'totalPage'     => intVal($totalPage),
            'totalCount'    => intVal($totalCnt),         // 총 갯수
            'page'          => $this->_template . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function create
     *
     * 상품 등록 폼
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-18
     * @access
     */
    public function create()
    {
        $item_cd = get_generate_cd();

        $_page = "item_create";
        $data = array(
            'item_cd'   => $item_cd,
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function calculation_coin_quotation_ajax
     *
     * 시세정보를 이용한 판매가 계산
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-18
     * @access
     */
    public function calculation_coin_quotation_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('btc_quotation', 'BTC 시세정보', 'trim|required');
        $this->form_validation->set_rules('usdb_quotation', 'USDB 시세정보', 'trim|required');
        $this->form_validation->set_rules('usd_price', '판매가(USD)정보', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE) {
            $btc_quotation  = $this->input->post('btc_quotation');
            $usdb_quotation = $this->input->post('usdb_quotation');
            $usd_price     = $this->input->post('usd_price');

            $btc_price  = exp_to_dec(intval($usd_price)/$btc_quotation);
            $usdb_price = exp_to_dec(intval($usd_price)/$usdb_quotation);
            $yearly_btc_price = $btc_price * 12;
            $yearly_usdb_price = $usdb_price * 12;

            $returnData = array(
                'result'            => "SUCCESS",
                'btc_price'         => number_format_coin($btc_price, 8),
                'yearly_btc_price'  => number_format_coin($yearly_btc_price, 8),
                'usdb_price'        => $usdb_price,
                'yearly_usdb_price' => $yearly_usdb_price,
            );
        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 정보를 가져올 수가 없습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function create_process_ajax
     *
     * 상품 등록 처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-18
     * @access
     */
    public function create_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->form_validation->set_rules('stock_cd', '대표 코드', 'trim|required');
        $this->form_validation->set_rules('item_cd', '상품 코드', 'trim|required');
        $this->form_validation->set_rules('sales_status', '상태', 'trim|required');
        $this->form_validation->set_rules('item_name', '상품명', 'trim|required');
        $this->form_validation->set_rules('unit_count', '상품단위수량', 'trim|required');
        $this->form_validation->set_rules('sale_price', '판매가', 'trim|required');
        $this->form_validation->set_rules('release_count', '출고수량', 'trim|required');
        $this->form_validation->set_rules('warehousing_count', '재고수량', 'trim|required');
        $this->form_validation->set_rules('alert_count', '재고알림수량', 'trim|required');
        $this->form_validation->set_rules('warning_count', '재고경고수량', 'trim|required');
        $this->form_validation->set_rules('subscription_limit', '정기결제제한수', 'trim|required');
        $this->form_validation->set_rules('alarm_sms', '알림기준인원', 'trim|required');
        $this->form_validation->set_rules('alarm_send', '알림발송인원', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE) {

            $stock_cd = strtoupper($this->input->post('stock_cd'));
            $item_cd = $this->input->post('item_cd');
            $is_display = $this->input->post('is_display');
            $is_main = $this->input->post('is_main');
            $is_exchange = $this->input->post('is_exchange');
            $sales_status = $this->input->post('sales_status');
            $item_name = $this->input->post('item_name');
            $unit_count = $this->input->post('unit_count');
            $unit_name = $this->input->post('unit_name');
            $sale_price = $this->input->post('sale_price');
            $release_count = $this->input->post('release_count');
            $warehousing_count = $this->input->post('warehousing_count');
            $alert_count = $this->input->post('alert_count');
            $warning_count = $this->input->post('warning_count');
            $subscription_limit = $this->input->post('subscription_limit');
            $alarm_sms = $this->input->post('alarm_sms');
            $alarm_send = $this->input->post('alarm_send');
            $description = $this->input->post('description');
            $information = $this->input->post('information');

            $insertData = array(
                'item_cd'                   => $item_cd,
                'stock_cd'                  => $stock_cd,
                'item_name'                 => $item_name,
                'unit_count'                => $unit_count,
                'unit_name'                 => $unit_name,
                'description'               => $description,
                'information'               => $information,
                'sale_price'                => $sale_price,
                'is_display'                => $is_display,
                'is_main'                   => $is_main,
                'is_exchange'               => $is_exchange,
                'sales_status'              => $sales_status,
                'release_count'             => $release_count,
                'total_warehousing_count'   => $warehousing_count,
                'alert_count'               => $alert_count,
                'warning_count'             => $warning_count,
                'subscription_limit'        => $subscription_limit,
                'alarm_sms'                 => $alarm_sms,
                'alarm_send'                => $alarm_send,
                'reg_date'                  => __TIME_YMDHIS__,
                'reg_ip'                    => __REMOTE_ADDR__,
                'reg_admin_seq'             => $this->_admin->seq
            );
            $this->db->trans_begin();

            $item_seq = $this->m_item->insert($insertData);

            //업로드
            if (count($_FILES) > 0) {
                if ( $_FILES['upfile']['name'] != "" ) {
                    $this->load->library(array('upload', 'aws_s3_lib' => 's3'));
                    //첨부파일 업로드
                    $uploadPath = __DATA_PATH__ . "/item/" . $item_cd . "/main";
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0707, TRUE);
                    } // End if

                    $config = array(
                        "upload_path" => $uploadPath,
                        "allowed_types" => 'gif|jpg|png|jpeg',
                        "encrypt_name" => true,
                        "remove_spaces" => true
                    );
                    $this->upload->initialize($config);

                    $file_chk = $this->upload->do_upload('upfile');
                    $upfile = $this->upload->data();

                    if ($upfile) {
                        $orig_name = $upfile['orig_name'];
                        $file_name = $upfile['file_name'];

                        //s3에 업로드
                        $url = $uploadPath . "/" . $upfile['file_name'];
                        $url = str_replace(__DATA_PATH__, __DEFAULT_HOST__ . "/data", $url);

                        $key = "item/" . $item_cd . "/main/" . $upfile['file_name'];
                        $source = $url;
                        $rlt = $this->s3->set_object($key, $source);

                        if ($rlt) {
                            //기존 파일 삭제
                            $this->m_item_image->delete_by(array('item_cd' => $item_cd));
                            $insertImageData = array(
                                'item_cd'           => $item_cd,
                                'real_file_name'    => $upfile['orig_name'],
                                'file_name'         => $upfile['file_name'],
                                'file_size'         => $upfile['file_size'],
                                'file_path'         => $upfile['file_path'],
                                'reg_date'          => __TIME_YMDHIS__,
                                'reg_ip'            => __REMOTE_ADDR__,
                                'reg_admin_seq'     => $this->_admin->seq
                            );
                            $this->m_item_image->insert($insertImageData);
                        }
                    } // End if
                } // End if
            } // End if

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                $returnData = array(
                    'result' => "ERROR_INSERT",
                    'msg' => "오류로 인해 등록에 실패했습니다."
                );
            } else {
                $this->db->trans_commit();

                $returnData = array(
                    'result'    => "SUCCESS",
                    'msg'       => "성공적으로 처리되었습니다."
                );
            } // End if
        } else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 처리에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function edit
     *
     * 상품 상세/수정 폼
     *
     * @param $item_seq
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-22
     * @access
     */
    public function detail($item_seq)
    {
        $_item = $this->m_item->get_by(array('seq' => $item_seq));
        if (!isset($_item->seq)) {
            alert("잘못된 접근입니다.", "/");
        } // End if

        $_item->item_image = $this->m_item_image->get_by(array('item_cd'=>$_item->item_cd));
        $_item->subscription_count = $this->m_member_subscription->get_count_by_itemcd($_item->item_cd);
        $_page = "item_edit";
        $data = array(
            'item'          => $_item,
            'page'          => $this->_template . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function update_process_ajax
     *
     * 상품 수정 처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-22
     * @access
     */
    public function update_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->form_validation->set_rules('item_seq', '상품순번', 'trim|required');
        $this->form_validation->set_rules('stock_cd', '대표 코드', 'trim|required');
        $this->form_validation->set_rules('item_cd', '상품 코드', 'trim|required');
        $this->form_validation->set_rules('sales_status', '상태', 'trim|required');
        $this->form_validation->set_rules('item_name', '상품명', 'trim|required');
        $this->form_validation->set_rules('unit_count', '상품단위수량', 'trim|required');
        $this->form_validation->set_rules('sale_price', '판매가', 'trim|required');
        $this->form_validation->set_rules('release_count', '기본출고수량', 'trim|required');
        $this->form_validation->set_rules('change_warehousing_count', '입고조정수량', 'trim|required');
        $this->form_validation->set_rules('change_release_count', '출고조정수량', 'trim|required');
        $this->form_validation->set_rules('alert_count', '재고알림수량', 'trim|required');
        $this->form_validation->set_rules('warning_count', '재고경고수량', 'trim|required');
        $this->form_validation->set_rules('subscription_limit', '정기결제제한수', 'trim|required');
        $this->form_validation->set_rules('alarm_sms', '알림기준인원', 'trim|required');
        $this->form_validation->set_rules('alarm_send', '알림발송인원', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE) {

            $item_seq = $this->input->post('item_seq'); // 상품순번
            $stock_cd = strtoupper($this->input->post('stock_cd'));
            $item_cd = $this->input->post('item_cd');
            $is_display = $this->input->post('is_display');
            $is_main = $this->input->post('is_main');
            $is_exchange = $this->input->post('is_exchange');
            $sales_status = $this->input->post('sales_status');
            $item_name = $this->input->post('item_name');
            $unit_count = $this->input->post('unit_count');
            $unit_name = $this->input->post('unit_name');
            $sale_price = $this->input->post('sale_price');
            $release_count = $this->input->post('release_count');
            $change_warehousing_count = $this->input->post('change_warehousing_count');
            $change_release_count = $this->input->post('change_release_count');
            $alert_count = $this->input->post('alert_count');
            $warning_count = $this->input->post('warning_count');
            $subscription_limit = $this->input->post('subscription_limit');
            $alarm_sms = $this->input->post('alarm_sms');
            $alarm_send = $this->input->post('alarm_send');
            $description = $this->input->post('description');
            $information = $this->input->post('information');

            $total_warehousing_count = $this->input->post('total_warehousing_count');
            $total_release_count = $this->input->post('total_release_count');

            $total_warehousing_count = intVal($total_warehousing_count) + intVal($change_warehousing_count);
            $total_release_count = intVal($total_release_count) + intVal($change_release_count);

            $updateData = array(
                'item_cd' => $item_cd,
                'stock_cd' => $stock_cd,
                'item_name' => $item_name,
                'unit_count' => $unit_count,
                'unit_name' => $unit_name,
                'description' => $description,
                'information' => $information,
                'sale_price' => $sale_price,
                'is_display' => $is_display,
                'is_main' => $is_main,
                'is_exchange' => $is_exchange,
                'sales_status' => $sales_status,
                'release_count' => $release_count,
                'total_warehousing_count' => $total_warehousing_count,
                'total_release_count' => $total_release_count,
                'alert_count' => $alert_count,
                'warning_count' => $warning_count,
                'subscription_limit' => $subscription_limit,
                'alarm_sms' => $alarm_sms,
                'alarm_send' => $alarm_send,
                'mod_date' => __TIME_YMDHIS__,
                'mod_ip' => __REMOTE_ADDR__,
                'mod_admin_seq' => $this->_admin->seq
            );

            $this->db->trans_begin();

            $this->item_lib->set_item($item_seq, $updateData, $this->_admin->seq);

            if (count($_FILES) > 0) {
                if ( $_FILES['upfile']['name'] != "" ) {
                    $this->load->library(array('upload', 'aws_s3_lib' => 's3'));
                    //첨부파일 업로드
                    $uploadPath = __DATA_PATH__ . "/item/" . $item_cd . "/main";
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0707, TRUE);
                    } // End if

                    $config = array(
                        "upload_path" => $uploadPath,
                        "allowed_types" => 'gif|jpg|png|jpeg',
                        "encrypt_name" => true,
                        "remove_spaces" => true
                    );
                    $this->upload->initialize($config);

                    $file_chk = $this->upload->do_upload('upfile');
                    $upfile = $this->upload->data();

                    if ($upfile) {
                        $orig_name = $upfile['orig_name'];
                        $file_name = $upfile['file_name'];

                        //s3에 업로드
                        $url = $uploadPath . "/" . $upfile['file_name'];
                        $url = str_replace(__DATA_PATH__, __DEFAULT_HOST__ . "/data", $url);

                        $key = "item/" . $item_cd . "/main/" . $upfile['file_name'];
                        $source = $url;
                        $rlt = $this->s3->set_object($key, $source);

                        if ($rlt) {
                            //기존 파일 삭제
                            $this->m_item_image->delete_by(array('item_cd' => $item_cd));
                            $insertImageData = array(
                                'item_cd'           => $item_cd,
                                'real_file_name'    => $upfile['orig_name'],
                                'file_name'         => $upfile['file_name'],
                                'file_size'         => $upfile['file_size'],
                                'file_path'         => $upfile['file_path'],
                                'reg_date'          => __TIME_YMDHIS__,
                                'reg_ip'            => __REMOTE_ADDR__,
                                'reg_admin_seq'     => $this->_admin->seq
                            );
                            $this->m_item_image->insert($insertImageData);
                        }
                    } // End if
                } // End if
            } // End if

            if ($sales_status == "9") {
                // 판매중지일 경우 정기결제 진행 회원에게 문자 알림.
                /*
                $receiver = $this->member_subscription->get_member_by_itemcd($item_cd);

                $msg = "현재 정기결제 중인 [" . $item_name . "]은 정기 배송이 불가합니다.\n상품을 변경하지 않으면 배송보류 처리되오니, 정기결제 상품을 변경해주시기 바랍니다";

                foreach ($receiver as $key => $row):
                    $arrInData = array(
                        'sms_type' => '1',                                                // (고정)
                        'title' => '[' . __SITE_TITLE__ . '] 정기결제 상품 판매중지 안내', // 제목
                        'content' => $msg,                                            // 발송 내용
                        'to_name' => $row->nickname,                                     // 수신자 이름
                        'to_phone' => $row->hp_s,                                         // 수신자 번호
                        'dest_info' => "상품변경안내^" . $this->secure->decrypt($row->hp_s),
                        'callback_phone' => __SITE_SMS_HP_NO__,                                 // 발신자 번호
                        'msg_type' => 'SMS',                                                // 문자타입(SMS - 80byte 이하, LMS - 2000byte 이하)
                        'schedule_type' => '0',                                                // (고정)
                        'send_date' => date("YmdHis"),                             // 발송시간 (고정)
                        'option1' => '',                                                    // 옵션1
                        'option2' => '',                                                    // 옵션2
                        'return_url' => 'http://' . __DEFAULT_HOST__ . '/sms/result',  // 발송 결과를 받는 리턴 URL
                    );
                    //$_rlt = $this->sms_lib->set_sms_send($arrInData, '1');
                endforeach;

                $arrInData = array(
                    'sms_type' => '1',                                                // (고정)
                    'title' => '[' . __SITE_TITLE__ . '] 정기결제 상품 판매중지 안내', // 제목
                    'content' => $msg,                                            // 발송 내용
                    'to_name' => '이내',                                     // 수신자 이름
                    'to_phone' => '41xZbSc/+PC0hvjROc3Olw==',                                         // 수신자 번호
                    'dest_info' => "상품변경안내^" . $this->secure->decrypt('41xZbSc/+PC0hvjROc3Olw=='),
                    'callback_phone' => __SITE_SMS_HP_NO__,                                 // 발신자 번호
                    'msg_type' => 'SMS',                                                // 문자타입(SMS - 80byte 이하, LMS - 2000byte 이하)
                    'schedule_type' => '0',                                                // (고정)
                    'send_date' => date("YmdHis"),                             // 발송시간 (고정)
                    'option1' => '',                                                    // 옵션1
                    'option2' => '',                                                    // 옵션2
                    'return_url' => 'http://' . __DEFAULT_HOST__ . '/sms/result',  // 발송 결과를 받는 리턴 URL
                );
                $_rlt = $this->sms_lib->set_sms_send($arrInData, '1');
                */
            } // End if

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                $returnData = array(
                    'result' => "ERROR_UPDATE",
                    'msg' => $item_name . "(" . $item_cd . ") 오류로 인해 상품 수정에 실패했습니다."
                );
            }
            else {
                $this->db->trans_commit();

                $returnData = array(
                    'result' => "SUCCESS",
                    'msg' => $item_name . "(" . $item_cd . ") 상품이 성공적으로 수정되었습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 수정에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-07-03
     * TIME : 오후 7:39
     * DESC : 상품 이미지 업로드
     */
    public function image_upload()
    {
        $this->load->library(array('form_validation', 'upload_lib'));

        if (isset($_FILES['file']) && count($_FILES['file']) > 0):

            $item_cd = $this->input->post("item_cd");

            $file_path = __DATA_PATH__ . "/item/" . $item_cd;

            $files = $this->upload_lib->upload_files($file_path, $_FILES['file']);

            $i = 0;
            $insertArray = array();
            foreach ($files as $key => $file):

                $insertArray[$i] = array(
                    'item_cd' => $item_cd,
                    'real_file_name' => $file['orig_name'],
                    'file_name' => $file['file_name'],
                    'file_size' => $file['file_size'],
                    'file_path' => $file['file_path'],
                    'orders' => 1,
                    'reg_date' => __TIME_YMDHIS__,
                    'reg_ip' => __REMOTE_ADDR__,
                    'reg_admin_seq' => $this->_admin->seq
                );
                $i++;
            endforeach;

            if (count($insertArray) > 0):
                $result = $this->item_image->insert_many($insertArray);

                if ($result):
                    $this->load->library(array('aws_s3_lib' => 's3'));

                    foreach ($files as $key => $file):
                        //s3에 업로드
                        $url = $file_path . "/" . $file['file_name'];
                        $url = str_replace(__DATA_PATH__, __DEFAULT_HOST__ . "/data", $url);

                        $key = "item/" . $item_cd . "/" . $file['file_name'];
                        $source = $url;
                        $rlt = $this->s3->set_object($key, $source);
                    endforeach;

                endif;
            endif;
        endif;

        echo json_encode($insertArray);
    }

    /**
     * Function item_exchange
     *
     * 교환 상품 리스트 레이아웃
     *
     * @param $order_item_seq
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-24
     * @access
     */
    public function item_exchange($order_item_seq)
    {
        $_order_item = $this->m_order_item->get($order_item_seq);

        $_page = "item_exchange";
        $data = array(
            'order_item'    => $_order_item,
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function item_exchange_list_ajax
     *
     * 교환 상품 리스트
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-24
     * @access
     */
    public function item_exchange_list_ajax()
    {
        $this->util->is_ajax_alert();

        $sch_key= "A.item_name";
        $sch_str= $this->input->post('lyr_sch_str');
        $page   = $this->input->post('lyr_page');

        $whereData = array(
            'sch_key'    => $sch_key,
            'sch_str'    => $sch_str,
            'is_exchange'   => "Y"
        );
        $perpage        = 10;
        $from_record    = ($page - 1) * $perpage;
        $lists          = $this->m_item->get_list_all($from_record, $perpage, $whereData);
        $totalcnt       = $this->m_item->get_count_all($whereData);

        $list = array();
        $i = 0;
        foreach ($lists as $key => $row) {
            $row->no = $totalcnt - $from_record - $i;

            $list[$i] = $row;
            $i++;
        } // End foreach

        $_page = "item_exchange_list";
        $data = array(
            'lists'         => arrayToObject($list),
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );
        $html = $this->load->view($this->_container_layer, $data, true);

        $rlt = array(
            'perpage'   => $perpage,
            'page'      => $page,
            'totalcnt'  => $totalcnt,
            'html'      => $html
        );
        echo json_encode($rlt);
    }

    /**
     * Function alarm
     *
     * 알림관리 목록
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-10-01
     * @access
     */
    public function alarm()
    {
        $this->_menu_item = 'alarm';

        //아이템 정보
        $whereData = array();
        $total_count = $this->m_item->get_count_all($whereData);
        $items = $this->m_item->get_list_all(0, intVal($total_count), $whereData);

        $current_page = $this->input->get('current_page') ? $this->input->get('current_page') : 1;
        $from_record = ($current_page - 1) * $this->_rows;

        $item_cd    = $this->input->get('item_cd');
        $date_type  = $this->input->get('date_type') ? $this->input->get('date_type') : "reg_date";
        $date_start = $this->input->get('date_start') ? $this->input->get('date_start') : "2021-02-01";
        $date_end   = $this->input->get('date_end') ? $this->input->get('date_end') : Date('Y-m-t');
        $is_send    = $this->input->get('is_send');
        $sch_key    = $this->input->get('sch_key') ? $this->input->get('sch_key') : "loing_id";
        $sch_str    = $this->input->get('sch_str') ? urldecode($this->input->get('sch_str')) : "";

        $whereData = array(
            'item_cd'       => $item_cd,
            'date_type'     => $date_type,
            'date_start'    => $date_start,
            'date_end'      => $date_end,
            'is_send'       => $is_send,
            'sch_key'       => $sch_key,
            'sch_str'       => $sch_str
        );
        $totalCnt = $this->m_item_alarm->get_count_all($whereData);
        $lists = $this->m_item_alarm->get_list_all($from_record, $this->_rows, $whereData);
        $totalPage = ceil($totalCnt / $this->_rows);

        $list = array();
        $i = 0;
        foreach ($lists as $key => $row) {
            $row->no = $totalCnt - $from_record - $i;
            $row->login_id = $this->secure->decrypt($row->login_id_s);
            $row->hp = $this->secure->decrypt($row->hp_s);

            $list[$i] = $row;
            $i++;
        } // End if

        $_page = "alarm_list";
        $data = array(
            'items'         => $items,
            'item_cd'       => $item_cd,
            'date_type'     => $date_type,
            'date_start'    => $date_start,
            'date_end'      => $date_end,
            'is_send'       => $is_send,
            'sch_key'       => $sch_key,
            'sch_str'       => urldecode(str_replace("-withdrawal", "", $sch_str)),
            'lists'         => arrayToObject($list),
            'currentPage'   => intVal($current_page),
            'perPage'       => intVal($this->_rows),
            'totalPage'     => intVal($totalPage),
            'totalCount'    => intVal($totalCnt),         // 총 갯수
            'page'          => $this->_template . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    public function alarm_send_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->form_validation->set_rules('seq', '알림정보 시퀀스', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE) {

            $alarm_seq = $this->input->post('seq');
            $_alarm = $this->m_item_alarm->get_by(array('seq' => $alarm_seq, 'is_send' => 'N'));
            if (isset($_alarm->seq)) {
                $_alarm->member = $this->member_lib->get_member($_alarm->member_seq);

                $this->load->library(array('sms_lib'));

                //SMS 발송
                $_member = $_alarm->member;
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
                    'msg_type'      => (mb_strwidth($msg)>=80)?'LMS':'SMS',                               // 문자타입(SMS - 80byte 이하, LMS - 2000byte 이하)
                    'schedule_type' => '0',                                 // (고정)
                    'schedule_date' => date("YmdHis"),              // 발송 시각 : YmdHis. 즉시 발송일 경우 현재 시각
                    'option1'       => '',                                  // 옵션1 :: 인증번호
                    'option2'       => '',                                  // 옵션2
                    'option3'       => '',                                  // 옵션3
                    'return_url'    => '',                                  // 발송 결과를 받는 리턴 URL
                );
                $_rlt = $this->sms_lib->send_single_message($arrInData);

                $this->db->trans_begin();

                $updateItemAlarmData = array(
                    'is_send'   => 'Y',
                    'send_date' => __TIME_YMDHIS__
                );
                $this->m_item_alarm->update($alarm_seq, $updateItemAlarmData);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $returnData = array(
                        'result' => "ERROR_UPDATE",
                        'msg' => "오류로 인해 발송에 실패했습니다."
                    );
                }
                else {
                    $this->db->trans_commit();
                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "성공적으로 발송 되었습니다."
                    );
                } // End if

            } else {
                $returnData = array(
                    'result' => "ERROR_NO_DATA",
                    'msg' => "발송 대상 정보를 찾을 수 없습니다. 새로고침 후 이용해주세요."
                );
            }
        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 수정에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }
}