<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-07-13
 */

/**
 * Class Category_lib
 *
 * 카테고리 라이브러리
 *
 * Created on 2021-07-13
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Category_lib
{
    protected $_ci;

    public function __construct()
    {
        $this->_ci = &get_instance();
        $this->_ci->load->model(array(
            'common/category_model' => 'm_category',
        ));
    }

    /**
     * Function get_category_array
     *
     * 카테고리 정보를 조회하여 배열로 재구성하여 반환
     *
     * @param $cate_code 상위 카테고리 코드 ( A의 경우에는 반환하지 않음. )
     * @param $cate_type 카테고리 등급 ( B:중분류, C:소분류 ) :
     * @param $lang_cd 노출 언어 선택
     * @param string $array_key [0]cate_code, [1]cate_option1, [2]cate_option2 : 키 선택
     * @param boolean $is_del 삭제된 카테고리 포함 여부
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-13
     * @access
     */
    public function get_category_array($cate_code, $cate_type, $lang_cd, $array_key = 0, $is_del = false)
    {
        $whereData = array(
            'cate_code' => $cate_code
        );
        $_parent = $this->_ci->m_category->get_by($whereData);

        if (!$_parent) {
            return false;
        } // End if

        $whereData = array(
            'parent_seq'=> $_parent->seq,
            'cate_type' => $cate_type,
            'is_del'    => 'N'
        );

        //삭제된 내용 포함일 경우
        if ($is_del) {
            unset($whereData['is_del']);
        } // End if

        $result = $this->_ci->m_category->get_list_all($whereData);

        $ary = array();
        foreach ($result as $key => $row ) {
            switch ($lang_cd) {
                case "KOR" :
                    $value = $row->cate_name_kor;
                    break;
                case "CHN" :
                    $value = $row->cate_name_chn;
                    break;
                case "JPN" :
                    $value = $row->cate_name_jpn;
                    break;
                case "ENG" :
                    $value = $row->cate_name_eng;
                    break;
                case "VNM" :
                    $value = $row->cate_name_vnm;
                    break;
            } // End switch

            switch ($array_key) {
                case 0 :
                    $ary[$row->cate_code] = $value;
                    break;
                case 1 :
                    $ary[$row->cate_option1] = $value;
                    break;
                case 2 :
                    $ary[$row->cate_option2] = $value;
                    break;
            } // End switch

        } // End foreach;

        return $ary;
    }

    /**
     * Function get_category_object
     *
     * 카테고리 정보를 조회하여 전달.
     *
     * @param $cate_code 상위 카테고리 코드 ( A(대분류)의 경우에는 반환하지 않음. )
     * @param $cate_type 카테고리 등급 ( B:중분류, C:소분류 ) :
     * @param boolean $is_del 삭제된 카테고리 포함 여부
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-13
     * @access
     */
    public function get_category_object($cate_code, $cate_type, $is_del = false) {

        $whereData = array(
            'cate_code' => $cate_code
        );
        $_parent = $this->_ci->m_category->get_by($whereData);

        if (!$_parent) {
            return false;
        } // End if

        $whereData = array(
            'parent_seq'=> $_parent->seq,
            'cate_type' => $cate_type,
            'is_del'    => 'N'
        );

        //삭제된 내용 포함일 경우
        if ($is_del) {
            unset($whereData['is_del']);
        } // End if

        $result = $this->_ci->m_category->get_list_all($whereData);

        return $result;
    }

    /**
     * Function get_category_option
     *
     * 카테고리 정보를 조회하여 HTML ( SELECT -> OPTION )의 구성으로 HTML 반환
     *
     * @param $cate_code
     * @param $cate_type
     * @param $lang_cd
     * @param $option_value
     * @param $selected_value
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-07-13
     * @access
     */
    public function get_category_option($cate_code, $cate_type, $lang_cd, $option_value = 0, $selected_value = '') {
        $whereData = array(
            'cate_code' => $cate_code
        );
        $_parent = $this->_ci->m_category->get_by($whereData);
        if (!$_parent) {
            return false;
        } // End if

        $whereData = array(
            'parent_seq'=> $_parent->seq,
            'cate_type' => $cate_type,
            'is_del'    => 'N'
        );
        $result = $this->_ci->m_category->get_list_all($whereData);

        $html_option = "";
        foreach ($result as $key => $row ) {
            switch ($lang_cd) {
                case "KOR" :
                    $text = $row->cate_name_kor;
                    break;
                case "CHN" :
                    $text = $row->cate_name_chn;
                    break;
                case "JPN" :
                    $text = $row->cate_name_jpn;
                    break;
                case "ENG" :
                    $text = $row->cate_name_eng;
                    break;
                case "VNM" :
                    $text = $row->cate_name_vnm;
                    break;
            } // End switch

            switch ($option_value) {
                case 0 :
                    $value = $row->cate_code;
                    break;
                case 1 :
                    $value = $row->cate_option1;
                    break;
                case 2 :
                    $value = $row->cate_option2;
                    break;
            } // End switch

            if ($selected_value == $value) {
                $html_option .= "<option value='".$value."' SELECTED>".$text."</option>";
            }
            else {
                $html_option .= "<option value='".$value."'>".$text."</option>";
            }
        } // End foreach;

        return $html_option;
    }

    public function get_category_option_with_value($cate_code, $cate_type, $lang_cd, $option_value = 0, $selected_value = '') {
        $whereData = array(
            'cate_code' => $cate_code
        );
        $_parent = $this->_ci->m_category->get_by($whereData);
        if (!$_parent) {
            return false;
        } // End if

        $whereData = array(
            'parent_seq'=> $_parent->seq,
            'cate_type' => $cate_type,
            'is_del'    => 'N'
        );
        $result = $this->_ci->m_category->get_list_all($whereData);

        $html_option = "";
        foreach ($result as $key => $row ) {
            switch ($lang_cd) {
                case "KOR" :
                    $text = $row->cate_name_kor;
                    break;
                case "CHN" :
                    $text = $row->cate_name_chn;
                    break;
                case "JPN" :
                    $text = $row->cate_name_jpn;
                    break;
                case "ENG" :
                    $text = $row->cate_name_eng;
                    break;
                case "VNM" :
                    $text = $row->cate_name_vnm;
                    break;
            } // End switch

            switch ($option_value) {
                case 0 :
                    $value = $row->cate_code;
                    break;
                case 1 :
                    $value = $row->cate_option1;
                    break;
                case 2 :
                    $value = $row->cate_option2;
                    break;
            } // End switch

            if ($selected_value == $value) {
                $html_option .= "<option value='".$value."' SELECTED>".$value." (".$text.")</option>";
            }
            else {
                $html_option .= "<option value='".$value."'>".$value." (".$text.")</option>";
            }
        } // End foreach;

        return $html_option;
    }
}