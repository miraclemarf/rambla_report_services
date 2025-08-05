<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class LaporanExt extends My_Controller
{
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $this->load->helper(array('form', 'url', 'api_helper'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Horeca');
    }
    public function index($test)
    {
        echo $test;
    }

    public function penjualan_hf()
    {

        $this->ceklogin();
        extract(populateform());
        $data['title'] = 'SMJ Tools | Laporan Penjualan Happy Fresh';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        $data['happyFreshSales'] = $this->M_Horeca->getHappyFreshSales();

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/penjualan_hf', $data);
        $this->load->view('template_member/footer');
    }

    public function list_sales_hf()
    {
        $draw = intval($this->input->post('draw'));
        $happyFreshSales = $this->M_Horeca->getHappyFreshSales();

        $response = array(
            'draw' => $draw,
            'recordsTotal' => count($happyFreshSales),
            'recordsFiltered' => count($happyFreshSales),
            'data' => $happyFreshSales
        );

        echo json_encode($response);
    }

    public function set_hfredis()
    {
        $result = $this->M_Horeca->setRedisHappyFreshSales();
        if ($result) {
            api_response("success", "Set Redis Ok", null);
        } else {
            api_response("error", "Set Redis fail", null, ["Database error"], 500);
        }
    }

    public function testRedis()
    {
        // Load the Redis library
        $this->load->library('redislib');

        // Set and Get Redis values
        $this->redislib->set('test_key', 'Hello, Redis!');
        echo $this->redislib->get('test_key');
    }

    public function sa_lebaran_2025()
    {
        $this->ceklogin();
        extract(populateform());
        $data['title'] = 'SMJ Tools | Laporan SA Lebaran 2025';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');


        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        $cek_usersite = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "'")->result_array();
        
        $data['iframe'] = 'https://meta.rambla.id/public/dashboard/479b229d-6978-4292-bec7-99e3aebaf929';
        if(count($cek_usersite) == 1){
            if($cek_usersite[0]['branch_id'] == 'R001'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/fddd894d-23ca-4084-99f7-5f597edab2be';
            }
            if($cek_usersite[0]['branch_id'] == 'R002'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/5b6a2961-e0c2-467e-8e58-0359daad4bae';
            }
            if($cek_usersite[0]['branch_id'] == 'S002'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/5963a6ab-fb20-428c-aa2d-5a5c6719b86d';
            }
            if($cek_usersite[0]['branch_id'] == 'S003'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/24d92e5b-6743-4fd5-85d1-f2770c0416cc';
            }
        }
        

        //$data['happyFreshSales'] = $this->M_Horeca->getHappyFreshSales();

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/sa-lebaran-2025', $data);
        $this->load->view('template_member/footer');
    }
    public function sa_target_monthly()
    {
        $this->ceklogin();
        extract(populateform());
        $data['title'] = 'SMJ Tools | Laporan Bulanan Target SA';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');


        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        $cek_usersite = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "'")->result_array();
        
        $data['iframe'] = 'https://meta.rambla.id/public/dashboard/44ef14bc-51bc-424b-89d3-08ef6ad6b582';
        if(count($cek_usersite) == 1){
            if($cek_usersite[0]['branch_id'] == 'R001'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/420ecf96-bcac-4d14-9f99-ae9e16d5b517';
            }
            if($cek_usersite[0]['branch_id'] == 'R002'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/8a0c1dfd-241d-4fbc-92dd-423ec53fe24b';
            }
            if($cek_usersite[0]['branch_id'] == 'S002'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/26bc6bed-3b31-46aa-be2b-03d546f953aa';
            }
            if($cek_usersite[0]['branch_id'] == 'S003'){
                $data['iframe'] = 'https://meta.rambla.id/public/question/ca6e8963-9493-4ee4-aebf-1c4353287073';
            }
        }
        

        //$data['happyFreshSales'] = $this->M_Horeca->getHappyFreshSales();

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/sa-lebaran-2025', $data);
        $this->load->view('template_member/footer');
    }
}
