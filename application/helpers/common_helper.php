<?php
/**
 * Created by PhpStorm.
 * User: kamiz
 * Date: 2016-03-03
 * Time: 오전 10:32
 * 자바 스크립트 함수 모음
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//메세지 출력 후 이동
function alert($msg='이동합니다', $url='')
{
    echo "
		<script type='text/javascript'>
			alert('".$msg."');
	";

    if($url !== "") {

        echo "
			location.replace('" . $url . "');
	";
    }
    echo "
		</script>
	";
    exit;
}

function alert_callback($msg, $callback){
    echo "
		<script type='text/javascript'>
			alert('".$msg."');
	";

    if($callback !== "") {

        echo "
			{$callback};
	    ";
    }
    echo "
		</script>
	";
    exit;
}

// 창닫기
function alert_close($msg)
{
    $CI =& get_instance();
    echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
    echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
    exit;
}

// 경고창 만
function alert_only($msg, $exit=TRUE)
{
    $CI =& get_instance();
    echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
    echo "<script type='text/javascript'> alert('".$msg."'); </script>";
    if ($exit) exit;
}

function replace($url='/') {
    echo "<script type='text/javascript'>";
    if ($url)
        echo "window.location.replace('".$url."');";
    echo "</script>";
    exit;
}

function opener_url_alert_close($msg, $url) {
    $CI =& get_instance();

    echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
    echo "<script type='text/javascript'> alert('".$msg."'); opener.location.href = '".$url."'; window.close(); </script>";
    exit;
}

function opener_reload_alert_close($msg) {
    $CI =& get_instance();

    echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
    echo "<script type='text/javascript'> alert('".$msg."'); opener.location.reload(true); window.close(); </script>";
    exit;
}

// 해당 url로 이동
function goto_url($url) {
    $temp = parse_url($url);
    if (empty($temp['host'])) {
        $CI =& get_instance();
        $url = ($temp['path'] != '/') ? RT_PATH.'/'.$url : $CI->config->item('base_url').RT_PATH;
    }
    echo "<script type='text/javascript'> location.replace('".$url."'); </script>";
    exit;
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_token() {
    $CI =& get_instance();

    $token = md5(uniqid(rand(), TRUE));
    $CI->session->set_userdata('ss_token', $token);

    return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_token($url=FALSE) {
    $CI =& get_instance();
    // 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교하여 틀리면 에러
    if ($CI->input->post('token') && $CI->session->userdata('ss_token') == $CI->input->post('token')) {
        // 맞으면 세션을 지운다. 세션을 지우는 이유는 새로운 폼을 통해 다시 들어오도록 하기 위함
        $CI->session->unset_userdata('ss_token');
    }
    else
        alert('Access Error',($url) ? $url : $CI->input->server('HTTP_REFERER'));

    // 잦은 토큰 에러로 인하여 토큰을 사용하지 않도록 수정
    // $CI->session->unset_userdata('ss_token');
    // return TRUE;
}

function check_token_json($url=FALSE) {
    $CI =& get_instance();
    // 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교하여 틀리면 에러
    if ($CI->input->post('token') && $CI->session->userdata('ss_token') == $CI->input->post('token')) {
        // 맞으면 세션을 지운다. 세션을 지우는 이유는 새로운 폼을 통해 다시 들어오도록 하기 위함
        $CI->session->unset_userdata('ss_token');
        return "success";
    }else {
        return "AccessError";
    }
    // 잦은 토큰 에러로 인하여 토큰을 사용하지 않도록 수정
    // $CI->session->unset_userdata('ss_token');
    // return TRUE;
}

function check_wrkey() {
    $CI =& get_instance();
    $key = $CI->session->userdata('captcha_keystring');
    if (!($key && $key == $CI->input->post('wr_key'))) {
        $CI->session->unset_userdata('captcha_keystring');
        alert('정상적인 접근이 아닙니다.', '/');
    }
}

//글자 자르기
function sub_string($string,$start,$length,$charset="UTF-8") {

    /* 정확한 문자열의 길이를 계산하기 위해, mb_strlen 함수를 이용 */
    $str_len=mb_strlen($string,$charset);

    if($str_len>$length) {
        /* mb_substr  PHP 4.0 이상, iconv_substr PHP 5.0 이상 */
        $string=mb_substr($string,$start,$length,$charset);
        $string.="..";
    }
    return $string;
}


function base64UrlEncode($inputStr)
{
    return strtr(base64_encode($inputStr), '+/=', '-_,');
}

function base64UrlDecode($inputStr)
{
    return base64_decode(strtr($inputStr, '-_,', '+/='));
}

/**
 * @param $array
 * @return mixed
 * array 를 object 로 변환
 */
