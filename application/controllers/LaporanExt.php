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
        $this->ceklogin();
    }
    public function index($test){
        echo $test;
    }

    public function penjualan_hf()
    {
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

    public function list_sales_hf(){
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

    public function set_hfredis() {
        $result = $this->M_Horeca->setRedisHappyFreshSales();
        if ($result) {
            api_response("success", "Set Redis Ok", null);
        } else {
            api_response("error", "Set Redis fail", null, ["Database error"], 500);
        }
    }

    public function testRedis() {
        // Load the Redis library
        $this->load->library('redislib');

        // Set and Get Redis values
        $this->redislib->set('test_key', 'Hello, Redis!');
        echo $this->redislib->get('test_key');
    }
}