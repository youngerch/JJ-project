<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Admin_lib
 *
 * 관리자 정보 관리 라이브러리
 * 관리자 세션 정보
 *
 * Created on 2021-06-09
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Admin_lib
{
    protected $_ci;

    public function __construct()
    {
        $this->_ci = &get_instance();

        $this->_ci->load->model(array(
            'common/admin_model'        => 'm_admin'
        ));
    }

    /**
     * Function set_administrator
     *
     * 관리자 정보 업데이트
     *
     * @param $admin_seq 관리자 순번
     * @param $updateData 업데이트 대상 데이터 ( Key => Value ) 조합
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function set_administrator($admin_seq, $updateData)
    {
        $rlt = $this->_ci->m_admin->update($admin_seq, $updateData);

        return $rlt;
    }

    /**
     * Function get_administrator
     *
     * 관리자 순번을 이용한 정보 조회
     *
     * @param $admin_seq 관리자 순번
     * @return mixed 실패 시 false, 성공 시 관리자 Object
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function get_administrator($admin_seq)
    {
        $_admin = $this->_ci->m_admin->get($admin_seq);

        if (!isset($_admin->seq)) {
            return false;
        }
        else {
            return $_admin;
        } // End if
    }

    /**
     * Function get_administrator_by_data
     *
     * 지정된 정보를 이용한 관리자 정보 조회
     *
     * @param $whereData 컬럼 정보와 데이터를 가진 Array
     * @return mixed 실패 시 false, 성공 시 관리자 Object
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function get_administrator_by_column($whereData)
    {
        $_admin = $this->_ci->m_admin->get_by($whereData);

        if (!isset($_admin->seq)) {
            return false;
        }
        else {
            return $_admin;
        } // End if
    }

    /**
     * Function is_login
     *
     * 관리자 세션 정보 유무를 판단하여 로그인 상태를 전달
     *
     * @return bool
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function is_login()
    {
        if ($this->_ci->session->userdata("session_admin_seq") == "") {
            return false;
        }
        else {
            return true;
        } // End if
    }

    /**
     * Function get_session
     *
     * 특정 세션에 담겨진 정보를 조회
     *
     * @param string $col 세션 컬럼명
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function get_session($col = "")
    {
        if ($col === "") {
            return $this->_ci->session->userdata;
        }
        else {
            return $this->_ci->session->userdata("session_admin_" . $col);
        } // End if
    }

    /**
     * Function find_administrator
     *
     * 관리자 정보 찾기
     *
     * @param $name 관리자 이름
     * @param $email 관리자 이메일 정보 ( 아이디 )
     * @return false|mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function find_administrator($name, $email)
    {
        $whereData = array(
            'name'      => $name,
            'email'     => $email
        );
        $_admin = $this->get_administrator_by_column($whereData);

        if (!isset($_admin->seq)) {
            return false;
        }
        else {
            return $_admin;
        } // End if
    }

    /**
     * Function generateStrongPassword
     *
     * 비밀번호 생성 함수
     *
     * @param int $length 암호 길이 (Default 8 )
     * @param false $add_dashes 대쉬(-) 추가 여부
     * @param string $available_sets 암호 조합 ( Default lud :: 소문자, 대문자, 숫자 조합 )
     * @return string
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function generateStrongPassword($length = 8, $add_dashes = false, $available_sets = 'lud')
    {
        $all = '';
        $password = '';
        $sets = array();

        // 영문 소문자
        if(strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        } // End if

        // 영문 대문자
        if(strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        } // End if

        // 숫자
        if(strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        } // End if

        // 특수문자
        if(strpos($available_sets, 's') !== false) {
            $sets[] = '!@#$%&*?';
        } // End if

        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        } // End foreach

        $all = str_split($all);

        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        } // End for

        $password = str_shuffle($password);
        if (!$add_dashes) {
            return $password;
        }
        else {
            $dash_len = floor(sqrt($length));
            $dash_str = '';
            while (strlen($password) > $dash_len) {
                $dash_str .= substr($password, 0, $dash_len) . '-';
                $password = substr($password, $dash_len);
            }
            $dash_str .= $password;
                 return $dash_str;
        } // End if
    }

    /**
     * Function set_password
     *
     * 비밀번호 변경
     *
     * @param $admin_seq 관리자 순번
     * @param $password 신규 패스워드
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function set_password($admin_seq, $password)
    {
        $updateData = array(
            'password_s'        => $this->_ci->secure->password_hash($password),
            'login_error_cnt'   => 0
        );
        $rlt = $this->set_administrator($admin_seq, $updateData);

        return $rlt;
    }

    /**
     * 정기점검 페이지 정보
     */
    public function get_inspection()
    {
        $result = $this->_ci->m_inspection->get(1);

        return $result;
    }
}
