<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ApiGenExcel extends CI_Controller
{
    private $client;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_TokenOneD');
        $this->load->model('M_Supermarket');
        $this->client = new Client();
    }

    public function generate_excel()
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $query = "select b.code as barcode, mi.supplier_pname as article_name, mb.brand_name, mk.SUB_DIVISION, mk.DEPT, mk.SUB_DEPT, a.promo_id, promo_desc, tp.promo_name, date_format(start_date, '%d %M %Y') as start_date, start_time, date_format(end_date, '%d %M %Y') as end_date, end_time from t_promo_hdr a inner join t_promo_dtl b on a.promo_id = b.promo_id left JOIN m_codebar mc on b.code = mc.barcode left JOIN m_item_master mi on mc.article_number = mi.article_number LEFT JOIN m_brand mb on mb.brand_code = mi.brand LEFT JOIN m_kategori_list mk on mk.CATEGORY_CODE = mi.category_code LEFT JOIN t_promo_type tp on tp.promo_type = a.promo_type where status = 'S' and a.promo_type < 30 and aktif = '1' and date_format(date_add(curdate(),interval 0 day),'%y%m%d') between date_format(date_add(start_date ,interval 0 day),'%y%m%d') and date_format(date_add(end_date,interval 0 day),'%y%m%d') and a.branch_id = 'V001' and mi.branch_id = 'V001'";

        $data = $dbCentral->query($query)->result_array();

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */
        $sheet->setCellValue('A1', 'Barcode');
        $sheet->setCellValue('B1', 'Article Name');
        $sheet->setCellValue('C1', 'Brand');
        $sheet->setCellValue('D1', 'Division');
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
        $filename = 'coba2_' . date('m-d-Y_His');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('D:/upload/' . $filename . '.xlsx');
    }
    public function groSalesDaily()
    {
        $store = $this->input->get('storeid');
        $data = $this->M_Supermarket->getSalesDaily($store);
        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //$spreadsheet->getDefaultStyle()->getNumberFormat()->setFormatCode('#');

        $headerColumns = array_keys($data[0]);
        $columnIndex = 'A';
        $row = 1;

        foreach ($headerColumns as $columnName) {
            $sheet->setCellValue($columnIndex . $row, $columnName);
            $columnIndex = $this->incrementColumn($columnIndex);
        }
        // /* Excel Data */
        $row_number = 2;
        $lastRow = count($data) + $row_number;
        $spreadsheet->getActiveSheet()->getStyle('K' . $row_number . ':K' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('L' . $row_number . ':L' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('R' . $row_number . ':R' . $lastRow)->getNumberFormat()->setFormatCode('+#');
        $spreadsheet->getActiveSheet()->getStyle('AJ' . $row_number . ':AJ' . $lastRow)->getNumberFormat()->setFormatCode('#');
        foreach ($data as $key => $row) {
            $columnIndex2 = 'A';
            foreach ($headerColumns as $columnName) {
                $sheet->setCellValue($columnIndex2 . $row_number, $row[$columnName]);
                $columnIndex2 = $this->incrementColumn($columnIndex2);
            }
            $row_number++;
        }

        $writer = new Xlsx($spreadsheet);
        $prefixFn = $store == '03' ? 'HH-sales-article_' : 'RSMKG-sales-article_';
        $dirName = $store == '03' ? '/HH' : '/RSMKG';
        $filename = $prefixFn . date('d-m-Y', strtotime('-1 day'));
        $targetDir = rawurlencode('/STAR/RSHH'.$dirName);
        $accessToken = $this->M_TokenOneD->getAccessToken();

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('D:/upload/' . $filename . '.xlsx');

        $url = 'https://graph.microsoft.com/v1.0/me/drive/root:' . $targetDir . '/' . rawurlencode($filename. '.xlsx') . ':/content';
        $fileContent = file_get_contents('D:/upload/' . $filename . '.xlsx');

        $response = $this->uploadToOneDrive($url, $fileContent, $accessToken);

        // If the token was invalid, refresh the token and retry
        if ($response['status'] === 'InvalidAuthenticationToken') {
            $accessToken = $this->M_TokenOneD->refreshToken();
            if ($accessToken) {
                $response = $this->uploadToOneDrive($url, $fileContent, $accessToken);
            } else {
                echo json_encode(['error' => 'Failed to refresh token.']);
                return;
            }
        }

        // Only unlink the temporary file if the upload is successful
        if ($response['status'] === 'success') {
            unlink('D:/upload/' . $filename . '.xlsx'); // Delete the temporary file
        }       


        header('Content-Type: application/json');
        echo json_encode($data);

    }


    public function happyFreshSku()
    {
        $listItem = $this->getJsonFile('assortItem.json');
        $data = $this->M_Supermarket->getHappyFreshSKU($listItem);
        $dataSales = $this->M_Supermarket->getRealTimeSales('V001');
        $result = $this->subtractArrByBarcode($data, 'StoreStock', $dataSales);

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //$spreadsheet->getDefaultStyle()->getNumberFormat()->setFormatCode('#');
        $headerColumns = array_keys($result[0]);
        $columnIndex = 'A';
        $row = 1;

        foreach ($headerColumns as $columnName) {
            $sheet->setCellValue($columnIndex . $row, $columnName);
            $columnIndex = $this->incrementColumn($columnIndex);
        }
        /* Excel Data */
        $row_number = 2;
        $lastRow = count($data) + $row_number;
        $spreadsheet->getActiveSheet()->getStyle('B' . $row_number . ':B' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('C' . $row_number . ':C' . $lastRow)->getNumberFormat()->setFormatCode('#');
        foreach ($result as $key => $row) {
            $columnIndex2 = 'A';
            foreach ($headerColumns as $columnName) {
                $sheet->setCellValue($columnIndex2 . $row_number, $row[$columnName]);
                $columnIndex2 = $this->incrementColumn($columnIndex2);
            }
            $row_number++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'HappyFreshSKU' . date('d-m-Y_His');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('D:/upload/' . $filename . '.xlsx');

        header('Content-Type: application/json');
        echo json_encode($result);

    }

    public function trxKeyDisc()
    {
        $currentDate = new DateTime();
        $currentDate->modify('-1 week');
        // Get the year and week number for the date one week ago
        $lastWeekYear = $currentDate->format('o');
        $lastWeekNumber = $currentDate->format('W');
        // Create DateTime objects for the start and end of last week
        $startOfWeek = (new DateTime())->setISODate($lastWeekYear, $lastWeekNumber);
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');

        $data = $this->M_Supermarket->getTrxDiscByPeriod($startOfWeek->format("Y-m-d"), $endOfWeek->format("Y-m-d"));

        /* Spreadsheet Init */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //$spreadsheet->getDefaultStyle()->getNumberFormat()->setFormatCode('#');
        $headerColumns = array_keys($data[0]);
        $columnIndex = 'A';
        $row = 1;

        foreach ($headerColumns as $columnName) {
            $sheet->setCellValue($columnIndex . $row, $columnName);
            $columnIndex = $this->incrementColumn($columnIndex);
        }
        /* Excel Data */
        $row_number = 2;
        $lastRow = count($data) + $row_number;
        $spreadsheet->getActiveSheet()->getStyle('A' . $row_number . ':A' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('D' . $row_number . ':D' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('F' . $row_number . ':F' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('H' . $row_number . ':H' . $lastRow)->getNumberFormat()->setFormatCode('+#');
        $spreadsheet->getActiveSheet()->getStyle('I' . $row_number . ':I' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('J' . $row_number . ':J' . $lastRow)->getNumberFormat()->setFormatCode('#');
        $spreadsheet->getActiveSheet()->getStyle('K' . $row_number . ':K' . $lastRow)->getNumberFormat()->setFormatCode('#');
        foreach ($data as $key => $row) {
            $columnIndex2 = 'A';
            foreach ($headerColumns as $columnName) {
                $sheet->setCellValue($columnIndex2 . $row_number, $row[$columnName]);
                $columnIndex2 = $this->incrementColumn($columnIndex2);
            }
            $row_number++;
        }

        $writer = new Xlsx($spreadsheet);
        $suffixFn = $startOfWeek->format("m") != $endOfWeek->format("m") ? $startOfWeek->format("d M") . ' - ' . $endOfWeek->format("d M") : $startOfWeek->format("d") . ' - ' . $endOfWeek->format("d M");
        $filename = 'TrxKeyDisc ' . $endOfWeek->format("Y") . ', ' . $suffixFn;

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('D:/upload/' . $filename . '.xlsx');

        header('Content-Type: application/json');
        echo json_encode($data);

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
    private function subtractArrByBarcode($existingArray, $attribute, $newArray)
    {
        $existingArrayMap = [];
        foreach ($existingArray as $item) {
            if (array_key_exists('Barcode', $item)) {
                $existingArrayMap[$item['Barcode']] = $item;
            }
        }
        foreach ($newArray as $newItem) {
            $id = $newItem['Barcode'];
            $existingValue = isset($existingArrayMap[$id][$attribute]) ? (float)$existingArrayMap[$id][$attribute] : 0;
            $subtractValue = (float) $newItem['sales'];
            if (isset($existingArrayMap[$id])) {
                $newAttributeValue =  $existingValue - $subtractValue;
                $existingArrayMap[$id][$attribute] = max(0, $newAttributeValue);
            }
        }
        return array_values($existingArrayMap);
    }
    private function getJsonFile($file)
    {
        $jsonFilePath = FCPATH . 'assets/datahelper/' . $file;
        if (!file_exists($jsonFilePath)) {
            show_error('JSON file not found: ' . $jsonFilePath);
            return;
        }
        $jsonData = file_get_contents($jsonFilePath);

        // Decode the JSON data into a PHP array
        $dataArray = json_decode($jsonData, true);
        $listAssortItem = array();
        $key = 'Barcode';
        array_walk_recursive($dataArray, function ($value, $currentKey) use ($key, &$listAssortItem) {
            if ($currentKey === $key) {
                $listAssortItem[] = $value;
            }
        });

        return $listAssortItem;
    }

    private function uploadToOneDrive($url, $fileContent, $accessToken) {
        try {
            $response = $this->client->put($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/octet-stream',
                ],
                'body' => $fileContent
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            return ['status' => 'success', 'body' => $responseBody];
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorCode = $responseBody['error']['code'] ?? 'Unknown';
            if ($errorCode === 'InvalidAuthenticationToken') {
                return ['status' => 'InvalidAuthenticationToken', 'body' => $responseBody];
            }
            log_message('error', 'Upload failed: ' . $e->getMessage());
            return ['status' => 'error', 'body' => $responseBody];
        }
    }
}