function arrayToObject($array){
    $object = json_decode(json_encode($array));
    return $object;
}

/**
 * @param $object
 * @return mixed
 * object 를 array 로 변환
 */
function objectToArray($object){
    $array = json_decode(json_encode($object), true);
    return $array;
}


if(!function_exists('get_instance'))
{
    function get_instance()
    {
        $CI = &get_instance();
    }
}

if ( !function_exists('jsredirect') )
{
    function jsredirect($url, $top = false)
    {
        $script_redirect = '<script type="text/javascript">';
        $script_redirect .= 'window';
        if( $top ) $script_redirect .= '.top';
        $script_redirect .= '.location.href="'.$url.'"';
        $script_redirect .= '</script>';
        echo $script_redirect;
        die();
    }
}
if ( !function_exists('arr2json') )
{
    function arr2json($arr, $echo = true)
    {
        if( $echo )
        {
            header('Content-type: application/json');
            echo json_encode($arr);
        }
        else
        {
            return json_encode($arr);
        }
    }
}
if ( !function_exists('pr') )
{
    function pr($arr)
    {
        echo '<PRE>';
        print_r($arr);
        echo '</PRE>';
    }
}
if ( !function_exists('pd') )
{
    function pd($arr)
    {
        echo '<PRE>';
        var_dump($arr);
        echo '</PRE>';
    }
}
if ( !function_exists('prd') )
{
    function prd($arr)
    {
        echo '<PRE>';
        print_r($arr);
        echo '</PRE>';
        die();
    }
}
if ( !function_exists('dd') )
{
    function dd($arr)
    {
        echo '<PRE>';
        var_dump($arr);
        echo '</PRE>';
        die();
    }
}

//프로토콜 확인
//true : https
//false : http
function is_secure() {
    return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
}

function get_protocol(){
    $protocol = (is_secure() ? "https://" : "http://");
    return $protocol;
}

function get_yoil($week_no)
{
    switch ($week_no):
        case 0:
            $yoil = "일";
            break;
        case 1:
            $yoil = "월";
            break;
        case 2:
            $yoil = "화";
            break;
        case 3:
            $yoil = "수";
            break;
        case 4:
            $yoil = "목";
            break;
        case 5:
            $yoil = "금";
            break;
        case 6:
            $yoil = "토";
            break;
    endswitch;

    return $yoil;
}

//휴대폰 번호 하이픈 붙여서 적용하기
function format_phone($phone){
    $phone = preg_replace("/[^0-9]/", "", $phone);
    $length = strlen($phone);

    switch($length){
        case 11 :
            return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
            break;
        case 10:
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            break;
        default :
            return $phone;
            break;
    }
}

function selectBoxCode($id, $class, $data, $default = array(), $selected = '')
{
    $html = "";
    $html .= "<select name='{$id}' id='{$id}' class='{$class}'>";
    if(isset($default)):
        $html .= "<option value='{$default['value']}'>{$default['text']}</option>";
    endif;
    foreach($data as $key => $row):
        $sel = $row->C_CODE === $selected ? "selected" : "";
        $html .= "<option value='{$row->C_CODE}' {$sel}>{$row->C_NAME}</option>";
    endforeach;
    $html .= "</select>";

    return $html;
}

function selectBoxCodeName($id, $class, $data, $default = array(), $selected = '')
{
    $html = "";
    $html .= "<select name='{$id}' id='{$id}' class='{$class}'>";
    if(isset($default)):
        $html .= "<option value='{$default['value']}'>{$default['text']}</option>";
    endif;
    foreach($data as $key => $row):
        $sel = $row->C_NAME === $selected ? "selected" : "";
        $html .= "<option value='{$row->C_NAME}' {$sel}>{$row->C_NAME}</option>";
    endforeach;
    $html .= "</select>";

    return $html;
}

/**
 * @param $yoil
 * @param bool $is_short
 * @return string
 * 요일명 가져오기
 */
function get_yoil_name($yoil, $is_short = true)
{
    $short = $long = "";
    switch ($yoil):
        case "0":
            $short = "일";
            $long = "일요일";
            break;
        case "1":
            $short = "월";
            $long = "월요일";
            break;
        case "2":
            $short = "화";
            $long = "화요일";
            break;
        case "3":
            $short = "수";
            $long = "수요일";
            break;
        case "4":
            $short = "목";
            $long = "목요일";
            break;
        case "5":
            $short = "금";
            $long = "금요일";
            break;
        case "6":
            $short = "토";
            $long = "토요일";
            break;
    endswitch;

    if($is_short):
        return $short;
    else:
        return $long;
    endif;
}


