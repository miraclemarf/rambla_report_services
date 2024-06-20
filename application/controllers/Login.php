<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends My_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}

	public function index()
	{
		extract(populateform());

		if ($this->input->cookie('cookie_invent_user') != NULL) {
			redirect('dashboard', 'refresh');
		} else {
			$this->form_validation->set_rules('username', 'Username', 'required');

			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]');

			if ($this->form_validation->run() == FALSE) {
				$data['title'] = 'Rambla | Login Page';
				$this->load->view('template_auth/header', $data);
				$this->load->view('template_auth/login', $data);
				$this->load->view('template_auth/footer', $data);
			} else {
				$this->login_proses($username, $password);
			}
		}
	}

	function login_proses($username, $password)
	{
		extract(populateform());

		$query = $this->db->query("SELECT * FROM m_login a
        inner join m_role b
        on a.role_id = b.role_id
        where username ='" . $username . "' AND password = '" . $password . "' and a.is_active ='1'");


		if ($query->num_rows() > 0) {

			$vToken   = $this->randstring();
			$username = $query->row()->username;
			$tipe     = $query->row()->role_id;

			//Delete login data 
			$this->Models->queryhandle("DELETE FROM t_login_log WHERE username = '" . $username . "'");

			$this->input_cookie_login($username, $tipe, $vToken);

			//Update Login Status//
			$this->Models->queryhandle("UPDATE m_login set last_login_date = NOW(), LoginStatus = 1 WHERE username = '" . $username . "'");


			$jam = $this->db->query("SELECT (SELECT CURRENT_TIMESTAMP()) AS sekarang, (SELECT CURRENT_TIMESTAMP() + INTERVAL 8 HOUR) AS hangus")->row();

			$this->db->query("INSERT INTO t_login_log (username, sesi, time_in, expired_time) 
                              VALUES ( '" . $username . "','" . $vToken . "','" . $jam->sekarang . "','" . $jam->hangus . "' ) ");

			redirect(base_url() . "Dashboard");
		} else {
			// $this->session->set_flashdata('message-failed', 'Login Failed !');
			redirect(base_url() . "login");
		}
	}

	function randstring()
	{
		$pass = 60;
		$allchar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		mt_srand((float) microtime() * 1000000);
		$string = '';
		for ($i = 0; $i < $pass; $i++) {
			$string .= $allchar[mt_rand(0, strlen($allchar) - 1)];
		}
		return $string;
	}

	function input_cookie_login($user, $tipe, $vToken)
	{
		$cookie1 = array(
			'name'   => 'cookie_invent_user',
			'value'  => strtoupper($user),
			'expire' => '86400'
		);
		$this->input->set_cookie($cookie1);

		$cookie2 = array(
			'name'   => 'cookie_invent_sesi',
			'value'  => $vToken,
			'expire' => '86400'
		);
		$this->input->set_cookie($cookie2);

		$cookie3 = array(
			'name'   => 'cookie_invent_tipe',
			'value'  => $tipe,
			'expire' => '86400'
		);
		$this->input->set_cookie($cookie3);
	}
}
