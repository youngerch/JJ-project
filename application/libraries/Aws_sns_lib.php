<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-03-08
 */
putenv('HOME=/var/www/shop');

require_once APPPATH . '/libraries/aws/aws-autoloader.php'; // change path as needed

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
class Aws_sns_lib
{
    private $_ci;
    private $_config;
    private $_client;
    public function __construct()
    {
        $this->_ci = &get_instance();

        $this->_config = array(
            'profile'   => 'default',
            'region'    => 'ap-northeast-1',
            'version'   => '2010-03-31'
        );

        $this->_client = new SnsClient($this->_config);
    }

    public function send_sms($phone_no, $msg)
    {

        try
        {
            $result = $this->_client->publish([ 'Message' => $msg, 'PhoneNumber' => $phone_no]);
            //pr($result);
            return $result;
        }
        catch (AwsException $e)
        {
            // 실패했을 시에 오류 메시지를 뱉습니다.
            pr($e->getMessage());
        }
    }
}