<?php

class M_Stock extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('redislib');
        $this->load->model('M_Categories');
        $this->load->model('M_Division');
    }

    public function test()
    {
        $cursor = 0;
        $allKeys = [];
        $allKeys = $this->redislib->keys('*');
        print_r($allKeys);
    }

    public function getListStock($postData = null)
    {
        $response = array();
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $brand_code = $postData['params1'] ? " AND brand_code = '" . $postData['params1'] . "'"  : '';
        $division = $postData['params2'] ? " AND DIVISION = '" . str_replace("%20", " ", $postData['params2']) . "'" : '';
        $sub_division = $postData['params3'] ? "AND SUB_DIVISION = '" . str_replace("%20", " ", $postData['params3']) . "'" : '';
        $dept = $postData['params4'] ? "AND DEPT = '" . str_replace("%20", " ", $postData['params4']) . "'" : '';
        $sub_dept = $postData['params5'] ? "AND SUB_DEPT = '" . str_replace("%20", " ", $postData['params5']) . "'" : '';
        $store = $postData['params6'] ? "AND branch_id = '" . $postData['params6'] . "'" : '';
        $uom = $postData['params7'] ? ($postData['params7'] == "pcs" ? " AND tag_5 in ('TIMBANG') is not true" : " AND tag_5 in ('TIMBANG')") : '';
        $article_status = $postData['params8'] ? "AND status_article = '" . $postData['params8'] . "'" : '';
        // $deltype = $postData['deltype'] ? $postData['deltype'] : '';
        // $paytype = $postData['paytype'] ? $postData['paytype'] : '';
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $whereClause = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $whereClause = $this->M_Division->get_division($data['username'], $postData['params6']);
        } else {
            // UNTUK MD
            $whereClause = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        $whereClause .= $brand_code . $division . $sub_division . $dept . $sub_dept . $store . $uom . $article_status;

        $cache_key_list = "getListStock_{$start}_length_{$rowperpage}_search_" . md5($whereClause);
        $cached_data = $this->redislib->get($cache_key_list); // Try to fetch cached data

        if ($cached_data) {
            return json_decode($cached_data, true);
        }

        $query = "SELECT * FROM r_s_item_stok WHERE 1=1 $whereClause";
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (vendor_code like '%" . $searchValue . "%' or vendor_name like '%" . $searchValue . "%' or brand_code like'%" . $searchValue . "%' ) ";
        }

        $orderBy = "";

        $totalRecords = $this->db->query($query)->num_rows();

        ## Fetch records
        //$this->db->select('*');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $totalRecordwithFilter = $this->db->query($query)->num_rows();
        // $this->db->order_by($columnName, $columnSortOrder);
        $limitStart = ' LIMIT ' . $rowperpage . ' OFFSET ' . $start;
        $records = $this->db->query($query . $orderBy . $limitStart)->result();

        //var_dump($query.$whereClause.$limitStart);
        $data = array();
        foreach ($records as $record) {

            $data[] = array(
                "id"                => $record->id,
                "branch_id"         => $record->branch_id,
                "periode"           => $record->periode,
                "year"              => $record->year,
                "month"             => $record->month,
                "category_code"     => $record->category_code,
                "vendor_code"       => $record->vendor_code,
                "vendor_name"       => $record->vendor_name,
                "DIVISION"          => $record->DIVISION,
                "SUB_DIVISION"      => $record->SUB_DIVISION,
                "DEPT"              => $record->DEPT,
                "SUB_DEPT"          => $record->SUB_DEPT,
                "brand_code"        => $record->brand_code,
                "brand_name"        => $record->brand_name,
                "barcode"           => $record->barcode,
                "article_number"    => $record->article_number,
                "sku_code"          => $record->sku_code,
                "article_code"      => $record->article_code,
                "article_name"      => $record->article_name,
                "varian_option1"    => $record->varian_option1,
                "varian_option2"    => $record->varian_option2,
                "tag_5"             => $record->tag_5,
                "first_stock"       => $record->first_stock,
                "receipt"           => $record->receipt,
                "issue"             => $record->issue,
                "sales"             => $record->sales,
                "refund"            => $record->refund,
                "adj_in"            => $record->adj_in,
                "adj_out"           => $record->adj_out,
                "transfer_in"       => $record->transfer_in,
                "transfer_out"      => $record->transfer_out,
                "last_stock"        => $record->last_stock,
                "import_date"       => $record->import_date,
                "current_price"     => $record->current_price,
                "publish"           => $record->publish,
                "isactive"          => $record->isactive,
                "status_article"    => $record->status_article
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        $cache_key_export = "getExportStock_search_" . md5($whereClause);

        $this->redislib->set($cache_key_list, json_encode($response));
        $this->redislib->set($cache_key_export, json_encode($this->db->query($query)->result()));

        return $response;
    }

    // public function getListStock($postData = null)
    // {
    //     // Check if cached data exists
    //     if ($this->redislib->exists($this->redis_key_list)) {
    //         $data = json_decode($this->redislib->get($this->redis_key_list), true);

    //         // Use array_filter with dynamic conditions
    //         $filteredData = array_filter($data, function ($item) use ($postData) {
    //             foreach ($postData as $key => $value) {
    //                 if (isset($item[$key]) && $item[$key] !== $value) {
    //                     return false;
    //                 }
    //             }
    //             return true;
    //         });
    //         // UNTUK REINDEX ARRAY DARI 0
    //         return array_values($filteredData);
    //     }
    //     return $this->setListStock();
    // }


    // public function setListStock()
    // {
    //     $dbCentral = $this->load->database('dbcentral', TRUE);
    //     $dbCentral->select([
    //         '*'
    //     ]);
    //     $dbCentral->from('v_full_s_item_stok');

    //     $query = $dbCentral->get();
    //     $result = $query->result_array();

    //     // Store the entire list in Redis cache
    //     $this->redislib->set($this->redis_key_list, json_encode($result));

    //     return $result;
    // }
}
