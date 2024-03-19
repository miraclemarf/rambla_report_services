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
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Datatables');
        $this->load->model('M_Categories');
        $this->load->model('M_Division');
        $this->ceklogin();
    }

    public function penjualan_artikel()
    {
                
        extract(populateform());
        $data['title']          = 'Rambla | Laporan Penjualan';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['vendor']         = $this->input->cookie('cookie_invent_vendor');

        $cek_operation = $this->db->query("SELECT * from m_login where username ='".$data['username']."'")->row();
        $cek_operation = $cek_operation->login_type_id;

        if($cek_operation == "1"){
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
        $data['title']          = 'Rambla | Laporan Penjualan Operation';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['vendor']         = $this->input->cookie('cookie_invent_vendor');

        $cek_operation = $this->db->query("SELECT * from m_login where username ='".$data['username']."'")->row();
        $cek_operation = $cek_operation->login_type_id;

        if($cek_operation != "1"){
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
        $data['title']          = 'Rambla | Laporan Master Item';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['vendor']         = $this->input->cookie('cookie_invent_vendor');
        
        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/masteritem', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function list_stok()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Laporan Stock';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['vendor']         = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/stok', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function list_promo()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Laporan Promo';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['vendor']         = $this->input->cookie('cookie_invent_vendor');

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/promo', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function penjualan_artikel_where(){
        
        extract(populateform());

        $params4        = str_replace("%20"," ",$params4);
        $params5        = str_replace("%20"," ",$params5);
        $params6        = str_replace("%20"," ",$params6);
        $params7        = str_replace("%20"," ",$params7); 

        $tables     = "r_sales";

        $search     = array('branch_id','periode','barcode','brand_code','brand_name','article_name','varian_option1','varian_option2','price','tot_qty','disc_pct','total_disc_amt','total_moredisc_amt','moredisc_pct','margin','gross_after_margin','gross','source_data','trans_no','no_ref');

        $data['username']       = $this->input->cookie('cookie_invent_user');
        // $vendor_code    = $this->input->post('vendor_code');
        $where      = array('');
        $fromdate   = '';
        $todate     = '';

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $filter = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $filter = $this->M_Division->get_division($data['username'],$params8);
        }else{
            // UNTUK MD
            $filter = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($params1 || $params2 || $params3 || $params4 || $params5 || $params6 || $params7 || $params8){
            if($params1){
                $filter1 = " AND brand_code = '".$params1."'";
                $filter.= $filter1;
            }
            if($params2){
                $filter2 = " AND source_data = '".$params2."'";
                $filter.=$filter2;
            }
            if($params3) {
                if (strpos($params3, '-') !== false) {
                    $tgl = explode("-", $params3);
                    $fromdate = date("Y-m-d", strtotime($tgl[0]));
                    $todate = date("Y-m-d", strtotime($tgl[1]));
                }
                $filter3 = " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '".$fromdate."' and '".$todate."'";
                $filter.=$filter3;
            }
            if($params4){
                $filter4 = " AND DIVISION = '".$params4."'";
                $filter.=$filter4;
            }
            if($params5){
                $filter5 = " AND SUB_DIVISION = '".$params5."'";
                $filter.=$filter5;
            }
            if($params6){
                $filter6 = " AND DEPT = '".$params6."'";
                $filter.=$filter6;
            }
            if($params7){
                $filter7 = " AND SUB_DEPT = '".$params7."'";
                $filter.=$filter7;
            }
            if($params8){
                $filter8 = " AND branch_id = '".$params8."'";
                $filter.=$filter8;
            }
            if($params9){
                $arrParams9 = explode(',', $params9);
                if(count($arrParams9) > 1){
                    $filter9 = " AND substring(trans_no,9,1) in ('".$arrParams9[0]."', '".$arrParams9[1]."')";
                }
                else{
                    $filter9 = " AND substring(trans_no,9,1) in ('".$params9."')";
                }
                $filter.=$filter9;
            }
            $isWhere = $filter;
        }else{
            $isWhere = $filter;
        }
      
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables,$search,$where,$isWhere);
    }

    public function stock_where(){
        extract(populateform());
        $tables     = "r_s_item_stok";
        $search     = array('branch_id','brand_code','brand_name','barcode','varian_option1','varian_option2','periode','DIVISION','SUB_DIVISION','DEPT','SUB_DEPT','article_name','last_stock');
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where      = array('');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $filter = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $filter = $this->M_Division->get_division($data['username'],$params6);
        }else{
            // UNTUK MD
            $filter = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($params1 || $params2 || $params3 || $params4 || $params5 || $params6 || $params7){
            if($params1){
                $filter1 = " AND brand_code = '".$params1."'";
                $filter.= $filter1;
            }
            if($params2){
                $filter2 = " AND DIVISION = '".$params2."'";
                $filter.=$filter2;
            }
            if($params3){
                $filter3 = " AND SUB_DIVISION = '".$params3."'";
                $filter.=$filter3;
            }
            if($params4){
                $filter4 = " AND DEPT = '".$params4."'";
                $filter.=$filter4;
            }
            if($params5){
                $filter5 = " AND SUB_DEPT = '".$params5."'";
                $filter.=$filter5;
            }
            if($params6){
                $filter6 = " AND branch_id = '".$params6."'";
                $filter.=$filter6;
            }
            if($params7){
                if($params7 == "pcs"){
                    $filter7 = " AND tag_5 in ('TIMBANG') is not true";
                }else{
                    $filter7 = " AND tag_5 in ('TIMBANG')";
                }
                $filter.=$filter7;
            }
            $isWhere = $filter;
        }else{
            $isWhere = $filter;
        }

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables,$search,$where,$isWhere);
    }

    public function promo_where(){
       
        extract(populateform());

        $params4        = str_replace("%20"," ",$params4);
        $params5        = str_replace("%20"," ",$params5);
        $params6        = str_replace("%20"," ",$params6);
        $params7        = str_replace("%20"," ",$params7); 
        
        $tables     = "r_promo_aktif";
        $search     = array('category_code','vendor_code','vendor_name','brand','brand_name','barcode','pos_pname','varian_option1','varian_option2','promo_type','promo_desc','start_date','end_date','promo_id','current_price','min_qty','min_purchase','disc_percentage','disc_amount','add_disc_percentage','free_qty','special_price','aktif','active_monday','active_tuesday','active_wednesday','active_thursday','active_friday','active_saturday','active_sunday','division');

        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where      = array('');
        
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $filter = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $filter = $this->M_Division->get_division($data['username'],$params8);
        }else{
            // UNTUK MD
            $filter = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($params1 || $params2 || $params3 || $params4 || $params5 || $params6 || $params7 || $params8){
            if($params1){
                $filter1 = " AND brand = '".$params1."'";
                $filter.= $filter1;
            }
            if($params2){
                $filter2 = "AND promo_type = '".$params2."'";
                $filter.=$filter2;
            }
            if($params3) {
                if (strpos($params3, '-') !== false) {
                    $tgl = explode("-", $params3);
                    $fromdate = date("Y-m-d", strtotime($tgl[0]));
                    $todate = date("Y-m-d", strtotime($tgl[1]));
                }
                $filter3 = " AND (DATE_FORMAT(start_date,'%Y-%m-%d') >= '".$fromdate."' OR DATE_FORMAT(end_date,'%Y-%m-%d') <= '".$todate."')";
                $filter.=$filter3;
            }
            if($params4){
                $filter4 = " AND DIVISION = '".$params4."'";
                $filter.=$filter4;
            }
            if($params5){
                $filter5 = " AND SUB_DIVISION = '".$params5."'";
                $filter.=$filter5;
            }
            if($params6){
                $filter6 = " AND DEPT = '".$params6."'";
                $filter.=$filter6;
            }
            if($params7){
                $filter7 = " AND SUB_DEPT = '".$params7."'";
                $filter.=$filter7;
            }
            if($params8){
                $filter8 = " AND branch_id = '".$params8."'";
                $filter.=$filter8;
            }
            $isWhere = $filter;
        }else{
            $isWhere = $filter;
        }
    
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables,$search,$where,$isWhere);
    }

    public function masteritem_where(){
        extract(populateform());
        $tables     = "r_item_master";
        $search     = array('branch_id','article_code','barcode','supplier_pcode','article_name','supplier_pname','brand','brand_name','option1','varian_option1','option2','varian_option2','normal_price','tag_5');
        // $vendor_code    = $this->input->post('vendor_code');
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where      = array('');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $filter = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $filter = $this->M_Division->get_division($data['username'],$params6);
        }else{
            // UNTUK MD
            $filter = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($params1 || $params2 || $params3 || $params4 || $params5 || $params6){
            if($params1){
                $filter1 = " AND brand = '".$params1."'";
                $filter.= $filter1;
            }
            if($params2){
                $filter2 = " AND DIVISION = '".$params2."'";
                $filter.=$filter2;
            }
            if($params3){
                $filter3 = " AND SUB_DIVISION = '".$params3."'";
                $filter.=$filter3;
            }
            if($params4){
                $filter4 = " AND DEPT = '".$params4."'";
                $filter.=$filter4;
            }
            if($params5){
                $filter5 = " AND SUB_DEPT = '".$params5."'";
                $filter.=$filter5;
            }
            if($params6){
                $filter6 = " AND branch_id = '".$params6."'";
                $filter.=$filter6;
            }
            $isWhere = $filter;
        }else{
            $isWhere = $filter;
        }
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables,$search,$where,$isWhere);
    }

    function export_excel_stock($brand_code, $division, $sub_division, $dept, $sub_dept, $store, $art_type)
	{
        /* Data */
        $data['username']      = $this->input->cookie('cookie_invent_user');

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA
       
        if($brand_code !== "null"){
            $where.=" AND brand_code = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        if($art_type !== "null"){
            if($art_type == "pcs"){
                $where.=" AND tag_5 in ('TIMBANG') is not true";
            }else{
                $where.=" AND tag_5 in ('TIMBANG')";
            }
        }

        $data         = $this->db->query("SELECT * FROM r_s_item_stok WHERE 1=1 $where")->result_array();

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
        $sheet->setCellValue('M1', 'DIVISION');
        $sheet->setCellValue('N1', 'SUB_DIVISION');
        $sheet->setCellValue('O1', 'DEPT');
        $sheet->setCellValue('P1', 'SUB_DEPT');
        $sheet->setCellValue('Q1', 'last_stock');
        
        /* Excel Data */
        $row_number = 2;
        foreach($data as $key => $row)
        {
            $sheet->setCellValue('A'.$row_number, $key+1);
            $sheet->setCellValue('B'.$row_number, $row['branch_id']);
            $sheet->setCellValue('C'.$row_number, $row['periode']);
            $sheet->setCellValue('D'.$row_number, $row['barcode']);
            $sheet->setCellValue('E'.$row_number, $row['article_code']);
            $sheet->setCellValue('F'.$row_number, $row['article_name']);
            $sheet->setCellValue('G'.$row_number, $row['varian_option1']);
            $sheet->setCellValue('H'.$row_number, $row['varian_option2']);
            $sheet->setCellValue('I'.$row_number, $row['vendor_code']);
            $sheet->setCellValue('J'.$row_number, $row['vendor_name']);
            $sheet->setCellValue('K'.$row_number, $row['brand_code']);
            $sheet->setCellValue('L'.$row_number, $row['brand_name']);
            $sheet->setCellValue('M'.$row_number, $row['DIVISION']);
            $sheet->setCellValue('N'.$row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('O'.$row_number, $row['DEPT']);
            $sheet->setCellValue('P'.$row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('Q'.$row_number, $row['last_stock']);
        
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'stock_report';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_promo($brand_code, $promo, $fromdate, $todate,$division, $sub_division, $dept, $sub_dept, $branch_id)
	{
        /* Data */
        $data['username']      = $this->input->cookie('cookie_invent_user');

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$branch_id);
        }else{
            // UNTUK MD
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($brand_code !== "null"){
            $where.=" and brand = '".$brand_code."'";
        }
        if($promo !== "null"){
            $where.=" and promo_type = '".$promo."'";
        }

        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($fromdate !== "null" AND $todate !== "null"){
            $where.= " AND (DATE_FORMAT(start_date,'%Y-%m-%d') >= '".$fromdate."' OR DATE_FORMAT(end_date,'%Y-%m-%d') <= '".$todate."')";
        }

        if($branch_id !== "null"){
            $where.=" AND branch_id = '".$branch_id."'";
        }
      
        $data         = $this->db->query("SELECT * FROM r_promo_aktif WHERE 1=1 $where")->result_array();

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
        $sheet->setCellValue('V1', 'Special Price');
        $sheet->setCellValue('W1', 'Aktif');
        $sheet->setCellValue('X1', 'Monday');
        $sheet->setCellValue('Y1', 'Tuesday');
        $sheet->setCellValue('Z1', 'Wednesday');
        $sheet->setCellValue('AA1', 'Thusday');
        $sheet->setCellValue('AB1', 'Friday');
        $sheet->setCellValue('AC1', 'Saturday');
        $sheet->setCellValue('AD1', 'Sunday');
        $sheet->setCellValue('AE1', 'Division');
        $sheet->setCellValue('AF1', 'Sub Division');
        $sheet->setCellValue('AG1', 'Dept');
        $sheet->setCellValue('AH1', 'Sub Dept');
        
        /* Excel Data */
        $row_number = 2;
        foreach($data as $key => $row)
        {
            $sheet->setCellValue('A'.$row_number, $key+1);
            $sheet->setCellValue('B'.$row_number, $row['branch_id']);
            $sheet->setCellValue('C'.$row_number, $row['start_date']);
            $sheet->setCellValue('D'.$row_number, $row['end_date']);
            $sheet->setCellValue('E'.$row_number, $row['promo_id']);
            $sheet->setCellValue('F'.$row_number, $row['promo_type']);
            $sheet->setCellValue('G'.$row_number, $row['category_code']);
            $sheet->setCellValue('H'.$row_number, $row['vendor_name']);
            $sheet->setCellValue('I'.$row_number, $row['brand']);
            $sheet->setCellValue('J'.$row_number, $row['barcode']);
            $sheet->setCellValue('K'.$row_number, $row['pos_pname']);
            $sheet->setCellValue('L'.$row_number, $row['varian_option1']);
            $sheet->setCellValue('M'.$row_number, $row['varian_option2']);
            $sheet->setCellValue('N'.$row_number, $row['promo_desc']);
            $sheet->setCellValue('O'.$row_number, $row['current_price']);
            $sheet->setCellValue('P'.$row_number, $row['min_qty']);
            $sheet->setCellValue('Q'.$row_number, $row['min_purchase']);
            $sheet->setCellValue('R'.$row_number, $row['disc_percentage']);
            $sheet->setCellValue('S'.$row_number, $row['disc_amount']);
            $sheet->setCellValue('T'.$row_number, $row['add_disc_percentage']);
            $sheet->setCellValue('U'.$row_number, $row['free_qty']);
            $sheet->setCellValue('V'.$row_number, $row['special_price']);
            $sheet->setCellValue('W'.$row_number, $row['aktif']);
            $sheet->setCellValue('X'.$row_number, $row['active_monday']);
            $sheet->setCellValue('Y'.$row_number, $row['active_tuesday']);
            $sheet->setCellValue('Z'.$row_number, $row['active_wednesday']);
            $sheet->setCellValue('AA'.$row_number, $row['active_thursday']);
            $sheet->setCellValue('AB'.$row_number, $row['active_friday']);
            $sheet->setCellValue('AC'.$row_number, $row['active_saturday']);
            $sheet->setCellValue('AD'.$row_number, $row['active_sunday']);
            $sheet->setCellValue('AE'.$row_number, $row['DIVISION']);
            $sheet->setCellValue('AF'.$row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('AG'.$row_number, $row['DEPT']);
            $sheet->setCellValue('AH'.$row_number, $row['SUB_DEPT']);
        
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'promo_report';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_masteritem($brand_code, $division, $sub_division, $dept, $sub_dept, $store)
	{
        /* Data */
        $data['username']      = $this->input->cookie('cookie_invent_user');

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($brand_code !== "null"){
            $where.=" AND brand = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        $data         = $this->db->query("SELECT * FROM r_item_master WHERE 1=1 $where")->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Store');
        $sheet->setCellValue('C1', 'Number Artikel');
        $sheet->setCellValue('D1', 'Kode Artikel');
        $sheet->setCellValue('E1', 'Barcode');
        $sheet->setCellValue('F1', 'Kode Produk');
        $sheet->setCellValue('G1', 'Kode Kategori');
        $sheet->setCellValue('H1', 'Nama Produk');
        $sheet->setCellValue('I1', 'Nama Produk Supplier');
        $sheet->setCellValue('J1', 'Kode Brand');
        $sheet->setCellValue('K1', 'Nama Brand');
        $sheet->setCellValue('L1', 'Option1');
        $sheet->setCellValue('M1', 'Varian Option1');
        $sheet->setCellValue('N1', 'Option2');
        $sheet->setCellValue('O1', 'Varian Option2');
        $sheet->setCellValue('P1', 'Division');
        $sheet->setCellValue('Q1', 'Sub Division');
        $sheet->setCellValue('R1', 'Dept');
        $sheet->setCellValue('S1', 'Sub Dept');
        $sheet->setCellValue('T1', 'Normal Price');
        $sheet->setCellValue('U1', 'Tag 5');
        
        /* Excel Data */
        $row_number = 2;
        foreach($data as $key => $row)
        {
            $sheet->setCellValue('A'.$row_number, $key+1);
            $sheet->setCellValue('B'.$row_number, $row['branch_id']);
            $sheet->setCellValue('C'.$row_number, $row['article_number']);
            $sheet->setCellValue('D'.$row_number, $row['article_code']);
            $sheet->setCellValue('E'.$row_number, $row['barcode']);
            $sheet->setCellValue('F'.$row_number, $row['supplier_pcode']);
            $sheet->setCellValue('G'.$row_number, $row['category_code']);
            $sheet->setCellValue('H'.$row_number, $row['article_name']);
            $sheet->setCellValue('I'.$row_number, $row['supplier_pname']);
            $sheet->setCellValue('J'.$row_number, $row['brand']);
            $sheet->setCellValue('K'.$row_number, $row['brand_name']);
            $sheet->setCellValue('L'.$row_number, $row['option1']);
            $sheet->setCellValue('M'.$row_number, $row['varian_option1']);
            $sheet->setCellValue('N'.$row_number, $row['option2']);
            $sheet->setCellValue('O'.$row_number, $row['varian_option2']);
            $sheet->setCellValue('P'.$row_number, $row['DIVISION']);
            $sheet->setCellValue('Q'.$row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('R'.$row_number, $row['DEPT']);
            $sheet->setCellValue('S'.$row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('T'.$row_number, $row['normal_price']);
            $sheet->setCellValue('U'.$row_number, $row['tag_5']);
        
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'masteritem_report';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_penjualanartikel($fromdate, $todate, $source, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx)
	{
        $data['username']      = $this->input->cookie('cookie_invent_user');
        /* Data */
        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA
        
        if($source !== "null"){
            $where.=" AND source_data = '".$source."'";
        }

        if($brand_code !== "null"){
            $where.=" AND brand_code = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        if($fromdate !== null AND $todate !== null){
            $where.= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '".$fromdate."' and '".$todate."'";
        }
        
        if($areatrx !== "null"){
            $arrAreatrx = explode(',', $areatrx);
            if(count($arrAreatrx) > 1){
                $where .= " AND substring(trans_no,9,1) in ('".$arrAreatrx[0]."', '".$arrAreatrx[1]."')";
            }
            else{
                $where .= " AND substring(trans_no,9,1) in ('".$areatrx."')";
            }
        }
      
        $data         = $this->db->query("SELECT * FROM r_sales WHERE 1=1 $where order by periode")->result_array();

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
        foreach($data as $key => $row)
        {
            $sheet->setCellValue('A'.$row_number, $key+1);
            $sheet->setCellValue('B'.$row_number, $row['branch_id']);
            $sheet->setCellValue('C'.$row_number, substr($row['periode'],0,10));
            $sheet->setCellValue('D'.$row_number, substr($row['periode'],5,2));
            $sheet->setCellValue('E'.$row_number, $row['DIVISION']);
            $sheet->setCellValue('F'.$row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('G'.$row_number, $row['tag_5']);
            $sheet->setCellValue('H'.$row_number, $row['category_code']);
            $sheet->setCellValue('I'.$row_number, $row['DEPT']);
            $sheet->setCellValue('J'.$row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('K'.$row_number, $row['article_code']);
            $sheet->setCellValue('L'.$row_number, $row['barcode']);
            $sheet->setCellValue('M'.$row_number, $row['brand_code']);
            $sheet->setCellValue('N'.$row_number, $row['brand_name']);
            $sheet->setCellValue('O'.$row_number, $row['article_name']);
            $sheet->setCellValue('P'.$row_number, $row['varian_option1']);
            $sheet->setCellValue('Q'.$row_number, $row['varian_option2']);
            $sheet->setCellValue('R'.$row_number, $row['price']);
            $sheet->setCellValue('S'.$row_number, $row['vendor_code']);
            $sheet->setCellValue('T'.$row_number, $row['vendor_name']);
            $sheet->setCellValue('U'.$row_number, $row['tot_qty']);
            $sheet->setCellValue('V'.$row_number, $row['tot_berat']);
            $sheet->setCellValue('W'.$row_number, $row['disc_pct']);
            $sheet->setCellValue('X'.$row_number, $row['total_disc_amt']);
            $sheet->setCellValue('Y'.$row_number, $row['total_moredisc_amt']);
            $sheet->setCellValue('Z'.$row_number, $row['moredisc_pct']);
            $sheet->setCellValue('AA'.$row_number, $row['margin']);
            $sheet->setCellValue('AB'.$row_number, $row['gross_after_margin']);
            $sheet->setCellValue('AC'.$row_number, $row['gross']);
            $sheet->setCellValue('AD'.$row_number, $row['net_bf']);
            $sheet->setCellValue('AE'.$row_number, $row['net_af']);
            if(substr($row['trans_no'], 8,1) != '5'){
                $data_areatrx = substr($row['trans_no'], 8,1) == '3' ? 'BAZZAR' : 'FLOOR';
            }
            $sheet->setCellValue('AF'.$row_number, $data_areatrx);
            $sheet->setCellValue('AG'.$row_number, $row['source_data']);
            $sheet->setCellValue('AH'.$row_number, $row['trans_no']);
            $sheet->setCellValue('AI'.$row_number, $row['no_ref']);
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'sales_by_artikel_report';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_excel_penjualanartikel_operation($fromdate, $todate, $source, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx)
	{
        /* Data */
        $data['username']      = $this->input->cookie('cookie_invent_user');
        /* Data */
        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA
        
        if($source !== "null"){
            $where.=" AND source_data = '".$source."'";
        }

        if($brand_code !== "null"){
            $where.=" AND brand_code = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        if($fromdate !== null AND $todate !== null){
            $where.= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '".$fromdate."' and '".$todate."'";
        }

        if($areatrx !== "null"){
            $arrAreatrx = explode(',', $areatrx);
            if(count($arrAreatrx) > 1){
                $where .= " AND substring(trans_no,9,1) in ('".$arrAreatrx[0]."', '".$arrAreatrx[1]."')";
            }
            else{
                $where .= " AND substring(trans_no,9,1) in ('".$areatrx."')";
            }
        }

        $data         = $this->db->query("SELECT * FROM r_sales WHERE 1=1 $where order by periode")->result_array();

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
        foreach($data as $key => $row)
        {
            $sheet->setCellValue('A'.$row_number, $key+1);
            $sheet->setCellValue('B'.$row_number, $row['branch_id']);
            $sheet->setCellValue('C'.$row_number, substr($row['periode'],0,10));
            $sheet->setCellValue('D'.$row_number, substr($row['periode'],5,2));
            $sheet->setCellValue('E'.$row_number, $row['DIVISION']);
            $sheet->setCellValue('F'.$row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('G'.$row_number, $row['tag_5']);
            $sheet->setCellValue('H'.$row_number, $row['category_code']);
            $sheet->setCellValue('I'.$row_number, $row['DEPT']);
            $sheet->setCellValue('J'.$row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('K'.$row_number, $row['article_code']);
            $sheet->setCellValue('L'.$row_number, $row['barcode']);
            $sheet->setCellValue('M'.$row_number, $row['brand_code']);
            $sheet->setCellValue('N'.$row_number, $row['brand_name']);
            $sheet->setCellValue('O'.$row_number, $row['article_name']);
            $sheet->setCellValue('P'.$row_number, $row['varian_option1']);
            $sheet->setCellValue('Q'.$row_number, $row['varian_option2']);
            $sheet->setCellValue('R'.$row_number, $row['price']);
            $sheet->setCellValue('S'.$row_number, $row['vendor_code']);
            $sheet->setCellValue('T'.$row_number, $row['vendor_name']);
            $sheet->setCellValue('U'.$row_number, $row['tot_qty']);
            $sheet->setCellValue('V'.$row_number, $row['tot_berat']);
            $sheet->setCellValue('W'.$row_number, $row['disc_pct']);
            $sheet->setCellValue('X'.$row_number, $row['total_disc_amt']);
            $sheet->setCellValue('Y'.$row_number, $row['total_moredisc_amt']);
            $sheet->setCellValue('Z'.$row_number, $row['moredisc_pct']);
            $sheet->setCellValue('AA'.$row_number, $row['gross']);
            $sheet->setCellValue('AB'.$row_number, $row['net_bf']);
            $sheet->setCellValue('AC'.$row_number, $row['net_af']);
            if(substr($row['trans_no'], 8,1) != '5'){
                $data_areatrx = substr($row['trans_no'], 8,1) == '3' ? 'BAZZAR' : 'FLOOR';
            }
            $sheet->setCellValue('AD'.$row_number, $data_areatrx);
            $sheet->setCellValue('AE'.$row_number, $row['source_data']);
            $sheet->setCellValue('AF'.$row_number, $row['trans_no']);
            $sheet->setCellValue('AG'.$row_number, $row['no_ref']);
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'sales_by_artikel_report';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    function export_csv_stock($brand_code, $division, $sub_division, $dept, $sub_dept, $store, $art_type){
        $filename = 'stock_report.csv';

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Type: application/csv;");

        $data['username']      = $this->input->cookie('cookie_invent_user');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($brand_code !== "null"){
            $where.=" AND brand_code = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        if($art_type !== "null"){
            if($art_type == "pcs"){
                $where.=" AND tag_5 in ('TIMBANG') is not true";
            }else{
                $where.=" AND tag_5 in ('TIMBANG')";
            }
        }

        $data   = $this->db->query("SELECT branch_id,periode,barcode, article_code, article_name,varian_option1,varian_option2, vendor_code, vendor_name, brand_code,brand_name,DIVISION,SUB_DIVISION,DEPT,SUB_DEPT,last_stock FROM r_s_item_stok where 1=1 $where")->result_array();
        $file   = fopen('php://output','w');

        $header = array('branch_id','periode','barcode','article_code','article_name','varian_option1','varian_option2','vendor_code','vendor_name','brand_code','brand_name','DIVISION','SUB_DIVISION','DEPT','SUB_DEPT','last_stock');

        fputcsv($file,$header);

        foreach($data as $key => $value){
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_csv_masteritem($brand_code, $division, $sub_division, $dept, $sub_dept, $store){
        $filename = 'masteritem_report.csv';

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Type: application/csv;");

        $data['username']      = $this->input->cookie('cookie_invent_user');
        
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($brand_code !== "null"){
            $where.=" AND brand = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        $data   = $this->db->query("SELECT branch_id,article_number,article_code,barcode,supplier_pcode,category_code, article_name,supplier_pname, brand,brand_name, option1,varian_option1,option2,varian_option2, division, sub_division, dept, sub_dept, normal_price, current_price, tag_5 FROM r_item_master where 1=1 $where")->result_array();
        $file   = fopen('php://output','w');

        $header = array('branch_id','article_number','article_code','barcode','supplier_pcode','category_code','article_name','supplier pname','brand','brand name','option1','varian_option1','option2','varian_option2','division', 'sub_division', 'dept', 'sub_dept', 'normal_price', 'current_price','tag 5');

        fputcsv($file,$header);

        foreach($data as $key => $value){
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_csv_promo($brand_code, $promo, $fromdate, $todate,$division, $sub_division, $dept, $sub_dept, $branch_id){
        $filename = 'promo_report.csv';

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Type: application/csv;");

        $data['username']      = $this->input->cookie('cookie_invent_user');
        
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$branch_id);
        }else{
            // UNTUK MD
            $where = "AND brand in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($brand_code !== "null"){
            $where.=" AND brand = '".$brand_code."'";
        }

        if($promo !== "null"){
            $where.=" AND promo_type = '".$promo."'";
        }

        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($fromdate !== "null" AND $todate !== "null"){
            $where.= " AND (DATE_FORMAT(start_date,'%Y-%m-%d') >= '".$fromdate."' OR DATE_FORMAT(end_date,'%Y-%m-%d') <= '".$todate."')";
        }

        if($branch_id !== "null"){
            $where.=" AND branch_id = '".$branch_id."'";
        }

        $data   = $this->db->query("SELECT branch_id, start_date,end_date,promo_id,promo_type,category_code,vendor_name,brand,barcode,pos_pname,varian_option1,varian_option2,promo_desc,current_price,min_qty,min_purchase,disc_percentage,disc_amount,add_disc_percentage,free_qty,special_price,aktif,active_monday,active_tuesday,active_wednesday,active_thursday,active_friday,active_saturday,active_sunday,DIVISION, SUB_DIVISION, DEPT, SUB_DEPT FROM r_promo_aktif WHERE 1=1 $where")->result_array();
        $file   = fopen('php://output','w');

        $header = array('Store','Start Date','End Date','Promo Id','Promo Type','Category Code','Vendor Name','Brand','Barcode','Article Name','Varian Option1','Varian Option2','Promo desc','Current Price','Min Qty','Min Purchase','Disc %','Disc Amount','Add Disc %','Free Qty','Special Price','Aktif','Monday','Tuesday','Wednesday','Thusday','Friday','Saturday','Sunday','Division','Sub Division','Dept','Sub Dept');

        fputcsv($file,$header);

        foreach($data as $key => $value){
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function generate_date(){
        extract(populateform());
        
        if (strpos($periode, '-') !== false) {
            $tgl = explode("-", $periode);
            $fromdate = date("Y-m-d", strtotime($tgl[0]));
            $todate = date("Y-m-d", strtotime($tgl[1]));
            $data = array('fromdate' => $fromdate,'todate' => $todate);
        }else{
            $data = array('fromdate' => null,'todate' => null);
        }
        echo json_encode($data);
    }

    function export_csv_penjualanartikel($fromdate, $todate, $source_data, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx){
        extract(populateform());

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        $filename = 'sales_by_artikel_report.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Type: application/csv;");

        $data['username']      = $this->input->cookie('cookie_invent_user');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA


        if($source_data !== "null"){
            $where.=" AND source_data = '".$source_data."'";
        }

        if($brand_code !== "null"){
            $where.=" AND brand_code = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        if($areatrx !== "null"){
            $arrAreatrx = explode(',', $areatrx);
            if(count($arrAreatrx) > 1){
                $where .= " AND substring(trans_no,9,1) in ('".$arrAreatrx[0]."', '".$arrAreatrx[1]."')";
            }
            else{
                $where .= " AND substring(trans_no,9,1) in ('".$areatrx."')";
            }
        }

        if($fromdate !== null AND $todate !== null){
            $where.= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '".$fromdate."' and '".$todate."'";
        }

        $data   = $this->db->query("SELECT branch_id, SUBSTRING(periode, 1, 10) as periode,SUBSTRING(periode, 6, 2) as bulan, DIVISION,SUB_DIVISION,tag_5,category_code,DEPT,SUB_DEPT,article_code,barcode,brand_code,brand_name,article_name,varian_option1,varian_option2,price,vendor_code,vendor_name, tot_qty,tot_berat, disc_pct,total_disc_amt,total_moredisc_amt,moredisc_pct,margin,gross_after_margin,gross,net_bf,net_af,trans_no as areatrx,source_data,trans_no, no_ref FROM r_sales where 1=1 $where order by periode")->result_array();
        $file   = fopen('php://output','w');

        $header = array('Store','Periode','Bulan','DIVISION','SUB DIVISION','Tipe Artikel','Kode Kategori','DEPT','SUB DEPT','Article Code','Barcode','Kode Brand','Nama Brand','Nama Produk','Varian Option1','Varian Option2','Harga','Kode Vendor','Nama Vendor','Total Qty(Pcs)','Total Berat(Kg)','Disc(%)','Total Disc','Disc. Tambahan(Rp)','Disc. Tambahan(%)','Margin','Gross After Margin','Gross(Rp)','Net Before(Rp)','Net After(Rp)','Area Transaksi','Source Data','Trans No','No Ref');

        fputcsv($file,$header);

        foreach($data as $key => $value){
            if(substr($value['areatrx'], 8,1) != '5'){
                $value['areatrx']= substr($value['areatrx'], 8,1) == '3' ? 'BAZZAR' : 'FLOOR';
            }
            else{
                $value['areatrx'] = '';
            }
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_csv_penjualanartikel_operation($fromdate, $todate, $source_data, $brand_code, $division, $sub_division, $dept, $sub_dept, $store, $areatrx){
        extract(populateform());

        $division       = str_replace("%20"," ",$division);
        $sub_division   = str_replace("%20"," ",$sub_division);
        $dept           = str_replace("%20"," ",$dept);
        $sub_dept       = str_replace("%20"," ",$sub_dept); 

        $filename = 'sales_by_artikel_report.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Type: application/csv;");

        $data['username']      = $this->input->cookie('cookie_invent_user');

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='".$data['username']."'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='".$data['username']."' and flagactv = '1' limit 1")->row();
        if($cek_user_category){
            $where = $this->M_Categories->get_category($data['username']);
        }else if($cek_user_site){
            $where = $this->M_Division->get_division($data['username'],$store);
        }else{
            // UNTUK MD
            $where = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '".$data['username']."'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if($source_data !== "null"){
            $where.=" AND source_data = '".$source_data."'";
        }

        if($brand_code !== "null"){
            $where.=" AND brand_code = '".$brand_code."'";
        }
        
        if($division !== "null"){
            $where.=" AND DIVISION = '".$division."'";
        }

        if($sub_division !== "null"){
            $where.=" AND SUB_DIVISION = '".$sub_division."'";
        }

        if($dept !== "null"){
            $where.=" AND DEPT = '".$dept."'";
        }

        if($sub_dept !== "null"){
            $where.=" AND SUB_DEPT = '".$sub_dept."'";
        }

        if($store !== "null"){
            $where.=" AND branch_id = '".$store."'";
        }

        if($areatrx !== "null"){
            $arrAreatrx = explode(',', $areatrx);
            if(count($arrAreatrx) > 1){
                $where .= " AND substring(trans_no,9,1) in ('".$arrAreatrx[0]."', '".$arrAreatrx[1]."')";
            }
            else{
                $where .= " AND substring(trans_no,9,1) in ('".$areatrx."')";
            }
        }

        if($fromdate !== null AND $todate !== null){
            $where.= " AND DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '".$fromdate."' and '".$todate."'";
        }

        $data   = $this->db->query("SELECT branch_id, SUBSTRING(periode, 1, 10) as periode,SUBSTRING(periode, 6, 2) as bulan, DIVISION,SUB_DIVISION,tag_5,category_code,DEPT,SUB_DEPT,article_code,barcode,brand_code,brand_name,article_name,varian_option1,varian_option2,price,vendor_code,vendor_name, tot_qty,tot_berat, disc_pct,total_disc_amt,total_moredisc_amt,moredisc_pct,margin,gross_after_margin,gross,net_bf,net_af,trans_no as areatrx,source_data,trans_no, no_ref FROM r_sales where 1=1 $where order by periode")->result_array();
        $file   = fopen('php://output','w');

        $header = array('Store','Periode','Bulan','DIVISION','SUB DIVISION','Tipe Artikel','Kode Kategori','DEPT','SUB DEPT','Article Code','Barcode','Kode Brand','Nama Brand','Nama Produk','Varian Option1','Varian Option2','Harga','Kode Vendor','Nama Vendor','Total Qty(Pcs)','Total Berat(Kg)','Disc(%)','Total Disc','Disc. Tambahan(Rp)','Disc. Tambahan(%)','Margin','Gross After Margin','Gross(Rp)','Net Before(Rp)','Net After(Rp)','Area Transaksi','Source Data','Trans No','No Ref');

        fputcsv($file,$header);

        foreach($data as $key => $value){
            if(substr($value['areatrx'], 8,1) != '5'){
                $value['areatrx']= substr($value['areatrx'], 8,1) == '3' ? 'BAZZAR' : 'FLOOR';
            }
            else{
                $value['areatrx'] = '';
            }
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }


}
