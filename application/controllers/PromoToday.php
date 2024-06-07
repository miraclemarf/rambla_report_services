<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class PromoToday extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_PromoToday');
        $this->ceklogin();
    }

    public function index($store)
    {
        extract(populateform());
        $data['title']          = 'Rambla | Promo Hari Ini';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['store']          = $store;
        // echo $this->M_Categories->get_category('tessa');
        
 

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/promo-today', $data);
        $this->load->view('template_member/footer', $data);        
    }

    public function promo_today_list()
    {
        // POST data
        $postData = $this->input->post();
        // Get data
        $data = $this->M_PromoToday->getPromoToday($postData);
        echo json_encode($data);
    }
    function export_csv_promotoday()
    {
        extract(populateform());        

        $filename = 'promo_today_'.date("Ymd").'.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");

        $getData = $this->input->get();
        $columnName = $getData['columnName'];
        $columnSortOrder = $getData['columnSortOrder']; // asc or desc
        $searchValue = $getData['searchValue']; // Search value
        
        $promotype = $getData['promotype'] ? $getData['promotype'] : '';
        $ismember = $getData['ismember'] ? $getData['ismember'] : '';
        $brand = $getData['brand'] ? $getData['brand'] : '';
        $division = $getData['division'] ? $getData['division'] : '';
        $sub_division = $getData['sub_division'] ? $getData['sub_division'] : '';
        $dept = $getData['dept'] ? $getData['dept'] : '';
        $sub_dept = $getData['sub_dept'] ? $getData['sub_dept'] : '';
        $storeId = $getData['storeId'] ? $getData['storeId'] : '';
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $query = "select b.code as barcode, mi.supplier_pname as article_name, mb.brand_name, mk.SUB_DIVISION, mk.DEPT, mk.SUB_DEPT, a.promo_id, promo_desc, tp.promo_name, date_format(start_date, '%d %M %Y') as start_date, start_time, date_format(end_date, '%d %M %Y') as end_date, end_time from t_promo_hdr a inner join t_promo_dtl b on a.promo_id = b.promo_id left JOIN m_codebar mc on b.code = mc.barcode left JOIN m_item_master mi on mc.article_number = mi.article_number LEFT JOIN m_brand mb on mb.brand_code = mi.brand LEFT JOIN m_kategori_list mk on mk.CATEGORY_CODE = mi.category_code LEFT JOIN t_promo_type tp on tp.promo_type = a.promo_type where status = 'S' and a.promo_type < 30 and aktif = '1' and a.branch_id = '".$storeId."' and mi.branch_id = '".$storeId."' ";

        $whereClause = "";
        
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " and (b.code like '%" . $searchValue . "%' or mi.article_name like '%" . $searchValue . "%' or mb.brand_name like '%" . $searchValue . "%' or promo_desc like'%" . $searchValue . "%' ) ";
        }

        $whereClause = "";
        if($division != ''){
            $whereClause .= " and mk.DIVISION ='".$division."' ";
        }
        if($sub_division != ''){
            $whereClause .= " and mk.SUB_DIVISION ='".$sub_division."' ";
        }
        if($dept != ''){
            $whereClause .= " and mk.DEPT ='".$dept."' ";
        }
        if($sub_dept != ''){
            $whereClause .= " and mk.SUB_DEPT ='".$sub_dept."' ";
        }
        if($brand != ''){
            $whereClause .= " and mi.brand ='".$brand."' ";
        }
        if($ismember != ''){
            $whereClause .= " and a.ismember =".$ismember." ";
        }
        if($promotype != ''){
            $whereClause .= " and a.promo_type =".$promotype." ";
        }

        $orderBy = "ORDER BY ".$columnName." ".$columnSortOrder;
        $data = $dbCentral->query($query . $whereClause.$searchQuery . $orderBy)->result_array();

        $file = fopen('php://output', 'w');

        $header = array('Barcode', 'Article Name', 'Brand', 'Division', 'Category', 'Sub Category', 'Promo ID', 'Promo Description', 'Promo Type', 'Start Date', 'Start Time', 'End Date', 'End Time');

        fputcsv($file, $header);
        foreach ($data as $key => $value) {
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_excel_promotoday()
    {
        $getData = $this->input->get();
        $columnName = $getData['columnName'];
        $columnSortOrder = $getData['columnSortOrder']; // asc or desc
        $searchValue = $getData['searchValue']; // Search value
        
        $promotype = $getData['promotype'] ? $getData['promotype'] : '';
        $ismember = $getData['ismember'] ? $getData['ismember'] : '';
        $brand = $getData['brand'] ? $getData['brand'] : '';
        $division = $getData['division'] ? $getData['division'] : '';
        $sub_division = $getData['sub_division'] ? $getData['sub_division'] : '';
        $dept = $getData['dept'] ? $getData['dept'] : '';
        $sub_dept = $getData['sub_dept'] ? $getData['sub_dept'] : '';
        $storeId = $getData['storeId'] ? $getData['storeId'] : '';
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $data['username'] = $this->input->cookie('cookie_invent_user');

        $query = "select b.code as barcode, mi.supplier_pname as article_name, mb.brand_name, mk.SUB_DIVISION, mk.DEPT, mk.SUB_DEPT, a.promo_id, promo_desc, tp.promo_name, date_format(start_date, '%d %M %Y') as start_date, start_time, date_format(end_date, '%d %M %Y') as end_date, end_time from t_promo_hdr a inner join t_promo_dtl b on a.promo_id = b.promo_id left JOIN m_codebar mc on b.code = mc.barcode left JOIN m_item_master mi on mc.article_number = mi.article_number LEFT JOIN m_brand mb on mb.brand_code = mi.brand LEFT JOIN m_kategori_list mk on mk.CATEGORY_CODE = mi.category_code LEFT JOIN t_promo_type tp on tp.promo_type = a.promo_type where status = 'S' and a.promo_type < 30 and aktif = '1' and a.branch_id = '".$storeId."' and mi.branch_id = '".$storeId."' ";

        $whereClause = "";
        
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " and (b.code like '%" . $searchValue . "%' or mi.article_name like '%" . $searchValue . "%' or mb.brand_name like '%" . $searchValue . "%' or promo_desc like'%" . $searchValue . "%' ) ";
        }

        $whereClause = "";
        if($division != ''){
            $whereClause .= " and mk.DIVISION ='".$division."' ";
        }
        if($sub_division != ''){
            $whereClause .= " and mk.SUB_DIVISION ='".$sub_division."' ";
        }
        if($dept != ''){
            $whereClause .= " and mk.DEPT ='".$dept."' ";
        }
        if($sub_dept != ''){
            $whereClause .= " and mk.SUB_DEPT ='".$sub_dept."' ";
        }
        if($brand != ''){
            $whereClause .= " and mi.brand ='".$brand."' ";
        }
        if($ismember != ''){
            $whereClause .= " and a.ismember =".$ismember." ";
        }
        if($promotype != ''){
            $whereClause .= " and a.promo_type =".$promotype." ";
        }

        $orderBy = "ORDER BY ".$columnName." ".$columnSortOrder;
        $data = $dbCentral->query($query . $whereClause.$searchQuery . $orderBy)->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', 'Barcode');
        $sheet->setCellValue('B1', 'Article Name');
        $sheet->setCellValue('C1', 'Brand');
        $sheet->setCellValue('D1', 'Division');;
        $sheet->setCellValue('E1', 'Category');
        $sheet->setCellValue('F1', 'Sub Category');
        $sheet->setCellValue('G1', 'Promo ID');
        $sheet->setCellValue('H1', 'Promo Description');
        $sheet->setCellValue('I1', 'Promo Type');
        $sheet->setCellValue('J1', 'Start Date');
        $sheet->setCellValue('K1', 'Start Time');
        $sheet->setCellValue('L1', 'End Date');
        $sheet->setCellValue('M1', 'End Time');

        /* Excel Data */
        $row_number = 2;
        $maskDelType = '';
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $row_number, $row['barcode']);
            $sheet->setCellValue('B' . $row_number, $row['article_name']);
            $sheet->setCellValue('C' . $row_number, $row['brand_name']);
            $sheet->setCellValue('D' . $row_number, $row['SUB_DIVISION']);
            $sheet->setCellValue('E' . $row_number, $row['DEPT']);
            $sheet->setCellValue('F' . $row_number, $row['SUB_DEPT']);
            $sheet->setCellValue('G' . $row_number, $row['promo_id']);
            $sheet->setCellValue('H' . $row_number, $row['promo_desc']);
            $sheet->setCellValue('I' . $row_number, $row['promo_name']);
            $sheet->setCellValue('J' . $row_number, $row['start_date']);
            $sheet->setCellValue('K' . $row_number, $row['start_time']);
            $sheet->setCellValue('L' . $row_number, $row['end_date']);
            $sheet->setCellValue('M' . $row_number, $row['end_time']);
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'promo_today_'.date("Ymd");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

}
