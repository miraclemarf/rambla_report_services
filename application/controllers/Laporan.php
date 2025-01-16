<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Laporan extends My_Controller
{

    public function __construct()
    {
        parent::__construct();


        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Datatables');
        $this->load->model('M_PaidOnline');
        $this->load->model('M_OperationalFee');
        $this->load->model('M_Categories');
        $this->load->model('M_Division');
        $this->load->model('M_Stock');
        $this->ceklogin();
    }

    public function penjualan_artikel()
    {

        extract(populateform());
        $data['title'] = 'Rambla | Laporan Penjualan';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        if ($cek_operation == "1") {
            redirect(base_url() . "Laporan/penjualan_artikel_operation");
        }

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/penjualan_artikel', $data);
        $this->load->view('template_member/footer');
    }

    public function penjualan_artikel_operation()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Penjualan Operation';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        if ($cek_operation != "1") {
            redirect(base_url() . "Laporan/penjualan_artikel");
        }

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/penjualan_artikel_operation', $data);
        $this->load->view('template_member/footer');
    }

    public function list_master_item()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Master Item';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');
        $data['tipe'] = $this->input->cookie('cookie_invent_tipe');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/masteritem', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function update_master_item()
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        extract(populateform());

        $username = $this->input->cookie('cookie_invent_user');

        $sql = "UPDATE report_service.r_item_master set status_article = '" . $status . "', last_update = CURRENT_TIMESTAMP() where article_number ='" . $article_number . "' and branch_id = '" . $branch_id . "'";

        $active = "";

        if ($status == "ACTIVE") {
            $active = "1";
        } else if ($status == "PURGE") {
            $active = "2";
        } else if ($status == "DISCONTINUE") {
            $active = "3";
        }
        $sql_central = "UPDATE m_item_master set isactive = '" . $active . "', last_update = CURRENT_TIMESTAMP(), update_by ='" . $username . "' where article_number ='" . $article_number . "' and branch_id = '" . $branch_id . "'";
        $this->db->query($sql);
        $dbCentral->query($sql_central);
        // if ($this->db->affected_rows()) {
        //     $data['status'] = true;
        // } else {
        //     $data['status'] = false;
        // }

        // echo json_encode($data['status']);
        // echo json_encode($sql_central);
    }

    public function list_stok()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Stock';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/stok', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function list_stok_v2()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Stock';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        // $postData = array(
        //     'draw'         => 1,
        //     'start'        => 0,
        //     'length'       => 10,
        //     'search'       => array('value' => null),
        //     'params1'      => null,
        //     'params2'      => 'Supermarket',
        //     'params3'      => null,
        //     'params4'      => null,
        //     'params5'      => null,
        //     'params6'      => 'V001',
        //     'params7'      => null,
        //     'params8'      => null,
        // );

        // $data['list_stock'] = $this->M_Stock->getListStock($postData);

        // var_dump($data['list_stock']);
        // die;

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/stok_v2', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function stockv2_where()
    {
        $postData = $this->input->post();
        $data = $this->M_Stock->getListStock($postData);
        echo json_encode($data);
    }

    public function list_promo()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Promo';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/promo', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function penjualan_artikel_where()
    {

        extract(populateform());

        $params4 = str_replace("%20", " ", $params4);
        $params5 = str_replace("%20", " ", $params5);
        $params6 = str_replace("%20", " ", $params6);
        $params7 = str_replace("%20", " ", $params7);

        $tables = "r_sales";

        $search = array('branch_id', 'periode', 'barcode', 'brand_code', 'brand_name', 'article_name', 'varian_option1', 'varian_option2', 'price', 'tot_qty', 'disc_pct', 'total_disc_amt', 'total_moredisc_amt', 'moredisc_pct', 'margin', 'gross_after_margin', 'gross', 'source_data', 'trans_no', 'no_ref');

        $data['username'] = $this->input->cookie('cookie_invent_user');
        // $vendor_code    = $this->input->post('vendor_code');
        $where = array('');
        $fromdate = '';
        $todate = '';

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $filter = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $filter = $this->M_Division->get_division($data['username'], $params8);
        } else {
            // UNTUK MD
            $filter = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($params1 || $params2 || $params3 || $params4 || $params5 || $params6 || $params7 || $params8) {
            if ($params1) {
                $filter1 = " AND brand_code = '" . $params1 . "'";
                $filter .= $filter1;
            }
            if ($params2) {
                $filter2 = " AND source_data = '" . $params2 . "'";
                $filter .= $filter2;
            }
            if ($params3) {
                if (strpos($params3, '-') !== false) {
                    $tgl = explode("-", $params3);
                    $fromdate = date("Y-m-d", strtotime($tgl[0]));
                    $todate = date("Y-m-d", strtotime($tgl[1]));
                }
                $filter3 = " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
                $filter .= $filter3;
            }
            if ($params4) {
                $filter4 = " AND DIVISION = '" . $params4 . "'";
                $filter .= $filter4;
            }
            if ($params5) {
                $filter5 = " AND SUB_DIVISION = '" . $params5 . "'";
                $filter .= $filter5;
            }
            if ($params6) {
                $filter6 = " AND DEPT = '" . $params6 . "'";
                $filter .= $filter6;
            }
            if ($params7) {
                $filter7 = " AND SUB_DEPT = '" . $params7 . "'";
                $filter .= $filter7;
            }
            if ($params8) {
                $filter8 = " AND branch_id = '" . $params8 . "'";
                $filter .= $filter8;
            }
            if ($params9) {
                $arrParams9 = explode(',', $params9);
                if (count($arrParams9) > 1) {
                    $filter9 = " AND substring(trans_no,9,1) in ('" . $arrParams9[0] . "', '" . $arrParams9[1] . "')";
                } else {
                    $filter9 = " AND substring(trans_no,9,1) in ('" . $params9 . "')";
                }
                $filter .= $filter9;
            }
            $isWhere = $filter;
        } else {
            $isWhere = $filter;
        }

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    public function stock_where()
    {
        extract(populateform());
        $tables = "r_s_item_stok";
        $search = array('branch_id', 'brand_code', 'brand_name', 'barcode', 'varian_option1', 'varian_option2', 'periode', 'DIVISION', 'SUB_DIVISION', 'DEPT', 'SUB_DEPT', 'article_name', 'last_stock');
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $where = array('');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $filter = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $filter = $this->M_Division->get_division($data['username'], $params6);
        } else {
            // UNTUK MD
            $filter = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($params1 || $params2 || $params3 || $params4 || $params5 || $params6 || $params7 || $params8) {
            if ($params1) {
                $filter1 = " AND brand_code = '" . $params1 . "'";
                $filter .= $filter1;
            }
            if ($params2) {
                $filter2 = " AND DIVISION = '" . $params2 . "'";
                $filter .= $filter2;
            }
            if ($params3) {
                $filter3 = " AND SUB_DIVISION = '" . $params3 . "'";
                $filter .= $filter3;
            }
            if ($params4) {
                $filter4 = " AND DEPT = '" . $params4 . "'";
                $filter .= $filter4;
            }
            if ($params5) {
                $filter5 = " AND SUB_DEPT = '" . $params5 . "'";
                $filter .= $filter5;
            }
            if ($params6) {
                $filter6 = " AND branch_id = '" . $params6 . "'";
                $filter .= $filter6;
            }
            if ($params7) {
                if ($params7 == "pcs") {
                    $filter7 = " AND tag_5 in ('TIMBANG') is not true";
                } else {
                    $filter7 = " AND tag_5 in ('TIMBANG')";
                }
                $filter .= $filter7;
            }
            if ($params8) {
                $filter8 = " AND status_article = '" . $params8 . "'";
                $filter .= $filter8;
            }
            $isWhere = $filter;
        } else {
            $isWhere = $filter;
        }

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    public function promo_where()
    {

        extract(populateform());

        $params4 = str_replace("%20", " ", $params4);
        $params5 = str_replace("%20", " ", $params5);
        $params6 = str_replace("%20", " ", $params6);
        $params7 = str_replace("%20", " ", $params7);

        $tables = "r_promo_aktif";
        $search = array('category_code', 'vendor_code', 'vendor_name', 'brand', 'brand_name', 'barcode', 'pos_pname', 'varian_option1', 'varian_option2', 'promo_type', 'promo_desc', 'start_date', 'end_date', 'promo_id', 'current_price', 'min_qty', 'min_purchase', 'disc_percentage', 'disc_amount', 'add_disc_percentage', 'free_qty', 'special_price', 'aktif', 'active_monday', 'active_tuesday', 'active_wednesday', 'active_thursday', 'active_friday', 'active_saturday', 'active_sunday', 'division');

        $data['username'] = $this->input->cookie('cookie_invent_user');
        $where = array('');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $filter = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $filter = $this->M_Division->get_division($data['username'], $params8);
        } else {
            // UNTUK MD
            $filter = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($params1 || $params2 || $params3 || $params4 || $params5 || $params6 || $params7 || $params8) {
            if ($params1) {
                $filter1 = " AND brand = '" . $params1 . "'";
                $filter .= $filter1;
            }
            if ($params2) {
                $filter2 = "AND promo_type = '" . $params2 . "'";
                $filter .= $filter2;
            }
            if ($params3) {
                if (strpos($params3, '-') !== false) {
                    $tgl = explode("-", $params3);
                    $fromdate = date("Y-m-d", strtotime($tgl[0]));
                    $todate = date("Y-m-d", strtotime($tgl[1]));
                }
                $filter3 = " AND (DATE_FORMAT(start_date,'%Y-%m-%d') >= '" . $fromdate . "' OR DATE_FORMAT(end_date,'%Y-%m-%d') <= '" . $todate . "')";
                $filter .= $filter3;
            }
            if ($params4) {
                $filter4 = " AND DIVISION = '" . $params4 . "'";
                $filter .= $filter4;
            }
            if ($params5) {
                $filter5 = " AND SUB_DIVISION = '" . $params5 . "'";
                $filter .= $filter5;
            }
            if ($params6) {
                $filter6 = " AND DEPT = '" . $params6 . "'";
                $filter .= $filter6;
            }
            if ($params7) {
                $filter7 = " AND SUB_DEPT = '" . $params7 . "'";
                $filter .= $filter7;
            }
            if ($params8) {
                $filter8 = " AND branch_id = '" . $params8 . "'";
                $filter .= $filter8;
            }
            $isWhere = $filter;
        } else {
            $isWhere = $filter;
        }
        // echo json_encode($isWhere);
        // die;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    public function masteritem_where()
    {
        extract(populateform());
        $tables = "r_item_master";
        $search = array('branch_id', 'article_code', 'barcode', 'supplier_pcode', 'article_name', 'supplier_pname', 'brand', 'brand_name', 'option1', 'varian_option1', 'option2', 'varian_option2', 'normal_price', 'tag_5');
        // $vendor_code    = $this->input->post('vendor_code');
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $where = array('');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $filter = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $filter = $this->M_Division->get_division($data['username'], $params6);
        } else {
            // UNTUK MD
            $filter = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($params1 || $params2 || $params3 || $params4 || $params5 || $params6 || $params7) {
            if ($params1) {
                $filter1 = " AND brand = '" . $params1 . "'";
                $filter .= $filter1;
            }
            if ($params2) {
                $filter2 = " AND DIVISION = '" . $params2 . "'";
                $filter .= $filter2;
            }
            if ($params3) {
                $filter3 = " AND SUB_DIVISION = '" . $params3 . "'";
                $filter .= $filter3;
            }
            if ($params4) {
                $filter4 = " AND DEPT = '" . $params4 . "'";
                $filter .= $filter4;
            }
            if ($params5) {
                $filter5 = " AND SUB_DEPT = '" . $params5 . "'";
                $filter .= $filter5;
            }
            if ($params6) {
                $filter6 = " AND branch_id = '" . $params6 . "'";
                $filter .= $filter6;
            }
            if ($params7) {
                $filter7 = " AND status_article = '" . $params7 . "'";
                $filter .= $filter7;
            }
            $isWhere = $filter;
        } else {
            $isWhere = $filter;
        }
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    function export_excel_stock($brand_code, $division, $sub_division, $dept, $sub_dept, $store, $art_type, $article_status)
    {
        /* Data */
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($art_type !== "null") {
            if ($art_type == "pcs") {
                $where .= " AND tag_5 in ('TIMBANG') is not true";
            } else {
                $where .= " AND tag_5 in ('TIMBANG')";
            }
        }

        if ($article_status !== "null") {
            $where .= " AND status_article = '" . $article_status . "'";
        }


        $data = $this->db->query("SELECT * FROM r_s_item_stok WHERE 1=1 $where")->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'store');
        $sheet->setCellValue('C1', 'periode');
        $sheet->setCellValue('D1', 'barcode');
        $sheet->setCellValue('E1', 'article_code');
        $sheet->setCellValue('F1', 'article_name');
        $sheet->setCellValue('G1', 'varian_option1');
        $sheet->setCellValue('H1', 'varian_option2');
        $sheet->setCellValue('I1', 'vendor_code');
        $sheet->setCellValue('J1', 'vendor_name');
        $sheet->setCellValue('K1', 'brand_code');
        $sheet->setCellValue('L1', 'brand_name');
        $sheet->setCellValue('M1', 'category_code');
        $sheet->setCellValue('N1', 'DIVISION');
        $sheet->setCellValue('O1', 'SUB_DIVISION');
        $sheet->setCellValue('P1', 'DEPT');
        $sheet->setCellValue('Q1', 'SUB_DEPT');
        $sheet->setCellValue('R1', 'last_stock');
        $sheet->setCellValue('S1', 'current price');
        $sheet->setCellValue('T1', 'retail value');
        $sheet->setCellValue('U1', 'Article Status');

        /* Excel Data */
        $row_number = 2;
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $key + 1);
            $sheet->setCellValue('B' . $row_number, $row['branch_id']);
            $sheet->setCellValue('C' . $row_number, substr($row['periode'], 0, 7));
            $sheet->setCellValue('D' . $row_number, $row['barcode']);
            $sheet->setCellValue('E' . $row_number, $row['article_code']);
            $sheet->setCellValue('F' . $row_number, $row['article_name']);
            $sheet->setCellValue('G' . $row_number, $row['varian_option1']);
            $sheet->setCellValue('H' . $row_number, $row['varian_option2']);
            $sheet->setCellValue('I' . $row_number, $row['vendor_code']);
            $sheet->setCellValue('J' . $row_number, $row['vendor_name']);
            $sheet->setCellValue('K' . $row_number, $row['brand_code']);
            $sheet->setCellValue('L' . $row_number, $row['brand_name']);
            $sheet->setCellValue('M' . $row_number, $row['category_code']);
            $sheet->setCellValue('N' . $row_number, $row['DIVISION']);
            $sheet->setCellValue('O' . $row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('P' . $row_number, $row['DEPT']);
            $sheet->setCellValue('Q' . $row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('R' . $row_number, $row['last_stock']);
            $sheet->setCellValue('S' . $row_number, $row['current_price']);
            $sheet->setCellValue('T' . $row_number, $row['current_price'] * $row['last_stock']);
            $sheet->setCellValue('U' . $row_number, $row['status_article']);
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'stock_report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_stockv2($brand_code, $division, $sub_division, $dept, $sub_dept, $branch_id, $art_type, $article_status)
    {
        /* Data */
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        $brand_code = ($brand_code !== "null") ?  " AND brand_code = '" . $brand_code . "'"  : '';
        $division = ($division !== "null") ? " AND DIVISION = '" . $division . "'" : '';
        $sub_division = ($sub_division !== "null") ? "AND SUB_DIVISION = '" . $sub_division . "'" : '';
        $dept = ($dept !== "null") ? "AND DEPT = '" . $dept . "'" : '';
        $sub_dept = ($sub_dept !== "null") ? "AND SUB_DEPT = '" . $sub_dept . "'" : '';
        $store = ($branch_id !== "null") ? "AND a.branch_id = '" . $branch_id . "'" : '';
        $uom = ($art_type !== "null") ? ($art_type == "pcs" ? " AND tag_5 in ('TIMBANG') is not true" : " AND tag_5 in ('TIMBANG')") : '';
        $article_status = ($article_status !== "null") ? "AND status_article = '" . $article_status . "'" : '';

        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $whereClause = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $whereClause = $this->M_Division->get_division($data['username'], $branch_id);
        } else {
            // UNTUK MD
            $whereClause = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }

        $whereClause .= $brand_code . $division . $sub_division . $dept . $sub_dept . $store . $uom . $article_status;

        $cache_key_export = "getExportStock_search_" . md5($whereClause);
        $cached_data = $this->redislib->get($cache_key_export); // Try to fetch cached data

        if ($cached_data) {
            $data = json_decode($cached_data, true);
        } else {
            $this->session->set_flashdata('message-failed', 'Export Data Gagal! Silakan di coba kembali');
            redirect(base_url() . "Laporan/list_stok_v2");
        }

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', 'store');
        $sheet->setCellValue('B1', 'periode');
        $sheet->setCellValue('C1', 'barcode');
        $sheet->setCellValue('D1', 'article_code');
        $sheet->setCellValue('E1', 'article_name');
        $sheet->setCellValue('F1', 'varian_option1');
        $sheet->setCellValue('G1', 'varian_option2');
        $sheet->setCellValue('H1', 'vendor_code');
        $sheet->setCellValue('i1', 'vendor_name');
        $sheet->setCellValue('J1', 'brand_code');
        $sheet->setCellValue('K1', 'brand_name');
        $sheet->setCellValue('L1', 'category_code');
        $sheet->setCellValue('M1', 'DIVISION');
        $sheet->setCellValue('N1', 'SUB_DIVISION');
        $sheet->setCellValue('O1', 'DEPT');
        $sheet->setCellValue('P1', 'SUB_DEPT');
        $sheet->setCellValue('Q1', 'last_stock');
        $sheet->setCellValue('R1', 'current price');
        $sheet->setCellValue('S1', 'pruchase price');
        $sheet->setCellValue('T1', 'retail value');
        $sheet->setCellValue('U1', 'Article Status');

        /* Excel Data */
        $row_number = 2;
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $row['branch_id']);
            $sheet->setCellValue('B' . $row_number, substr($row['periode'], 0, 7));
            $sheet->setCellValue('C' . $row_number, $row['barcode']);
            $sheet->setCellValue('D' . $row_number, $row['article_code']);
            $sheet->setCellValue('E' . $row_number, $row['article_name']);
            $sheet->setCellValue('F' . $row_number, $row['varian_option1']);
            $sheet->setCellValue('G' . $row_number, $row['varian_option2']);
            $sheet->setCellValue('H' . $row_number, $row['vendor_code']);
            $sheet->setCellValue('I' . $row_number, $row['vendor_name']);
            $sheet->setCellValue('J' . $row_number, $row['brand_code']);
            $sheet->setCellValue('K' . $row_number, $row['brand_name']);
            $sheet->setCellValue('L' . $row_number, $row['category_code']);
            $sheet->setCellValue('M' . $row_number, $row['DIVISION']);
            $sheet->setCellValue('N' . $row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('O' . $row_number, $row['DEPT']);
            $sheet->setCellValue('P' . $row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('Q' . $row_number, $row['last_stock']);
            $sheet->setCellValue('R' . $row_number, $row['current_price']);
            $sheet->setCellValue('S' . $row_number, $row['purchase_price']);
            $sheet->setCellValue('T' . $row_number, $row['current_price'] * $row['last_stock']);
            $sheet->setCellValue('U' . $row_number, $row['status_article']);
            $row_number++;
        }
        $sheet->getStyle('C2:D' . $row_number . '')->getNumberFormat()->setFormatCode('#');

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'stock_report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_promo($brand_code, $promo, $fromdate, $todate, $division, $sub_division, $dept, $sub_dept, $branch_id)
    {
        /* Data */
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $where = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $where = $this->M_Division->get_division($data['username'], $branch_id);
        } else {
            // UNTUK MD
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($brand_code !== "null") {
            $where .= " and brand = '" . $brand_code . "'";
        }
        if ($promo !== "null") {
            $where .= " and promo_type = '" . $promo . "'";
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

        if ($fromdate !== "null" and $todate !== "null") {
            $where .= " AND (DATE_FORMAT(start_date,'%Y-%m-%d') >= '" . $fromdate . "' OR DATE_FORMAT(end_date,'%Y-%m-%d') <= '" . $todate . "')";
        }

        if ($branch_id !== "null") {
            $where .= " AND branch_id = '" . $branch_id . "'";
        }

        $data = $this->db->query("SELECT * FROM r_promo_aktif WHERE 1=1 $where")->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Store');
        $sheet->setCellValue('C1', 'Start Date');
        $sheet->setCellValue('D1', 'End Date');
        $sheet->setCellValue('E1', 'Promo Id');
        $sheet->setCellValue('F1', 'Promo Type');
        $sheet->setCellValue('G1', 'Category Code');
        $sheet->setCellValue('H1', 'Vendor Name');
        $sheet->setCellValue('I1', 'Brand');
        $sheet->setCellValue('J1', 'Barcode');
        $sheet->setCellValue('K1', 'Article Name');
        $sheet->setCellValue('L1', 'Varian Option1');
        $sheet->setCellValue('M1', 'Varian Option2');
        $sheet->setCellValue('N1', 'Promo desc');
        $sheet->setCellValue('O1', 'Current Price');
        $sheet->setCellValue('P1', 'Min Qty');
        $sheet->setCellValue('Q1', 'Min Purchase');
        $sheet->setCellValue('R1', 'Disc %');
        $sheet->setCellValue('S1', 'Disc Amount');
        $sheet->setCellValue('T1', 'Add Disc %');
        $sheet->setCellValue('U1', 'Free Qty');

        $sheet->setCellValue('V1', 'Q0');
        $sheet->setCellValue('W1', 'Price 0');
        $sheet->setCellValue('X1', 'Q1');
        $sheet->setCellValue('Y1', 'Price 1');
        $sheet->setCellValue('Z1', 'Q2');
        $sheet->setCellValue('AA1', 'Price 2');

        $sheet->setCellValue('AB1', 'Special Price');
        $sheet->setCellValue('AC1', 'Aktif');
        $sheet->setCellValue('AD1', 'Monday');
        $sheet->setCellValue('AE1', 'Tuesday');
        $sheet->setCellValue('AF1', 'Wednesday');
        $sheet->setCellValue('AG1', 'Thusday');
        $sheet->setCellValue('AH1', 'Friday');
        $sheet->setCellValue('AI1', 'Saturday');
        $sheet->setCellValue('AJ1', 'Sunday');
        $sheet->setCellValue('AK1', 'Division');
        $sheet->setCellValue('AL1', 'Sub Division');
        $sheet->setCellValue('AM1', 'Dept');
        $sheet->setCellValue('AN1', 'Sub Dept');

        /* Excel Data */
        $row_number = 2;
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $key + 1);
            $sheet->setCellValue('B' . $row_number, $row['branch_id']);
            $sheet->setCellValue('C' . $row_number, $row['start_date']);
            $sheet->setCellValue('D' . $row_number, $row['end_date']);
            $sheet->setCellValue('E' . $row_number, $row['promo_id']);
            $sheet->setCellValue('F' . $row_number, $row['promo_type']);
            $sheet->setCellValue('G' . $row_number, $row['category_code']);
            $sheet->setCellValue('H' . $row_number, $row['vendor_name']);
            $sheet->setCellValue('I' . $row_number, $row['brand']);
            $sheet->setCellValue('J' . $row_number, $row['barcode']);
            $sheet->setCellValue('K' . $row_number, $row['pos_pname']);
            $sheet->setCellValue('L' . $row_number, $row['varian_option1']);
            $sheet->setCellValue('M' . $row_number, $row['varian_option2']);
            $sheet->setCellValue('N' . $row_number, $row['promo_desc']);
            $sheet->setCellValue('O' . $row_number, $row['current_price']);
            $sheet->setCellValue('P' . $row_number, $row['min_qty']);
            $sheet->setCellValue('Q' . $row_number, $row['min_purchase']);
            $sheet->setCellValue('R' . $row_number, $row['disc_percentage']);
            $sheet->setCellValue('S' . $row_number, $row['disc_amount']);
            $sheet->setCellValue('T' . $row_number, $row['add_disc_percentage']);
            $sheet->setCellValue('U' . $row_number, $row['free_qty']);

            $sheet->setCellValue('V' . $row_number, $row['Q0']);
            $sheet->setCellValue('W' . $row_number, $row['price0']);
            $sheet->setCellValue('X' . $row_number, $row['Q1']);
            $sheet->setCellValue('Y' . $row_number, $row['price1']);
            $sheet->setCellValue('Z' . $row_number, $row['Q2']);
            $sheet->setCellValue('AA' . $row_number, $row['price2']);


            $sheet->setCellValue('AB' . $row_number, $row['special_price']);
            $sheet->setCellValue('AC' . $row_number, $row['aktif']);
            $sheet->setCellValue('AD' . $row_number, $row['active_monday']);
            $sheet->setCellValue('AE' . $row_number, $row['active_tuesday']);
            $sheet->setCellValue('AF' . $row_number, $row['active_wednesday']);
            $sheet->setCellValue('AG' . $row_number, $row['active_thursday']);
            $sheet->setCellValue('AH' . $row_number, $row['active_friday']);
            $sheet->setCellValue('AI' . $row_number, $row['active_saturday']);
            $sheet->setCellValue('AJ' . $row_number, $row['active_sunday']);
            $sheet->setCellValue('AK' . $row_number, $row['DIVISION']);
            $sheet->setCellValue('AL' . $row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('AM' . $row_number, $row['DEPT']);
            $sheet->setCellValue('AN' . $row_number, $row['SUB_DEPT']);

            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'promo_report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_masteritem($brand_code, $division, $sub_division, $dept, $sub_dept, $store, $article_status)
    {
        /* Data */
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

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
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($brand_code !== "null") {
            $where .= " AND brand = '" . $brand_code . "'";
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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($article_status !== "null") {
            $where .= " AND status_article = '" . $article_status . "'";
        }

        $data = $this->db->query("SELECT * FROM r_item_master WHERE 1=1 $where")->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Store');
        $sheet->setCellValue('C1', 'Article Number');
        $sheet->setCellValue('D1', 'Article Code');
        $sheet->setCellValue('E1', 'Barcode');
        $sheet->setCellValue('F1', 'Supplier Pcode');
        $sheet->setCellValue('G1', 'Category Code');
        $sheet->setCellValue('H1', 'Article Name');
        $sheet->setCellValue('I1', 'Supplier Pname');
        $sheet->setCellValue('J1', 'Vendor Code');
        $sheet->setCellValue('K1', 'Vendor Name');
        $sheet->setCellValue('L1', 'Brand Code');
        $sheet->setCellValue('M1', 'Brand Brand');
        $sheet->setCellValue('N1', 'Option1');
        $sheet->setCellValue('O1', 'Varian Option1');
        $sheet->setCellValue('P1', 'Option2');
        $sheet->setCellValue('Q1', 'Varian Option2');
        $sheet->setCellValue('R1', 'Division');
        $sheet->setCellValue('S1', 'Sub Division');
        $sheet->setCellValue('T1', 'Dept');
        $sheet->setCellValue('U1', 'Sub Dept');
        $sheet->setCellValue('V1', 'Normal Price');
        $sheet->setCellValue('W1', 'Current Price');
        $sheet->setCellValue('X1', 'Tag 5');
        $sheet->setCellValue('Y1', 'Add Date');
        $sheet->setCellValue('Z1', 'Last Update');
        $sheet->setCellValue('AA1', 'Article Status');

        /* Excel Data */
        $row_number = 2;
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $key + 1);
            $sheet->setCellValue('B' . $row_number, $row['branch_id']);
            $sheet->setCellValue('C' . $row_number, $row['article_number']);
            $sheet->setCellValue('D' . $row_number, $row['article_code']);
            $sheet->setCellValue('E' . $row_number, $row['barcode']);
            $sheet->setCellValue('F' . $row_number, $row['supplier_pcode']);
            $sheet->setCellValue('G' . $row_number, $row['category_code']);
            $sheet->setCellValue('H' . $row_number, $row['article_name']);
            $sheet->setCellValue('I' . $row_number, $row['supplier_pname']);
            $sheet->setCellValue('J' . $row_number, $row['vendor_code']);
            $sheet->setCellValue('K' . $row_number, $row['vendor_name']);
            $sheet->setCellValue('L' . $row_number, $row['brand']);
            $sheet->setCellValue('M' . $row_number, $row['brand_name']);
            $sheet->setCellValue('N' . $row_number, $row['option1']);
            $sheet->setCellValue('O' . $row_number, $row['varian_option1']);
            $sheet->setCellValue('P' . $row_number, $row['option2']);
            $sheet->setCellValue('Q' . $row_number, $row['varian_option2']);
            $sheet->setCellValue('R' . $row_number, $row['DIVISION']);
            $sheet->setCellValue('S' . $row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('T' . $row_number, $row['DEPT']);
            $sheet->setCellValue('U' . $row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('V' . $row_number, $row['normal_price']);
            $sheet->setCellValue('W' . $row_number, $row['current_price']);
            $sheet->setCellValue('X' . $row_number, $row['tag_5']);
            $sheet->setCellValue('Y' . $row_number, substr($row['add_date'], 0, 10));
            $sheet->setCellValue('Z' . $row_number, substr($row['last_update'], 0, 10));
            $sheet->setCellValue('AA' . $row_number, $row['status_article']);
            $row_number++;
        }

        $sheet->getStyle('C2:E' . $row_number . '')->getNumberFormat()->setFormatCode('#');

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'masteritem_report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_penjualanartikel($fromdate, $todate, $source, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx)
    {
        $data['username'] = $this->input->cookie('cookie_invent_user');
        /* Data */
        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

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

        if ($source !== "null") {
            $where .= " AND source_data = '" . $source . "'";
        }

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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($fromdate !== null and $todate !== null) {
            $where .= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        if ($areatrx !== "null") {
            $arrAreatrx = explode(',', $areatrx);
            if (count($arrAreatrx) > 1) {
                $where .= " AND substring(trans_no,9,1) in ('" . $arrAreatrx[0] . "', '" . $arrAreatrx[1] . "')";
            } else {
                $where .= " AND substring(trans_no,9,1) in ('" . $areatrx . "')";
            }
        }

        $data = $this->db->query("SELECT * FROM r_sales WHERE 1=1 $where order by periode")->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Store');
        $sheet->setCellValue('C1', 'Periode');
        $sheet->setCellValue('D1', 'Bulan');
        $sheet->setCellValue('E1', 'DIVISION');
        $sheet->setCellValue('F1', 'SUB DIVISION');
        $sheet->setCellValue('G1', 'Tipe Artikel');
        $sheet->setCellValue('H1', 'Kode Kategori');
        $sheet->setCellValue('I1', 'DEPT');
        $sheet->setCellValue('J1', 'SUB DEPT');
        $sheet->setCellValue('K1', 'Article Code');
        $sheet->setCellValue('L1', 'Barcode');
        $sheet->setCellValue('M1', 'Kode Brand');
        $sheet->setCellValue('N1', 'Nama Brand');
        $sheet->setCellValue('O1', 'Nama Produk');
        $sheet->setCellValue('P1', 'Varian Option1');
        $sheet->setCellValue('Q1', 'Varian Option2');
        $sheet->setCellValue('R1', 'Harga');
        $sheet->setCellValue('S1', 'Kode Vendor');
        $sheet->setCellValue('T1', 'Nama Vendor');
        $sheet->setCellValue('U1', 'Total Qty(Pcs)');
        $sheet->setCellValue('V1', 'Total Berat(Kg)');
        $sheet->setCellValue('W1', 'Disc(%)');
        $sheet->setCellValue('X1', 'Total Disc');
        $sheet->setCellValue('Y1', 'Disc. Tambahan(Rp)');
        $sheet->setCellValue('Z1', 'Disc. Tambahan(%)');
        $sheet->setCellValue('AA1', 'Margin');
        $sheet->setCellValue('AB1', 'Gross After Margin');
        $sheet->setCellValue('AC1', 'Gross(Rp)');
        $sheet->setCellValue('AD1', 'Net Before(Rp)');
        $sheet->setCellValue('AE1', 'Net After(Rp)');
        $sheet->setCellValue('AF1', 'Area Transaksi');
        $sheet->setCellValue('AG1', 'Source Data');
        $sheet->setCellValue('AH1', 'Trans No');
        $sheet->setCellValue('AI1', 'No Ref');

        /* Excel Data */
        $row_number = 2;
        $data_areatrx = '';
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $key + 1);
            $sheet->setCellValue('B' . $row_number, $row['branch_id']);
            $sheet->setCellValue('C' . $row_number, substr($row['periode'], 0, 10));
            $sheet->setCellValue('D' . $row_number, substr($row['periode'], 5, 2));
            $sheet->setCellValue('E' . $row_number, $row['DIVISION']);
            $sheet->setCellValue('F' . $row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('G' . $row_number, $row['tag_5']);
            $sheet->setCellValue('H' . $row_number, $row['category_code']);
            $sheet->setCellValue('I' . $row_number, $row['DEPT']);
            $sheet->setCellValue('J' . $row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('K' . $row_number, $row['article_code']);
            $sheet->setCellValue('L' . $row_number, $row['barcode']);
            $sheet->setCellValue('M' . $row_number, $row['brand_code']);
            $sheet->setCellValue('N' . $row_number, $row['brand_name']);
            $sheet->setCellValue('O' . $row_number, $row['article_name']);
            $sheet->setCellValue('P' . $row_number, $row['varian_option1']);
            $sheet->setCellValue('Q' . $row_number, $row['varian_option2']);
            $sheet->setCellValue('R' . $row_number, $row['price']);
            $sheet->setCellValue('S' . $row_number, $row['vendor_code']);
            $sheet->setCellValue('T' . $row_number, $row['vendor_name']);
            $sheet->setCellValue('U' . $row_number, $row['tot_qty']);
            $sheet->setCellValue('V' . $row_number, $row['tot_berat']);
            $sheet->setCellValue('W' . $row_number, $row['disc_pct']);
            $sheet->setCellValue('X' . $row_number, $row['total_disc_amt']);
            $sheet->setCellValue('Y' . $row_number, $row['total_moredisc_amt']);
            $sheet->setCellValue('Z' . $row_number, $row['moredisc_pct']);
            $sheet->setCellValue('AA' . $row_number, $row['margin']);
            $sheet->setCellValue('AB' . $row_number, $row['gross_after_margin']);
            $sheet->setCellValue('AC' . $row_number, $row['gross']);
            $sheet->setCellValue('AD' . $row_number, $row['net_bf']);
            $sheet->setCellValue('AE' . $row_number, $row['net_af']);
            if (substr($row['trans_no'], 8, 1) != '5') {
                $data_areatrx = substr($row['trans_no'], 8, 1) == '3' ? 'BAZAAR' : 'FLOOR';
            }
            else{
                $data_areatrx = "ONLINE"
            }
            $sheet->setCellValue('AF' . $row_number, $data_areatrx);
            $sheet->setCellValue('AG' . $row_number, $row['source_data']);
            $sheet->setCellValue('AH' . $row_number, $row['trans_no']);
            $sheet->setCellValue('AI' . $row_number, $row['no_ref']);
            $row_number++;
        }
        $sheet->getStyle('K2:L' . $row_number . '')->getNumberFormat()->setFormatCode('#');
        $sheet->getStyle('AH2:AI' . $row_number . '')->getNumberFormat()->setFormatCode('#');

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'sales_by_artikel_report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_penjualanartikel_operation($fromdate, $todate, $source, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx)
    {
        /* Data */
        $data['username'] = $this->input->cookie('cookie_invent_user');
        /* Data */
        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

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

        if ($source !== "null") {
            $where .= " AND source_data = '" . $source . "'";
        }

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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($fromdate !== null and $todate !== null) {
            $where .= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        if ($areatrx !== "null") {
            $arrAreatrx = explode(',', $areatrx);
            if (count($arrAreatrx) > 1) {
                $where .= " AND substring(trans_no,9,1) in ('" . $arrAreatrx[0] . "', '" . $arrAreatrx[1] . "')";
            } else {
                $where .= " AND substring(trans_no,9,1) in ('" . $areatrx . "')";
            }
        }

        $data = $this->db->query("SELECT * FROM r_sales WHERE 1=1 $where order by periode")->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Store');
        $sheet->setCellValue('C1', 'Periode');
        $sheet->setCellValue('D1', 'Bulan');
        $sheet->setCellValue('E1', 'DIVISION');
        $sheet->setCellValue('F1', 'SUB DIVISION');
        $sheet->setCellValue('G1', 'Tipe Artikel');
        $sheet->setCellValue('H1', 'Kode Kategori');
        $sheet->setCellValue('I1', 'DEPT');
        $sheet->setCellValue('J1', 'SUB DEPT');
        $sheet->setCellValue('K1', 'Article Code');
        $sheet->setCellValue('L1', 'Barcode');
        $sheet->setCellValue('M1', 'Kode Brand');
        $sheet->setCellValue('N1', 'Nama Brand');
        $sheet->setCellValue('O1', 'Nama Produk');
        $sheet->setCellValue('P1', 'Varian Option1');
        $sheet->setCellValue('Q1', 'Varian Option2');
        $sheet->setCellValue('R1', 'Harga');
        $sheet->setCellValue('S1', 'Kode Vendor');
        $sheet->setCellValue('T1', 'Nama Vendor');
        $sheet->setCellValue('U1', 'Total Qty(Pcs)');
        $sheet->setCellValue('V1', 'Total Berat(Kg)');
        $sheet->setCellValue('W1', 'Disc(%)');
        $sheet->setCellValue('X1', 'Total Disc');
        $sheet->setCellValue('Y1', 'Disc. Tambahan(Rp)');
        $sheet->setCellValue('Z1', 'Disc. Tambahan(%)');
        $sheet->setCellValue('AA1', 'Gross(Rp)');
        $sheet->setCellValue('AB1', 'Net Before(Rp)');
        $sheet->setCellValue('AC1', 'Net After(Rp)');
        $sheet->setCellValue('AD1', 'Area Transaksi');
        $sheet->setCellValue('AE1', 'Source Data');
        $sheet->setCellValue('AF1', 'Trans No');
        $sheet->setCellValue('AG1', 'No Ref');

        /* Excel Data */
        $row_number = 2;
        $data_areatrx = '';
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $key + 1);
            $sheet->setCellValue('B' . $row_number, $row['branch_id']);
            $sheet->setCellValue('C' . $row_number, substr($row['periode'], 0, 10));
            $sheet->setCellValue('D' . $row_number, substr($row['periode'], 5, 2));
            $sheet->setCellValue('E' . $row_number, $row['DIVISION']);
            $sheet->setCellValue('F' . $row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('G' . $row_number, $row['tag_5']);
            $sheet->setCellValue('H' . $row_number, $row['category_code']);
            $sheet->setCellValue('I' . $row_number, $row['DEPT']);
            $sheet->setCellValue('J' . $row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('K' . $row_number, $row['article_code']);
            $sheet->setCellValue('L' . $row_number, $row['barcode']);
            $sheet->setCellValue('M' . $row_number, $row['brand_code']);
            $sheet->setCellValue('N' . $row_number, $row['brand_name']);
            $sheet->setCellValue('O' . $row_number, $row['article_name']);
            $sheet->setCellValue('P' . $row_number, $row['varian_option1']);
            $sheet->setCellValue('Q' . $row_number, $row['varian_option2']);
            $sheet->setCellValue('R' . $row_number, $row['price']);
            $sheet->setCellValue('S' . $row_number, $row['vendor_code']);
            $sheet->setCellValue('T' . $row_number, $row['vendor_name']);
            $sheet->setCellValue('U' . $row_number, $row['tot_qty']);
            $sheet->setCellValue('V' . $row_number, $row['tot_berat']);
            $sheet->setCellValue('W' . $row_number, $row['disc_pct']);
            $sheet->setCellValue('X' . $row_number, $row['total_disc_amt']);
            $sheet->setCellValue('Y' . $row_number, $row['total_moredisc_amt']);
            $sheet->setCellValue('Z' . $row_number, $row['moredisc_pct']);
            $sheet->setCellValue('AA' . $row_number, $row['gross']);
            $sheet->setCellValue('AB' . $row_number, $row['net_bf']);
            $sheet->setCellValue('AC' . $row_number, $row['net_af']);
            if (substr($row['trans_no'], 8, 1) != '5') {
                $data_areatrx = substr($row['trans_no'], 8, 1) == '3' ? 'BAZAAR' : 'FLOOR';
            }
            else{
                $data_areatrx = "ONLINE"
            }
            $sheet->setCellValue('AD' . $row_number, $data_areatrx);
            $sheet->setCellValue('AE' . $row_number, $row['source_data']);
            $sheet->setCellValue('AF' . $row_number, $row['trans_no']);
            $sheet->setCellValue('AG' . $row_number, $row['no_ref']);
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'sales_by_artikel_report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_csv_stock($brand_code, $division, $sub_division, $dept, $sub_dept, $store, $art_type, $article_status)
    {
        $filename = 'stock_report.csv';

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $data['username'] = $this->input->cookie('cookie_invent_user');

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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($art_type !== "null") {
            if ($art_type == "pcs") {
                $where .= " AND tag_5 in ('TIMBANG') is not true";
            } else {
                $where .= " AND tag_5 in ('TIMBANG')";
            }
        }

        if ($article_status !== "null") {
            $where .= " AND status_article = '" . $article_status . "'";
        }

        $data = $this->db->query("SELECT branch_id,SUBSTRING(periode, 1, 7) as periode,barcode, article_code, article_name,varian_option1,varian_option2, vendor_code, vendor_name, brand_code,brand_name,category_code, DIVISION,SUB_DIVISION,DEPT,SUB_DEPT,last_stock, current_price, (last_stock * current_price) as retail_value, status_article FROM r_s_item_stok where 1=1 $where")->result_array();
        $file = fopen('php://output', 'w');

        $header = array('branch_id', 'periode', 'barcode', 'article_code', 'article_name', 'varian_option1', 'varian_option2', 'vendor_code', 'vendor_name', 'brand_code', 'brand_name', 'Kode_Kategori', 'DIVISION', 'SUB_DIVISION', 'DEPT', 'SUB_DEPT', 'last_stock', 'current_price', 'retail_value', 'article status');

        fputcsv($file, $header);

        foreach ($data as $key => $value) {
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_csv_stockv2($brand_code, $division, $sub_division, $dept, $sub_dept, $branch_id, $art_type, $article_status)
    {
        $filename = 'stock_report.csv';
        /* Data */
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        $brand_code = ($brand_code !== "null") ?  " AND brand_code = '" . $brand_code . "'"  : '';
        $division = ($division !== "null") ? " AND DIVISION = '" . $division . "'" : '';
        $sub_division = ($sub_division !== "null") ? "AND SUB_DIVISION = '" . $sub_division . "'" : '';
        $dept = ($dept !== "null") ? "AND DEPT = '" . $dept . "'" : '';
        $sub_dept = ($sub_dept !== "null") ? "AND SUB_DEPT = '" . $sub_dept . "'" : '';
        $store = ($branch_id !== "null") ? "AND a.branch_id = '" . $branch_id . "'" : '';
        $uom = ($art_type !== "null") ? ($art_type == "pcs" ? " AND tag_5 in ('TIMBANG') is not true" : " AND tag_5 in ('TIMBANG')") : '';
        $article_status = ($article_status !== "null") ? "AND status_article = '" . $article_status . "'" : '';

        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $whereClause = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $whereClause = $this->M_Division->get_division($data['username'], $branch_id);
        } else {
            // UNTUK MD
            $whereClause = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }

        $whereClause .= $brand_code . $division . $sub_division . $dept . $sub_dept . $store . $uom . $article_status;

        $cache_key_export = "getExportStock_search_" . md5($whereClause);
        $cached_data = $this->redislib->get($cache_key_export); // Try to fetch cached data

        if ($cached_data) {
            $data = json_decode($cached_data, true);
        } else {
            $this->session->set_flashdata('message-failed', 'Export Data Gagal! Silakan di coba kembali');
            redirect(base_url() . "Laporan/list_stok_v2");
        }


        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $file = fopen('php://output', 'w');

        $header = array('store', 'periode', 'barcode', 'article_code', 'article_name', 'varian_option1', 'varian_option2', 'vendor_code', 'vendor_name', 'brand_code', 'brand_name', 'category_code', 'DIVISION', 'SUB_DIVISION', 'DEPT', 'SUB_DEPT', 'last_stock', 'current_price', 'purchase_price', 'retail_value', 'article status');

        fputcsv($file, $header);

        foreach ($data as $key => $value) {

            $value['retail_value'] = ($value['last_stock'] * $value['current_price']);

            $column = [
                $value['branch_id'],
                $value['periode'],
                $value['barcode'],
                $value['article_code'],
                $value['article_name'],
                $value['varian_option1'],
                $value['varian_option2'],
                $value['vendor_code'],
                $value['vendor_name'],
                $value['brand_code'],
                $value['brand_name'],
                $value['category_code'],
                $value['DIVISION'],
                $value['SUB_DIVISION'],
                $value['DEPT'],
                $value['SUB_DEPT'],
                $value['last_stock'],
                $value['current_price'],
                $value['purchase_price'],
                $value['retail_value'],
                $value['status_article'],
            ];
            // Write the selected column to the CSV file
            fputcsv($file, $column);
        }
        fclose($file);
        exit;
    }

    function export_csv_masteritem($brand_code, $division, $sub_division, $dept, $sub_dept, $store, $article_status)
    {
        $filename = 'masteritem_report.csv';

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $data['username'] = $this->input->cookie('cookie_invent_user');

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
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($brand_code !== "null") {
            $where .= " AND brand = '" . $brand_code . "'";
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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($article_status !== "null") {
            $where .= " AND status_article = '" . $article_status . "'";
        }

        $data = $this->db->query("SELECT branch_id,article_number,article_code,barcode,supplier_pcode,category_code, article_name,supplier_pname, vendor_code, vendor_name, brand,brand_name, option1,varian_option1,option2,varian_option2, division, sub_division, dept, sub_dept, normal_price, current_price, tag_5,SUBSTRING(add_date, 1, 10) as add_date,SUBSTRING(last_update, 1, 10) as last_update, status_article FROM r_item_master where 1=1 $where")->result_array();
        $file = fopen('php://output', 'w');

        $header = array('branch id', 'article number', 'article code', 'barcode', 'supplier_pcode', 'category code', 'article name', 'supplier pname', 'vendor code', 'vendor name', 'brand code', 'brand name', 'option1', 'varian option1', 'option2', 'varian option2', 'division', 'sub division', 'dept', 'sub dept', 'normal_price', 'current_price', 'tag 5', 'add_date', 'last_update', 'article status');

        fputcsv($file, $header);

        foreach ($data as $key => $value) {
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_csv_promo($brand_code, $promo, $fromdate, $todate, $division, $sub_division, $dept, $sub_dept, $branch_id)
    {
        $filename = 'promo_report.csv';

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $data['username'] = $this->input->cookie('cookie_invent_user');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $where = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $where = $this->M_Division->get_division($data['username'], $branch_id);
        } else {
            // UNTUK MD
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($brand_code !== "null") {
            $where .= " AND brand = '" . $brand_code . "'";
        }

        if ($promo !== "null") {
            $where .= " AND promo_type = '" . $promo . "'";
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

        if ($fromdate !== "null" and $todate !== "null") {
            $where .= " AND (DATE_FORMAT(start_date,'%Y-%m-%d') >= '" . $fromdate . "' OR DATE_FORMAT(end_date,'%Y-%m-%d') <= '" . $todate . "')";
        }

        if ($branch_id !== "null") {
            $where .= " AND branch_id = '" . $branch_id . "'";
        }

        $data = $this->db->query("SELECT branch_id, start_date,end_date,promo_id,promo_type,category_code,vendor_name,brand,barcode,pos_pname,varian_option1,varian_option2,promo_desc,current_price,min_qty,min_purchase,disc_percentage,disc_amount,add_disc_percentage,free_qty,Q0, price0, Q1, price1, Q2, price2, special_price,aktif,active_monday,active_tuesday,active_wednesday,active_thursday,active_friday,active_saturday,active_sunday,DIVISION, SUB_DIVISION, DEPT, SUB_DEPT FROM r_promo_aktif WHERE 1=1 $where")->result_array();
        $file = fopen('php://output', 'w');

        $header = array('Store', 'Start Date', 'End Date', 'Promo Id', 'Promo Type', 'Category Code', 'Vendor Name', 'Brand', 'Barcode', 'Article Name', 'Varian Option1', 'Varian Option2', 'Promo desc', 'Current Price', 'Min Qty', 'Min Purchase', 'Disc %', 'Disc Amount', 'Add Disc %', 'Free Qty', 'Q0', 'Price0', 'Q1', 'Price1', 'Q2', 'Price2', 'Special Price', 'Aktif', 'Monday', 'Tuesday', 'Wednesday', 'Thusday', 'Friday', 'Saturday', 'Sunday', 'Division', 'Sub Division', 'Dept', 'Sub Dept');

        fputcsv($file, $header);

        foreach ($data as $key => $value) {
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function generate_date()
    {
        extract(populateform());

        if (strpos($periode, '-') !== false) {
            $tgl = explode("-", $periode);
            $fromdate = date("Y-m-d", strtotime($tgl[0]));
            $todate = date("Y-m-d", strtotime($tgl[1]));
            $data = array('fromdate' => $fromdate, 'todate' => $todate);
        } else {
            $data = array('fromdate' => null, 'todate' => null);
        }
        echo json_encode($data);
    }

    function export_csv_penjualanartikel($fromdate, $todate, $source_data, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx)
    {
        extract(populateform());

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        $filename = 'sales_by_artikel_report.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $data['username'] = $this->input->cookie('cookie_invent_user');

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


        if ($source_data !== "null") {
            $where .= " AND source_data = '" . $source_data . "'";
        }

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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($areatrx !== "null") {
            $arrAreatrx = explode(',', $areatrx);
            if (count($arrAreatrx) > 1) {
                $where .= " AND substring(trans_no,9,1) in ('" . $arrAreatrx[0] . "', '" . $arrAreatrx[1] . "')";
            } else {
                $where .= " AND substring(trans_no,9,1) in ('" . $areatrx . "')";
            }
        }

        if ($fromdate !== null and $todate !== null) {
            $where .= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        $delimiter = ';';

        $data = $this->db->query("SELECT branch_id, SUBSTRING(periode, 1, 10) as periode,SUBSTRING(periode, 6, 2) as bulan, DIVISION,SUB_DIVISION,tag_5,category_code,DEPT,SUB_DEPT,article_code,barcode,brand_code,brand_name,article_name,varian_option1,varian_option2,price,vendor_code,vendor_name, tot_qty,tot_berat, disc_pct,total_disc_amt,total_moredisc_amt,moredisc_pct,margin,gross_after_margin,gross,net_bf,net_af,trans_no as areatrx,source_data,trans_no, no_ref FROM r_sales where 1=1 $where order by periode")->result_array();
        $file = fopen('php://output', 'w');

        $header = array('Store', 'Periode', 'Bulan', 'DIVISION', 'SUB DIVISION', 'Tipe Artikel', 'Kode Kategori', 'DEPT', 'SUB DEPT', 'Article Code', 'Barcode', 'Kode Brand', 'Nama Brand', 'Nama Produk', 'Varian Option1', 'Varian Option2', 'Harga', 'Kode Vendor', 'Nama Vendor', 'Total Qty(Pcs)', 'Total Berat(Kg)', 'Disc(%)', 'Total Disc', 'Disc. Tambahan(Rp)', 'Disc. Tambahan(%)', 'Margin', 'Gross After Margin', 'Gross(Rp)', 'Net Before(Rp)', 'Net After(Rp)', 'Area Transaksi', 'Source Data', 'Trans No', 'No Ref');

        fputcsv($file, $header);

        foreach ($data as $key => $value) {
            if (substr($value['areatrx'], 8, 1) != '5') {
                $value['areatrx'] = substr($value['areatrx'], 8, 1) == '3' ? 'BAZAAR' : 'FLOOR';
            } else {
                $value['areatrx'] = 'ONLINE';
            }
            fputcsv($file, $value, $delimiter);
        }
        fclose($file);
        exit;
    }

    function export_csv_penjualanartikel_operation($fromdate, $todate, $source_data, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx)
    {
        extract(populateform());

        $division = str_replace("%20", " ", $division);
        $sub_division = str_replace("%20", " ", $sub_division);
        $dept = str_replace("%20", " ", $dept);
        $sub_dept = str_replace("%20", " ", $sub_dept);

        $filename = 'sales_by_artikel_report.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $data['username'] = $this->input->cookie('cookie_invent_user');

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

        if ($source_data !== "null") {
            $where .= " AND source_data = '" . $source_data . "'";
        }

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

        if ($store !== "null") {
            $where .= " AND branch_id = '" . $store . "'";
        }

        if ($areatrx !== "null") {
            $arrAreatrx = explode(',', $areatrx);
            if (count($arrAreatrx) > 1) {
                $where .= " AND substring(trans_no,9,1) in ('" . $arrAreatrx[0] . "', '" . $arrAreatrx[1] . "')";
            } else {
                $where .= " AND substring(trans_no,9,1) in ('" . $areatrx . "')";
            }
        }

        if ($fromdate !== null and $todate !== null) {
            $where .= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        $delimiter = ';';

        $data = $this->db->query("SELECT branch_id, SUBSTRING(periode, 1, 10) as periode,SUBSTRING(periode, 6, 2) as bulan, DIVISION,SUB_DIVISION,tag_5,category_code,DEPT,SUB_DEPT,article_code,barcode,brand_code,brand_name,article_name,varian_option1,varian_option2,price,vendor_code,vendor_name, tot_qty,tot_berat, disc_pct,total_disc_amt,total_moredisc_amt,moredisc_pct,margin,gross_after_margin,gross,net_bf,net_af,trans_no as areatrx,source_data,trans_no, no_ref FROM r_sales where 1=1 $where order by periode")->result_array();
        $file = fopen('php://output', 'w');

        $header = array('Store', 'Periode', 'Bulan', 'DIVISION', 'SUB DIVISION', 'Tipe Artikel', 'Kode Kategori', 'DEPT', 'SUB DEPT', 'Article Code', 'Barcode', 'Kode Brand', 'Nama Brand', 'Nama Produk', 'Varian Option1', 'Varian Option2', 'Harga', 'Kode Vendor', 'Nama Vendor', 'Total Qty(Pcs)', 'Total Berat(Kg)', 'Disc(%)', 'Total Disc', 'Disc. Tambahan(Rp)', 'Disc. Tambahan(%)', 'Margin', 'Gross After Margin', 'Gross(Rp)', 'Net Before(Rp)', 'Net After(Rp)', 'Area Transaksi', 'Source Data', 'Trans No', 'No Ref');

        fputcsv($file, $header);

        foreach ($data as $key => $value) {
            if (substr($value['areatrx'], 8, 1) != '5') {
                $value['areatrx'] = substr($value['areatrx'], 8, 1) == '3' ? 'BAZAAR' : 'FLOOR';
            } else {
                $value['areatrx'] = 'ONLINE';
            }
            fputcsv($file, $value, $delimiter);
        }
        fclose($file);
        exit;
    }

    public function operational_fee()
    {
        extract(populateform());
        $data['title'] = 'Rambla | Laporan Operational Fee';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/operational_fee', $data);
        $this->load->view('template_member/footer');
    }

    public function pembayaran_operational_fee()
    {
        $postData = $this->input->post();
        $data = $this->M_OperationalFee->getOperationalFee($postData);
        echo json_encode($data);
    }

    public function pembayaran_online_paid()
    {

        extract(populateform());
        $data['title'] = 'Rambla | Laporan Pembayaran Trx Online';
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $data['vendor'] = $this->input->cookie('cookie_invent_vendor');

        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        if ($cek_operation == "1") {
            redirect(base_url() . "Laporan/penjualan_artikel_operation");
        }

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/pembayaran_trx_online', $data);
        $this->load->view('template_member/footer');
    }

    public function pembayaran_online_list()
    {
        $postData = $this->input->post();
        $data = $this->M_PaidOnline->getPaidOnline($postData);
        echo json_encode($data);
    }
    function export_csv_pembayaran_online()
    {
        extract(populateform());

        $getData = $this->input->get();
        $fromdate = $getData['fromdate'];
        $todate = $getData['todate'];
        $store = $getData['store'];
        $deltype = $getData['deltype'];
        $paytype = $getData['paytype'];
        $kode = "";

        $dbCentral = $this->load->database('dbcentral', TRUE);

        $filename = 'payment_trx_online.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $data['username'] = $this->input->cookie('cookie_invent_user');

        $query = "SELECT distinct CASE WHEN ( substr( a.trans_no, 7, 2 ) = '01' ) THEN 'R001' WHEN ( substr( a.trans_no, 7, 2 ) = '02' ) THEN 'R002' WHEN ( substr( a.trans_no, 7, 2 ) = '03' ) THEN 'V001' WHEN ( substr( a.trans_no, 7, 2 ) = '04' ) THEN 'S002' WHEN ( substr( a.trans_no, 7, 2 ) = '05' ) THEN 'S003' END  AS branch_id,  DATE_FORMAT(a.trans_date,'%Y-%m-%d'), DATE_FORMAT(a.trans_date,'%m'), a.trans_no, a.no_ref, a.delivery_type, a.delivery_number, CASE left(tp.mop_code,2) when 'VA' THEN 'Virtual Account' WHEN 'VC' THEN 'Voucher' WHEN 'PP' THEN 'Point' WHEN 'CC' THEN 'Credit Card' WHEN 'CP' THEN 'Coupon' ELSE description end mop_name, card_name, tp.paid_amount FROM t_sales_trans_hdr a LEFT JOIN t_paid tp on tp.trans_no = a.trans_no LEFT JOIN m_mop mm on mm.mop_code = tp.mop_code where a.trans_status = '1' and substr( a.trans_no, 9, 1 )  = '5' ";

        $whereClause = "";

        if ($store != '') {
            if ($store == "R001") {
                $kode = "01";
            } else if ($store == "R002") {
                $kode = "02";
            } else if ($store == "V001") {
                $kode = "03";
            } else if ($store == "S002") {
                $kode = "04";
            } else if ($store == "S003") {
                $kode = "05";
            }
            $whereClause .= " and substr( a.trans_no, 7, 2 ) ='" . $kode . "' ";
        }

        if ($fromdate != "" && $todate != "") {
            $whereClause .= " AND DATE_FORMAT(a.trans_date,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }
        if ($deltype != "") {
            $whereClause .= " and a.delivery_type ='" . $deltype . "' ";
        }
        if ($paytype != "") {
            if ($paytype != 'VA' || $paytype != 'VC' || $paytype != 'PP' || $paytype != 'CC' || $paytype != 'CP') {

                $whereClause .= " and mm.description ='" . $paytype . "' ";
            } else {
                $whereClause .= " and left(tp.mop_code,2) ='" . $paytype . "' ";
            }
        }
        $orderBy = " order by DATE_FORMAT(a.trans_date,'%Y-%m-%d') desc ";
        $data = $dbCentral->query($query . $whereClause . $orderBy)->result_array();

        $file = fopen('php://output', 'w');

        $header = array('Store', 'Periode', 'Bulan', 'Trans No', 'No Ref', 'Delivery Type', 'Delivery Provider', 'Tipe Pembayaran', 'Nama Pembayaran', 'Jumlah Pembayaran');

        fputcsv($file, $header);
        foreach ($data as $key => $value) {
            if ($value['delivery_type'] == 'P') {
                $value['delivery_type'] = 'Pickup';
            } else if ($value['delivery_type'] == 'I') {
                $value['delivery_type'] = 'Instan';
            } else if ($value['delivery_type'] == 'R') {
                $value['delivery_type'] = 'Reguler';
            } else {
                $value['delivery_type'] = '';
            }
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_excel_pembayaran_online()
    {
        $getData = $this->input->get();
        $fromdate = $getData['fromdate'];
        $todate = $getData['todate'];
        $store = $getData['store'];
        $deltype = $getData['deltype'];
        $paytype = $getData['paytype'];
        $kode = "";
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $query = "SELECT distinct CASE WHEN ( substr( a.trans_no, 7, 2 ) = '01' ) THEN 'R001' WHEN ( substr( a.trans_no, 7, 2 ) = '02' ) THEN 'R002' WHEN ( substr( a.trans_no, 7, 2 ) = '03' ) THEN 'V001' WHEN ( substr( a.trans_no, 7, 2 ) = '04' ) THEN 'S002' WHEN ( substr( a.trans_no, 7, 2 ) = '05' ) THEN 'S003' END  AS branch_id,  DATE_FORMAT(a.trans_date,'%Y-%m-%d') as periode, DATE_FORMAT(a.trans_date,'%m') as bulan, a.trans_no, a.no_ref, a.delivery_type, a.delivery_number, CASE left(tp.mop_code,2) when 'VA' THEN 'Virtual Account' WHEN 'VC' THEN 'Voucher' WHEN 'PP' THEN 'Point' WHEN 'CC' THEN 'Credit Card' WHEN 'CP' THEN 'Coupon' ELSE description end mop_name, card_name, tp.paid_amount FROM t_sales_trans_hdr a LEFT JOIN t_paid tp on tp.trans_no = a.trans_no LEFT JOIN m_mop mm on mm.mop_code = tp.mop_code where a.trans_status = '1' and substr( a.trans_no, 9, 1 )  = '5' ";

        $whereClause = "";
        if ($store != '') {
            if ($store == "R001") {
                $kode = "01";
            } else if ($store == "R002") {
                $kode = "02";
            } else if ($store == "V001") {
                $kode = "03";
            } else if ($store == "S002") {
                $kode = "04";
            } else if ($store == "S003") {
                $kode = "05";
            }
            $whereClause .= " and substr( a.trans_no, 7, 2 ) ='" . $kode . "' ";
        }
        if ($fromdate != "" && $todate != "") {
            $whereClause .= " AND DATE_FORMAT(a.trans_date,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }
        if ($deltype != "") {
            $whereClause .= " and a.delivery_type ='" . $deltype . "' ";
        }
        if ($paytype != "") {
            if ($paytype != 'VA' || $paytype != 'VC' || $paytype != 'PP' || $paytype != 'CC' || $paytype != 'CP') {

                $whereClause .= " and mm.description ='" . $paytype . "' ";
            } else {
                $whereClause .= " and left(tp.mop_code,2) ='" . $paytype . "' ";
            }
        }
        $orderBy = " order by DATE_FORMAT(a.trans_date,'%Y-%m-%d') desc ";
        $data = $dbCentral->query($query . $whereClause . $orderBy)->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Store');
        $sheet->setCellValue('C1', 'Periode');
        $sheet->setCellValue('D1', 'Bulan');;
        $sheet->setCellValue('E1', 'Trans No');
        $sheet->setCellValue('F1', 'No Ref');
        $sheet->setCellValue('G1', 'Delivery Type');
        $sheet->setCellValue('H1', 'Delivery Provider');
        $sheet->setCellValue('I1', 'Tipe Pembayaran');
        $sheet->setCellValue('J1', 'Nama Pembayaran');
        $sheet->setCellValue('K1', 'Jumlah Pembayaran');

        /* Excel Data */
        $row_number = 2;
        $maskDelType = '';
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $key + 1);
            $sheet->setCellValue('B' . $row_number, $row['branch_id']);
            $sheet->setCellValue('C' . $row_number, $row['periode']);
            $sheet->setCellValue('D' . $row_number, $row['bulan']);
            $sheet->setCellValue('E' . $row_number, $row['trans_no']);
            $sheet->setCellValue('F' . $row_number, $row['no_ref']);
            if ($row['delivery_type'] == 'P') {
                $maskDelType = 'Pickup';
            } else if ($row['delivery_type'] == 'I') {
                $maskDelType = 'Instan';
            } else if ($row['delivery_type'] == 'R') {
                $maskDelType = 'Reguler';
            } else {
                $maskDelType = '';
            }
            $sheet->setCellValue('G' . $row_number, $maskDelType);
            $sheet->setCellValue('H' . $row_number, $row['delivery_number']);
            $sheet->setCellValue('I' . $row_number, $row['mop_name']);
            $sheet->setCellValue('J' . $row_number, $row['card_name']);
            $sheet->setCellValue('K' . $row_number, $row['paid_amount']);
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'payment_trx_online';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
    function export_csv_operational_fee()
    {
        extract(populateform());

        $getData = $this->input->get();
        $fromdate = $getData['fromdate'];
        $todate = $getData['todate'];
        $store = $getData['store'];

        $dbCentral = $this->load->database('dbcentral', TRUE);

        $filename = 'operational_fee_' . $store . '_' . $fromdate . '_' . $todate . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $data['username'] = $this->input->cookie('cookie_invent_user');

        $whereClause = "";
        $kode = "";

        if ($store != '') {
            if ($store == "R001") {
                $kode = "01";
            } else if ($store == "R002") {
                $kode = "02";
            } else if ($store == "V001") {
                $kode = "03";
            } else if ($store == "S002") {
                $kode = "04";
            } else if ($store == "S003") {
                $kode = "05";
            }
        }
        if ($fromdate != "" && $todate != "") {
            $whereClause .= " AND DATE_FORMAT(trans_date,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        $query = "SELECT row_number() over() No, sales.vendor_code, vendor_name, brand_code, 
        sum(net_bf_floor)net_floor, sum(net_bf_bazzar)nett_bazzar, 
        sum(gross_bf_floor)gross_floor, sum(gross_bf_bazzar)gross_bazzar,
        ifnull(ops_fee,0)ops_fee, 
        ifnull(sum((gross_bf_floor*ops_fee)/100),0)TotalOpsFee
        from (
            select date_format(trans_date , '%Y.%m') bulan, 
            round(sum(td.net_prc),0) net_af_floor, 0 net_af_bazzar,
            round(sum(td.net_all),0) net_bf_floor, 0 net_bf_bazzar,    
            round(sum(td.gross_BF),0) gross_bf_floor, 0 gross_bf_bazzar,
            td.brand, mim.vendor_code
            from t_sales_trans_hdr th inner join 
            (select *, case when flag_tax in(1) then net_price / 1.11				
                          else net_price
                      end as net_prc, 	
                          
                  case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price / 1.11	
                        when '1' then (net_price + (fee * -1))/ 1.11
                        when '2' then (net_price - fee)/ 1.11
                    end		
                    else 		
                      case when flag_tax in(1)  then net_price / 1.11	
                          else net_price
                      end	
                    end as net_all,
                    case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price	
                        when '1' then net_price + (fee * -1)
                        when '2' then net_price - fee
                    end		
                    else net_price 
                    end as gross_BF
                    from t_sales_trans_dtl
            ) td on th.trans_no = td.trans_no left join m_codebar mc on td.barcode = mc.barcode
            left join m_item_master mim on mc.article_number = mim.article_number and mim.branch_id = (CASE
            WHEN substring(th.trans_no,7,2) = '01' THEN 'R001'
            WHEN substring(th.trans_no,7,2) = '02' THEN 'R002'
            WHEN substring(th.trans_no,7,2) = '03' THEN 'V001' 
            WHEN substring(th.trans_no,7,2) = '04' THEN 'S002'
            WHEN substring(th.trans_no,7,2) = '05' THEN 'S003'
            END)
            where trans_status in ('1') and td.category_code != 'RSOTMKVC01' $whereClause 
            and substring(th.trans_no,7,2) = '$kode'
            and substring(th.trans_no,9,1) in ('0','1','2','5') 
            group by bulan, td.brand, mim.vendor_code		
            union all 
            select date_format(trans_date , '%Y.%m') bulan, 
            0 net_af_floor, round(sum(td.net_prc),0) net_af_bazzar, 
            0 net_bf_floor, round(sum(td.net_all),0) net_bf_bazzar, 
            0 gross_bf_floor, round(sum(td.gross_BF),0) gross_bf_bazzar,
            td.brand, mim.vendor_code
            from t_sales_trans_hdr th inner join 
            (select *, case when flag_tax in(1) then net_price / 1.11				
                          else net_price
                      end as net_prc, 	
                          
                  case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price / 1.11	
                        when '1' then (net_price + (fee * -1))/ 1.11
                        when '2' then (net_price - fee)/ 1.11
                    end		
                    else 		
                      case when flag_tax in(1)  then net_price / 1.11	
                          else net_price
                      end	
                    end as net_all,
                    case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price	
                        when '1' then net_price + (fee * -1)
                        when '2' then net_price - fee
                    end		
                    else net_price 
                    end as gross_BF
                    from t_sales_trans_dtl
            ) td on th.trans_no = td.trans_no left join m_codebar mc on td.barcode = mc.barcode
            left join m_item_master mim on mc.article_number = mim.article_number and mim.branch_id = (CASE
            WHEN substring(th.trans_no,7,2) = '01' THEN 'R001'
            WHEN substring(th.trans_no,7,2) = '02' THEN 'R002'
            WHEN substring(th.trans_no,7,2) = '03' THEN 'V001'
            WHEN substring(th.trans_no,7,2) = '04' THEN 'S002'
            WHEN substring(th.trans_no,7,2) = '05' THEN 'S003'
            END)
            where trans_status in ('1') and td.category_code != 'RSOTMKVC01' $whereClause    
            and substring(th.trans_no,7,2) = '$kode' 
            and substring(th.trans_no,9,1) in ('3') 
            group by bulan, td.brand, mim.vendor_code    
        )sales left join (
            SELECT mc.vendor_code, brand_code, vendor_name, ops_fee
            FROM m_margin_code mc left join m_vendor mv on mc.vendor_code = mv.vendor_code
            where mc.branch_id = '" . $store . "'
        ) Margin on sales.brand = Margin.brand_code and sales.vendor_code = Margin.vendor_code  
        group by sales.vendor_code, vendor_name, brand_code, ops_fee
        ";

        $orderBy = " ";
        $data = $dbCentral->query($query . $orderBy)->result_array();

        $file = fopen('php://output', 'w');

        $header = array('No', 'Vendor Code', 'Vendor Name', 'Brand Code', 'Net Floor', 'Net Bazaar', 'Gross Floor', 'Gross Bazaar', 'Ops Fee', 'Total Ops Fee');

        fputcsv($file, $header);
        foreach ($data as $key => $value) {
            fputcsv($file, $value);
        }
        // fclose($file);
        exit;
    }

    function export_excel_operational_fee()
    {
        $getData = $this->input->get();
        $fromdate = $getData['fromdate'];
        $todate = $getData['todate'];
        $store = $getData['store'];

        $dbCentral = $this->load->database('dbcentral', TRUE);
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $whereClause = "";
        $kode = "";

        if ($store != '') {
            if ($store == "R001") {
                $kode = "01";
            } else if ($store == "R002") {
                $kode = "02";
            } else if ($store == "V001") {
                $kode = "03";
            } else if ($store == "S002") {
                $kode = "04";
            } else if ($store == "S003") {
                $kode = "05";
            }
        }
        if ($fromdate != "" && $todate != "") {
            $whereClause .= " AND DATE_FORMAT(trans_date,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        $query = "SELECT row_number() over() No, sales.vendor_code, vendor_name, brand_code, 
        sum(net_bf_floor)net_floor, sum(net_bf_bazzar)nett_bazzar, 
        sum(gross_bf_floor)gross_floor, sum(gross_bf_bazzar)gross_bazzar,
        ifnull(ops_fee,0)ops_fee, 
        ifnull(sum((gross_bf_floor*ops_fee)/100),0)TotalOpsFee
        from (
            select date_format(trans_date , '%Y.%m') bulan, 
            round(sum(td.net_prc),0) net_af_floor, 0 net_af_bazzar,
            round(sum(td.net_all),0) net_bf_floor, 0 net_bf_bazzar,    
            round(sum(td.gross_BF),0) gross_bf_floor, 0 gross_bf_bazzar,
            td.brand, mim.vendor_code
            from t_sales_trans_hdr th inner join 
            (select *, case when flag_tax in(1) then net_price / 1.11				
                          else net_price
                      end as net_prc, 	
                          
                  case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price / 1.11	
                        when '1' then (net_price + (fee * -1))/ 1.11
                        when '2' then (net_price - fee)/ 1.11
                    end		
                    else 		
                      case when flag_tax in(1)  then net_price / 1.11	
                          else net_price
                      end	
                    end as net_all,
                    case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price	
                        when '1' then net_price + (fee * -1)
                        when '2' then net_price - fee
                    end		
                    else net_price 
                    end as gross_BF
                    from t_sales_trans_dtl
            ) td on th.trans_no = td.trans_no left join m_codebar mc on td.barcode = mc.barcode
            left join m_item_master mim on mc.article_number = mim.article_number and mim.branch_id = (CASE
            WHEN substring(th.trans_no,7,2) = '01' THEN 'R001'
            WHEN substring(th.trans_no,7,2) = '02' THEN 'R002'
            WHEN substring(th.trans_no,7,2) = '03' THEN 'V001'
            WHEN substring(th.trans_no,7,2) = '04' THEN 'S002'
            WHEN substring(th.trans_no,7,2) = '05' THEN 'S003'
            END)
            where trans_status in ('1') and td.category_code != 'RSOTMKVC01' $whereClause 
            and substring(th.trans_no,7,2) = '$kode'
            and substring(th.trans_no,9,1) in ('0','1','2','5') 
            group by bulan, td.brand, mim.vendor_code		
            union all 
            select date_format(trans_date , '%Y.%m') bulan, 
            0 net_af_floor, round(sum(td.net_prc),0) net_af_bazzar, 
            0 net_bf_floor, round(sum(td.net_all),0) net_bf_bazzar, 
            0 gross_bf_floor, round(sum(td.gross_BF),0) gross_bf_bazzar,
            td.brand, mim.vendor_code
            from t_sales_trans_hdr th inner join 
            (select *, case when flag_tax in(1) then net_price / 1.11				
                          else net_price
                      end as net_prc, 	
                          
                  case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price / 1.11	
                        when '1' then (net_price + (fee * -1))/ 1.11
                        when '2' then (net_price - fee)/ 1.11
                    end		
                    else 		
                      case when flag_tax in(1)  then net_price / 1.11	
                          else net_price
                      end	
                    end as net_all,
                    case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price	
                        when '1' then net_price + (fee * -1)
                        when '2' then net_price - fee
                    end		
                    else net_price 
                    end as gross_BF
                    from t_sales_trans_dtl
            ) td on th.trans_no = td.trans_no left join m_codebar mc on td.barcode = mc.barcode
            left join m_item_master mim on mc.article_number = mim.article_number and mim.branch_id = (CASE
            WHEN substring(th.trans_no,7,2) = '01' THEN 'R001'
            WHEN substring(th.trans_no,7,2) = '02' THEN 'R002'
            WHEN substring(th.trans_no,7,2) = '03' THEN 'V001'
            WHEN substring(th.trans_no,7,2) = '04' THEN 'S002'
            WHEN substring(th.trans_no,7,2) = '05' THEN 'S003'
            END)
            where trans_status in ('1') and td.category_code != 'RSOTMKVC01' $whereClause    
            and substring(th.trans_no,7,2) = '$kode' 
            and substring(th.trans_no,9,1) in ('3') 
            group by bulan, td.brand, mim.vendor_code    
        )sales left join (
            SELECT mc.vendor_code, brand_code, vendor_name, ops_fee
            FROM m_margin_code mc left join m_vendor mv on mc.vendor_code = mv.vendor_code
            where mc.branch_id = '" . $store . "'
        ) Margin on sales.brand = Margin.brand_code and sales.vendor_code = Margin.vendor_code  
        group by sales.vendor_code, vendor_name, brand_code, ops_fee
        ";


        $orderBy = " ";
        $data = $dbCentral->query($query . $orderBy)->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Vendor Code');
        $sheet->setCellValue('C1', 'Vendor Name');
        $sheet->setCellValue('D1', 'Brand Code');;
        $sheet->setCellValue('E1', 'Net Floor');
        $sheet->setCellValue('F1', 'Net Bazaar');
        $sheet->setCellValue('G1', 'Gross Floor');
        $sheet->setCellValue('H1', 'Gross Bazaar');
        $sheet->setCellValue('I1', 'Ops Fee');
        $sheet->setCellValue('J1', 'Total Ops Fee');

        /* Excel Data */
        $row_number = 2;
        $maskDelType = '';
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $key + 1);
            $sheet->setCellValue('B' . $row_number, $row['vendor_code']);
            $sheet->setCellValue('C' . $row_number, $row['vendor_name']);
            $sheet->setCellValue('D' . $row_number, $row['brand_code']);
            $sheet->setCellValue('E' . $row_number, $row['net_floor']);
            $sheet->setCellValue('F' . $row_number, $row['nett_bazzar']);
            $sheet->setCellValue('G' . $row_number, $row['gross_floor']);
            $sheet->setCellValue('H' . $row_number, $row['gross_bazzar']);
            $sheet->setCellValue('I' . $row_number, $row['ops_fee']);
            $sheet->setCellValue('J' . $row_number, $row['TotalOpsFee']);
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'operational_fee_' . $store . '_' . $fromdate . '_' . $todate;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
