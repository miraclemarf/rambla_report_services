<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends My_Controller
{
    function index()
    {
        $this->load->helper('cookie');

        $username = $this->input->cookie('cookie_invent_user');

        delete_cookie('cookie_invent_user');
        delete_cookie('cookie_invent_tipe');
        delete_cookie('cookie_invent_sesi');
        delete_cookie('cookie_invent_vendor');
        $this->Models->queryhandle("UPDATE m_login set update_login_date = NOW(), LoginStatus = 0 WHERE username = '" . $username . "'");
        $this->Models->queryhandle("DELETE FROM t_login_log WHERE username = '" . $username . "'");

        redirect('login');
    }
}
