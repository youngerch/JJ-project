<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-07-09
 */

require_once APPPATH . '/libraries/aws/aws-autoloader.php'; // change path as needed

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

class Aws_s3_lib
{
    protected $_client;

    function __construct()
    {
        $this->_ci = &get_instance();

        define('S3_KEY', 'AKIAXWOE6KD63BZQUKFQ');
        define('S3_SECRET_KEY', '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');
        define('BUCKET', 'data.sambosarang.com');

        $this->_client = new S3Client(array(
            'version' => 'latest',
            'region' => 'ap-northeast-2',
            'credentials' => array(
                'key' => S3_KEY,
                'secret' => S3_SECRET_KEY,
            ),
        ));
    }

    /**
     * @param $key
     * @param $source
     * @return array
     * 파일 저장
     * 폴더는 키값을 폴더명 / 키값으로 하면 됨
     */
    public function set_object($key, $source)
    {
        $uploader = new MultipartUploader($this->_client, $source, array(
            'bucket' => BUCKET,
            'key' => $key,
        ));

        try {
            $result = $uploader->upload();

            $returnData = array(
                'result' => "SUCCESS",
                'key' => $key,
                'data' => $result
            );

        } catch (MultipartUploadException $e) {
            $result = $e->getMessage() . "\n";

            $result = array("Errors" => "Upload Exception", "description" => $e->getMessage());

            $returnData = array(
                'result' => "ERROR",
                'data' => $result
            );
        }

        return $returnData;

    }

    /**
     * 버킷 리스트 가져오기
     */
    public function get_bucket_list()
    {
        $buckets = $this->_client->listBuckets();
        foreach ($buckets['Buckets'] as $bucket) {
            echo $bucket['Name'] . "\n";
        }
    }

    /**
     * @param $folder_key
     * @return array
     * 버킷에 폴더 생성
     * 사용안함~
     */
    public function create_folder($folder_key)
    {

        try {
            $result = $this->_client->putObject(array(
                'Bucket' => BUCKET, // Defines name of Bucket
                'Key' => $folder_key, //Defines Folder name
                'Body' => "",
                'ACL' => 'public-read-write' // Defines Permission to that folder
            ));

            $returnData = array(
                'result' => "SUCCESS",
                'data' => $result
            );

        } catch (Exception $e) {

            $result = array("Errors" => "Put Object Exception", "description" => $e->getMessage());

            $returnData = array(
                'result' => "ERROR",
                'data' => $result
            );
        }

        return $returnData;
    }

    /**
     * @param array $images
     * @return array|\Aws\Result
     * 오브젝트 삭제
     */
    public function delete_object($images = array())
    {
        $img_list = array();

        for ($i = 0; $i < sizeof($images); $i++):
            array_push($img_list, array('Key' => $images[$i]));
        endfor;

        $objects = array('Objects' => $img_list);
        try {
            $result = $this->_client->deleteObjects(Array(
                'Bucket' => BUCKET,
                'Delete' => $objects
            ));
            $returnData = array(
                'result' => "SUCCESS",
                'data' => $result
            );
        } catch (Exception $e) {
            $result = array("Errors" => "Delete Image Exception", "description" => $e->getMessage());

            $returnData = array(
                'result' => "ERROR",
                'data' => $result
            );
        }

        return $returnData;
    }


    /**
     * @param $key
     * @return array|\Aws\Result
     * 오브젝트 가져오기
     */
    public function get_object($key)
    {
        try {
            $result = $this->_client->getObject(array(
                'Bucket' => BUCKET,
                'Key' => $key
            ));

            $returnData = array(
                'result' => "SUCCESS",
                'data' => $result
            );

        } catch (Exception $e) {
            $result = array("Errors" => "Get Object Exception", "description" => $e->getMessage());

            $returnData = array(
                'result' => "ERROR",
                'data' => $result
            );
        }

        return $returnData;
    }

    /**
     * @param $key
     * 이미지 뷰어~
     */
    public function view($key)
    {
        $obj = $this->get_object($key);

        if ($obj['result'] === "SUCCESS"):

            ob_clean();

            //가져온 객체를 브라우저 상에 보여줍니다
            header("Content-Type: {$obj['data']['ContentType']}");
            echo $obj['data']['Body'];
        else:
            pr($obj);
        endif;
    }


}
