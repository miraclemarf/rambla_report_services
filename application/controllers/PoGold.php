<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PoGold extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Gold');
        $this->ceklogin();
    }

    public function index()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Purchase Order';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        // echo $this->M_Categories->get_category('tessa');
        

        $data['result'] = $this->M_Gold->get_data();
 

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('po-gold/index', $data);
        $this->load->view('template_member/footer', $data);
    }

}
