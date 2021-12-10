<?php

/**
 * Class Util
 * 기본 공용 유틸
 */
class Util
{
    protected $_ci;
    public function __construct()
    {
        $this->_ci = &get_instance();
    }

    /**
     * @return bool : 로그인 상태면 true, 아니면 false
     * 로그인 여부 체크
     */
    public function is_login()
    {

        if($this->_ci->session->userdata("session_member_seq") == ""):

            return false;

        else:

            return true;

        endif;

    }


    /**
     * @param string $msg : 얼럿 메시지
     * @param bool $is_auto : 자동 이동 여부
     * 로그인 체크 후 로그아웃 상태면 얼럿 후 로그인 페이지로 이동
     */
    public function is_login_alert($msg = "로그인 후 이용이 가능합니다.", $is_auto = true)
    {

        if(!self::is_login()):

            if($is_auto):
                $this->_ci->alert->basic_url($msg, __SITE_TITLE__, "/member/login");
            else:
                $this->_ci->alert->basic($msg, __SITE_TITLE__);
            endif;
        else:

            $member_seq = $this->_ci->session->userdata("session_member_seq");
            $_member = $this->_ci->member_lib->get_member($member_seq);

            if($_member->C_MEMBER_STATUS === "9"):

                $_member_leave = $this->_ci->member_lib->get_member_leave($member_seq);

                if($_member_leave->C_LEAVE_STATUS === "3"):

                    $this->_ci->alert->basic_url("탈퇴신청 취소처리중입니다. 빠른 시일내에 처리해 드리겠습니다.", "아이템고", "/");

                else:

                    $this->_ci->alert->basic_url("탈퇴신청 회원입니다.<br/>해당 기능은 탈퇴신청 취소 후 이용이 가능합니다.", "아이템고", "/member/leave_cancel");

                endif;
            endif;
        endif;
    }


    /**
     * 로그인 체크 후 로그아웃 상태면 로그인 페이지로 이동
     * 2019.12.06 박민아 추가
     */
    public function is_login_move()
    {
        if(!self::is_login()):
            $this->_ci->alert->url_move("/member/login");
        else:
            $member_seq = $this->_ci->session->userdata("session_member_seq");
            $_member = $this->_ci->member_lib->get_member($member_seq);

            if($_member->C_MEMBER_STATUS === "9"):

                $_member_leave = $this->_ci->member_lib->get_member_leave($member_seq);

                if($_member_leave->C_LEAVE_STATUS === "3"):

                    $this->_ci->alert->basic_url("탈퇴신청 취소처리중입니다. 빠른 시일내에 처리해 드리겠습니다.", "아이템고", "/");

                else:

                    $this->_ci->alert->basic_url("탈퇴신청 회원입니다.<br/>해당 기능은 탈퇴신청 취소 후 이용이 가능합니다.", "아이템고", "/member/leave_cancel");

                endif;

            endif;
        endif;
    }

    public function is_login_alert_all($msg = "로그인 후 이용이 가능합니다.", $is_auto = true)
    {
        if(!self::is_login()):
            if($is_auto):
                $this->_ci->alert->basic_url($msg, __SITE_TITLE__, "/member/login");
            else:
                $this->_ci->alert->basic($msg, __SITE_TITLE__);
            endif;
        endif;
    }


    public function is_login_alert_close($msg = "로그인 후 이용이 가능합니다.")
    {

        if(!self::is_login()):

            $this->_ci->alert->callback_eval($msg, __SITE_TITLE__, "close()");

        else:

            $member_seq = $this->_ci->session->userdata("session_member_seq");
            $_member = $this->_ci->member_lib->get_member($member_seq);

            if($_member->C_MEMBER_STATUS === "9"):

                $_member_leave = $this->_ci->member_lib->get_member_leave($member_seq);

                if($_member_leave->C_LEAVE_STATUS === "3"):

                    $this->_ci->alert->callback_eval("탈퇴신청 취소처리중입니다. 빠른 시일내에 처리해 드리겠습니다.", "close()");

                else:

                    $this->_ci->alert->callback_eval("탈퇴신청 회원입니다.<br/>해당 기능은 탈퇴신청 취소 후 이용이 가능합니다.", "close()");

                endif;



            endif;
        endif;
    }




    /**
     * @param string $col
     * @return mixed
     * 세션 정보를 가져온다.
     */
    public function get_session($col = "")
    {
        if($col === ""){
            return $this->_ci->session->userdata;
        }else {
            return $this->_ci->session->userdata("session_member_" . $col);
        }
    }

