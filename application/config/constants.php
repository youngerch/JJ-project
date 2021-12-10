<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Site Custom Define
|--------------------------------------------------------------------------
*/
// define("__DEV_IP__",        "125.131.205.42");  //개발지 아이피
define("__DEV_IP__",        "127.0.0.1");  //개발지 아이피

define("__DEFAULT_HOST__",  "http://admin.jjproject.com");
define("__WWW_DOMAIN__",    "http://jjproject.com");

define('__SITE_TITLE__',    "관리자");

define("__TABLE_PREFIX__", "tbl_"); //database table prefix
define('__DATA_DIR__', '/data');
define('__DATA_PATH__', $_SERVER['DOCUMENT_ROOT'].'/data');

function getRealClientIp()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {

        $ipaddress = getenv('HTTP_CLIENT_IP');

    } else if(getenv('HTTP_X_FORWARDED_FOR')) {

        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');

    } else if(getenv('HTTP_X_FORWARDED')) {

        $ipaddress = getenv('HTTP_X_FORWARDED');

    } else if(getenv('HTTP_FORWARDED_FOR')) {

        $ipaddress = getenv('HTTP_FORWARDED_FOR');

    } else if(getenv('HTTP_FORWARDED')) {

        $ipaddress = getenv('HTTP_FORWARDED');

    } else if(getenv('REMOTE_ADDR')) {

        $ipaddress = getenv('REMOTE_ADDR');

    } else {
        $ipaddress = '알수없음';
    }

    return $ipaddress;
}
define('__REMOTE_ADDR__', getRealClientIp());

//날짜 및 시간
define('__TIME_YMD__',      date('Y-m-d', time()));
define('__TIME_HIS__',      date('H:i:s', time()));
define('__TIME_HI__',       date('H:i', time()));
define('__TIME_YMDHI__',    date('Y-m-d H:i', time()));
define('__TIME_YMDHIS__',   date('Y-m-d H:i:s', time()));
//define('__WITHDRAW_YMD__',      date('Y-m-d', strtotime("-365 days")));

//로그인 관련
define("__DEFAULT_LOGIN_FAIL_CNT__", 5);    //비밀번호 오류 횟수

define("__DEFAULT_LANG_CD__", "LANGKOR");