function exp_to_dec($float_str){
    $float_str = (string)((float)($float_str));
    if(($pos = strpos(strtolower($float_str), 'e')) !== false){
        $exp = substr($float_str, $pos+1);
        $num = substr($float_str, 0, $pos);

        if((($num_sign = $num[0]) === '+') || ($num_sign === '-')) $num = substr($num, 1);
        else $num_sign = '';
        if($num_sign === '+') $num_sign = '';

        if((($exp_sign = $exp[0]) === '+') || ($exp_sign === '-')) $exp = substr($exp, 1);
        else trigger_error("Could not convert exponential notation to decimal notation: invalid float string '$float_str'", E_USER_ERROR);

        $right_dec_places = (($dec_pos = strpos($num, '.')) === false) ? 0 : strlen(substr($num, $dec_pos+1));
        $left_dec_places = ($dec_pos === false) ? strlen($num) : strlen(substr($num, 0, $dec_pos));

        if($exp_sign === '+') $num_zeros = $exp - $right_dec_places;
        else $num_zeros = $exp - $left_dec_places;

        $zeros = str_pad('', $num_zeros, '0');

        if($dec_pos !== false) $num = str_replace('.', '', $num);

        if($exp_sign === '+') return $num_sign.$num.$zeros;
        else return $num_sign.'0.'.$zeros.$num;
    }
    else return $float_str;
}

/* End of file */

//  한페이지에 보여줄 행, 현재페이지, 총페이지수
function get_user_paging($write_pages, $cur_page, $total_page, $segment_num = 2)
{
    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;
    if ($end_page >= $total_page) $end_page = $total_page;

    $str = "";

    $first_page_link    = makePagingUrl($segment_num,1);
    $prev_page_link     = makePagingUrl($segment_num,$start_page);
    $next_page_link     = makePagingUrl($segment_num,$end_page);

    //처음페이지
    $str .= "<a href='".$first_page_link."' title='처음 페이지' class='paging-button first'></a>";

    //이전페이지
    if ($start_page > 1) :
        $prev_page_link = makePagingUrl($segment_num,$start_page-1);
    endif;
    $str .= '<a href="'.$prev_page_link.'" title="이전 페이지" class="paging-button prev"></a>';

    //페이지 요소
    if ($total_page > 1):
        for ($k=$start_page;$k<=$end_page;$k++):
            $useLink = makePagingUrl($segment_num, $k);
            if ($cur_page != $k):
                $str .= '<a href="'.$useLink.'" class="paging-link">'.$k.'</a>';
            else:
                $str .= '<span class="paging-link paging-current">'.$k.'</span>';
            endif;
        endfor;
    else:
        $str .= '<span class="paging-link paging-current">1</span>';
    endif;

    // 다음페이지
    if ($total_page > $end_page):
        $next_page_link = makePagingUrl($segment_num, $end_page+1);
    endif;
    $str .= '<a href="'.$next_page_link.'" title="다음 페이지" class="paging-button next"></a>';

    // 마지막 페이지
    $last_page_link = makePagingUrl($segment_num, $total_page);
    $str .= '<a href="'.$last_page_link.'" title="마지막 페이지" class="paging-button last"></a>';
    $str .= "";

    return $str;
}

function makePagingUrl($where, $val)
{
    $params = $_SERVER['QUERY_STRING'];

    $now_segment = explode('/',uri_string());
    $now_segment[$where] = $val;

    return '/'.implode('/',$now_segment) .'?'.$params;
}

/**
 * Created by Kamiz
 * DATE : 2020-08-07
 * DESC : 코인용 포맷
 * @param $number
 * @return string
 */
function number_format_coin($number, $point = 3)
{
    $tmp = explode(".", $number);
    if ( count($tmp) > 1 ) {
        $_number = number_format($tmp[0]) . "." . substr($tmp[1], 0, $point);
    }
    else {
        $_number = number_format($number, $point);
    }
    return $_number;
}

function number_format_usd($val)
{
    $tmp = intVal(($val * 1000)) / 1000;
    return number_format($tmp, 3);
}

/**
 * Function get_order_status_string
 *
 * 주문상태에 대한 문자열 리턴
 *
 * @param $order_status 주문 상태 코드
 * @param $rowData 주문 정보
 * @return string
 * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-11
 * @access
 */
