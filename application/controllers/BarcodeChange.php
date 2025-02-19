<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class BarcodeChange extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_BarcodeChange');
        //$this->ceklogin();
    }

    public function index()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Perubahan Barcode Hari Ini dan Besok';
        $data['username']       = $this->input->cookie('cookie_invent_user');
        // echo $this->M_Categories->get_category('tessa');
        
 

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('laporan/perubahan-barcode', $data);
        $this->load->view('template_member/footer', $data);        
    }

    public function barcode_change_list()
    {
        // POST data
        $postData = $this->input->post();
        // Get data
        $nonOffset = $postData['isAll'] == '1' ? true : false;
        $data = $this->M_BarcodeChange->getBarcodeChange($postData, $nonOffset);
        echo json_encode($data);
    }
    function export_csv_update_price()
    {
        extract(populateform());        

        $filename = 'barcode_change_'.date("Ymd").'.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv;");
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $getData = $this->input->get();
        $nonOffset = $getData['isAll'] == '1' ? true : false;
        $dataModel = $this->M_UpdatePrice->getUpdatePrice($getData, $nonOffset);
        $data = $dataModel['aaData'];

        $file = fopen('php://output', 'w');

        $header = array('No Trx', 'Store', 'Article Code', 'Article Number', 'Barcode',  'Category Code', 'Division', 'Sub-Division', 'Dept', 'Sub-Dept', 'Brand Code', 'Brand Name', 'Article Name', 'Varian Opt 1', 'Varian Opt 2', 'Harga Lama', 'Harga Baru', 'Remark', 'Selisih', 'Effective Date', 'Created Date');

        fputcsv($file, $header);
        foreach ($data as $key => $value) {
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    function export_excel_update_price()
    {
        $getData = $this->input->get();
        $nonOffset = $getData['isAll'] == '1' ? true : false;
        $dataModel = $this->M_UpdatePrice->getUpdatePrice($getData, $nonOffset);
        $data = $dataModel['aaData'];
        //$data['username'] = $this->input->cookie('cookie_invent_user');

        
        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $header = array('No Trx', 'Store', 'Article Code', 'Article Number', 'Barcode',  'Category Code', 'Division', 'Sub-Division', 'Dept', 'Sub-Dept', 'Brand Code', 'Brand Name', 'Article Name', 'Varian Opt 1', 'Varian Opt 2', 'Harga Lama', 'Harga Baru', 'Remark', 'Selisih', 'Effective Date', 'Created Date');
        $columnIndex = 'A';
        foreach ($header as $columnName) {
            $sheet->setCellValue($columnIndex .'1', $columnName);
            $columnIndex = $this->incrementColumn($columnIndex);
        }

        /* Excel Data */
        $headerColumns = array_keys($data[0]);
        $row_number = 2;
        $lastRow = count($data) + $row_number;
        $spreadsheet->getActiveSheet()->getStyle('C' . $row_number . ':C' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('D' . $row_number . ':D' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('E' . $row_number . ':E' . $lastRow)->getNumberFormat()->setFormatCode('#');
        foreach ($data as $key => $row1) {
            $columnIndex2 = 'A';
            foreach ($headerColumns as $columnName) {
                $sheet->setCellValue($columnIndex2 . $row_number, $row1[$columnName]);
                $columnIndex2 = $this->incrementColumn($columnIndex2);
            }
            $row_number++;
        }

        /* Excel File Format */
        $writer = new Xlsx($spreadsheet);
        $filename = 'barcode_change_'.date("Ymd");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    private function incrementColumn($currentColumn)
    {
        $length = strlen($currentColumn);
        $index = $length - 1;

        while ($index >= 0) {
            if ($currentColumn[$index] === 'Z') {
                $currentColumn[$index] = 'A';
                $index--;
            } else {
                $currentColumn[$index] = chr(ord($currentColumn[$index]) + 1);
                return $currentColumn;
            }
        }

        // If we have reached this point, it means all characters were 'Z'
        return 'A' . $currentColumn;
    }

}
