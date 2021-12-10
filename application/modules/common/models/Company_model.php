<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-07-08
 */

/**
 * Class Company_model
 *
 * 회사 정보
 *
 * Created on 2021-07-08
 * @subpackage
 * @category
 * @author Kim Chang Soo <cs.kim@ablex.co.kr>
 * @link
 * @version
 * @copyright
 */
class Company_model extends MY_Model
{
    public $_table          = __TABLE_PREFIX__ . "company";
    public $primary_key     = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}