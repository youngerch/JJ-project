<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-07-02
 */

class Upload_lib
{
    private $_ci;

    public function __construct()
    {
        $this->_ci = &get_instance();
    }

    /**
     * @param $path : 업로드 경로
     * @param $files : 파일
     * @return array|bool
     * 다중 파일 업로드
     */
    public function upload_files($path, $files)
    {
        $config = array(
            'upload_path'   => $path,
            'allowed_types' => 'jpg|gif|png|jpeg|PNG|GIF|JPG|JPEG',
            'overwrite'     => false,
            'encrypt_name'  => true
        );
        $this->_ci->load->library('upload', $config);

        if (!is_dir($path)) {
            mkdir($path, 0707, TRUE);
        }

        $lists = array();
        $i = 0;
        var_dump($files);
        foreach ($files as $key => $file_name) {
            $_FILES['images[]']['name'] = $file_name['name'][$key];
            $_FILES['images[]']['type'] = $file_name['type'][$key];
            $_FILES['images[]']['tmp_name'] = $file_name['tmp_name'][$key];
            $_FILES['images[]']['error'] = $file_name['error'][$key];
            $_FILES['images[]']['size'] = $file_name['size'][$key];

            $this->_ci->upload->initialize($config);

            if ($this->_ci->upload->do_upload('images[]')) {
                $lists[$i] = $this->_ci->upload->data();
                $i++;
            } else {
                return false;
            }
        }

        return $lists;
    }

    /**
     * @param $path : 업로드 경로
     * @param $files : 파일
     * @return array|bool
     * 다중 파일 업로드
     */
    public function upload_shop_files($path, $files)
    {
        $config = array(
            'upload_path' => $path,
            'allowed_types' => 'jpg|gif|png|jpeg|PNG|GIF|JPG|JPEG',
            'overwrite' => 1,
            'encrypt_name' => true
        );
        $this->_ci->load->library('upload', $config);

        if (!is_dir($path)) {
            mkdir($path, 0707, TRUE);
        }

        $lists = array();
        $i = 0;
        var_dump($files);
        foreach ($files['name'] as $key => $file_name) {
            var_dump($file_name);
            $_FILES['images[]']['name'] = $files['name'][$key];
            $_FILES['images[]']['type'] = $files['type'][$key];
            $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['images[]']['error'] = $files['error'][$key];
            $_FILES['images[]']['size'] = $files['size'][$key];

            $this->_ci->upload->initialize($config);

            if ($this->_ci->upload->do_upload('images[]')) {
                $lists[$i] = $this->_ci->upload->data();
                $i++;
            } else {
                return false;
            }
        }

        return $lists;
    }


}