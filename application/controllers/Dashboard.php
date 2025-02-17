<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;


class Dashboard extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Categories');
        $this->load->model('M_Division');
        $this->ceklogin();
    }

    public function index()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Dashboard Page';
        $data['username']       = $this->input->cookie('cookie_invent_user');

        $where = "";

        $data['store_name']     = "";


        $data['site'] = $this->db->query("SELECT a.branch_id, b.branch_name from m_user_site a
        inner join m_branches b
        on a.branch_id = b.branch_id
        where a.flagactv ='1'
        and username ='" . $data['username'] . "'")->result();

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();
        if ($cek_user_category) {
            $where .= $this->M_Categories->get_category($data['username']);
        } else if ($data['site']) {
            $where = $this->M_Division->get_division($data['username'], $data['site'][0]->branch_id);
        } else {
            $where .= "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($data['site']) {
            $data['store_name'] = $data['site'][0]->branch_name;
            $store = !$this->input->post('storeid') ? $data['site'][0]->branch_id : $this->input->post('storeid');
        } else {
            $data['store_name'] = 'Rambla Kelapa Gading';
            $store = !$this->input->post('storeid') ? 'R001' : $this->input->post('storeid');
        }
        $data['storeid']        =  $store ? $store : 'R001';

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('dashboard/index_new', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function default_load()
    {
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $branch_id = null;
        $filter = null;
        // START CEK ADA DEPT NGGA
        $cek_user_dept = $this->db->query("SELECT * from m_user_sub_division where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT a.branch_id, branch_name from m_user_site a
        inner join m_branches b
        on a.branch_id = b.branch_id
        where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();

        if ($cek_user_dept) {
            // HANYA USER DENGAN DEPT TERTENTU
            $query = "SELECT DISTINCT brand_code as brand FROM m_vendor_category
            where left(category_code,4) in (
            SELECT distinct kode_sub_division from m_user_sub_division where username = '" . $data['username'] . "' and flagactv = '1'
            ) and isactive = '1'";
        } else if ($cek_user_site) {
            $branch_id = $cek_user_site->branch_id;
            // HANYA USER DENGAN SITE TERTENTU
            $filter = $this->M_Division->get_division($data['username'], $branch_id);
            $query = "SELECT DISTINCT brand_code as brand from m_brand";
        } else {
            // UNTUK MD
            $query = "SELECT distinct brand from m_user_brand where username = '" . $data['username'] . "'";
        }
        // END CEK ADA DEPTNYA NGGA

        $branch_id = $branch_id ? $branch_id : 'R001';

        $query = "SELECT brand_code from (
        SELECT ROW_NUMBER() OVER (order by sum(net_af) desc) ranking1, date_format(periode , '%Y.%m') periode1, brand_code as brand_code ,  sum(net_af) tnet1
        from r_sales 
        where DATE_FORMAT(periode,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m')
        and brand_code in (" . $query . ")
        and branch_id = '" . $branch_id . "'
        $filter
        group by date_format(periode , '%Y.%m'), brand_code
        order by tnet1 desc
        limit 3) brand";

        $brand = $this->db->query($query)->result();

        $data = array();

        foreach ($brand as $row) {
            array_push($data, $row->brand_code);
        }

        return $data;
    }

    public function penjualan_brand_where()
    {
        $postData = $this->input->post();

        $data['username'] = $this->input->cookie('cookie_invent_user');
        $kode_brand = array(null);
        $category = array(null);
        $division = array(null);
        $branch_id = null;

        // START CEK ADA DEPT NGGA
        $cek_user_dept = $this->db->query("SELECT * from m_user_sub_division where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT a.branch_id, branch_name from m_user_site a
        inner join m_branches b
        on a.branch_id = b.branch_id
        where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();

        if ($cek_user_dept) {
            // HANYA USER DENGAN DEPT TERTENTU
            $list_sub_div = $this->db->query("SELECT distinct kode_sub_division from m_user_sub_division where username = '" . $data['username'] . "' and flagactv = '1'")->result();
            foreach ($list_sub_div as $row) {
                array_push($category, $row->kode_sub_division);
            }
        } else if ($cek_user_site) {
            $branch_id = $cek_user_site->branch_id;
            $division = $this->M_Division->get_division_meta($data['username'], $branch_id);
        } else {
            // UNTUK MD
            $list_brand = $this->db->query("SELECT distinct brand from m_user_brand where username = '" . $data['username'] . "'")->result();
            foreach ($list_brand as $row) {
                array_push($kode_brand, $row->brand);
            }
        }
        // END CEK ADA DEPTNYA NGGA
        $branch_id =  $branch_id ?  $branch_id : 'R001';
        $store = $postData["params1"] ? $postData["params1"] : $branch_id;
        $division = $division ? $division : array(null);
        $periode =  ubahFormatTanggal($postData["params3"]);
        $kode_brand = $postData['params2'] ? $postData['params2'] : $this->default_load();
        $category = $category ? $category : array(null);

        // $metabaseSiteUrl = 'http://192.168.8.99:3000';
        $metabaseSiteUrl = 'https://metabase.stardeptstore.com';
        $metabaseSecretKey = '91465c305d756abd48b936a0a9ae99ce4e868bb3cfa36ca6dbc824158a60c489';

        //metabase
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($metabaseSecretKey));
        $builder = $config->builder();

        $token = $builder
            ->withClaim('resource', ['dashboard' => 241])
            ->withClaim('params', [
                'store'         => $store,
                'periode'       => $periode,
                'division'      => $division,
                'brand'         => $kode_brand,
                'kode_sub_div'  => $category,
            ])
            ->getToken($config->signer(), $config->signingKey());

        $tokenString = $token->toString();
        $iframeUrl = "$metabaseSiteUrl/embed/dashboard/$tokenString#bordered=false&titled=false&hide_header=true/";
        echo $iframeUrl;
    }

    public function get_top10_rank($store)
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where = "";


        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $where .= $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $where .= $this->M_Division->get_division($data['username'], $store);
        } else {
            $where .= "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        $data['hasil'] = $this->Models->showdata("SELECT * from (
            select ROW_NUMBER() OVER (order by sum(net_af) desc) ranking1, date_format(periode , '%Y.%m') periode1, brand_name as brand_name1 , sum(tot_qty) tot_qty1, sum(net_af) tnet1
            from r_sales 
            where date_format(periode , '%Y.%m') = date_format(DATE_ADD(current_date(), INTERVAL -2 MONTH),'%Y.%m') 
            $where
            and branch_id = '" . $store . "'
            group by date_format(periode , '%Y.%m'), brand_name1
            order by tnet1 desc
            limit 10	
        ) a inner join (
            select ROW_NUMBER() OVER (order by sum(net_af) desc) ranking2, date_format(periode , '%Y.%m') periode2, brand_name as brand_name2, sum(tot_qty) tot_qty2, sum(net_af) tnet2
            from r_sales 
            where date_format(periode , '%Y.%m') = date_format(DATE_ADD(current_date(), INTERVAL -1 MONTH),'%Y.%m') 
            $where
            and branch_id = '" . $store . "'
            group by date_format(periode , '%Y.%m'), brand_name2
            order by tnet2 desc
            limit 10
        ) b on a.ranking1 = b. ranking2 inner join (
            select ROW_NUMBER() OVER (order by sum(net_af) desc) ranking3, date_format(periode , '%Y.%m') periode3, brand_name as brand_name3, sum(tot_qty) tot_qty3, sum(net_af) tnet3
            from r_sales 
            where date_format(periode , '%Y.%m') = date_format(DATE_ADD(current_date(), INTERVAL 0 MONTH),'%Y.%m') 
            $where
            and branch_id = '" . $store . "'
            group by date_format(periode , '%Y.%m'), brand_name3
            order by tnet3 desc
            limit 10
        ) c on a.ranking1 = c.ranking3");
        echo json_encode($data);
    }
}
