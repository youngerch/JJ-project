<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-07-09
 */
class Board_lib
{

    private $_ci;

    public function __construct()
    {
        $this->_ci =& get_instance();

        $this->_ci->load->model(array(
            'common/board_model'        => 'board',
            'common/board_file_model'   => 'board_file',
            'common/banner_model'       => 'banner',
            'common/faq_model'          => 'faq',
            'common/inquiry_model'      => 'inquiry',
        ));
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-28
     * @param $board_seq
     * @return bool
     * 게시물 정보를 가져온다
     */
    public function get_board($board_seq)
    {
        $_board = $this->_ci->board->get($board_seq);

        if(!isset($_board->seq)):
            return false;
        else:
            $_board_file = $this->_ci->board_file->get_many_by(array('board_seq' => $_board->seq, 'is_display' => 1));

            $_board->files = $_board_file;

            return $_board;
        endif;
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-28
     * @param $banner_seq
     * @return bool
     * 배너 정보를 가져온다
     */
    public function get_banner($banner_seq)
    {
        $_banner = $this->_ci->banner->get($banner_seq);

        if(!isset($_banner->seq)):
            return false;
        else:
            return $_banner;
        endif;
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-28
     * @param $faq_seq
     * @return bool
     * 자주묻는질문 정보를 가져온다
     */
    public function get_faq($faq_seq)
    {
        $_faq = $this->_ci->faq->get($faq_seq);

        if(!isset($_faq->seq)):
            return false;
        else:
            return $_faq;
        endif;
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-28
     * @param $inquiry_seq
     * @return bool
     * 배너 정보를 가져온다
     */
    public function get_inquiry($inquiry_seq)
    {
        $_inquiry = $this->_ci->inquiry->get($inquiry_seq);

        if(!isset($_inquiry->seq)):
            return false;
        else:
            return $_inquiry;
        endif;
    }

}