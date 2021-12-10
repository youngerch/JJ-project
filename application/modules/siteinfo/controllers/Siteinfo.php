<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2020-09-01
 */

/**
 * Class Siteinfo
 *
 * 사이트 전반에 공통 요소 모음
 *
 * Created on 2021-06-25
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Siteinfo extends Sub_Controller
{
    protected $_module;
    protected $_rows = 20;

    function __construct()
    {
        parent::__construct();

        if (!in_array("siteinfo", $this->_accessible_arr)) {
            $this->alert->error("권한이 없습니다.", __SITE_TITLE__, "/");
        }

        $this->_module          = "siteinfo";
        $this->_menu_item       = 'siteinfo';

        $this->load->model(array(
            'common/inspection_model'           => 'm_inspection',
            'common/category_model'             => 'm_category',
            'common/document_model'             => 'm_document',
            'common/allow_ips_model'            => 'm_allow_ips',
            'common/company_model'              => 'm_company',
        ));

        $this->load->library(array());
    }

    /**
     * Function index
     *
     * 인덱스
     * 점검 페이지 셋팅으로 전환
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function index()
    {
        $this->inspection();
    }

    /**
     * Function inspection
     *
     * 점검 셋팅 폼
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-25
     * @access
     */
    public function inspection()
    {
        $info = $this->admin_lib->get_inspection();

        $this->_menu_item = 'inspection';
        $_page = "inspection";
        $data = array(
            'info'    => $info,
            'page'    => $this->_template . $_page,
            'module'  => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function inspection_process_ajax
     *
     * 점검 셋팅 정보 처리 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-25
     * @access
     */
    public function inspection_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('s_date', '점검 시작일', 'trim|required');
        $this->form_validation->set_rules('s_hour', '점검 시작시', 'trim|required');
        $this->form_validation->set_rules('s_minute', '점검 시작분', 'trim|required');
        $this->form_validation->set_rules('e_date', '점검 종료일', 'trim|required');
        $this->form_validation->set_rules('e_hour', '점검 종료시', 'trim|required');
        $this->form_validation->set_rules('e_minute', '점검 종료분', 'trim|required');
        $this->form_validation->set_rules('title', '제목', 'trim|required');
        $this->form_validation->set_rules('content', '점검내용', 'trim|required');

        //validation
        if ($this->form_validation->run() === TRUE) {
            $seq            = 1;
            $is_active      = $this->input->post('is_active') ? $this->input->post('is_active') : "N";
            $s_date         = $this->input->post('s_date');
            $s_hour         = $this->input->post('s_hour');
            $s_minute       = $this->input->post('s_minute');
            $date_start     = $s_date . " " . $s_hour . ":" . $s_minute;
            $e_date         = $this->input->post('e_date');
            $e_hour         = $this->input->post('e_hour');
            $e_minute       = $this->input->post('e_minute');
            $date_end       = $e_date . " " . $e_hour . ":" . $e_minute;
            $title          = $this->input->post('title');
            $content        = $this->input->post('content');

            $this->db->trans_begin();

            $updateData = array(
                'date_start'    => $date_start,
                'date_end'      => $date_end,
                'is_active'     => $is_active,
                'title'         => $title,
                'content'       => $content,
                'reg_date'      => __TIME_YMDHIS__,
                'reg_ip'        => __REMOTE_ADDR__,
            );
            $this->m_inspection->update($seq, $updateData);

            if($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();
                $returnData = array(
                    'result'    => "ERROR_INSERT",
                    'msg'       => "오류로 인해 수정에 실패했습니다."
                );
            }
            else {
                $this->db->trans_commit();
                $returnData = array(
                    'result'    => "SUCCESS",
                    'msg'       => "점검 설정이 성공적으로 완료되었습니다."
                );
            } // End if
        }
        else  {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 설정에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function category
     *
     * 사이트 내 카테고리 관리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-29
     * @access
     */
    public function category()
    {
        $whereData = array(
            'cate_type' => 'A'
        );
        $total_count= $this->m_category->get_count_all($whereData);
        $_categorys = $this->m_category->get_list_all($whereData);    //대분류 코드 조회

        $this->_menu_item = 'category';
        $_page = 'category_layout';
        $data = array(
            'categorys' => $_categorys,
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function category_list_ajax
     *
     * 중/소 분류 카테고리 리스트
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-30
     * @access
     */
    public function category_list_ajax()
    {
        $cate_type  = $this->input->post('cate_type');
        $cate_seq   = $this->input->post('cate_seq');

        //부모 검색
        $_category = $this->m_category->get($cate_seq);

        //회원에 대한 메모 정보
        $whereData = array(
            'parent_seq'        => $_category->seq,
            'cate_type'         => $cate_type,
            'cate_code_search'  => $_category->cate_code,
        );
        $lists = $this->m_category->get_list_all($whereData);

        $html = "";
        if (count($lists) > 0) {
            $_page = "category_list";
            $data = array(
                'lists'     => $lists,
                'page'      => $this->_template_layer . $_page,
                'module'    => $this->_module
            );
            $html = $this->load->view($this->_container_layer, $data, true);
        }
        else {
            $html = "";
        }

        $returnData = array(
            'result'    => "SUCCESS",
            'html'      => $html
        );

        echo json_encode($returnData);
    }

    /**
     * Function category_order_change_ajax
     *
     * 카테고리 정렬 순서 변경
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-30
     * @access
     */
    public function category_order_change_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('cate_type', '카테고리 타입', 'trim|required');
        $this->form_validation->set_rules('categorys', '대상 카테고리 정보', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $cate_type = $this->input->post('cate_type');
            $categorys = $this->input->post('categorys');
            $categorys = explode("|", $categorys);

            for ($i = 0;$i < count($categorys);$i++) {
                $updateCategoryData = array (
                    'cate_order'    => ($i + 1),
                );
                $this->m_category->update($categorys[$i], $updateCategoryData);
            } // End for

            $returnData = array(
                'result' => "SUCCESS",
                'msg' => "카테고리 정렬이 성공적으로 처리되었습니다."
            );
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 저장에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function category_create_form
     *
     * 코드 등록 폼 레이어
     *
     * @param $cate_type 코드 타입 ( A:대분류, B:중분류, C:소분류 )
     * @param $cate_seq 상위 코드 순번
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-29
     * @access
     */
    public function category_create_form($cate_type, $cate_seq)
    {
        $_parent_code = "";
        $_parent_seq = 0;
        if ( intVal($cate_seq) > 0 ) {
            $_parent = $this->m_category->get($cate_seq);
            $_parent_seq    = $_parent->seq;
            $_parent_code   = $_parent->cate_code;
        }

        $_page = 'category_create_form';
        $data = array(
            'parent_seq'    => $_parent_seq,
            'parent_code'   => $_parent_code,
            'cate_type'     => $cate_type,
            'page'          => $this->_template_layer . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function category_create_process_ajax
     *
     * 코드 등록 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-29
     * @access
     */
    public function category_create_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('lyr_parent_seq', '상위 카테고리 순번', 'trim|required');
        $this->form_validation->set_rules('lyr_cate_type', '카테고리 타입', 'trim|required');
        $this->form_validation->set_rules('lyr_cate_code', '카테고리 코드', 'trim|required');
        $this->form_validation->set_rules('lyr_cate_name_kor', '카테고리명(한국어)', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $parent_seq     = $this->input->post('lyr_parent_seq');
            $cate_type      = $this->input->post('lyr_cate_type');
            $parent_code    = $this->input->post('lyr_parent_code');
            $cate_code      = $this->input->post('lyr_cate_code');
            $cate_code      = $parent_code . $cate_code;
            $cate_name_kor  = $this->input->post('lyr_cate_name_kor');
            $cate_name_chn  = $this->input->post('lyr_cate_name_chn') ? $this->input->post('lyr_cate_name_chn') : $cate_name_kor;
            $cate_name_jpn  = $this->input->post('lyr_cate_name_jpn') ? $this->input->post('lyr_cate_name_jpn') : $cate_name_kor;
            $cate_name_eng  = $this->input->post('lyr_cate_name_eng') ? $this->input->post('lyr_cate_name_eng') : $cate_name_kor;
            $cate_name_vnm  = $this->input->post('lyr_cate_name_vnm') ? $this->input->post('lyr_cate_name_vnm') : $cate_name_kor;
            $cate_option1   = $this->input->post('lyr_cate_option1');
            $cate_option2   = $this->input->post('lyr_cate_option2');

            $_count = $this->m_category->get_count_all(array('cate_code' => $cate_code));
            if ($_count > 0) {
                $returnData = array(
                    'result'    => "ERROR_ALREADY",
                    'msg'       => "이미 사용 중인 코드 입니다."
                );
            }
            else {
                $insertCategoryData = array(
                    'parent_seq'    => $parent_seq,
                    'cate_type'     => $cate_type,
                    'cate_code'     => $cate_code,
                    'cate_name_kor' => $cate_name_kor,
                    'cate_name_chn' => $cate_name_chn,
                    'cate_name_jpn' => $cate_name_jpn,
                    'cate_name_eng' => $cate_name_eng,
                    'cate_name_vnm' => $cate_name_vnm,
                    'cate_option1'  => $cate_option1,
                    'cate_option2'  => $cate_option2,
                    'reg_date'      => __TIME_YMDHIS__,
                    'reg_ip'        => __REMOTE_ADDR__,
                    'reg_admin_seq' => $this->_admin->seq
                );

                $this->db->trans_begin();
                $result = $this->m_category->insert($insertCategoryData);

                if ($this->db->trans_status() === FALSE && !$result) {

                    $this->db->trans_rollback();

                    $returnData = array(
                        'result' => "ERROR_INSERT",
                        'msg' => "오류로 인해 카테고리 저장에 실패했습니다."
                    );
                }
                else {
                    $this->db->trans_commit();

                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "카테고리가 성공적으로 저장되었습니다."
                    );
                } // End if
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 저장에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function category_code_duplication_check_ajax
     *
     * 카테고리 코드 중복 검사
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-29
     * @access
     */
    public function category_code_duplication_check_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('cate_code', '코드', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $parent_code    = $this->input->post('parent_code');
            $cate_code      = trim(str_replace(' ', '', $this->input->post('cate_code')));
            $cate_code      = $parent_code . $cate_code;

            $_count = $this->m_category->get_count_all(array('cate_code' => $cate_code));

            if ($_count > 0) {
                $returnData = array(
                    'result'    => "ERROR_ALREADY",
                    'msg'       => "이미 사용 중인 코드 입니다."
                );
            }
            else {
                $returnData = array(
                    'result'    => "SUCCESS",
                    'msg'       => "사용 가능한 코드 입니다."
                );
            }
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 적용에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function category_update_form
     *
     * 카테고리 수정/삭제 폼
     *
     * @param $cate_seq
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-30
     * @access
     */
    public function category_update_form($cate_seq)
    {
        $_category = $this->m_category->get($cate_seq);

        $_page = 'category_update_form';
        $data = array(
            'category'  => $_category,
            'page'      => $this->_template_layer . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }

    /**
     * Function category_update_process_ajax
     *
     * 카테고리 수정 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-01
     * @access
     */
    public function category_update_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('lyr_cate_seq', '카테고리 순번', 'trim|required');
        $this->form_validation->set_rules('lyr_cate_name_kor', '카테고리명(한국어)', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $cate_seq       = $this->input->post('lyr_cate_seq');
            $cate_name_kor  = $this->input->post('lyr_cate_name_kor');
            $cate_name_chn  = $this->input->post('lyr_cate_name_chn') ? $this->input->post('lyr_cate_name_chn') : $cate_name_kor;
            $cate_name_jpn  = $this->input->post('lyr_cate_name_jpn') ? $this->input->post('lyr_cate_name_jpn') : $cate_name_kor;
            $cate_name_eng  = $this->input->post('lyr_cate_name_eng') ? $this->input->post('lyr_cate_name_eng') : $cate_name_kor;
            $cate_name_vnm  = $this->input->post('lyr_cate_name_vnm') ? $this->input->post('lyr_cate_name_vnm') : $cate_name_kor;
            $cate_option1   = $this->input->post('lyr_cate_option1');
            $cate_option2   = $this->input->post('lyr_cate_option2');

            $_count = $this->m_category->get_count_all(array('seq' => $cate_seq));
            if ($_count > 0) {
                $updateCategoryData = array(
                    'cate_name_kor' => $cate_name_kor,
                    'cate_name_chn' => $cate_name_chn,
                    'cate_name_jpn' => $cate_name_jpn,
                    'cate_name_eng' => $cate_name_eng,
                    'cate_name_vnm' => $cate_name_vnm,
                    'cate_option1'  => $cate_option1,
                    'cate_option2'  => $cate_option2
                );

                $this->db->trans_begin();

                $result = $this->m_category->update($cate_seq, $updateCategoryData);

                if ($this->db->trans_status() === FALSE && !$result) {

                    $this->db->trans_rollback();

                    $returnData = array(
                        'result' => "ERROR_INSERT",
                        'msg' => "오류로 인해 카테고리 저장에 실패했습니다."
                    );
                }
                else {
                    $this->db->trans_commit();

                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "카테고리가 성공적으로 저장되었습니다."
                    );
                } // End if
            }
            else {
                $returnData = array(
                    'result'    => "ERROR_EMPTY",
                    'msg'       => "카테고리 정보를 확인 할 수 없습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 저장에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function category_delete_process_ajax
     *
     * 카테고리 삭제 처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-01
     * @access
     */
    public function category_delete_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('lyr_cate_seq', '카테고리 순번', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $cate_seq = $this->input->post('lyr_cate_seq');
            
            $_count = $this->m_category->get_count_all(array('seq' => $cate_seq));
            if ($_count > 0) {
                $updateCategoryData = array(
                    'is_del' => 'Y',
                );

                $this->db->trans_begin();

                $result = $this->m_category->update($cate_seq, $updateCategoryData);

                if ($this->db->trans_status() === FALSE && !$result) {

                    $this->db->trans_rollback();

                    $returnData = array(
                        'result' => "ERROR_INSERT",
                        'msg' => "오류로 인해 카테고리 삭제에 실패했습니다."
                    );
                }
                else {
                    $this->db->trans_commit();

                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "카테고리가 성공적으로 삭제되었습니다."
                    );
                } // End if
            }
            else {
                $returnData = array(
                    'result'    => "ERROR_EMPTY",
                    'msg'       => "카테고리 정보를 확인 할 수 없습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 삭제에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function category_repair_process_ajax
     *
     * 카테고리 복구(삭제 취소) 처리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-01
     * @access
     */
    public function category_repair_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('lyr_cate_seq', '카테고리 순번', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $cate_seq = $this->input->post('lyr_cate_seq');

            $_count = $this->m_category->get_count_all(array('seq' => $cate_seq));
            if ($_count > 0) {
                $updateCategoryData = array(
                    'is_del' => 'N',
                );

                $this->db->trans_begin();

                $result = $this->m_category->update($cate_seq, $updateCategoryData);

                if ($this->db->trans_status() === FALSE && !$result) {

                    $this->db->trans_rollback();

                    $returnData = array(
                        'result' => "ERROR_INSERT",
                        'msg' => "오류로 인해 카테고리 삭제에 실패했습니다."
                    );
                }
                else {
                    $this->db->trans_commit();

                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "카테고리가 성공적으로 삭제되었습니다."
                    );
                } // End if
            }
            else {
                $returnData = array(
                    'result'    => "ERROR_EMPTY",
                    'msg'       => "카테고리 정보를 확인 할 수 없습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 삭제에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function docs
     *
     * 문서관리 ( 사이트 내 사용되는 문구 집합체 관리 )
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-01
     * @access
     */
    public function docs()
    {
        $current_page = $this->input->get('current_page') ? $this->input->get('current_page') : 1;
        $from_record = ($current_page - 1) * $this->_rows;

        $date_start     = $this->input->get('date_start') ? $this->input->get('date_start') : "2021-02-01";
        $date_end       = $this->input->get('date_end') ? $this->input->get('date_end') : Date('Y-m-t');
        $sch_key        = $this->input->get('sch_key') ? $this->input->get('sch_key') : "A.login_id";
        $sch_str        = $this->input->get('sch_str');

        $whereData = array(
            'date_start'        => $date_start,
            'date_end'          => $date_end,
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode($sch_str),
        );
        $lists      = $this->m_document->get_list_all($from_record, $this->_rows, $whereData);
        $totalCnt   = $this->m_document->get_count_all($whereData);
        $totalPage  = ceil($totalCnt / $this->_rows);

        $list = array();
        $i = 0;
        foreach ($lists as $key => $row) {
            $row->no = $totalCnt - $from_record - $i;

            $list[$i] = $row;
            $i++;
        } // End foreach

        $this->_menu_item = "docs";
        $_page = 'document_list';
        $data = array(
            'date_start'        => $date_start,
            'date_end'          => $date_end,
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode(str_replace("-withdrawal", "", $sch_str)),
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
     * Function document_create
     *
     * 문서관리 등록
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-02
     * @access
     */
    public function document_create()
    {
        $this->_menu_item = "docs";
        $_page = "document_create";
        $data = array(
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function document_create_process_ajax
     *
     * 문서관리 등록 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-02
     * @access
     */
    public function document_create_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('code',       '구분코드', 'trim|required');
        $this->form_validation->set_rules('title',      '제목', 'trim|required');
        $this->form_validation->set_rules('content',    '내용', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $code       = $this->input->post('code');
            $title      = $this->input->post('title');
            $content    = $this->input->post('content');

            $_count = $this->m_document->get_count_all(array('date_start' => '2021-02-01', 'date_end' => Date('Y-m-d'), 'code' => $code));
            if ($_count > 0) {
                $returnData = array(
                    'result'    => "ERROR_ALREADY",
                    'msg'       => "이미 사용 중인 코드 입니다."
                );
            }
            else {
                $insertDocumentData = array(
                    'code'          => $code,
                    'title'         => $title,
                    'content'       => $content,
                    'reg_date'      => __TIME_YMDHIS__,
                    'reg_ip'        => __REMOTE_ADDR__,
                    'reg_admin_seq' => $this->_admin->seq
                );

                $this->db->trans_begin();
                $this->m_document->insert($insertDocumentData);

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();

                    $returnData = array(
                        'result' => "ERROR_INSERT",
                        'msg' => "오류로 인해 문서 저장에 실패했습니다."
                    );
                }
                else {
                    $this->db->trans_commit();

                    $returnData = array(
                        'result' => "SUCCESS",
                        'msg' => "문서가 성공적으로 저장되었습니다."
                    );
                } // End if
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 저장에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function document_edit
     *
     * 문서관리 수정
     *
     * @param $doc_seq 문서데이터 순번
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-05
     * @access
     */
    public function document_edit($doc_seq)
    {
        $info = $this->m_document->get($doc_seq);

        $this->_menu_item = "docs";
        $_page = "document_edit";
        $data = array(
            'info'      => $info,
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function document_update_process_ajax
     *
     * 문서관리 수정 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-05
     * @access
     */
    public function document_update_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('seq',        '순번', 'trim|required');
        $this->form_validation->set_rules('title',      '제목', 'trim|required');
        $this->form_validation->set_rules('content',    '내용', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $doc_seq    = $this->input->post('seq');
            $title      = $this->input->post('title');
            $content    = $this->input->post('content');

            $updateDocumentData = array(
                'title'         => $title,
                'content'       => $content,
                'mod_date'      => __TIME_YMDHIS__,
                'mod_ip'        => __REMOTE_ADDR__,
                'mod_admin_seq' => $this->_admin->seq
            );

            $this->db->trans_begin();
            $this->m_document->update($doc_seq, $updateDocumentData);

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                $returnData = array(
                    'result' => "ERROR_INSERT",
                    'msg' => "오류로 인해 문서 저장에 실패했습니다."
                );
            }
            else {
                $this->db->trans_commit();

                $returnData = array(
                    'result' => "SUCCESS",
                    'msg' => "문서가 성공적으로 저장되었습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 저장에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function allowips
     *
     * 접속 아이피 관리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-08
     * @access
     */
    public function allowips()
    {
        $sch_key        = $this->input->get('sch_key') ? $this->input->get('sch_key') : "A.login_id";
        $sch_str        = $this->input->get('sch_str');

        $whereData = array(
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode($sch_str),
        );
        $totalCnt   = $this->m_allow_ips->get_count_all($whereData);
        $lists      = $this->m_allow_ips->get_list_all(0, intVal($totalCnt), $whereData);

        $list = array();
        $i = 0;
        foreach ($lists as $key => $row) {
            $row->no = $totalCnt - $i;

            $list[$i] = $row;
            $i++;
        } // End foreach

        $this->_menu_item = "allowips";
        $_page = 'allowips_layout';
        $data = array(
            'sch_key'           => $sch_key,
            'sch_str'           => urldecode($sch_str),
            'lists'             => arrayToObject($list),
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function allowips_process_ajax
     *
     * 접속 아이피 등록/수정
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-08
     * @access
     */
    public function allowips_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('seq', '접속아이피 순번', 'trim|required');
        $this->form_validation->set_rules('ai_ip', '접속아이피', 'trim|required');
        $this->form_validation->set_rules('ai_name', '명칭', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $seq      = $this->input->post('seq');
            $ai_ip    = $this->input->post('ai_ip');
            $ai_name      = $this->input->post('ai_name');

            $this->db->trans_begin();

            if (intVal($seq) == 0) {
                //신규 등록
                $_count = $this->m_allow_ips->get_count_all(array('ai_ip' => $ai_ip));
                if ($_count > 0) {
                    $returnData = array(
                        'result'    => "ERROR_ALREADY",
                        'msg'       => "이미 등록된 아이피입니다."
                    );
                }
                else {
                    $insertAllowIPData = array(
                        'ai_ip'         => $ai_ip,
                        'ai_name'       => $ai_name,
                        'is_use'        => "Y",
                        'reg_date'      => __TIME_YMDHIS__,
                        'reg_ip'        => __REMOTE_ADDR__,
                        'reg_admin_seq' => $this->_admin->seq
                    );

                    $result = $this->m_allow_ips->insert($insertAllowIPData);
                } // End if
            }
            else {
                //수정
                $_count = $this->m_allow_ips->get_count_all(array('seq' => $seq));
                if ($_count == 0) {
                    $returnData = array(
                        'result'    => "ERROR_EMPTY",
                        'msg'       => "정보를 확인할 수 없습니다."
                    );
                }
                else {
                    $updateAllowIPData = array(
                        'ai_ip'         => $ai_ip,
                        'ai_name'       => $ai_name,
                        'mod_date'      => __TIME_YMDHIS__,
                        'mod_ip'        => __REMOTE_ADDR__,
                        'mod_admin_seq' => $this->_admin->seq
                    );

                    $result = $this->m_allow_ips->update($seq, $updateAllowIPData);
                } // End if
            } // End if

            if ($this->db->trans_status() === FALSE && !$result) {

                $this->db->trans_rollback();

                $returnData = array(
                    'result' => "ERROR_INSERT",
                    'msg' => "오류로 인해 아이피 저장에 실패했습니다."
                );
            }
            else {
                $this->db->trans_commit();

                $returnData = array(
                    'result' => "SUCCESS",
                    'msg' => "아이피가 성공적으로 저장되었습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 저장에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }

    /**
     * Function company
     *
     * 회사 정보 관리
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-08
     * @access
     */
    public function company()
    {
        $info = $this->m_company->get(1);

        $this->_menu_item = "company";
        $_page = 'company_layout';
        $data = array(
            'info'      => $info,
            'page'      => $this->_template . $_page,
            'module'    => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Function company_process_ajax
     *
     * 회사 정보 저장 프로세스
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-08
     * @access
     */
    public function company_process_ajax()
    {
        $this->util->is_ajax_alert();

        // validate form input
        $this->form_validation->set_rules('name', '회사명', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $seq        = $this->input->post('seq');
            $name       = $this->input->post('name');
            $ceo        = $this->input->post('ceo');
            $bizno      = $this->input->post('bizno');
            $buyno      = $this->input->post('buyno');
            $address    = $this->input->post('address');
            $cscenter   = $this->input->post('cscenter');
            $fax        = $this->input->post('fax');
            $email      = $this->input->post('email');
            $kakao      = $this->input->post('kakao');
            $telegram   = $this->input->post('telegram');

            $this->db->trans_begin();

            $updateCompanyData = array(
                'name'      => $name,
                'ceo'       => $ceo,
                'bizno'     => $bizno,
                'buyno'     => $buyno,
                'address'   => $address,
                'cscenter'  => $cscenter,
                'fax'       => $fax,
                'email'     => $email,
                'kakao'     => $kakao,
                'telegram'  => $telegram,
            );

            $result = $this->m_company->update($seq, $updateCompanyData);

            if ($this->db->trans_status() === FALSE && !$result) {

                $this->db->trans_rollback();

                $returnData = array(
                    'result' => "ERROR_INSERT",
                    'msg' => "오류로 인해 저장에 실패했습니다."
                );
            }
            else {
                $this->db->trans_commit();

                $returnData = array(
                    'result' => "SUCCESS",
                    'msg' => "성공적으로 저장되었습니다."
                );
            } // End if
        }
        else {
            $returnData = array(
                'result'    => "ERROR",
                'msg'       => (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 저장에 실패했습니다.")
            );
        } // End if

        echo json_encode($returnData);
    }
}

