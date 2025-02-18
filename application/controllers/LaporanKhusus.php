<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class LaporanKhusus extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $data['username'] = $this->input->cookie('cookie_invent_user');
        // $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        // $cek_operation = $cek_operation->login_type_id;

        // if ($cek_operation == "1") {
        //     echo "<script>
        //     alert('Anda tidak punya hak akses!');
        //     window.location.href='" . base_url('Dashboard') . "';
        //     </script>";
        // }

        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Datatables');
        $this->load->model('M_Sales');
        $this->load->model('M_Categories');
        $this->load->model('M_Division');
        $this->ceklogin();
    }

    public function penjualan_brand()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Penjualan';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan_khusus/penjualan_brand', $data);
        $this->load->view('template_member/footer');
    }

    public function penjualan_brand_meta()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Penjualan Metabase';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan_khusus/penjualan_brand_meta', $data);
        $this->load->view('template_member/footer');
    }

    public function penjualan_kategori()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Penjualan';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan_khusus/penjualan_kategori', $data);
        $this->load->view('template_member/footer');
    }

    public function penjualan_periode()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Penjualan';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan_khusus/penjualan_periode', $data);
        $this->load->view('template_member/footer');
    }

    public function penjualan_brand_where()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getPenjualanBrand($postData);
        echo json_encode($data);
    }

    public function penjualan_brand_meta_where()
    {
        $postData = $this->input->post();

        $store = $postData["params8"];
        $last_period =  ubahFormatTanggal($postData["params3"]);
        $this_period =  ubahFormatTanggal($postData["params9"]);
        $pecah = explode('~', $this_period);
        $target_date =  $pecah[0];

        if ($postData["params1"] == "") {
            $brand_code = null;
        } else {
            $brand_code = $postData["params1"];
        }

        if ($postData["params5"] == "") {
            $sbu = null;
        } else {
            $sbu = $postData["params5"];
        }

        if ($postData["params6"] == "") {
            $departement = null;
        } else {
            $departement = strtoupper($postData["params6"]);
        }


        $metabaseSiteUrl = 'https://metabase.stardeptstore.com';
        $metabaseSecretKey = '91465c305d756abd48b936a0a9ae99ce4e868bb3cfa36ca6dbc824158a60c489';

        //metabase
        $now = new DateTimeImmutable();
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($metabaseSecretKey));
        $builder = $config->builder();
        $signer = new Sha256();

        $token = $builder
            ->withClaim('resource', ['dashboard' => 173])
            ->withClaim('params', [
                'target_periode' => $target_date,
                'brand' => [$brand_code],
                'store' => [$store],
                'sbu' => [$sbu],
                'departement' => [$departement],
                'last_periode' => $last_period,
                'this_periode' => $this_period
            ])
            ->getToken($config->signer(), $config->signingKey());



        $tokenString = $token->toString();
        $iframeUrl = "$metabaseSiteUrl/embed/dashboard/$tokenString#theme=transparent&bordered=false&titled=false&hide_header=true/";
        echo $iframeUrl;
    }

    public function penjualan_kategori_where()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getPenjualanKategori($postData);
        echo json_encode($data);
    }

    function generate_date()
    {
        extract(populateform());

        if (strpos($last_periode, '-') !== false and strpos($this_periode, '-') !== false) {
            $tgl1 = explode("-", $last_periode);
            $fromdate1 = date("Y-m-d", strtotime($tgl1[0]));
            $todate1 = date("Y-m-d", strtotime($tgl1[1]));

            $tgl2 = explode("-", $this_periode);
            $fromdate2 = date("Y-m-d", strtotime($tgl2[0]));
            $todate2 = date("Y-m-d", strtotime($tgl2[1]));

            $data = array('fromdate1' => $fromdate1, 'todate1' => $todate1, 'fromdate2' => $fromdate2, 'todate2' => $todate2);
        } else {
            $data = array('fromdate1' => null, 'todate1' => null, 'fromdate2' => null, 'todate2' => null);
        }
        echo json_encode($data);
    }

    function export_excel_penjualanbrand($fromdate1, $todate1, $fromdate2, $todate2, $brand_code, $division, $sub_division, $dept, $sub_dept, $store)
    {
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        $last_period = "";
        $this_period = "";

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $where = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $where = $this->M_Division->get_division($data['username'], $store);
        } else {
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($brand_code !== "null") {
            $where .= " AND brand_code = '" . $brand_code . "'";
        }

        if ($division !== "null") {
            $where .= " AND DIVISION = '" . $division . "'";
        }

        if ($sub_division !== "null") {
            $where .= " AND SUB_DIVISION = '" . $sub_division . "'";
        }

        if ($dept !== "null") {
            $where .= " AND DEPT = '" . $dept . "'";
        }

        if ($sub_dept !== "null") {
            $where .= " AND SUB_DEPT = '" . $sub_dept . "'";
        }

        if ($fromdate1 !== null and $todate1 !== null) {
            $last_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate1 . "' and '" . $todate1 . "'";
        }

        if ($fromdate2 !== null and $todate2 !== null) {
            $this_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate2 . "' and '" . $todate2 . "'";
        }

        $query = "SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        CASE WHEN LP.DEPT is null then TP.DEPT else LP.DEPT end as DEPT, 
        CONCAT(CASE WHEN LP.brand_code is null then TP.brand_code else LP.brand_code end,' - ',CASE WHEN LP.brand_name is null then TP.brand_name else LP.brand_name end) as BRAND, 
        -- FLOOR
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.net_online as LP_Sales3, '' as TP_Target3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) LP
        left join 
        (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) TP on LP.brand_code = TP.brand_code and TP.DEPT = LP.DEPT  
        union
        SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        CASE WHEN LP.DEPT is null then TP.DEPT else LP.DEPT end as DEPT, 
        CONCAT(CASE WHEN LP.brand_code is null then TP.brand_code else LP.brand_code end,' - ',CASE WHEN LP.brand_name is null then TP.brand_name else LP.brand_name end) as BRAND, 
        -- FLOOR
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.net_online as LP_Sales3, '' as TP_Target3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) LP
        right join 
        (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) TP on LP.brand_code = TP.brand_code and TP.DEPT = LP.DEPT   
        ORDER BY SBU, DEPT";

        $data = $this->db->query($query)->result_array();
        $store_code = $this->db->query("SELECT concat(branch_name,' (',branch_id,')') as store_name from m_branches where branch_id ='" . $store . "'")->row();

        $lp = indo_date3($fromdate1) . ' - ' . indo_date3($todate1);
        $tp = indo_date3($fromdate2) . ' - ' . indo_date3($todate2);

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '' . $store_code->store_name . '')->mergeCells('A1:C1');
        $sheet->setCellValue('A2', '(LP) ' . $lp . '')->mergeCells('A2:C2');
        $sheet->setCellValue('A3', '(TP) ' . $tp . '')->mergeCells('A3:C3');
        $sheet->setCellValue('A4', 'SBU')->mergeCells('A4:A5');
        $sheet->setCellValue('B4', 'DEPT')->mergeCells('B4:B5');
        $sheet->setCellValue('C4', 'BRAND')->mergeCells('C4:C5');
        $sheet->setCellValue('D4', 'FLOOR')->mergeCells('D4:L4');
        $sheet->setCellValue('D5', 'LP Sales');
        $sheet->setCellValue('E5', 'TP Target');
        $sheet->setCellValue('F5', 'TP Sales');
        $sheet->setCellValue('G5', '%Achieve');
        $sheet->setCellValue('H5', '%Growth	');
        $sheet->setCellValue('I5', '%LP Margin');
        $sheet->setCellValue('J5', '%TP Margin');
        $sheet->setCellValue('K5', 'LP Margin Value');
        $sheet->setCellValue('L5', 'TP Margin Value');
        $sheet->setCellValue('M4', 'ATRIUM')->mergeCells('M4:U4');
        $sheet->setCellValue('M5', 'LP Sales');
        $sheet->setCellValue('N5', 'TP Target');
        $sheet->setCellValue('O5', 'TP Sales');
        $sheet->setCellValue('P5', '%Achieve');
        $sheet->setCellValue('Q5', '%Growth	');
        $sheet->setCellValue('R5', '%LP Margin');
        $sheet->setCellValue('S5', '%TP Margin');
        $sheet->setCellValue('T5', 'LP Margin Value');
        $sheet->setCellValue('U5', 'TP Margin Value');
        $sheet->setCellValue('V4', 'ONLINE')->mergeCells('V4:AD4');
        $sheet->setCellValue('V5', 'LP Sales');
        $sheet->setCellValue('W5', 'TP Target');
        $sheet->setCellValue('X5', 'TP Sales');
        $sheet->setCellValue('Y5', '%Achieve');
        $sheet->setCellValue('Z5', '%Growth');
        $sheet->setCellValue('AA5', '%LP Margin');
        $sheet->setCellValue('AB5', '%TP Margin');
        $sheet->setCellValue('AC5', 'LP Margin Value');
        $sheet->setCellValue('AD5', 'TP Margin Value');
        $sheet->setCellValue('AE4', 'TOTAL')->mergeCells('AE4:AM4');
        $sheet->setCellValue('AE5', 'LP Sales');
        $sheet->setCellValue('AF5', 'TP Target');
        $sheet->setCellValue('AG5', 'TP Sales');
        $sheet->setCellValue('AH5', '%Achieve');
        $sheet->setCellValue('AI5', '%Growth');
        $sheet->setCellValue('AJ5', '%LP Margin');
        $sheet->setCellValue('AK5', '%TP Margin');
        $sheet->setCellValue('AL5', 'LP Margin Value');
        $sheet->setCellValue('AM5', 'TP Margin Value');


        $sheet->getStyle('A4:AM4')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('D5:AM5')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        /* Excel Data */
        $row_number = 6;
        $lastRow = count($data) + $row_number;
        $arrAmtCol = ['D', 'F', 'K', 'L', 'M', 'O', 'T', 'U', 'V', 'X', 'AC', 'AD', 'AE', 'AG', 'AL', 'AM'];
        foreach ($arrAmtCol as $val) {
            $sheet->getStyle($val . $row_number . ':' . $val . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        }

        $arrPctCol = ['H', 'I', 'J', 'Q', 'R', 'S', 'Z', 'AA', 'AB', 'AI', 'AJ', 'AK'];
        foreach ($arrPctCol as $val) {
            $sheet->getStyle($val . $row_number . ':' . $val . $lastRow)->getNumberFormat()->setFormatCode('0.0"%"');
        }
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $row['SBU']);
            $sheet->setCellValue('B' . $row_number, $row['DEPT']);
            $sheet->setCellValue('C' . $row_number, $row['BRAND']);
            $sheet->setCellValue('D' . $row_number, $row['LP_Sales1']);
            $sheet->setCellValue('E' . $row_number, $row['TP_Target1']);
            $sheet->setCellValue('F' . $row_number, $row['TP_Sales1']);
            $sheet->setCellValue('G' . $row_number, $row['Achieve1']);
            $sheet->setCellValue('H' . $row_number, $row['Growth1']);
            $sheet->setCellValue('I' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent1']);
            $sheet->setCellValue('J' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent1']);
            $sheet->setCellValue('K' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value1']);
            $sheet->setCellValue('L' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value1']);
            $sheet->setCellValue('M' . $row_number, $row['LP_Sales2']);
            $sheet->setCellValue('N' . $row_number, $row['TP_Target2']);
            $sheet->setCellValue('O' . $row_number, $row['TP_Sales2']);
            $sheet->setCellValue('P' . $row_number, $row['Achieve2']);
            $sheet->setCellValue('Q' . $row_number, $row['Growth2']);
            $sheet->setCellValue('R' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent2']);
            $sheet->setCellValue('S' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent2']);
            $sheet->setCellValue('T' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value2']);
            $sheet->setCellValue('U' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value2']);
            $sheet->setCellValue('V' . $row_number, $row['LP_Sales3']);
            $sheet->setCellValue('W' . $row_number, $row['TP_Target3']);
            $sheet->setCellValue('X' . $row_number, $row['TP_Sales3']);
            $sheet->setCellValue('Y' . $row_number, $row['Achieve3']);
            $sheet->setCellValue('Z' . $row_number, $row['Growth3']);
            $sheet->setCellValue('AA' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent3']);
            $sheet->setCellValue('AB' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent3']);
            $sheet->setCellValue('AC' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value3']);
            $sheet->setCellValue('AD' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value3']);
            $sheet->setCellValue('AE' . $row_number, $row['LP_Sales4']);
            $sheet->setCellValue('AF' . $row_number, $row['TP_Target4']);
            $sheet->setCellValue('AG' . $row_number, $row['TP_Sales4']);
            $sheet->setCellValue('AH' . $row_number, $row['Achieve4']);
            $sheet->setCellValue('AI' . $row_number, $row['Growth4']);
            $sheet->setCellValue('AJ' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent4']);
            $sheet->setCellValue('AK' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent4']);
            $sheet->setCellValue('AL' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value4']);
            $sheet->setCellValue('AM' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value4']);
            $row_number++;
        }


        $sheet->getStyle('A4:C' . $row_number . '')->getFont()->setBold(true);
        $sheet->getStyle('A4:AM' . $row_number . '')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $sheet->getStyle('D4:U' . $row_number . '')->getNumberFormat()->setFormatCode('#');
        $sheet->getStyle('D4:AM4')->getFont()->setBold(true);

        foreach (range('A', 'AM') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A' . $row_number . '', 'TOTAL')->mergeCells('A' . $row_number . ':C' . $row_number . '');
        $sheet->getStyle('A' . $row_number . ':C' . $row_number . '')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row_number . ':AM' . $row_number . '')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A' . $row_number . ':AM' . $row_number . '')->getFill()->getStartColor()->setRGB('FFF000');


        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'sales_by_brand_report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_penjualankategori($fromdate1, $todate1, $fromdate2, $todate2, $division, $sub_division, $store)
    {
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);

        $last_period = "";
        $this_period = "";

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $where = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $where = $this->M_Division->get_division($data['username'], $store);
        } else {
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($division !== "null") {
            $where .= " AND DIVISION = '" . $division . "'";
        }

        if ($sub_division !== "null") {
            $where .= " AND SUB_DIVISION = '" . $sub_division . "'";
        }

        if ($fromdate1 !== null and $todate1 !== null) {
            $last_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate1 . "' and '" . $todate1 . "'";
        }

        if ($fromdate2 !== null and $todate2 !== null) {
            $this_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate2 . "' and '" . $todate2 . "'";
        }

        $query = "SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        -- FLOOR
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.net_online as LP_Sales3, '' as TP_Target3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION
        ) LP
        left join 
        (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION
        ) TP on LP.SUB_DIVISION = TP.SUB_DIVISION  
        union
        SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        -- FLOOR
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.net_online as LP_Sales3, '' as TP_Target3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION
        ) LP
        right join 
        (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $where
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION
        ) TP on LP.SUB_DIVISION = TP.SUB_DIVISION  
        ORDER BY SBU";

        $data = $this->db->query($query)->result_array();
        $store_code = $this->db->query("SELECT concat(branch_name,' (',branch_id,')') as store_name from m_branches where branch_id ='" . $store . "'")->row();

        $lp = indo_date3($fromdate1) . ' - ' . indo_date3($todate1);
        $tp = indo_date3($fromdate2) . ' - ' . indo_date3($todate2);

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '' . $store_code->store_name . '')->mergeCells('A1:C1');
        $sheet->setCellValue('A2', '(LP) ' . $lp . '')->mergeCells('A2:C2');
        $sheet->setCellValue('A3', '(TP) ' . $tp . '')->mergeCells('A3:C3');
        $sheet->setCellValue('A4', 'SBU')->mergeCells('A4:A5');
        $sheet->setCellValue('B4', 'FLOOR')->mergeCells('B4:J4');
        $sheet->setCellValue('B5', 'LP Sales');
        $sheet->setCellValue('C5', 'TP Target');
        $sheet->setCellValue('D5', 'TP Sales');
        $sheet->setCellValue('E5', '%Achieve');
        $sheet->setCellValue('F5', '%Growth	');
        $sheet->setCellValue('G5', '%LP Margin');
        $sheet->setCellValue('H5', '%TP Margin');
        $sheet->setCellValue('I5', 'LP Margin Value');
        $sheet->setCellValue('J5', 'TP Margin Value');
        $sheet->setCellValue('K4', 'ATRIUM')->mergeCells('K4:S4');
        $sheet->setCellValue('K5', 'LP Sales');
        $sheet->setCellValue('L5', 'TP Target');
        $sheet->setCellValue('M5', 'TP Sales');
        $sheet->setCellValue('N5', '%Achieve');
        $sheet->setCellValue('O5', '%Growth');
        $sheet->setCellValue('P5', '%LP Margin');
        $sheet->setCellValue('Q5', '%TP Margin');
        $sheet->setCellValue('R5', 'LP Margin Value');
        $sheet->setCellValue('S5', 'TP Margin Value');
        $sheet->setCellValue('T4', 'ONLINE')->mergeCells('T4:AB4');
        $sheet->setCellValue('T5', 'LP Sales');
        $sheet->setCellValue('U5', 'TP Target');
        $sheet->setCellValue('V5', 'TP Sales');
        $sheet->setCellValue('W5', '%Achieve');
        $sheet->setCellValue('X5', '%Growth	');
        $sheet->setCellValue('Y5', '%LP Margin');
        $sheet->setCellValue('Z5', '%TP Margin');
        $sheet->setCellValue('AA5', 'LP Margin Value');
        $sheet->setCellValue('AB5', 'TP Margin Value');
        $sheet->setCellValue('AC4', 'TOTAL')->mergeCells('AC4:AK4');
        $sheet->setCellValue('AC5', 'LP Sales');
        $sheet->setCellValue('AD5', 'TP Target');
        $sheet->setCellValue('AE5', 'TP Sales');
        $sheet->setCellValue('AF5', '%Achieve');
        $sheet->setCellValue('AG5', '%Growth');
        $sheet->setCellValue('AH5', '%LP Margin');
        $sheet->setCellValue('AI5', '%TP Margin');
        $sheet->setCellValue('AJ5', 'LP Margin Value');
        $sheet->setCellValue('AK5', 'TP Margin Value');

        $sheet->getStyle('A4:AK4')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('B5:AK5')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        /* Excel Data */
        $row_number = 6;
        $lastRow = count($data) + $row_number;
        $arrAmtCol = ['B', 'D', 'I', 'J', 'K', 'M', 'R', 'S', 'T', 'V', 'AA', 'AB', 'AC', 'AE', 'AJ', 'AK'];
        foreach ($arrAmtCol as $val) {
            $sheet->getStyle($val . $row_number . ':' . $val . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        }

        $arrPctCol = ['F', 'G', 'H', 'O', 'P', 'Q', 'X', 'Y', 'Z', 'AG', 'AH', 'AI'];
        foreach ($arrPctCol as $val) {
            $sheet->getStyle($val . $row_number . ':' . $val . $lastRow)->getNumberFormat()->setFormatCode('0.0"%"');
        }

        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $row['SBU']);
            $sheet->setCellValue('B' . $row_number, $row['LP_Sales1']);
            $sheet->setCellValue('C' . $row_number, $row['TP_Target1']);
            $sheet->setCellValue('D' . $row_number, $row['TP_Sales1']);
            $sheet->setCellValue('E' . $row_number, $row['Achieve1']);
            $sheet->setCellValue('F' . $row_number, $row['Growth1']);
            $sheet->setCellValue('G' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent1']);
            $sheet->setCellValue('H' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent1']);
            $sheet->setCellValue('I' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value1']);
            $sheet->setCellValue('J' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value1']);
            $sheet->setCellValue('K' . $row_number, $row['LP_Sales2']);
            $sheet->setCellValue('L' . $row_number, $row['TP_Target2']);
            $sheet->setCellValue('M' . $row_number, $row['TP_Sales2']);
            $sheet->setCellValue('N' . $row_number, $row['Achieve2']);
            $sheet->setCellValue('O' . $row_number, $row['Growth2']);
            $sheet->setCellValue('P' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent2']);
            $sheet->setCellValue('Q' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent2']);
            $sheet->setCellValue('R' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value2']);
            $sheet->setCellValue('S' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value2']);
            $sheet->setCellValue('T' . $row_number, $row['LP_Sales3']);
            $sheet->setCellValue('U' . $row_number, $row['TP_Target3']);
            $sheet->setCellValue('V' . $row_number, $row['TP_Sales3']);
            $sheet->setCellValue('W' . $row_number, $row['Achieve3']);
            $sheet->setCellValue('X' . $row_number, $row['Growth3']);
            $sheet->setCellValue('Y' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent3']);
            $sheet->setCellValue('Z' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent3']);
            $sheet->setCellValue('AA' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value3']);
            $sheet->setCellValue('AB' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value3']);
            $sheet->setCellValue('AC' . $row_number, $row['LP_Sales4']);
            $sheet->setCellValue('AD' . $row_number, $row['TP_Target4']);
            $sheet->setCellValue('AE' . $row_number, $row['TP_Sales4']);
            $sheet->setCellValue('AF' . $row_number, $row['Achieve4']);
            $sheet->setCellValue('AG' . $row_number, $row['Growth4']);
            $sheet->setCellValue('AH' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Percent4']);
            $sheet->setCellValue('AI' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Percent4']);
            $sheet->setCellValue('AJ' . $row_number, ($cek_operation == "1") ? "" : $row['LP_Margin_Value4']);
            $sheet->setCellValue('AK' . $row_number, ($cek_operation == "1") ? "" : $row['TP_Margin_Value4']);
            $row_number++;
        }

        $sheet->getStyle('A4:A' . $row_number . '')->getFont()->setBold(true);
        // $sheet->getStyle('B4:Y' . $row_number . '')->getNumberFormat()->setFormatCode('#');
        $sheet->getStyle('A4:AK' . $row_number . '')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('B4:AK4')->getFont()->setBold(true);

        foreach (range('A', 'AK') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A' . $row_number . '', 'TOTAL');
        $sheet->getStyle('A' . $row_number . '')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row_number . ':AK' . $row_number . '')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A' . $row_number . ':AK' . $row_number . '')->getFill()->getStartColor()->setRGB('FFF000');


        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'sales_by_brand_kategori';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_test()
    {
        $data = $this->db->query("select periode, barcode, net_af from report_service.r_sales where branch_id = 'R001' and DATE_FORMAT(periode,'%Y-%m-%d') = '2025-01-01' limit 2000")->result_array();

        $chunkSize = 100; // Adjust chunk size as needed
        $outputDir = 'sales_by_brand_kategori'; // Directory to store the chunks

        // Create output directory if not exists
        if (!is_dir($outputDir)) {
            mkdir($outputDir);
        }

        $this->chunkDataIntoSpreadsheets($data, $chunkSize, $outputDir);
    }

    private function chunkDataIntoSpreadsheets($data, $chunkSize, $outputDir)
    {
        // Calculate how many files we will need to generate
        $totalChunks = ceil(count($data) / $chunkSize);

        for ($i = 0; $i < $totalChunks; $i++) {
            // Create a new spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Vendor Code');
            $sheet->setCellValue('C1', 'Vendor Name');


            // Slice the data to get the chunk
            $chunk = array_slice($data, $i * $chunkSize, $chunkSize);

            // Add data to the spreadsheet
            foreach ($chunk as $row) {
                $row_number = 2;
                foreach ($data as $key => $row) {
                    $sheet->setCellValue('A' . $row_number, $row['periode']);
                    $sheet->setCellValue('B' . $row_number, $row['barcode']);
                    $sheet->setCellValue('C' . $row_number, $row['net_af']);
                    $row_number++;
                }
            }

            // Write the spreadsheet to a file
            $fileName = $outputDir . '/chunk_' . ($i + 1);
            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
    }

    // function export_excel_test()
    // {
    //     $data = $this->db->query("select * from m_branches")->result_array();
    //     /* Spreadsheet Init */
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     /* Excel Header */
    //     $sheet->setCellValue('A1', 'R001');
    //     $sheet->setCellValue('A2', '(LP) LAST PERIODE : 1 SEPTEMBER - 30 SEPTEMBER 2023');
    //     $sheet->setCellValue('A3', '(TP) THIS PERIODE : 1 SEPTEMBER - 30 SEPTEMBER 2024');
    //     $sheet->setCellValue('A4', 'SBU')->mergeCells('A4:A5');
    //     $sheet->setCellValue('B4', 'DEPT')->mergeCells('B4:B5');
    //     $sheet->setCellValue('C4', 'BRAND')->mergeCells('C4:C5');
    //     $sheet->setCellValue('D4', 'FLOOR')->mergeCells('D4:I4');
    //     $sheet->setCellValue('D5', 'LP Sales');
    //     $sheet->setCellValue('E5', 'TP Target');
    //     $sheet->setCellValue('F5', 'TP Sales');
    //     $sheet->setCellValue('G5', '%Achieve');
    //     $sheet->setCellValue('H5', '%Growth	');
    //     $sheet->setCellValue('I5', '%Margin');
    //     $sheet->setCellValue('J4', 'ATRIUM')->mergeCells('J4:O4');
    //     $sheet->setCellValue('J5', 'LP Sales');
    //     $sheet->setCellValue('K5', 'TP Target');
    //     $sheet->setCellValue('L5', 'TP Sales');
    //     $sheet->setCellValue('M5', '%Achieve');
    //     $sheet->setCellValue('N5', '%Growth	');
    //     $sheet->setCellValue('O5', '%Margin');
    //     $sheet->setCellValue('P4', 'TOTAL')->mergeCells('P4:U4');
    //     $sheet->setCellValue('P5', 'LP Sales');
    //     $sheet->setCellValue('Q5', 'TP Target');
    //     $sheet->setCellValue('R5', 'TP Sales');
    //     $sheet->setCellValue('S5', '%Achieve');
    //     $sheet->setCellValue('T5', '%Growth	');
    //     $sheet->setCellValue('U5', '%Margin');


    //     $sheet->getStyle('A4:U4')
    //         ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    //     $sheet->getStyle('D5:U5')
    //         ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    //     /* Excel Data */
    //     $row_number = 6;
    //     foreach ($data as $key => $row) {
    //         $sheet->setCellValue('A' . $row_number, $key + 1);
    //         $sheet->setCellValue('B' . $row_number, $row['branch_id']);
    //         $sheet->setCellValue('C' . $row_number, $row['branch_name']);
    //         $row_number++;
    //     }

    //     $sheet->getStyle('A4:C' . $row_number . '')->getFont()->setBold(true);
    //     $sheet->getStyle('A4:U' . $row_number . '')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //     $sheet->getStyle('D4:U4')->getFont()->setBold(true);

    //     $sheet->getColumnDimension('B')->setAutoSize(true);
    //     $sheet->getColumnDimension('C')->setAutoSize(true);

    //     $sheet->setCellValue('A' . $row_number . '', 'TOTAL')->mergeCells('A' . $row_number . ':C' . $row_number . '');
    //     $sheet->getStyle('A6:C' . $row_number . '')
    //         ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    //     $sheet->getStyle('A' . $row_number . ':U' . $row_number . '')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    //     $sheet->getStyle('A' . $row_number . ':U' . $row_number . '')->getFill()->getStartColor()->setRGB('FFF000');

    //     /* Excel File Format */
    //     $writer = new Xlsx($spreadsheet);
    //     $filename = 'sales_by_brand_report';

    //     header('Content-Type: application/vnd.ms-excel');
    //     header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    //     header('Cache-Control: max-age=0');

    //     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $writer->save('php://output');
    // }
}
