<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Configuration extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Datatables');
        $this->load->model('M_PaidOnline');
        $this->load->model('M_OperationalFee');
        $this->load->model('M_Categories');
        $this->load->model('M_Division');
        $this->ceklogin();
    }

    public function users()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Users';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('configuration/users', $data);
        $this->load->view('template_member/footer');
    }

    public function users_where()
    {
        extract(populateform());
        $tables = "(
        SELECT CASE
        WHEN login_type_id = '1' THEN 'True'
        ELSE 'False'
        END as operational, username, `password`, email, b.role_id, role_name, last_login_date, loginStatus,is_active  from m_login a
        left join m_role b
        on a.role_id = b.role_id where a.role_id != '') a";
        $search = array('username', 'email');
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $where = array('');

        $filter = "";

        if ($params1 || $params2) {
            if ($params1) {
                $params1 = $params1 == "non" ? '0' : '1';
                $filter1 = " AND is_Active = '" . $params1 . "'";
                $filter .= $filter1;
            }
            if ($params2) {
                $filter2 = " AND role_id = '" . $params2 . "'";
                $filter .= $filter2;
            }
            $isWhere = $filter;
        } else {
            $isWhere = $filter;
        }

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }
}
