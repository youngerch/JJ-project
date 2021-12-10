<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-21
 */

/**
 * Class Banner_image_model
 *
 * 언어별 배너 이미지 관리
 *
 * Created on 2021-06-24
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Banner_image_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "banner_image";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}