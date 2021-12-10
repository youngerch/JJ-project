<?php
class Session_lib
{
    protected $_ci;

    public function __construct()
    {
        $this->_ci = &get_instance();

        $this->_ci->load->model(array(
            'common/member_model'   => 'member',
            'common/session_model'  => 'session_model'
        ));
    }

    public function get_session($session_id)
    {
        $session = $this->_ci->session_model->get($session_id);

        return $session;
    }

    public function set_session($session_id, $data = array())
    {
        $rlt = $this->_ci->session_model->update($session_id, $data);

        return $rlt;
    }
}