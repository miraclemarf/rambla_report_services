<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesToday extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Store');
        $this->ceklogin();
    }

    public function index()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Sales Today';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        // echo $this->M_Categories->get_category('tessa');
        // die;
        $store = !$this->input->post('storeid') ? 'R001' : $this->input->post('storeid');
        $data['storeid']        =  $store;

        $data['site'] = $this->db->query("SELECT a.branch_id, b.branch_name from m_user_site a
        inner join m_branches b
        on a.branch_id = b.branch_id
        where a.flagactv ='1'
        and username ='".$data['username']."'")->result();

        if(!$data['site']){
            echo "<script>
            alert('Anda tidak punya akses site');
            window.location.href='".base_url('Dashboard')."';
            </script>";
        }   

        $store = !$this->input->post('storeid') ? $data['site'][0]->branch_id : $this->input->post('storeid');
        $data['storeid']        =  $store;
        

        $data['result'] = $this->M_Store->get_sales_today($store);
        $data['resultBrand'] = $this->M_Store->get_top10_brand($store);
        // $data['resultArticle'] = $this->M_Store->get_top10_article($store);

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/salestoday', $data);
        $this->load->view('template_member/footer', $data);        
    }

}
