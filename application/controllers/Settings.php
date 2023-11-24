<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->ceklogin();
    }

    public function index()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Settings';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['vendor']         = $this->input->cookie('cookie_invent_vendor');
        $data['vendor_name']    = $this->Models->showsingle("SELECT * FROM m_vendor where vendor_code = '".$data['vendor']."'");
        $data['email']          = $this->Models->showsingle("SELECT * FROM m_login where username ='".$data['username']."'");
        
        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('dashboard/settings', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function edit_profile()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $this->db->query("UPDATE m_login set email ='".$email."' WHERE username ='".$data['username']."'");
        if($this->db->affected_rows()){
            $this->session->set_flashdata('message-success', 'Data Berhasil diubah');
        }else{
            $this->session->set_flashdata('message-failed', 'Tidak ada perubahan');
        }
        redirect(base_url() . "Settings");
    }

    public function edit_password()
    {
        extract(populateform());
        // echo $PasswordLama;
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $user = $this->Models->showsingle("SELECT * FROM m_login WHERE username ='".$data['username']."'");
        if($user->password != $PasswordLama){
            $this->session->set_flashdata('message-failed', 'Password Lama tidak sesuai');
        }else{
            if($PasswordBaru != $KonfirmPassword){
                $this->session->set_flashdata('message-failed', 'Konfirmasi Password tidak sesuai');
            }else{
                if(strlen($PasswordBaru) < 5) {
                    $this->session->set_flashdata('message-failed', 'Password setidaknya memiliki 5 karakter');
                }else{
                    $this->db->query("UPDATE m_login set password ='".$PasswordBaru."' WHERE username ='".$data['username']."'");
                    // $this->session->set_flashdata('message-success', 'Password Berhasil diubah, silakan login kembali');
                    $this->insert_history($PasswordLama, $PasswordBaru);
                    redirect(base_url() . "logout");
                }
            }
        }
        redirect(base_url() . "Settings");
    }

    public function insert_history($PasswordLama, $PasswordBaru)
    {
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['vendor']         = $this->input->cookie('cookie_invent_vendor');
        $this->db->query("INSERT into t_password_history (old_password,new_password, edit_date, edit_by, vendor_code) values ('".$PasswordLama."','".$PasswordBaru."',CURRENT_TIMESTAMP(),'".$data['username'] ."','".$data['vendor']."')"); 
    }
}