function get_order_status_string($order_status, $rowData)
{
    $str_status = "";
    // [10]미입금,[15]결제완료,[20]상품준비중,[21]배송중,[25]배송완료,[29]배송보류,[30]교환요청,[35]교환완료,[40]환불요청,[45]환불완료],[50]취소요청,[55]취소완료
    switch($order_status) {
        case "10" :
            $str_status = "미입금<br>" . $rowData->reg_date;
            break;
        case "15" :
            $str_status = "결제완료<br>" . $rowData->payment_date;
            break;
        case "20" :
            $str_status = "상품준비중<br>" . $rowData->ready_date;
            break;
        case "21" :
            $str_status = "배송중<br>" . $rowData->delivery_ing_date;
            break;
        case "25" :
            $str_status = "배송완료<br>" . $rowData->delivery_end_date;
            break;
        case "29" :
            $str_status = "배송보류<br>" . $rowData->delivery_hold_date;
            break;
        case "30" :
            $str_status = "교환요청<br>" . $rowData->exchange_req_date;
            break;
        case "35" :
            $str_status = "교환완료<br>" . $rowData->exchange_res_date;
            break;
        case "40" :
            $str_status = "환불요청<br>" . $rowData->refund_req_date;
            break;
        case "45" :
            $str_status = "환불완료<br>" . $rowData->refund_res_date;
            break;
        case "50" :
            $str_status = "취소요청<br>" . $rowData->cancel_req_date;
            break;
        case "55" :
            $str_status = "취소완료<br>" . $rowData->cancel_res_date;
            break;
    } // End Switch

    return $str_status;
}

function get_inout_class_string($inout_class)
{
    switch ($inout_class) {
        case "11" :
            $str_inout_class = "정회원적립";
            break;
        case "12" :
            $str_inout_class = "복귀결제";
            break;
        case "13" :
            $str_inout_class = "혜택3지급(추천인실적)";
            break;
        case "14" :
            $str_inout_class = "스타실적";
            break;
        case "15" :
            $str_inout_class = "수수료정산";
            break;
        case "16" :
            $str_inout_class = "혜택2지급";
            break;
        case "17" :
            $str_inout_class = "연결제적립";
            break;
        case "18" :
            $str_inout_class = "이벤트지급";
            break;
        case "19" :
            $str_inout_class = "관리자지급";
            break;
        case "20" :
            $str_inout_class = "관리자회수";
            break;
        case "21" :
            $str_inout_class = "출금대기";
            break;
        case "22" :
            $str_inout_class = "출금완료";
            break;
        case "28" :
            $str_inout_class = "출금거절";
            break;
        case "29" :
            $str_inout_class = "출금보류";
            break;
        case "99" :
            $str_inout_class = "출금보류";
            break;
        default :
            $str_inout_class = "";
            break;
    } // End switch

    return $str_inout_class;
}

function get_point_class_string($point_class)
{
    //[11]지점구매, [12]멤버십적립, [13]아이템고적립, [15]지점상품취소, [19]지점회수, [21]상품주문, [25]상품취소, [35]C/S상품취소, [36]기간만료, [37]교환불가, [38]포인트소멸
    switch ($point_class) {
        case "11" :
            $str_point_class = "지점적립";
            break;
        case "12" :
            $str_point_class = "엔젤멤버십적립";
            break;
        case "13" :
            $str_point_class = "아이템고적립";
            break;
        case "15" :
            $str_point_class = "지점상품취소";
            break;
        case "19" :
            $str_point_class = "지점회수";
            break;
        case "21" :
            $str_point_class = "상품주문";
            break;
        case "25" :
            $str_point_class = "상품취소";
            break;
        case "35" :
            $str_point_class = "C/S상품취소";
            break;
        case "36" :
            $str_point_class = "기간만료";
            break;
        case "37" :
            $str_point_class = "교환불가";
            break;
        case "38" :
            $str_point_class = "포인트소멸";
            break;
    } // End switch

    return $str_point_class;
}

function get_coin_status_string($coin_status)
{
    //[0]대기, [1]회원출금신청(출금대기중), [2]승인완료, [3]입금/출금완료, [7]출금거절, [8]출금실패, [9]관리자확인
    switch($coin_status) {
        case "0" :
            $str_coin_status = "대기";
            break;
        case "1" :
            $str_coin_status = "<span style='color:blue;font-weight:600;'>출금대기중</span>";
            break;
        case "2" :
            $str_coin_status = "<span style='color:#e49d0a;font-weight:600;'>승인완료</span>";
            break;
        case "3" :
            $str_coin_status = "출금완료";
            break;
        case "7" :
            $str_coin_status = "<span style='color:red;font-weight:600;'>출금거절</span>";
            break;
        case "8" :
            $str_coin_status = "<span style='color:red;font-weight:600;'>출금실패</span>";
            break;
        case "9" :
            $str_coin_status = "<span style='color:red;font-weight:600;'>관리자확인</span>";
            break;
        default :
            $str_coin_status = "-";
            break;
    } // End switch

    return $str_coin_status;
}

function get_generate_cd()
{
    $rand_num = (string) mt_rand(1000,9999);
    $rand_num = str_shuffle($rand_num);
    $cd 	  = time().$rand_num;

    return $cd;
}