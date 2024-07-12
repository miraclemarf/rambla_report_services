<?php

class M_UpdatePrice extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getUpdatePrice($postData = null, $nonOffset = false)
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $response = array();
        
        $store = $postData['store'];
        $brand = $postData['brand'] ? $postData['brand'] : '';
        $division = $postData['division'] ? $postData['division'] : '';
        $sub_division = $postData['sub_division'] ? $postData['sub_division'] : '';
        $dept = $postData['dept'] ? $postData['dept'] : '';
        $sub_dept = $postData['sub_dept'] ? $postData['sub_dept'] : '';
        $price_status = $postData['price_status'] ? $postData['price_status'] : '';
        $draw = !$nonOffset ? $postData['draw'] : '';
        $start = !$nonOffset ? $postData['start'] : '';
        $rowperpage = !$nonOffset ? $postData['length'] : ''; // Rows display per page
        $columnIndex = !$nonOffset ? $postData['order'][0]['column'] : ''; // Column index
        $columnName = !$nonOffset ? $postData['columns'][$columnIndex]['data'] : $postData['columnName']; // Column name
        $columnSortOrder = !$nonOffset ? $postData['order'][0]['dir'] : $postData['columnSortOrder']; // asc or desc
        $searchValue = !$nonOffset ? $postData['search']['value'] : $postData['searchValue']; // Search value

        //$storeId = $postData['storeId'] ? $postData['storeId'] : '';

        $query = "select trans_no, a.branch_id, a.article_code, a.article_number, barcode, a.category_code, mkl.DIVISION, mkl.SUB_DIVISION, mkl.DEPT, mkl.SUB_DEPT, a.brand, br.brand_name, article_name, varian_option1, varian_option2, old_price, new_price, case when old_price > new_price then 'Turun' when new_price > old_price then 'Naik' else 'Tetap' end status_price, (new_price - old_price) diff, effective_date, create_time from m_item_master a left join t_price_change b on a.article_number = b.article_number and a.branch_id = b.branch_id LEFT JOIN m_kategori_list mkl on mkl.CATEGORY_CODE = a.category_code LEFT JOIN m_brand br on br.brand_code = a.brand where a.branch_id = '".$store."' and trans_no is not null and new_price != old_price and b.`status` = '1' and date_format(DATE_ADD(effective_date, INTERVAL 0 DAY),'%Y.%m.%d') between date_format(DATE_ADD(curdate(), INTERVAL 0 DAY),'%Y.%m.%d') and date_format(DATE_ADD(curdate(), INTERVAL 1 DAY),'%Y.%m.%d') ";
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " and (
            trans_no like '%" . $searchValue . "%' 
            or a.article_code like '%" . $searchValue . "%' 
            or a.article_number like '%" . $searchValue . "%' 
            or barcode like'%" . $searchValue . "%' 
            or a.category_code like'%" . $searchValue . "%' 
            or br.brand_name like'%" . $searchValue . "%' 
            or article_name like'%" . $searchValue . "%'
            
            ) ";
        }
        $whereClause = "";
        if($division != ''){
            $whereClause .= " and mkl.DIVISION ='".$division."' ";
        }
        if($sub_division != ''){
            $whereClause .= " and mkl.SUB_DIVISION ='".$sub_division."' ";
        }
        if($dept != ''){
            $whereClause .= " and mkl.DEPT ='".$dept."' ";
        }
        if($sub_dept != ''){
            $whereClause .= " and mkl.SUB_DEPT ='".$sub_dept."' ";
        }
        if($brand != ''){
            $whereClause .= " and a.brand ='".$brand."' ";
        }        
        if($price_status != ''){
            $whereClause .= " and new_price ". $price_status ." old_price ";
        }
        $orderBy = "ORDER BY " . $columnName . " " . $columnSortOrder;
        $totalRecords = $dbCentral->query($query . $whereClause)->num_rows();
        $totalRecordwithFilter = $dbCentral->query($query . $whereClause . $searchQuery)->num_rows();
        $limitStart = '';
        if (!$nonOffset) {
            $limitStart = ' LIMIT ' . $rowperpage . ' OFFSET ' . $start;
        }
        $records = $dbCentral->query($query . $whereClause . $searchQuery . $orderBy . $limitStart);

        $data = $records->result_array();

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }

}