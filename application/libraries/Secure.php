<?php
class Secure
{
    protected $_ci;
    protected $_crypt_pass;
    protected $_crypt_iv;
    protected $_method;

    public function __construct()
    {
        $this->_ci = &get_instance();

        $password           = "www.cielclub.net";
        $this->_method      = "aes-256-cbc";
        $this->_crypt_pass  = $this->get_password($password);
        $this->_crypt_iv    = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

    }

    /**
     * @param $password
     * @return false|string
     * 비밀번호 바이너리
     */
    protected function get_password($password)
    {
        // 256 bit 키를 만들기 위해서 비밀번호를 해시해서 첫 32바이트를 사용합니다.
        $password = substr(hash('sha256', $password, true), 0, 32);

        return $password;
    }

    /**
     * @param $data
     * @return false|string
     * 암호화, 대칭키 알고리즘
     */
    public function encrypt($data)
    {
        $en_data = openssl_encrypt($data , $this->_method, $this->_crypt_pass, OPENSSL_RAW_DATA, $this->_crypt_iv);
        $en_data = base64_encode($en_data);

        return $en_data;
    }

    /**
     * @param $en_data
     * @return false|string
     * 복호화, 대칭키 알고리즘
     */
    public function decrypt($en_data)
    {
        $en_data = base64_decode($en_data);
        $de_data = openssl_decrypt($en_data, $this->_method, $this->_crypt_pass, OPENSSL_RAW_DATA, $this->_crypt_iv);

        return $de_data;
    }

    public function password_hash($password)
    {
        $hash = base64_encode(hash("sha256", $password, true));

        return $hash;
    }

    public function password_verify($password, $hash)
    {
        $new_hash = $this->password_hash($password);

        if($new_hash === $hash):
            return true;
        else:
            return false;
        endif;
    }

}