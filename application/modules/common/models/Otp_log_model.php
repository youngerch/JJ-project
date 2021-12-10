<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-09
 */

/**
 * Class Otp_log_model
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
class Otp_log_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "otp_log";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }
}