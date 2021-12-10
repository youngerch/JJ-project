<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Member
 *
 * 회원관리
 *
 * Created on 2021-06-09
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Member extends Sub_Controller
{
    protected $_module;
    protected $_rows = 20;

    function __construct()
    {
        parent::__construct();

        $this->_module = "member";
        $this->_menu_item = 'member';

        $this->load->model(array(
            'common/member_model'           => 'm_member',
            'common/member_memo_model'      => 'm_member_memo',
            'common/subscription_log_model' => 'm_subscription_log'
        ));

        $this->load->library(array(
            'member_lib'
        ));

    }

    /**
     * Function index
     *
     * 인덱스
     * 회원목록으로 전환처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function index()
    {
        if (in_array("member", $this->_accessible_arr)) {
            $this->lists(1);
        } else {
            $this->alert->error("권한이 없습니다.", __SITE_TITLE__, "/");
        }
    }

    /**
     * Function lists
     *
     * 회원목록
     *
     * @param $status 회원 상태 정보 [1] 활동, [5] 휴면, [9] 탈퇴 -- 휴면회원 노출은 없음.
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function lists($status)
    {
        switch ( $status ) {
            case "1" :
                $this->_menu_item = "active";
                break;
            case "9" :
                $this->_menu_item = "leave";
                break;
            default :
                $status = 1;
                $this->_menu_item = "active";
                break;
        } // End Switch

        $current_page = $this->input->get('current_page') ? $this->input->get('current_page') : 1;
        $from_record = ($current_page - 1) * $this->_rows;

        $date_type      = $this->input->get('date_type') ? $this->input->get('date_type') : "A.join_date";
        $date_start     = $this->input->get('date_start') ? $this->input->get('date_start') : "2021-02-01";
        $date_end       = $this->input->get('date_end') ? $this->input->get('date_end') : Date('Y-m-t');
        $sch_key        = $this->input->get('sch_key') ? $this->input->get('sch_key') : "A.login_id";
        $sch_str        = $this->input->get('sch_str');
        $sch_advisor    = $this->input->get('sch_advisor') ? $this->input->get('sch_advisor') : "N";
        $grade          = $this->input->get('grade');
        $star_grade     = $this->input->get('star_grade');
        $sch_auto_bill  = $this->input->get('sch_auto_bill');

        if ( $status == "9" ) {
            if ( $sch_str != "" && $sch_key == "hp_s" ) {
                $sch_str .= "-withdrawal";
            } // End if
        } // End if

        $whereData = array(
            'status'            => $status,
            'date_type'         => $date_type,
            'date_start'        => $date_start,
            'date_end'          => $date_end,
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode($sch_str),
            'sch_advisor'       => $sch_advisor,
            'grade'             => $grade,
            'star_grade'        => $star_grade,
            'sch_auto_bill'     => $sch_auto_bill,
        );
        $lists      = $this->m_member->get_list_all($from_record, $this->_rows, $whereData);
        $totalCnt   = $this->m_member->get_count_all($whereData);
        $totalPage  = ceil($totalCnt / $this->_rows);

        $list = array();
        $i = 0;
        foreach ($lists as $key => $row) {
            $row->no = $totalCnt - $from_record - $i;
            $row->login_id = $this->secure->decrypt($row->login_id_s);

            $row->advisor = "";
            if (!empty($row->recommend_seq)) {
                $recommend = $this->m_member->get($row->recommend_seq);
                $row->advisor = $recommend->nickname;
            } // End if

            $row->subscription = $this->member_lib->get_member_subscription($row->seq);
            
            $list[$i] = $row;
            $i++;
        } // End foreach

        $_page = "member_list";
        $data = array(
            'status'            => $status,
            'date_type'         => $date_type,
            'date_start'        => $date_start,
            'date_end'          => $date_end,
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode(str_replace("-withdrawal", "", $sch_str)),
            'sch_advisor'       => $sch_advisor,
            'grade'             => $grade,
            'star_grade'        => $star_grade,
            'sch_auto_bill'     => $sch_auto_bill,
            'lists'             => arrayToObject($list),
            'currentPage'       => intVal($current_page),
            'perPage'           => intVal($this->_rows),
            'totalPage'         => intVal($totalPage),
            'totalCount'        => intVal($totalCnt),         // 총 갯수
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function detail
     *
     * 회원 정보 상세 보기 ( layer )
     *
     * @param $member_seq 회원 순번
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2020-08-27
     * @access
     */
    public function detail($member_seq)
    {
        $_info = $this->member_lib->get_member($member_seq);

        $_page = "member_detail";
        $data = array(
            'info'          => arrayToObject($_info),
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );

        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function member_memo_ajax
     *
     * 회원 메모 내역
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2020-08-27
     * @access
     */
    public function member_memo_ajax()
    {
        $_member_seq = $this->input->post('member_seq');

        //회원에 대한 메모 정보
        $whereData = array('member_seq' => $_member_seq);
        $memo_total = $this->m_member_memo->get_count_all($whereData);
        $from_record  = 0;
        $rows         = intVal($memo_total);
        $memo         = $this->m_member_memo->get_list_all($from_record, $rows, $whereData);
        //$total_page   = ceil($total_cnt / $rows);
        $i = 0;
        $lists = array();
        foreach ($memo as $key=>$row) {
            $row->no = $memo_total - $from_record - $i;
            $row->admin_name = $this->admin_lib->get_administrator($row->reg_admin_seq)->name;
            $lists[] = $row;
            $i++;
        } // End foreach

        $_page = "member_memo_list";
        $data = array(
            'lists'         => arrayToObject($lists),
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function memo_create_process_ajax
     *
     * 회원 메모 등록 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2020-08-27
     * @access
     */
    public function memo_create_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('lyr_member_seq', '회원 시퀀스', 'trim|required');
        $this->form_validation->set_rules('lyr_member_memo', '메모내용', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $member_seq = $this->input->post('lyr_member_seq');
            $memo = $this->input->post("lyr_member_memo");

            $insertData = array(
                'member_seq' => $member_seq,
                'memo' => $memo,
                'reg_date' => __TIME_YMDHIS__,
                'reg_ip' => __REMOTE_ADDR__,
                'reg_admin_seq' => $this->_admin->seq
            );
            $this->db->trans_begin();

            $this->m_member_memo->insert($insertData);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $returnData = array(
                    'result' => "ERROR_INSERT",
                    'msg' => "오류로 인해 등록에 실패했습니다."
                );
            }
            else {
                $this->db->trans_commit();
                $returnData = array(
                    'result' => "SUCCESS",
                    'msg' => "정보수정이 성공적으로 완료되었습니다."
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
     * Function change_password_ajax
     *
     * 회원에게 임시 비밀번호 발송
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-23
     * @access
     */
    public function change_password_ajax()
    {
        $this->util->is_ajax_alert();

        $this->form_validation->set_rules('member_seq', '회원 순번', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $member_seq = $this->input->post('member_seq');
            $_member = $this->member_lib->get_member($member_seq);

            if ($_member) {
                $international = $_member->international;
                $hp = $_member->hp;

                $agent = $this->agent->agent_string();
                //임시비밀번호 생성 및 발송 처리
                $new_password = $this->member_lib->generateStrongPassword(8);
                $rlt = $this->member_lib->set_password($new_password, $_member->seq, $agent);

                if ($rlt) {
                    $msg = sprintf("[%s] %s님의 임시 비밀번호는 %s 입니다.", __SITE_TITLE__, $_member->hp, $new_password);

                    if ($international === '82') {
                        $this->load->library('sms_lib');

                        $dest = array();
                        $dest[0] = [
                            'to_name'   => $_member->nickname,
                            'to_phone'  => $_member->hp
                        ];

                        $arrInData = array(
                            'member_seq'    => $_member->seq,
                            'sms_type'      => '1',                                 // (고정)
                            'title'         => '['.__SITE_TITLE__.'] 임시비밀번호발송',     // 제목
                            'content'       => $msg,           					    // 발송 내용
                            'to_name'       => $_member->nickname,                  // 수신자 이름
                            'to_phone'      => $_member->hp_s,                      // 수신자 번호
                            'dest_info'     => $dest,
                            'callback_phone'=> __SITE_SMS_HP_NO__,                  // 발신자 번호
                            'msg_type'      => (mb_strwidth($msg)>=80)?'LMS':'SMS',                               // 문자타입(SMS - 80byte 이하, LMS - 2000byte 이하)
                            'schedule_type' => '0',                                 // (고정)
                            'schedule_date' => date("YmdHis"),              // 발송 시각 : YmdHis. 즉시 발송일 경우 현재 시각
                            'option1'       => '',                                  // 옵션1 :: 인증번호
                            'option2'       => $new_password,                       // 옵션2
                            'option3'       => '',                                  // 옵션3
                            'return_url'    => '',                                  // 발송 결과를 받는 리턴 URL
                        );
                        $_rlt = $this->sms_lib->send_single_message($arrInData);
                    } else {
                        $this->load->library(array('aws_sns_lib'));
                        $_rlt = $this->aws_sns_lib->send_sms($international . $hp, $msg);
                    } // End if

                    if (($international === '82' && $_rlt->result === "SUCCESS") || ($international !== '82' && isset($_rlt['MessageId']))) {
                        $returnData = array(
                            'result' => "SUCCESS",
                            'msg' => "회원님의 휴대폰으로 임시 비밀번호를 발송하였습니다."
                        );
                    } else {
                        $returnData = array(
                            'result' => "ERROR_SMS",
                            'msg' => "문자 발송에 실패했습니다. <br>휴대폰번호를 확인해 주세요."
                        );
                    } // End if
                } else {
                    $returnData = array(
                        'result' => "ERROR_PASSWORD_GENERATE",
                        'msg' => "오류로 인해 비밀번호 재설정에 실패했습니다."
                    );
                } // End if
            } else {
                $returnData = array(
                    'result' => "ERROR",
                    'msg' => "회원 정보를 확인하지 못하였습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 비밀번호 재설정에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function member_grade_change_process_ajax
     *
     * 회원 등급 변경 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-23
     * @access
     */
    public function member_grade_change_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->form_validation->set_rules('member_seq', '회원순번',      'trim|required');
        $this->form_validation->set_rules('change_grade', '멤버십등급',    'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $member_seq = $this->input->post('member_seq');
            $change_grade = $this->input->post('change_grade');
            $is_membership = "1";

            if ($change_grade == "0"):
                $is_membership = "0";
            endif;

            $this->db->trans_begin();

            $updateMemberData = array(
                'is_membership' => $is_membership,
                'grade' => $change_grade
            );
            $rlt = $this->member_lib->set_member($member_seq, $updateMemberData);

            if ($this->db->trans_status() === FALSE || !$rlt) {
                $this->db->trans_rollback();
                $returnData = array(
                    'result' => "ERROR",
                    'msg' => "오류로 인해 변경에 실패하였습니다."
                );
            } else {
                $this->db->trans_commit();
                $returnData = array(
                    'result' => "SUCCESS",
                    'msg' => "정상적으로 변경 처리되었습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 변경에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function member_subscription
     *
     * 회원 정기결제 내역 레이아웃
     *
     * @param $member_seq 회원 순번
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-23
     * @access
     */
    public function member_subscription($member_seq)
    {
        $_page = "member_subscription_layout";
        $data = array(
            'member_seq'    => $member_seq,
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function member_subscription_list_ajax
     *
     * 회원 정기결제 내역 리스트
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-23
     * @access
     */
    public function member_subscription_list_ajax()
    {
        $member_seq = $this->input->post('seq');
        $page       = $this->input->post('page');

        $whereData = array(
            'member_seq'    => $member_seq,
        );
        $from_record    = ($page - 1) * 10; //$this->_rows;
        $lists          = $this->m_subscription_log->get_list_all($from_record, 10, $whereData);
        $totalCnt       = $this->m_subscription_log->get_count_all($whereData);

        $list = array();
        $i = 0;
        foreach ($lists as $key => $row):
            //$row->no = $totalCnt - $from_record - $i;

            $list[$i] = $row;
            $i++;
        endforeach;

        $_page = "member_subscription_list";
        $data = array(
            'p'             => $page,
            'lists'         => arrayToObject($list),
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );
        $html = $this->load->view($this->_container_layer, $data, true);

        $rlt = array(
            'perpage'   => $this->_rows,
            'page'      => $page,
            'totalcnt'  => $totalCnt,
            'html'      => $html
        );
        echo json_encode($rlt);
    }

    /**
     * Function member_point_ajax
     *
     * 회원 포인트 적립/사용 내역
     * Kim Chang Soo - 더 이상 사용하지 않음 on 2021-06-23
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-23
     * @access
     */
    public function member_point_ajax()
    {
        $seq                = $this->input->post('seq');
        $page               = $this->input->post('page');
        $date_start         = "2020-01-01";
        $date_end           = "9999-12-31";
        $point_class        = "";
        $shop_name          = "";

        $whereData = array(
            'A.member_seq'        => $seq,
            'date_start'        => $date_start,
            'date_end'          => $date_end,
            'point_class'       => $point_class,
            'shop_name'         => urldecode($shop_name)
        );

        $perpage        = 10;
        $from_record    = ($page - 1) * $perpage;
        $lists          = $this->point->get_list_all($from_record, $perpage, $whereData);
        $totalCnt       = $this->point->get_count_all($whereData);

        $list = array();
        $i=0;
        foreach ($lists as $key => $row):
            $row->no = $totalCnt - $from_record - $i;

            $list[$i] = $row;
            $i++;
        endforeach;

        $_page = "member_point_list";
        $data = array(
            'lists'         => arrayToObject($list),
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );
        $html = $this->load->view($this->_container_layer, $data, true);

        $resData = array (
            'perpage'   => $perpage,
            'totalcnt'  => $totalCnt,
            'html'      => $html
        );
        echo json_encode($resData);
    }

    /**
     * Function update_password_error_reset_ajax
     *
     * 로그인 오류 횟수 초기화
     * Kim Chang Soo - 더 이상 사용하지 않음 on 2021-06-23
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-23
     * @access
     */
    public function update_password_error_reset_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('member_seq',         '회원 순번', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):
            $member_seq = $this->input->post('member_seq');

            if($this->agent->is_mobile()):
                $agent = 'M';
            else:
                $agent = 'P';
            endif;

            $this->db->trans_begin();

            $this->member_lib->set_password_error_init($member_seq, $agent);

            if($this->db->trans_status() === FALSE):

                $this->db->trans_rollback();

                $returnData = array(
                    'result'    => "ERROR_INSERT",
                    'msg'       => "오류로 인해 비밀번호 오류 초기화에 실패했습니다."
                );

            else:
                $this->db->trans_commit();

                $returnData = array(
                    'result'    => "SUCCESS",
                    'msg'       => "비밀번호 오류 초기화가 성공적으로 처리되었습니다."
                );
            endif;
        else:
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 비밀번호 오류 초기화에 실패했습니다.")
            );
        endif;

        echo json_encode($returnData);
    }
}
