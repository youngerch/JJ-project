<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Main
 *
 *
 *
 * Created on 2021-06-09
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Main extends Sub_Controller
{
    protected $_module;
    function __construct()
    {
        parent::__construct();
        $this->_module = "main";

        $this->load->model(array(
            'common/admin_model'       => 'm_admin'
        ));
    }

    public function index()
    {
        $this->dashboard();
    }

    /**
     * Function dashboard
     *
     * 대시보드
     *
     * @author Eunjung Moon <ejmoon@ablex.co.kr> on 2021-11.16
     * @access
     */
    public function dashboard()
    {
        //회원
        $memberData = array(
            'status'        => '1',
            'date_type'     => "A.join_date",
            'date_start'    => '0000-00-00 00:00:00',
            'date_end'      => Date('Y-m-d'),
        );
        $memberTotal = $this->m_member->get_count_all($memberData);

        $joinData = array(
            'status'        => '1',
            'date_type'     => "A.join_date",
            'date_start'    => Date('Y-m-d'),
            'date_end'      => Date('Y-m-d'),
        );
        $joinCnt = $this->m_member->get_count_all($joinData);

        $leaveData = array(
            'status'        => '1', //탈퇴완료시 status값 확인할 것
            'date_type'     => "A.leave_date",
            'date_start'    => Date('Y-m-d'),
            'date_end'      => Date('Y-m-d'),
        );
        $leaveCnt = $this->m_member->get_count_all($leaveData);

        $loginData = array(
            'status'        => '1',
            'date_type'     => "A.login_date",
            'date_start'    => Date('Y-m-d'),
            'date_end'      => Date('Y-m-d'),
        );
        $loginCnt = $this->m_member->get_count_all($loginData);

        $_page = "dashboard";
        $data = array(
            'memberTotal'       => $memberTotal,
            'joinCnt'           => $joinCnt,
            'loginCnt'          => $loginCnt,
            'leaveCnt'          => $leaveCnt,
            'page'              => $this->_template . $_page,
            'module'            => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

}
