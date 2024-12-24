<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SetRedis extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('dbcentral', TRUE);
        $this->load->model('M_Stock');
    }

    public function list()
    {
        $d = $this->M_Stock->test();
    }
}
