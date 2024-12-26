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
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        parent::__construct();
        $this->load->database('dbcentral', TRUE);
        $this->load->model('M_Stock');
        $this->load->library('redislib');
    }

    public function flush_redis_db()
    {
        // Flush the Redis database
        if ($this->redislib->flush_db()) {
            echo "Redis database flushed successfully!";
        } else {
            echo "Failed to flush Redis database.";
        }
    }

    public function list_stock()
    {
        $time_start = microtime(true);
        $toko = $this->db->query("SELECT DISTINCT branch_id FROM report_service.r_s_item_stok")->result();
        foreach ($toko as $row) {
            $postData = array(
                'draw'         => 1,
                'start'        => 0,
                'length'       => 10,
                'search'       => array('value' => null),
                'params1'      => null,
                'params2'      => null,
                'params3'      => null,
                'params4'      => null,
                'params5'      => null,
                'params6'      => $row->branch_id,
                'params7'      => null,
                'params8'      => null,
            );
            $this->M_Stock->getListStock($postData);
            sleep(10);
            // $division = $this->db->query("SELECT DISTINCT DIVISION FROM report_service.r_s_item_stok where branch_id ='" . $row->branch_id . "'")->result();

            // foreach ($division as $row2) {
            //     $postData = array(
            //         'draw'         => 1,
            //         'start'        => 0,
            //         'length'       => 10,
            //         'search'       => array('value' => null),
            //         'params1'      => null,
            //         'params2'      => $row2->DIVISION,
            //         'params3'      => null,
            //         'params4'      => null,
            //         'params5'      => null,
            //         'params6'      => $row->branch_id,
            //         'params7'      => null,
            //         'params8'      => null,
            //     );
            //     $this->M_Stock->getListStock($postData);
            // }
        }
        echo "\n\nTotal execution time (in seconds): " . (microtime(true) - $time_start);
    }
}
