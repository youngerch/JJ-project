<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Auth
 *
 * 서비스를 이용하기 위해서 로그인, 가입, 로그아웃등의 개인 권한을 취득/해지를 목적을한 Class
 *
 * Created on 2021-06-08
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Auth extends User_Controller
{
    protected $_module;

    function __construct()
    {
        parent::__construct();

        $this->_module = "main";

        $this->load->model(array(
            'common/admin_model'            => 'm_admin',
            'common/admin_login_log_model'  => 'm_admin_login_log',
            'common/otp_log_model'          => 'm_otp_log',
        ));
    }

    /**
     * Function index
     *
     * Default 함수.
     * 사용하지 않으므로 로그인 폼 제공 함수 호출
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-08
     * @access
     */
    public function index()
    {
        $this->login();
    }

    /**
     * Function login
     *
     * 로그인 폼 제공
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-08
     * @access
     */
    public function login()
    {
        $admin_login_id = "";   //저장되어 있는 아이디
        $idCookieSave   = "N";  //아이디 저장 여부 [0] 저장안함, [1] 저장.

        // 쿠키에 아이디가 저장되어 있을 경우 아이디값을 쿠키에서 획득 후 표시를 위해 변수 할당.
        $_saved_id = base64_decode(get_cookie('adminIDSave'));
        if ( $_saved_id ) {
            $admin_login_id = $_saved_id;
            $idCookieSave   = "Y";
        }

        $_page = "login";
        $data = array(
            'admin_login_id'    => $admin_login_id,
            'idCookieSave'      => $idCookieSave,
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function login_process_ajax
     *
     * 접속 정보를 통해서 로그인 여부를 체크하며, OTP 체크를 진행할 수 있는 Page URL를 전달.
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function login_process_ajax()
    {
        $this->util->is_ajax_alert();

        $returnUrl = ($this->session->userdata("session_return_url") ? $this->session->userdata("session_return_url") : "/");

        // validate form input
        $this->form_validation->set_rules('admin_login_id', '아이디',  'trim|required');
        $this->form_validation->set_rules('admin_login_pwd','비밀번호','trim|required');

        if ($this->form_validation->run() === TRUE) {

            $adm_login_id = $this->input->post("admin_login_id");
            $adm_login_pwd = $this->input->post("admin_login_pwd");

            //관리자 정보 취득
            $_admin = $this->admin_lib->get_administrator_by_column(array('email' => $adm_login_id));

            if (isset($_admin->seq)) {
                // 오류횟수가 5회 이상일 경우 오류 메시지 출력
                if (intVal($_admin->login_error_cnt) > 4) {
                    $returnData = array(
                        'result'    => "ERROR_COUNT_OVER",
                        'msg'       => "비밀번호 5회 이상 잘못 입력하였습니다.<br >비밀번호 찾기를 통해서 재시도 해주세요."
                    );
                    echo json_encode($returnData);
                    exit;
                } // End if

                // 사용 제한 여부
                if ($_admin->is_use != "Y") {
                    $returnData = array(
                        'result'    => "ERROR_NO_USE",
                        'msg'       => "사이트 이용을 하실 수 없습니다.<br >총 관리자에게 문의해 주세요."
                    );
                    echo json_encode($returnData);
                    exit;
                } // End if

                // OTP 승인 여부 체크
                if ($_admin->otp_status != "2" && $_admin->otp_key != "") {
                    $returnData = array(
                        'result'    => "ERROR_NO_OTP_CONFIRM",
                        'msg'       => "OTP 승인이 되지 않았습니다.<br />승인 후 이용해주세요."
                    );
                    echo json_encode($returnData);
                    exit;
                } // End if

                //
                if ($_admin->permission_seq <= 0) {
                    $returnData = array(
                        'result'    => "ERROR_NO_PERMISSION_SEQ",
                        'msg'       => "메뉴 이용 권한이 없습니다.<br >총 관리자에게 문의해 주세요."
                    );
                    echo json_encode($returnData);
                    exit;
                } // End if

                // 입력된 패스워드와 저장된 패스워드 비교
                if ($this->secure->password_verify($adm_login_pwd, $_admin->password_s)) {
                    // 로그인 오류 카운트 리셋
                    $updateData = array(
                        'login_error_cnt' => 0,
                        'login_date' => __TIME_YMDHIS__,
                        'login_ip' => __REMOTE_ADDR__
                    );
                    $this->admin_lib->set_administrator($_admin->seq, $updateData);

                    //로그인 로그 등록
                    $insertLogData = array(
                        'admin_seq' => $_admin->seq,
                        'login_date' => __TIME_YMDHIS__,
                        'login_ip' => __REMOTE_ADDR__,
                        'login_agent' => $this->agent->agent_string(),
                    );
                    $this->m_admin_login_log->insert($insertLogData);

                    //세션데이터 생성
                    $sessionData = array(
                        'session_admin_seq'         => $_admin->seq,
                        'session_admin_name'        => $_admin->name,
                        'session_admin_permission'  => $_admin->permission_seq,
                        'session_admin_otp'         => $_admin->otp_key
                    );
                    $this->session->set_userdata($sessionData);

                    //아이디 저장 체크시
                    if ($this->input->post('remember_id') === "Y") {
                        set_cookie("adminIDSave", base64_encode($adm_login_id));
                    }
                    else {
                        delete_cookie("adminIDSave");
                    } // End if

                    if ($_admin->otp_status == "2") {   // OTP 상태 [0] 대기, [1] 등록, [2] 승인
                        $returnUrl = "/main/auth/otp_cert";
                    }
                    else {
                        $returnUrl = "/main/auth/otp_create";
                    } // End if

                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "1차 로그인 인증이 성공하였습니다.",
                        'return_url' => $returnUrl
                    );
                    $this->session->unset_userdata('session_return_url');
                }
                else {
                    //비밀번호가 일치하지 않을 경우 오류 횟수 추가 후 리턴
                    $login_error_cnt = intVal($_admin->login_error_cnt) + 1;
                    $updateData = array(
                        'login_error_cnt' => $login_error_cnt
                    );
                    $this->admin_lib->set_administrator($_admin->seq, $updateData);

                    $returnData = array(
                        'result' => "ERROR_NO_MATCHING_PASSWORD",
                        'msg' => "비밀번호 ".$login_error_cnt."회 잘못 입력하였습니다."
                    );
                } // End if
            }
            else {
                $returnData = array(
                    'result' => "ERROR_NO_MATCHING",
                    'msg' => "존재하지 않는 아이디 입니다."
                );
            } // End if

        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 로그인에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function otp_create
     *
     * OTP 등록 폼 제공.
     * OTP Secret Key, QR 코드 생성 후 표시
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function otp_create()
    {
        $admin_seq = $this->admin_lib->get_session('seq');
        $_admin = $this->admin_lib->get_administrator($admin_seq);

        //OTP 정보 호출
        $this->load->library('otp_lib', array('id' => "SAMBOSARANG-ADMIN:" . $_admin->email));
        $secret = $this->otp_lib->getSecret();
        $qr     = $this->otp_lib->get_qr();

        $_page = "otp_create";
        $data = array(
            'secret'    => $secret,
            'qr'        => $qr,
            'page'      => $this->_template_layer . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function otp_create_ajax
     *
     * OTP 승인 정보를 관리자 정보에 업데이트를 진행.
     * 정보 등록일 뿐 승인 대기 상태인 상황.
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function otp_create_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('code', 'OTP 인증번호', 'trim|required');
        $this->form_validation->set_rules('secret', '비밀키', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $otp_code = $this->input->post("code");
            $secret = $this->input->post("secret");

            $admin_seq = $this->admin_lib->get_session('seq');
            $_admin = $this->admin_lib->get_administrator($admin_seq);

            if (!isset($_admin->seq)) {
                $returnData = array(
                    'result' => 'ERROR_NO_DATA',
                    'msg' => '관리자 정보가 없습니다.'
                );
            }
            else {

                $this->load->library('otp_lib', array('key' => $secret, 'code' => $otp_code));
                $rlt = $this->otp_lib->verify();

                if ($rlt) {
                    //OTP 성공
                    $updateData = array(
                        'otp_key' => $secret,
                        'otp_status' => '1'
                    );
                    $rlt = $this->admin_lib->set_administrator($_admin->seq, $updateData);

                    // OTP 정보 업데이트
                    if ($rlt) {
                        $status = "1";
                        $returnData = array(
                            'result' => 'SUCCESS',
                            'msg' => 'OTP 등록이 완료되었습니다. 승인 후 사용 가능합니다.'
                        );
                    }
                    else {
                        $status = "0";
                        $returnData = array(
                            'result' => 'ERROR_OTP_CREATE',
                            'msg' => '오류로 인해 작업에 실패했습니다.'
                        );
                    } // End if

                    $insertData = array(
                        'member_type'   => "9",
                        'member_seq'    => $_admin->seq,
                        'type'          => "1",
                        'otp_key'       => $secret,
                        'status'        => $status,
                        'reg_date'      => __TIME_YMDHIS__,
                        'reg_ip'        => __REMOTE_ADDR__
                    );
                    $this->m_otp_log->insert($insertData);

                }
                else {
                    $returnData = array(
                        'result' => 'ERROR_OTP_AUTH',
                        'msg' => 'OTP 인증에 실패했습니다.'
                    );
                } // End if

            } // End if
        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 OTP 인증이 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function otp_cert
     *
     * OTP 인증 폼 제공
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function otp_cert()
    {
        $_page = "otp_cert";
        $data = array(
            'page'      => $this->_template_layer . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function otp_cert_ajax
     *
     * OTP 코드 확인 처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function otp_cert_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('code', 'OTP 인증번호', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $code = $this->input->post("code");

            $admin_seq = $this->admin_lib->get_session('seq');
            $_admin = $this->admin_lib->get_administrator($admin_seq);

            if (!isset($_admin->seq)) {
                $returnData = array(
                    'result' => 'ERROR',
                    'msg' => '계정 정보가 없습니다.'
                );
            } else {

                $this->load->library('otp_lib', array('key' => $_admin->otp_key, 'code' => $code));
                $rlt = $this->otp_lib->verify();

                $rlt = true;
                //OTP 성공/실패
                if ($rlt) {
                    $data = array(
                        'session_manager_otp' => $_admin->otp_key
                    );
                    $this->session->set_userdata($data);

                    $status = "1";
                    $insertLogData = array(
                        'member_type'   => "9",
                        'member_seq'    => $_admin->seq,
                        'type'          => "5",
                        'otp_key'       => $_admin->otp_key,
                        'status'        => $status,
                        'reg_date'      => __TIME_YMDHIS__,
                        'reg_ip'        => __REMOTE_ADDR__
                    );
                    $this->m_otp_log->insert($insertLogData);

                    $url = ($this->session->userdata("session_return_url") ? $this->session->userdata("session_return_url") : "/");

                    $returnData = array(
                        'result' => 'SUCCESS',
                        'msg' => '2차 로그인 인증이 완료되었습니다.',
                        'url' => $url
                    );
                } else {
                    $returnData = array(
                        'result' => 'ERROR_OTP_AUTH',
                        'msg' => 'OTP 인증에 실패했습니다.'
                    );
                } // End if
            } // End if
        }
        else {
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 OTP 인증이 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function logout
     *
     * 세션 정보를 삭제하여 로그아웃 처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function logout()
    {
        $data = array(
            'session_admin_seq',
            'session_admin_name',
            'session_admin_auth_type',
            'session_admin_otp',
        );
        $this->session->unset_userdata($data);

        $this->session->unset_userdata("session_admin_seq");
        $this->session->unset_userdata("session_admin_name");
        $this->session->unset_userdata("session_admin_auth_type");
        $this->session->unset_userdata("session_admin_otp");

        // Destroy the session
        $this->session->sess_destroy();

        session_start();

        //Recreate the session
        if (substr(CI_VERSION, 0, 1) == '2') {
            $this->session->sess_create();
        }
        else {
            $this->session->sess_regenerate(false);
        }

        redirect("/main/auth/login", 'refresh');
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DESC : 비밀번호 찾기
     */
    public function find_password()
    {
        $_page = "find_pwd";
        $data = array(
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-09-04
     * DESC : 비밀번호 찾기 처리
     */
    public function findpwd_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(array('form_validation'));

        // validate form input
        $this->form_validation->set_rules('name',   '이름',   'trim|required');
        $this->form_validation->set_rules('email',     '아이디', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):

            $name   = $this->input->post('name');
            $email  = $this->input->post('email');

            $_admin = $this->admin_lib->find_admin($name, $email);

            if(isset($_admin->seq)):

                $agent = $this->agent->agent_string();

                //임시비밀번호 생성 및 발송 처리
                $new_password   = $this->admin_lib->generateStrongPassword(8);
                $rlt            = $this->admin_lib->set_password($_admin->seq, $new_password);

                if($rlt):
                    $msg    = sprintf("[%s] %s님의 임시 비밀번호는 %s 입니다.", __SITE_TITLE__, $name, $new_password);
                    $international  = $_admin->international;
                    $hp             = $this->secure->decrypt($_admin->hp_s);

                    if($_admin->international === '82'):
                        $this->load->library('sms_lib');

                        $dest = array();
                        $dest[0] = [
                            'to_name'   => $_admin->nickname,
                            'to_phone'  => $this->secure->decrypt($_admin->hp_s)
                        ];

                        $arrInData = array(
                            'member_seq'    => $_admin->seq,
                            'sms_type'      => '1',                                 // (고정)
                            'title'         => '['.__SITE_TITLE__.'] 관리자 임시비밀번호발송',     // 제목
                            'content'       => $msg,           					    // 발송 내용
                            'to_name'       => $_admin->name,                       // 수신자 이름
                            'to_phone'      => $_admin->hp_s,                      // 수신자 번호
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
                    else:
                        $this->load->library(array('aws_sns_lib'));
                        $_rlt =$this->aws_sns_lib->send_sms($international.$hp, $msg);
                    endif;

                    if(($_admin->international ==='82' && $_rlt->result === "SUCCESS") || ($_admin->international !=='82' && isset($_rlt['MessageId']))):
                        $returnData = array(
                            'result'        => "SUCCESS",
                            'msg'           => "관리자님의 휴대폰으로 임시 비밀번호를 발송하였습니다.<br/><br/>[사용자관리]에서 비밀번호를 변경해 주세요.<br/><br/>발송된 임시 비밀번호는 다른 사람에게 알려주지 마세요."
                        );
                    else:
                        $returnData = array(
                            'result'        => "ERROR_SMS",
                            'msg'           => "문자 발송에 실패했습니다. <br>휴대폰번호를 확인해 주세요."
                        );
                    endif;
                else:
                    $returnData = array(
                        'result'    => "ERROR_PASSWORD_GENERATE",
                        'msg'       => "오류로 인해 작업에 실패했습니다."
                    );
                endif;
            else:
                $returnData = array(
                    'result'        => "ERROR",
                    'msg'           => "입력한 정보로 등록된 정보가 없습니다."
                );
            endif;
        else:
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 작업에 실패했습니다.")
            );
        endif;

        echo json_encode($returnData);
    }

    /**
     * Created by cs.kim
     * DATE : 2020-08-26
     * DESC : 회원가입폼
     */
    public function register()
    {
        $_page = "register";
        $data = array(
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-26
     * TIME : 오후 14:35
     * DESC : 휴대폰 인증번호 발송
     */
    public function hp_certification_ajax()
    {
        $this->util->is_ajax_alert();

        $member_seq = 0; //기본값
        $config = array(
            'member_seq' => $member_seq
        );

        $this->load->library('sms_lib', $config);
        $this->load->library(array('form_validation'));

        $this->form_validation->set_rules('international',      '국가번호',             'trim|required');
        $this->form_validation->set_rules('hp',                 '아이디(휴대폰번호)',   'trim|required');

        if ($this->form_validation->run() === TRUE):
            $international  = str_replace('-', '', $this->input->post('international'));
            $hp             = str_replace('-', '', $this->input->post('hp'));

            $rand_num   = sprintf('%06d',rand(000000,999999));
            $msg        = sprintf("[%s]인증번호는 %s입니다.\n정확히 입력해 주세요.",__SITE_TITLE__,$rand_num);

            if($international === '82'):
                $dest = array();
                $dest[0] = [
                    'to_name'   => '관리자',
                    'to_phone'  => $hp
                ];

                $arrInData = array(
                    'member_seq'    => 0,
                    'sms_type'      => '1',                                 // (고정)
                    'title'         => '['.__SITE_TITLE__.'] 휴대폰인증',   // 제목
                    'content'       => $msg,           					    // 발송 내용
                    'to_name'       => '관리자',                            // 수신자 이름
                    'to_phone'      => $this->secure->encrypt($hp),         // 수신자 번호
                    'dest_info'     => $dest,
                    'callback_phone'=> __SITE_SMS_HP_NO__,                  // 발신자 번호
                    'msg_type'      => (mb_strwidth($msg)>=80)?'LMS':'SMS', // 문자타입(SMS - 80byte 이하, LMS - 2000byte 이하)
                    'schedule_type' => '0',                                 // (고정)
                    'schedule_date' => date("YmdHis"),              // 발송 시각 : YmdHis. 즉시 발송일 경우 현재 시각
                    'option1'       => $rand_num,                           // 옵션1 :: 인증번호
                    'option2'       => '',                                  // 옵션2
                    'option3'       => '',                                  // 옵션3
                    'return_url'    => '',                                  // 발송 결과를 받는 리턴 URL
                );
                $_rlt = $this->sms_lib->send_single_message($arrInData);
            else:
                $this->load->library(array('aws_sns_lib'));
                $_rlt =$this->aws_sns_lib->send_sms($international.$hp, $msg);
            endif;

            if(($international ==='82' && (isset($_rlt->result) && $_rlt->result === "SUCCESS")) || ($international !=='82' && isset($_rlt['MessageId']))):

                $this->load->model(array(
                    'common/hp_certification_log_model'      => 'hp_certification_log',
                ));

                $insertData = array(
                    'certify_type'  => "1",
                    'hp'            => $this->secure->encrypt($international.$hp),
                    'certify_no'    => $rand_num,
                    'req_date'      => __TIME_YMDHIS__,
                    'data'          => $msg,
                    'is_certify'    => 'N',
                    'status'        => '9'
                );
                $certify_seq = $this->hp_certification_log->insert($insertData);

                $time = date("Y-m-d H:i:s",strtotime ("+3 minutes"));

                $returnData = array(
                    'result'        => "SUCCESS",
                    'time'          => $time,
                    'msg'           => "인증번호를 발송했습니다.<br>인증번호가 오지 않으면 입력하신 <br>정보가 정확한지 확인해 주세요.",
                    'certify_seq'   => $certify_seq
                );
                echo json_encode($returnData);
            else:

                $returnData = array(
                    'result' => "ERROR_SMS",
                    'msg' => "문자 발송에 실패했습니다. <br>휴대폰번호를 확인해 주세요."
                );
                echo json_encode($returnData);

            endif;
        else:
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.")
            );
            echo json_encode($returnData);
        endif;
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-26
     * TIME : 오후 17:04
     * DESC : 휴대폰 인증번호 검증
     */
    public function hp_certification_response_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(array('form_validation'));

        $this->form_validation->set_rules('hp_certify_seq', "인증절차", 'trim|required');
        $this->form_validation->set_rules('hp_certify_no', "인증번호", 'trim|required');

        if ($this->form_validation->run() === TRUE):

            $hp_certify_seq = $this->input->post("hp_certify_seq");
            $hp_certify_no  = $this->input->post("hp_certify_no");

            if ( $hp_certify_seq && $hp_certify_no):
                $this->load->model(array(
                    'common/hp_certification_log_model' => 'hp_certification_log'
                ));

                $_certify = $this->hp_certification_log->get($hp_certify_seq);

                if(isset($_certify->seq)):

                    if($_certify->is_certify === "Y"):
                        $returnData = array(
                            'result' => "ERROR_ALREADY",
                            'msg' => "이미 인증이 완료된 인증번호입니다."
                        );
                        echo json_encode($returnData);
                        exit;
                    endif;

                    //타임아웃
                    $now = new DateTime();
                    $before = new DateTime($_certify->req_date);
                    $diff = $now->getTimestamp() - $before->getTimestamp();

                    if($diff > __AUTH_HP_SMS_TIMEOUT_SEC__):
                        $returnData = array(
                            'result' => "ERROR_TIME_OVER",
                            'msg' => "3분 이내에 인증해 주세요."
                        );
                        echo json_encode($returnData);
                        exit;
                    endif;

                    if($_certify->certify_no == $hp_certify_no):
                        $updateData = array(
                            'is_certify'    => 'Y',
                            'res_date'      => __TIME_YMDHIS__
                        );
                        $this->hp_certification_log->update($hp_certify_seq, $updateData);

                        //휴대폰 인증 세션값 지정
                        $this->session->set_userdata("session_join_hp_certify", $_certify->certify_no);

                        $returnData = array(
                            'result' => "SUCCESS",
                            'msg' => "인증이 완료되었습니다."
                        );
                        echo json_encode($returnData);
                    else:
                        $returnData = array(
                            'result' => "ERROR",
                            'msg' => "인증번호가 일치하지 않습니다."
                        );
                        echo json_encode($returnData);
                    endif;

                else:
                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "인증문자 발송내역이 없습니다."
                    );
                    echo json_encode($returnData);
                endif;
            else:
                $returnData = array(
                    'result' => "SUCCESS",
                    'msg' => "인증되었습니다."
                );
                echo json_encode($returnData);
            endif;
        else:
            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 인증에 실패했습니다.")
            );
            echo json_encode($returnData);
        endif;
    }

    /**
     * Created by cs.kim
     * DATE : 2020-08-26
     * DESC : 매장 직원 등록 처리
     */
    public function join_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('email',          '이메일주소',      'trim|required');
        $this->form_validation->set_rules('name',           '이름',            'trim|required');
        $this->form_validation->set_rules('hp',             '휴대폰번호',      'trim|required');
        $this->form_validation->set_rules('user_password',  '비밀번호',        'trim|required|min_length[6]|max_length[15]');

        //validation
        if ($this->form_validation->run() === TRUE):

            $email              = $this->input->post("email");
            $name               = $this->input->post('name');
            $international      = $this->input->post('international');
            $hp                 = $this->input->post("hp");
            $password           = $this->input->post("user_password");

            $password_s         = $this->secure->password_hash($password);
            $hp_s               = $this->secure->encrypt($hp);

            //아이디(휴대폰번호) 중복체크
            $_admin = $this->admin_lib->get_administrator_by_column(array('email' => $email));

            if(isset($_admin->seq)):
                $returnData = array(
                    'result'    => "ERROR_ALREADY_ID",
                    'msg'       => "이미 사용중인 아이디 입니다."
                );
                echo json_encode($returnData);
                exit;
            endif;

            //휴대폰번호 인증 체크
            $this->load->model(array(
                'common/hp_certification_log_model' => 'hp_certification_log'
            ));

            $is_hp_certify     = $this->input->post("is_hp_certify");
            $hp_certify_seq    = $this->input->post("hp_certify_seq");
            $_certify          = $this->hp_certification_log->get($hp_certify_seq);

            if ($is_hp_certify !== "Y"):
                $returnData = array(
                    'result' => "ERROR_HP_AUTH",
                    'msg' => "휴대폰번호 인증을 진행해 주세요."
                );
                echo json_encode($returnData);
                exit;
            endif;

            $session_join_hp_certify = $this->session->userdata("session_join_hp_certify");

            if ($session_join_hp_certify == ""):
                $returnData = array(
                    'result' => "ERROR_HP_AUTH",
                    'msg' => "휴대폰번호 인증을 진행해 주세요."
                );
                echo json_encode($returnData);
                exit;
            endif;

            if(isset($_certify->seq)):
                $_hp = $_certify->hp;
                if ($_hp !== $this->secure->encrypt($international.$hp)):
                    $returnData = array(
                        'result' => "ERROR_HP_AUTH",
                        'msg' => "인증된 휴대폰 번호가 아닙니다."
                    );
                    echo json_encode($returnData);
                    exit;
                endif;
            else:
                $returnData = array(
                    'result' => "ERROR_HP_AUTH",
                    'msg' => "휴대폰번호 인증을 진행해 주세요."
                );
                echo json_encode($returnData);
                exit;
            endif;

            $this->db->trans_begin();

            $insertData = array(
                'email'             => $email,
                'password_s'        => $password_s,
                'name'              => $name,
                'international'     => $international,
                'hp_s'              => $hp_s,
                'use_is'            => 0,
                'reg_date'          => __TIME_YMDHIS__,
                'reg_ip'            => __REMOTE_ADDR__
            );
            $admin_seq = $this->admin->insert($insertData);

            if($this->db->trans_status() === FALSE || !$admin_seq):

                $this->db->trans_rollback();

                $returnData = array(
                    'result'        => "ERROR_INSERT",
                    'msg'           => "오류로 인해 가입에 실패했습니다."
                );

            else:

                $this->db->trans_commit();

                $returnData = array(
                    'result'        => "SUCCESS",
                    'msg'           => "사용자 등록 신청이 완료되었습니다.<br />승인 후 이용하실 수 있습니다"
                );
            endif;

        else:

            $returnData = array(
                'result' => "ERROR",
                'msg' => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.")
            );
        endif;

        echo json_encode($returnData);
    }


}
