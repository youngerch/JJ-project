<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Admin
 *
 * 운영자관리
 *
 * Created on 2021-06-14
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Admin extends Sub_Controller
{
    protected $_module;
    protected $_rows = 20;

    function __construct()
    {
        parent::__construct();

        if (!in_array("admin", $this->_accessible_arr)) {
            $this->alert->error("권한이 없습니다.", __SITE_TITLE__, "/");
        }

        $this->_module = "admin";
        $this->_menu_item = 'admin';

        $this->load->model(array(
            'common/admin_model'            => 'm_admin',
            'common/admin_permission_model' => 'm_admin_permission'
        ));

    }

    /**
     * Function index
     *
     * 인덱스
     * 운영자목록으로 전환처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-14
     * @access
     */
    public function index()
    {
        $this->lists();
    }

    /**
     * Function lists
     *
     * 운영자 목록
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-14
     * @access
     */
    public function lists()
    {
        $this->_menu_item = 'lists';

        $current_page = $this->input->get('current_page') ? $this->input->get('current_page') : 1;
        $sch_key        = $this->input->get('sch_key') ? $this->input->get('sch_key') : "A.login_id";
        $sch_str        = $this->input->get('sch_str');

        $whereData = array(
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode($sch_str),
        );
        $from_record= ($current_page - 1) * $this->_rows;
        $lists      = $this->m_admin->get_list_all($from_record, $this->_rows, $whereData);
        $totalCnt   = $this->m_admin->get_count_all($whereData);
        $totalPage  = ceil($totalCnt / $this->_rows);

        $i = 0;
        $list = array();
        foreach ($lists as $key => $row) {

            if($row->permission_seq) {
                $row->permission = $this->m_admin_permission->get_name($row->permission_seq);
            }

            $row->no = $totalCnt - $from_record - $i;

            $list[$i] = $row;
            $i++;
        } // End foreach;

        $_page = "admin_list";
        $data = array(
            'lists'         => arrayToObject($list),
            'currentPage'   => intval($current_page),
            'perPage'       => intVal($this->_rows),
            'totalPage'     => intval($totalPage),
            'totalCount'    => intval($totalCnt),         // 총 갯수
            'sch_key'       => $sch_key,
            'sch_str'       => urldecode(str_replace("-withdrawal", "", $sch_str)),
            'page'          => $this->_template . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-30
     * TIME : 오전 11:02
     * DESC : 신규 등록 폼
     */
    public function create()
    {
        $this->_menu_item = 'lists';
        $whereData = array(
            'is_use'    => 'Y'
        );
        $permission_list = $this->m_admin_permission->get_list_all(0, $this->_rows, $whereData);
        $level_list = array(
            '9' => '슈퍼관리자',
            '5' => '관리자',
            '1' => '운영자'
        );

        $_page = "admin_create";
        $data = array(
            'level_list'        => $level_list,
            'permission_list'   => $permission_list,
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-30
     * TIME : 오전 11:02
     * DESC : 이메일 중복 체크
     * @param $email
     */
    public function email_check_ajax($email)
    {
        $email = urldecode($email);

        $check_email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($check_email === false):

            $returnData = array(
                'result' => "ERROR_NOT_VALIDATE",
                'msg' => "이메일 형식이 올바르지 않습니다."
            );
            echo json_encode($returnData);
            exit;
        endif;

        $is_email = $this->m_admin->count_by(array('email' => $email));

        if ($is_email > 0):
            $returnData = array(
                'result' => "ERROR_ALREADY",
                'msg' => "이미 사용중인 이메일입니다."
            );
        else:
            $returnData = array(
                'result' => "SUCCESS",
                'msg' => "사용이 가능한 이메일입니다."
            );
        endif;

        echo json_encode($returnData);
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-30
     * TIME : 오전 11:10
     * DESC : 등록 처리
     */
    public function create_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('email',          '이메일', 'trim|required|valid_email');
        $this->form_validation->set_rules('password',       '비밀번호', 'required|min_length[8]|matches[password_re]');
        $this->form_validation->set_rules('password_re',    '비밀번호 확인', 'required');
        $this->form_validation->set_rules('name',           '관리자명', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):

            $email      = $this->input->post("email");
            $password   = $this->input->post("password");
            $name       = $this->input->post("name");
            $hp         = $this->input->post("hp");
            $level      = $this->input->post("level");
            $permission = $this->input->post("permission");
            $department = $this->input->post("department");
            $position   = $this->input->post("position");
            $is_use     = $this->input->post("is_use");

            $password_hash = $this->secure->password_hash($password);

            $insertData = array(
                'email'             => $email,
                'password_s'        => $password_hash,
                'name'              => $name,
                'hp_s'              => $this->secure->encrypt($hp),
                'admin_level'       => $level,
                'permission_seq'    => $permission,
                'department'        => $department,
                'position'          => $position,
                'is_use'            => $is_use,
                'reg_date'          => __TIME_YMDHIS__,
                'reg_ip'            => __REMOTE_ADDR__,
                'reg_admin_seq'     => $this->_admin->seq
            );
            $seq = $this->m_admin->insert($insertData);

            if ($seq):
                $result = "SUCCESS";
                $msg = "관리자가 성공적으로 등록되었습니다.";
            else:
                $result = "SUCCESS";
                $msg = "오류로 인해 작업에 실패했습니다.";
            endif;

        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");

        endif;

        $returnData = array(
            'result' => $result,
            'msg' => $msg
        );
        echo json_encode($returnData);
        exit;
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-30
     * TIME : 오전 11:10
     * DESC : 사용여부 변경
     * @param $admin_seq
     * @param $use
     */
    public function use_ajax($admin_seq, $use)
    {
        $rlt = $this->m_admin->update($admin_seq, array('is_use' => $use, 'mod_date' => __TIME_YMDHIS__, 'mod_ip' => __REMOTE_ADDR__, 'mod_admin_seq' => $this->_admin->seq));

        if ($rlt):
            $returnData = array(
                'result' => "SUCCESS",
                'msg' => "상태값이 변경되었습니다."
            );

        else:
            $returnData = array(
                'result' => "ERROR",
                'msg' => "오류로 인해 작업에 실패했습니다."
            );
        endif;

        echo json_encode($returnData);
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-30
     * TIME : 오전 11:15
     * DESC : 변경 폼
     * @param $admin_seq
     */
    public function edit($admin_seq)
    {
        $this->_menu_item = 'lists';
        if ( $this->_admin->seq != $admin_seq):
            $this->alert->error("수정 권한이 없습니다.", __SITE_TITLE__, "/");
        endif;

        $admin = $this->m_admin->get($admin_seq);

        $whereData = array(
            'is_use'    => 'Y'
        );
        $permission_list = $this->m_admin_permission->get_list_all(0, $this->_rows, $whereData);
        $level_list = array(
            '9' => '슈퍼관리자',
            '5' => '관리자',
            '1' => '운영자'
        );

        $_page = "admin_edit";
        $data = array(
            'admin'             => $admin,
            'level_list'        => $level_list,
            'permission_list'   => $permission_list,
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-30
     * TIME : 오전 11:15
     * DESC : 변경 처리
     */
    public function edit_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('seq', '관리자순번', 'trim|required');
        $this->form_validation->set_rules('name', '관리자명', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):

            $seq            = $this->input->post("seq");
            $password       = $this->input->post("password");
            $password_re    = $this->input->post("password_re");
            $name           = $this->input->post("name");
            $hp             = $this->input->post("hp");
            $level          = $this->input->post("level");
            $permission     = $this->input->post("permission");
            $department     = $this->input->post("department");
            $position       = $this->input->post("position");
            $is_use         = $this->input->post("is_use");

            $updateData = array(
                'name'              => $name,
                'hp_s'              => $this->secure->encrypt($hp),
                'admin_level'       => $level,
                'permission_seq'    => $permission,
                'department'        => $department,
                'position'          => $position,
                'is_use'            => $is_use,
                'mod_date'          => __TIME_YMDHIS__,
                'mod_ip'            => __REMOTE_ADDR__,
                'mod_admin_seq'     => $this->_admin->seq
            );

            if ($password !== "" && $password === $password_re):
                $password_hash = $this->secure->password_hash($password);
                $updateData['password_s'] = $password_hash;
            endif;

            $rlt = $this->m_admin->update($seq, $updateData);

            if ($rlt):
                $result = "SUCCESS";
                $msg = "관리자 정보가 성공적으로 변경되었습니다.";
            else:
                $result = "SUCCESS";
                $msg = "오류로 인해 작업에 실패했습니다.";
            endif;

        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");

        endif;

        $returnData = array(
            'result' => $result,
            'msg' => $msg
        );
        echo json_encode($returnData);
        exit;
    }

    public function delete_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('seq', '관리자순번', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):
        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");

        endif;

        $returnData = array(
            'result' => $result,
            'msg' => $msg
        );
        echo json_encode($returnData);
        exit;
    }

    public function otp_status_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('seq', '관리자 시퀀스', 'trim|required');
        $this->form_validation->set_rules('status', 'OTP 상태', 'trim|required');
        //validation
        if ($this->form_validation->run() === TRUE):
            $admin_seq = $this->input->post('seq');
            $otp_status = $this->input->post('status');

            $updateOTPStatusData = array (
                'otp_status'    => $otp_status
            );
            $this->m_admin->update($admin_seq, $updateOTPStatusData);

            $result = "SUCCESS";
            $msg = "성공적으로 상태가 변경되었습니다.";
        else:
            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");
        endif;

        $returnData = array(
            'result' => $result,
            'msg' => $msg
        );
        echo json_encode($returnData);
    }

    /**
     * Function lists
     *
     * 운영자 권한관리
     *
     * @author Eunjung Moon <ejmoon@ablex.co.kr> on 2021-10-27
     * @access
     */
    public function permission()
    {
        $this->_menu_item = 'permission';

        $current_page   = $this->input->get('current_page') ? $this->input->get('current_page') : 1;
        $sch_key        = $this->input->get('sch_key') ? $this->input->get('sch_key') : "A.name";
        $sch_str        = $this->input->get('sch_str');

        $whereData = array(
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode($sch_str),
        );
        $from_record= ($current_page - 1) * $this->_rows;
        $lists      = $this->m_admin_permission->get_list_all($from_record, $this->_rows, $whereData);
        $totalCnt   = $this->m_admin_permission->get_count_all($whereData);
        $totalPage  = ceil($totalCnt / $this->_rows);

        $i = 0;
        $list = array();
        foreach ($lists as $key => $row) {
            $row->no = $totalCnt - $from_record - $i;

            $list[$i] = $row;

            $i++;
        } // End foreach;


        $_page = "permission_list";
        $data = array(
            'lists'         => arrayToObject($list),
            'currentPage'   => intval($current_page),
            'perPage'       => intVal($this->_rows),
            'totalPage'     => intval($totalPage),
            'totalCount'    => intval($totalCnt),         // 총 갯수
            'sch_key'       => $sch_key,
            'sch_str'       => urldecode(str_replace("-withdrawal", "", $sch_str)),
            'page'          => $this->_template . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Ejmoon
     * USER : ablex
     * DATE : 2021-10-27
     * TIME : 오전 2:30
     * DESC : 권한관리 신규 등록 폼
     */
    public function permission_create()
    {
        $_page = "permission_create";
        $data = array(
            'page' => $this->_template . $_page,
            'module' => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Ejmoon
     * USER : ablex
     * DATE : 2021-11-01
     * TIME : 오후 1:30
     * DESC : 권한관리 등록 처리
     */
    public function permission_create_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('name', '등급명', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):

            $name               = $this->input->post("name");
            $accessible_menu    = $this->input->post('_accessible_menu');
            $is_use             = $this->input->post("is_use");

            $insertData = array(
                'name'              => $name,
                'accessible_menu'   => $accessible_menu,
                'is_use'            => $is_use,
                'reg_date'          => __TIME_YMDHIS__,
                'reg_ip'            => __REMOTE_ADDR__,
                'reg_admin_seq'     => $this->_admin->seq
            );
            $seq = $this->m_admin_permission->insert($insertData);

            if ($seq):
                $result = "SUCCESS";
                $msg = "관리자 등급이 성공적으로 등록되었습니다.";
            else:
                $result = "ERROR";
                $msg = "오류로 인해 작업에 실패했습니다.";
            endif;

        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");

        endif;

        $returnData = array(
            'result' => $result,
            'msg' => $msg
        );
        echo json_encode($returnData);
        exit;
    }

    /**
     * Created by Ejmoon
     * USER : ablex
     * DATE : 2020-11-02
     * TIME : 오전 10:25
     * DESC : 권한관리 변경 폼
     * @param $seq
     */
    public function permission_edit($seq)
    {

        $permission = $this->m_admin_permission->get($seq);

        $accessible_arr = explode('|', $permission->accessible_menu);

        $_page = "permission_edit";
        $data = array(
            'permission'        => $permission,
            'accessible_arr'    => $accessible_arr,
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Ejmoon
     * USER : ablex
     * DATE : 2020-11-02
     * TIME : 오전 10:35
     * DESC : 권한관리 변경 처리
     */
    public function permission_edit_process_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('seq', '순번', 'trim|required');
        $this->form_validation->set_rules('name', '등급명', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):

            $seq                = $this->input->post("seq");
            $name               = $this->input->post("name");
            $accessible_menu    = $this->input->post('_accessible_menu');
            $is_use             = $this->input->post("is_use");


            $updateData = array(
                'name'              => $name,
                'accessible_menu'   => $accessible_menu,
                'is_use'            => $is_use,
                'mod_date'          => __TIME_YMDHIS__,
                'mod_ip'            => __REMOTE_ADDR__,
                'mod_admin_seq'     => $this->_admin->seq
            );

            $rlt = $this->m_admin_permission->update($seq, $updateData);

            if ($rlt):
                $result = "SUCCESS";
                $msg = "관리자 정보가 성공적으로 변경되었습니다.";
            else:
                $result = "ERROR";
                $msg = "오류로 인해 작업에 실패했습니다.";
            endif;

        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");

        endif;

        $returnData = array(
            'result' => $result,
            'msg' => $msg
        );
        echo json_encode($returnData);
        exit;
    }
}