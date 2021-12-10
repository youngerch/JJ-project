<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-06-26
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shop extends Sub_Controller
{
    protected $_module;

    function __construct()
    {
        parent::__construct();
        $this->_module = "config";

        $this->_menu_item = 'config';
        $this->_menu_item_sub = 'shop';

        $this->load->model(array(
            'common/shop_model' => 'shop',
            'common/shop_lang_model' => 'shop_lang'
        ));

        $this->load->library(array(
            'code_lib'
        ));

    }

    public function index()
    {
        $this->edit();
    }

    public function edit($lang_cd = __DEFAULT_LANG_CD__)
    {
        $shop = $this->shop->get_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'lang_cd' => $lang_cd));

        if(!isset($shop->shop_cd)):

            if($lang_cd === __DEFAULT_LANG_CD__):

                $insertData = array(
                    'shop_cd' => __DEFAULT_SHOP_CD__,
                    'lang_cd' => $lang_cd,
                    'shop_name' => "쇼핑몰 이름",
                    'reg_date' => __TIME_YMDHIS__,
                    'reg_ip' => __REMOTE_ADDR__,
                    'reg_admin_seq' => $this->_admin->seq
                );

            else:

                $shop = $this->shop->get_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'lang_cd' => __DEFAULT_LANG_CD__));

                $insertData = objectToArray($shop);
                unset($insertData['seq']);
                $insertData['lang_cd']          = $lang_cd;
                $insertData['reg_date']         = __TIME_YMDHIS__;
                $insertData['reg_ip']           = __REMOTE_ADDR__;
                $insertData['reg_admin_seq']    = $this->_admin->seq;
                $insertData['mod_date']         = '';
                $insertData['mod_ip']           = '';
                $insertData['mod_admin_seq']    = '';


            endif;

            $this->shop->insert($insertData);

            $insertData = array(
                'shop_cd'   => __DEFAULT_SHOP_CD__,
                'lang_cd'   => __DEFAULT_LANG_CD__,
                'use_is'    => '1',
                'reg_date'  => __TIME_YMDHIS__,
                'reg_admin_seq' => $this->_admin->seq
            );
            $this->shop_lang->insert($insertData);


            $shop = $this->shop->get_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'lang_cd' => $lang_cd));

        endif;

        $_lang_cds = $this->shop_lang->get_data(__DEFAULT_SHOP_CD__);

        $shop->lang_cds = array();
        for($i=0; $i < count($_lang_cds); $i++):
            $shop->lang_cds[$i] = $_lang_cds[$i]['lang_cd'];
        endfor;

        $trans_cds = $this->code_lib->get_codes("12");
        $lang_cds = $this->code_lib->get_codes("11");

        $_page = "shop_edit";
        $data = array(
            'shop'          => $shop,
            'trans_cds'     => $trans_cds,
            'lang_cds'      => $lang_cds,
            'page'          => $this->_template . $_page,
            'module'        => $this->_module
        );
        $this->load->view($this->_container, $data);
    }

    /**
     * Created by Kamiz
     * USER : ablex
     * DATE : 2020-06-29
     * TIME : 오후 4:12
     * DESC : 쇼핑몰 설정 저장
     */
    public function edit_process_ajax()
    {

        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('seq', '설정 순번', 'trim|required');
        $this->form_validation->set_rules('shop_name', '쇼핑몰 이름', 'trim|required');


        //validation
        if ($this->form_validation->run() === TRUE):

            $seq        = $this->input->post('seq');

            $updateData = array(
                'shop_name'             => $this->input->post('shop_name'),
                'shop_desc'             => $this->input->post('shop_desc'),
                'shop_tel'              => $this->input->post('shop_tel'),
                'shop_zip'              => $this->input->post('shop_zip'),
                'shop_addr'             => $this->input->post('shop_addr'),
                'shop_addr_detail'      => $this->input->post('shop_addr_detail'),
                'owner_name'            => $this->input->post('owner_name'),
                'dbank_use_is'          => $this->input->post('dbank_use_is'),
                'bank_account_data'     => $this->input->post('bank_account_data'),
                'vbank_use_is'          => $this->input->post('vbank_use_is'),
                'hp_use_is'             => $this->input->post('hp_use_is'),
                'card_use_is'           => $this->input->post('card_use_is'),
                'coin_use_is'           => $this->input->post('coin_use_is'),
                'pay_point_use_is'      => $this->input->post('pay_point_use_is'),
                'pay_point_min_amt'     => $this->input->post('pay_point_min_amt'),
                'pay_point_max_amt'     => $this->input->post('pay_point_max_amt'),
                'pay_point_unit'        => $this->input->post('pay_point_unit'),
                'coupon_use_is'         => $this->input->post('coupon_use_is'),
                'receipt_use_is'        => $this->input->post('receipt_use_is'),
                'trans_company_cd'      => $this->input->post('trans_company_cd'),
                'trans_type'            => $this->input->post('trans_type'),
                'trans_cost'            => $this->input->post('trans_cost'),
                'trans_data'            => $this->input->post('trans_data'),
                'exchange_return_data'  => $this->input->post('exchange_return_data'),
                'review_confirm_type'   => $this->input->post('review_confirm_type'),
                'saupja_reg_num'        => $this->input->post('saupja_reg_num'),
                'tongsin_num'           => $this->input->post('tongsin_num'),
                'buga_saupja_num'       => $this->input->post('buga_saupja_num'),
                'security_name'         => $this->input->post('security_name'),
                'security_email'        => $this->input->post('security_email'),
                'mod_date'              => __TIME_YMDHIS__,
                'mod_ip'                => __REMOTE_ADDR__,
                'mod_admin_seq'         => $this->_admin->seq
            );
            $rlt = $this->shop->update($seq, $updateData);

            $lang_cds = $this->input->post("lang_cds");

            $this->shop_lang->delete_by(array('shop_cd' => __DEFAULT_SHOP_CD__));

            foreach($lang_cds as $key => $lang):

                $insertData = array(
                    'shop_cd' => __DEFAULT_SHOP_CD__,
                    'lang_cd' => $lang,
                    'use_is' => '1',
                    'reg_date' => __TIME_YMDHIS__,
                    'reg_admin_seq' => $this->_admin->seq
                );
                $this->shop_lang->insert($insertData);

            endforeach;


            if($rlt):
                $result = "SUCCESS";
                $msg = "쇼핑몰 설정이 성공적으로 변경되었습니다.";
            else:
                $result = "ERROR_UPDATE";
                $msg = "쇼핑몰 설정 변경중 오류가 발생했습니다.";
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

    public function init_ajax()
    {
        $this->util->is_ajax_alert();

        $this->load->library(['form_validation']);

        // validate form input
        $this->form_validation->set_rules('lang_cd', '언어 코드', 'trim|required');


        //validation
        if ($this->form_validation->run() === TRUE):

            $lang_cd        = $this->input->post('lang_cd');

            $shop = $this->shop->get_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'lang_cd' => __DEFAULT_LANG_CD__));

            $updateData = objectToArray($shop);
            unset($updateData['seq']);
            $updateData['mod_date']         = __TIME_YMDHIS__;
            $updateData['mod_ip']           = __REMOTE_ADDR__;
            $updateData['mod_admin_seq']    = $this->_admin->seq;

            $rlt = $this->shop->update_by(array('shop_cd' => __DEFAULT_SHOP_CD__, 'lang_cd' => $lang_cd), $updateData);


            if($rlt):
                $result = "SUCCESS";
                $msg = "쇼핑몰 설정이 성공적으로 초기화되었습니다.";
            else:
                $result = "ERROR_UPDATE";
                $msg = "쇼핑몰 설정 초기화중 오류가 발생했습니다.";
            endif;

        else:

            $result = "ERROR";
            $msg = (validation_errors() ? strip_tags(validation_errors()) : "오류로 인해 초기화에 실패했습니다.");

        endif;

        $returnData = array(
            'result'    => $result,
            'msg'       => $msg
        );
        echo json_encode($returnData);
        exit;
    }


}