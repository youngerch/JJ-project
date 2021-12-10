<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Member_lib
 *
 * 회원 정보 관리 라이브러리
 *
 * Created on 2021-06-09
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Member_lib {

    protected $_ci;

    public function __construct()
    {
        $this->_ci = &get_instance();
        $this->_ci->load->model(array(
            'common/member_model'                   => 'm_member',
            'common/member_log_model'               => 'm_member_log',
            'common/member_login_model'             => 'm_member_login',
            'common/member_subscription_model'      => 'm_member_subscription',
            'common/member_subscription_log_model'  => 'm_member_subscription_log',
            'common/member_delivery_model'          => 'm_member_delivery',
            'common/item_model'                     => 'm_item',
        ));
    }

    /**
     * Function get_member
     *
     * 회원 순번을 이용한 정보 조회
     *
     * @param $member_seq 회원 순번
     * @param bool $secure 복호화 여부
     * @return false
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function get_member($member_seq, $decrypt = true)
    {
        $_member = $this->_ci->m_member->get($member_seq);

        if (!isset($_member->seq)) {
            return false;
        }
        else {
            if ($decrypt) {
                $_member->login_id  = $this->_ci->secure->decrypt($_member->login_id_s);
                $_member->hp = $this->_ci->secure->decrypt($_member->hp_s);
                $_member->email = $this->_ci->secure->decrypt($_member->email_s);
            } // End if

            //추천인 정보가 있을 경우
            $_member->advisor = "-";
            if (!empty($_member->recommend_seq)) {
                $_recommend = $this->_ci->m_member->get($_member->recommend_seq);
                if (isset($_recommend->seq)) {
                    $_member->advisor = $_recommend->nickname;
                } // End if
            } // End if

            //정기결제정보
            $_member->subscription = $this->get_member_subscription($_member->seq);
            if (isset($_member->subscription->seq)) {
                $_member->subscription->item = $this->_ci->m_item->get_by(array('item_cd' => $_member->subscription->item_cd));
            }

            //배송지정보
            $_member->delivery = $this->get_member_delivery($_member->seq);

            return $_member;
        } // End if
    }

    /**
     * Function get_member_by_column
     *
     * 컬럼 정보를 이용한 회원 정보 조회
     *
     * @param $whereData 컬럼 정보와 데이터를 가진 Array
     * @return false
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function get_member_by_column($whereData)
    {
        $_member = $this->_ci->m_member->get_by($whereData);

        if (!isset($_member->seq)) {
            return false;
        }
        else {
            if ($secure) {
                $_member->login_id  = $this->_ci->secure->decrypt($_member->login_id_s);
                $_member->hp        = $this->_ci->secure->decrypt($_member->hp_s);
                $_member->email     = $this->_ci->secure->decrypt($_member->email_s);
            } // End if

            //추천인 정보가 있을 경우
            $_member->advisor = "-";
            if (!empty($_member->recommend_seq)) {
                $_recommend = $this->_ci->m_member->get($_member->recommend_seq);
                if (isset($_recommend->seq)) {
                    $_member->advisor = $_recommend->nickname;
                } // End if
            } // End if

            //정기결제 여부
            $over_days = "";
            if (trim($_member->expire_date) != "") {
                $date_diff = date_diff(date_create($_member->expire_date), date_create(Date('Y-m-d')));
                if ($_member->expire_date < Date('Y-m-d')) {
                    $over_days = $date_diff->days * -1;
                }
                else {
                    $over_days = $date_diff->days;
                } // End if
            } // End if

            //쿠폰정보
            $_member->coupon = $this->get_member_coupon($_member->seq);

            //코인정보
            $_member->coin = $this->get_member_coin($_member->seq);

            //정기결제정보
            $_member->subscription = $this->get_member_subscription($_member->seq);
            if (isset($_member->subscription->seq)) {
                $_member->subscription->item = $this->_ci->m_item->get_by(array('item_cd' => $_member->subscription->item_cd));
            }

            //배송지정보
            $_member->delivery = $this->get_member_delivery($_member->seq);

            return $_member;
        } // End if
    }

    /**
     * Function set_member
     *
     * 회원 정보 업데이트
     *
     * @param $member_seq 회원순번
     * @param $updateData 업데이트 대상 데이터 ( Key => Value ) 조합
     * @return bool
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function set_member($member_seq, $updateData)
    {
        //수정 전 데이터
        $_member = $this->get_member($member_seq);

        $rlt = $this->_ci->m_member->update($member_seq, $updateData);

        if($rlt) {
            $this->member_log($_member);
            return true;
        }
        else {
            return false;
        } // End if
    }

    /**
     * Function member_log
     *
     * 회원 정보 변경 로그
     *
     * @param $beforeData 수정 전 데이터
     * @param string $admin_seq 관리자 순번 (Optional)
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function member_log($beforeData, $admin_seq = '')
    {
        // 수정 후 데이터
        $afterData = $this->get_member($beforeData->seq);

        $insertLogData = array(
            'member_seq'        => $beforeData->seq,
            'before_member_data'=> json_encode($beforeData),
            'after_member_data' => json_encode($afterData),
            'reg_date'          => __TIME_YMDHIS__,
            'reg_ip'            => __REMOTE_ADDR__,
            'reg_admin_seq'     => $admin_seq !== "" ? $admin_seq : "",
            'is_admin'          => $admin_seq !== "" ? "1" : "0",
        );
        $this->_ci->m_member_log->insert($insertLogData);
    }

    /**
     * Function get_member_subscription
     *
     * 회원 정기결제 정보 조회
     *
     * @param $member_seq
     * @return false
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function get_member_subscription($member_seq)
    {
        $_member_subscription = $this->_ci->m_member_subscription->get_by(array('member_seq' => $member_seq));
        
        if (isset($_member_subscription->seq)) {
            return $_member_subscription;
        }
        else {
            return false;
        } // End if
    }

    /**
     * Function set_member_subscription
     *
     * 회원 정기 결제 정보 업데이트
     *
     * @param $member_seq 회원순번
     * @param $updateData 업데이트 정보
     * @return bool
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function set_member_subscription($member_seq, $updateData)
    {
        $_member_subscription = $this->get_member_subscription($member_seq);

        if (isset($_member_subscription->seq)) {
            $member_subscription_seq = $_member_subscription->seq;

            $rlt = $this->_ci->m_member_subscription->update($member_subscription_seq, $updateData);
            if ($rlt) {
                $this->member_subscription_log($_member_subscription);
                return true;
            } else {
                return false;
            } // End if
        }
        else {
            return false;
        } // End if
    }

    /**
     * Function member_subscription_log
     *
     * 회원 정기결제 정보 변경 로그 기록
     *
     * @param $prev_data 수정 전 데이터
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function member_subscription_log($prev_data)
    {
        $after_data = $this->get_member_coupon($prev_data->member_seq);

        $insertData = array(
            'member_seq'     => $prev_data->member_seq,
            'before_data'    => json_encode($prev_data),
            'after_data'     => json_encode($after_data),
            'reg_date'       => __TIME_YMDHIS__,
            'reg_ip'         => __REMOTE_ADDR__,
        );
        $rlt = $this->_ci->m_member_subscription_log->insert($insertData);

        return $rlt;
    }

    /**
     * Function get_member_delivery
     *
     * 회원 배송지 정보 조회
     *
     * @param $member_seq 회원 순번
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function get_member_delivery($member_seq)
    {
        $_member_delivery = $this->_ci->m_member_delivery->get_latest_delivery($member_seq);

        if (!isset($_member_delivery->seq)) {
            //데이터가 없을 경우 초기 데이터로 데이터 생성
            $insertData = array(
                'seq'               => "0",
                'receiver'          => "",
                'phone'             => "",
                'zipcode'           => "",
                'address'           => "",
                'address_detail'    => "",
                'memo'              => ""
            );
            $_member_delivery = arrayToObject($insertData);
        }
        return $_member_delivery;
    }

    /**
     * Function set_member_delivery
     *
     * 회원 정보 배송지 업데이트
     *
     * @param $member_seq 회원 순번
     * @param $updateData 업데이트 정보
     * @return mixed
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-09
     * @access
     */
    public function set_member_delivery($member_seq, $updateData)
    {
        $_member_delivery = $this->_ci->m_member_delivery->get_latest_delivery($member_seq);

        if (!$_member_delivery) {
            $this->_ci->m_member_delivery->insert($updateData);
            return $this->_ci->db->insert_id();
        }
        else {
            $this->_ci->m_member_delivery->update($_member_delivery->seq, $updateData);
            return $_member_delivery->seq;
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

}
