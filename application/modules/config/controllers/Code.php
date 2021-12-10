<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-06-23
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Code extends Sub_Controller
{
    protected $_module;
    function __construct()
    {
        parent::__construct();
        $this->_module = "config";

        $this->_menu_item       = 'config';
        $this->_menu_item_sub   = 'code';

        $this->load->model(array(
            'common/code_model' => 'code'
        ));

        $this->load->library(array(
            'code_lib'
        ));

    }

    public function index()
    {
        $this->lists();
    }

    public function lists($class_cd = '11')
    {
        $lists = $this->code->get_list_all($class_cd, __DEFAULT_LANG_CD__, $whereData = array());

        $list = array();
        $i=0;

        foreach ($lists as $key => $row){
            $row->no = count($lists) - $i;
            $list[$i] = $row;
            $i++;
        }

        //분류코드를 가져온다.
        $this->code->order_by('orders','DESC');
        $codes = $this->code->get_many_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'lang_cd' => __DEFAULT_LANG_CD__, 'detail_cd' => '000'));


        $_page = "code_lists";
        $data = array(
            'lists'     => arrayToObject($list),
            'codes'     => $codes,
            'class_cd'  => $class_cd,
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    public function create($class_cd, $lang_cd = __DEFAULT_LANG_CD__)
    {
        //분류코드를 가져온다.
        $this->code->order_by('orders','DESC');
        $codes = $this->code->get_many_by(array('detail_cd' => '000', 'lang_cd' => __DEFAULT_LANG_CD__));

        $_page = "code_create";
        $data = array(
            'codes'     => $codes,
            'class_cd'  => $class_cd,
            'lang_cd'   => $lang_cd,
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );

        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-24
     * TIME : 오후 5:02
     * DESC : 코드 등록 처리
     */
    public function create_process_ajax()
    {

        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('class_cd', '분류 코드', 'trim|required');
        $this->form_validation->set_rules('lang_cd', '언어 코드', 'trim|required');
        $this->form_validation->set_rules('name', '코드 이름', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):

            $code = $this->generation_code($this->input->post('class_cd'));

            $lang_cd = $this->input->post("lang_cd");

            if(isset($code->detail_cd)):

                $insertData = array(
                    'shop_cd'       => __DEFAULT_SHOP_CD__,
                    'cd'            => $code->cd,
                    'class_cd'      => $code->class_cd,
                    'detail_cd'     => $code->detail_cd,
                    'lang_cd'       => $lang_cd,
                    'cd_name'       => $this->input->post('name'),
                    'desc'          => $this->input->post('desc'),
                    'dummy_col_1'   => $this->input->post('dummy_col_1'),
                    'dummy_col_2'   => $this->input->post('dummy_col_2'),
                    'orders'        => ($this->input->post('orders') === "" ? intval($code->detail_cd) :  $this->input->post('orders')),
                    'use_is'        => $this->input->post('use_is'),
                    'reg_date'      => __TIME_YMDHIS__,
                    'reg_ip'        => __REMOTE_ADDR__,
                    'reg_admin_seq' => $this->_admin->seq
                );
                $this->code->insert($insertData);

                $result = "SUCCESS";
                $msg = "코드가 성공적으로 등록되었습니다.";

            else:

                $result = "ERROR";
                $msg = "등록오류입니다.";

            endif;

        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");

        endif;

        $returnData = array(
            'result'    => $result,
            'msg'       => $msg
        );
        echo json_encode($returnData);
        exit;
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-26
     * TIME : 오후 2:38
     * DESC : 코드 수정 폼
     * @param $code
     * @param string $lang_cd
     */
    public function edit($code, $lang_cd = __DEFAULT_LANG_CD__)
    {

        $_code = $this->code->get_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'cd' => $code, 'lang_cd' => $lang_cd));

        if(!isset($_code->cd)):

            $rlt = $this->code_lib->copy_code($code, $lang_cd);

            if($rlt === false):
                alert("잘못된 접근입니다.", "/config/code/lists");
            else:

                $_code = $this->code->get_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'cd' => $code, 'lang_cd' => $lang_cd));

            endif;
        endif;

        //분류코드를 가져온다.
        $this->code->order_by('orders','DESC');
        $codes = $this->code->get_many_by(array('detail_cd' => '000', 'lang_cd' => __DEFAULT_LANG_CD__));

        $_page = "code_edit";
        $data = array(
            'codes'     => $codes,
            'code'      => $_code,
            'lang_cd'   => __DEFAULT_LANG_CD__,
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container, $data);

    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-26
     * TIME : 오후 2:38
     * DESC : 코드 수정 처리
     */
    public function edit_process_ajax()
    {

        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('cd', '코드', 'trim|required');
        $this->form_validation->set_rules('lang_cd', '언어 코드', 'trim|required');
        $this->form_validation->set_rules('name', '코드 이름', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE):

            $cd       = $this->input->post('cd');
            $lang_cd    = $this->input->post("lang_cd");

            $updateData = array(
                'cd_name'       => $this->input->post('name'),
                'desc'          => $this->input->post('desc'),
                'dummy_col_1'   => $this->input->post('dummy_col_1'),
                'dummy_col_2'   => $this->input->post('dummy_col_2'),
                'orders'        => $this->input->post('orders'),
                'use_is'        => $this->input->post('use_is'),
                'mod_date'      => __TIME_YMDHIS__,
                'mod_ip'        => __REMOTE_ADDR__,
                'mod_admin_seq' => $this->_admin->seq
            );
            $rlt = $this->code->update_by(array('cd' => $cd, 'lang_cd' => $lang_cd, 'shop_cd' => __DEFAULT_SHOP_CD__), $updateData);

            if($rlt):
                $result = "SUCCESS";
                $msg = "코드가 성공적으로 변경되었습니다.";
            else:
                $result = "ERROR_UPDATE";
                $msg = "코드 변경중 오류가 발생했습니다.";
            endif;

        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 등록에 실패했습니다.");

        endif;

        $returnData = array(
            'result'    => $result,
            'msg'       => $msg
        );
        echo json_encode($returnData);
        exit;
    }

    /**
     * @param $code
     * @param $use
     * 사용여부 변경
     */
    public function use_ajax($code, $use)
    {
        $rlt = $this->code->update_by(array('cd' => $code, 'shop_cd' => __DEFAULT_SHOP_CD__), array('use_is' => $use, 'mod_date' => __TIME_YMDHIS__, 'mod_ip' => __REMOTE_ADDR__, 'mod_admin_seq' => $this->_admin->seq));

        if($rlt):
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
     * @param $code
     * 코드 삭제
     */
    public function delete_ajax($code)
    {

        $data = $this->code->get_by(array('cd' => $code, 'shop_cd' => __DEFAULT_SHOP_CD__, 'lang_cd' => __DEFAULT_LANG_CD__));

        $is_process = true;

        //존재하는 코드일 경우
        if(isset($data->seq)):

            //삭제코드가 분류코드일 경우
            //하위 코드가 존재하면 삭제 불가
            if($data->detail_cd === "000"):

                $sub_cnt = $this->code->count_by(array('class_cd' => $data->class_cd, 'detail_cd <>' => '000', 'shop_cd' => __DEFAULT_SHOP_CD__));

                if($sub_cnt > 0):

                    $result = "ERROR";
                    $msg    = "상세코드가 존재합니다. 먼저 삭제 후 삭제해 주세요.";

                    $is_process = false;

                endif;

            endif;

            if($is_process):

                $rlt = $this->code->delete_by(array('cd' => $code, 'shop_cd' => __DEFAULT_SHOP_CD__));

                if($rlt):

                    $result = "SUCCESS";
                    $msg = "코드가 성공적으로 삭제되었습니다.";
                else:
                    $result = "ERROR";
                    $msg = "오류로 인해 작업에 실패했습니다.";
                endif;

            endif;

        else:

            $result = "ERROR";
            $msg = "삭제할 코드가 없거나 잘못된 접근입니다.";

        endif;

        $returnData = array(
            'result' => $result,
            'msg' => $msg
        );

        echo json_encode($returnData);
    }



    /**
     * @param $class_cd : 분류코드
     * @return stdClass : 코드객체
     * 코드를 생성한다.
     */
    private function generation_code($class_cd = "00")
    {

        $code = new stdClass();

        //분류코드 생성이라면~
        if($class_cd === "00"):

            //분류코드 최대값을 가져온다.
            $max_class_cd = $this->code->get_max_class_cd();

            if($max_class_cd === "" || $max_class_cd === null):
                $code->class_cd = "11";
            else:
                $code->class_cd = intval($max_class_cd) + 1;
            endif;

            $code->detail_cd = "000";

        else:
            $code->class_cd = $class_cd;

            //상세코드 최대값을 가져온다.
            $max_detail_cd = $this->code->get_max_detail_cd($class_cd);

            if($max_detail_cd === "" || $max_detail_cd === null):
                $code->detail_cd = "001";
            else:
                $code->detail_cd = str_pad(intval($max_detail_cd) + 1,"3","0",STR_PAD_LEFT);
            endif;
        endif;

        $code->cd = $code->class_cd . $code->detail_cd;

        return $code;
    }
}