    public function is_ajax()
    {
        if($this->_ci->input->is_ajax_request()):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * @param string $msg
     * @param string $url
     * ajax 호출인지 체크 후 얼럿
     */
    public function is_ajax_alert($msg = "잘못된 접근입니다.", $url="/")
    {

        if(!$this->_ci->input->is_ajax_request()):
            $this->_ci->alert->basic_url($msg, __SITE_TITLE__, $url);
        endif;
    }

    public function base64UrlEncode($inputStr)
    {
        return strtr(base64_encode($inputStr), '+/=', '-_,');
    }

    public function base64UrlDecode($inputStr)
    {
        return base64_decode(strtr($inputStr, '-_,', '+/='));
    }


    /**
     * @param $array
     * @return mixed
     * array 를 object 로 변환
     */
    public function arrayToObject($array)
    {
        $object = json_decode(json_encode($array));
        return $object;
    }

    /**
     * @param $object
     * @return mixed
     * object 를 array 로 변환
     */
    public function objectToArray($object)
    {
        $array = json_decode(json_encode($object), true);
        return $array;
    }

    /**
     * @param $content
     * @return mixed|string
     * 콘텐츠에서 이미지 추출
     */
    public function getImg($content)
    {
        $img = "";
        preg_match("<img [^<>]*>", $content, $imgTag);

        if($imgTag[0]){
            if( stristr($imgTag[0], "http://") ) {
                preg_match("/http:\/\/.*\.(jp[e]?g|gif|png)/Ui", $imgTag[0], $imgName);
                $img = $imgName[0];
            } else {
                preg_match("/.*\.(jp[e]?g|gif|png)/Ui", $imgTag[0], $imgName);
                $img = $imgName[0];
            }
        }

        return $img;
    }

    /**
     * @return bool : true => https, false => http
     * 프로토콜 확인
     */
    public function is_secure()
    {
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
    }

    /**
     * @return string
     * 프로토콜명을 가져온다.
     */
    public function get_protocol()
    {
        $protocol = (self::is_secure() ? "https://" : "http://");
        return $protocol;
    }

    // CURL 함수
    public function restful_curl($url, $param='', $method='POST', $header='', $timeout=10) {
        $method = (strtoupper($method) == 'POST') ? '1' : '0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if(is_array($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_POST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function restful_curl_get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function get_tel_mark($tel)
    {
        $tel = str_replace("-", "", trim($tel));

        if(strlen($tel) === 11):

            $tel = substr($tel, 0,3) . " - " . substr($tel, 3, 4) . "" . substr($tel, 7,4);

        else:

            $tel = substr($tel, 0,3) . " - " . substr($tel, 3, 3) . "" . substr($tel, 6,4);

        endif;

        return $tel;
    }

    public function get_hp_mark($hp)
    {
        $hp = str_replace("-", "", trim($hp));

        if(strlen($hp) === 11):

            $hp = substr($hp, 0,3) . " - " . substr($hp, 3, 4) . "" . substr($hp, 7,4);

        else:

            $hp = substr($hp, 0,3) . " - " . substr($hp, 3, 3) . "" . substr($hp, 6,4);

        endif;

        return $hp;
    }

    public function get_hp_array($hp)
    {

        $_hp = array();

        if(strpos($hp, "-") === false):

            if(strlen($hp) === 11):

                $_hp[0] = substr($hp, 0,3);
                $_hp[1] = substr($hp, 3, 4);
                $_hp[2] = substr($hp, 7,4);

            else:

                $_hp[0] = substr($hp, 0,3);
                $_hp[1] = substr($hp, 3, 3);
                $_hp[2] = substr($hp, 6,4);

            endif;

        else:
            $_hp = explode("-", $hp);
        endif;

        return $_hp;
    }

    public function get_tel_array($tel)
    {

        $_tel = array();

        if(strpos($tel, "-") === false):

            if(preg_match('/(0(?:2))([0-9]+)([0-9]{4}$)/', $tel)):
                $_tel[0] = substr($tel, 0,2);
                $_tel[1] = substr($tel, 2,  strlen($tel)-6);
                $_tel[2] = substr($tel, -4);

            elseif(preg_match('/(0(?:[0-9]{2}))([0-9]+)([0-9]{4}$)/', $tel)):
                $_tel[0] = substr($tel, 0,3);
                $_tel[1] = substr($tel, 3,  strlen($tel)-7);
                $_tel[2] = substr($tel, -4);
            else:

                if(strlen($tel) === 11):

                    $_tel[0] = substr($tel, 0,3);
                    $_tel[1] = substr($tel, 3, 4);
                    $_tel[2] = substr($tel, 7,4);

                else:

                    $_tel[0] = substr($tel, 0,3);
                    $_tel[1] = substr($tel, 3, 3);
                    $_tel[2] = substr($tel, 6,4);
                endif;

            endif;



        else:

            $_tel = explode("-", $tel);


            //$tel = preg_replace("/(0(?:2|[0-9]{2}))([0-9]+)([0-9]{4}$)/", "\1-\2-\3", $tel);
            //pr($tel);
            //$_tel = explode("-", $tel);

        endif;


        return $_tel;
    }

    /**
     * @param $day
     * @return false|string
     * 현재일 기준 $day 이전, 이후 날짜값을 가져온다.
     */
    public function get_datetime($day)
    {
        $timestamp  = strtotime($day . " days");
        $datetime   = date("YmdHis", $timestamp);

        return $datetime;
    }
}
