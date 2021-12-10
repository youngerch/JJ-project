<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-08
 */

/**
 * Class Common
 *
 * 범용적으로 사용되는 페이지/데이터 전달용
 *
 * Created on 2021-06-08
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Common extends User_Controller
{
    protected $_module;

    function __construct()
    {
        parent::__construct();

        $this->_module = "common";

    }

    /**
     * Function ckeditor_upload
     *
     * CKEditor 파일 업로드
     *
     * @param $path 파일업로드 경로 구분값1 ( controller ex) item )
     * @param $code 파일업로드 경로 구분값. ( code ex) item_cd )
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-22
     * @access
     */
    public function ckeditor_upload($path, $code)
    {
        $this->load->library('upload');

        $uploadPath = __DATA_PATH__ . "/" . $path . "/" . $code . "/editor";
        $config['upload_path'] = $uploadPath;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0707, TRUE);
        } // End if

        $this->upload->initialize(array(
            "upload_path" => $uploadPath,
            "allowed_types" => '*'
        ));

        if (!$this->upload->do_upload("upload")) {
            echo "<script>alert('업로드에 실패 했습니다. " . $this->upload->display_errors('', '') . "')</script>";
        }
        else {
            $data = $this->upload->data();
            $filename = $data['file_name'];

            $this->load->library(array('aws_s3_lib' => 's3'));

            //s3에 업로드
            $url = $uploadPath . "/" . $data['file_name'];
            $url = str_replace(__DATA_PATH__, __DEFAULT_HOST__ . "/data", $url);

            $key = $path . "/" . $code . "/editor/" . $data['file_name'];
            $source = $url;

            $rlt = $this->s3->set_object($key, $source);
            $image = str_replace(',', '', "/common/img/" . base64UrlEncode($key));
            $returnData = array(
                "filename" => $filename,
                "uploaded" => 1,
                "url" => $image
            );
            echo json_encode($returnData);
        } // End if
    }

    /**
     * Function img
     *
     * AWS S3와 연결하여 키값을 통하여 S3 URL를 표시.
     *
     * @param string $key 저장된 Image 키값
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-08
     * @access
     */
    public function img($key)
    {
        $key = base64UrlDecode($key);
        $this->load->library("aws_s3_lib");
        $this->aws_s3_lib->view($key);
    }

    /**
     * Function js_language
     *
     *
     *
     * @author Kim Chang Soo <cs.kim@ablex.co.kr> on 2021-06-08
     * @access
     */
    public function js_language()
    {
        $_page = "js_language";
        $data = array(
            'page'  => $this->_template_layer . $_page,
            'module'=> $this->_module
        );
        $this->load->view($this->_container_layer, $data);
    }
}