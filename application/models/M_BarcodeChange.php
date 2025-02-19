<?php

class M_BarcodeChange extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getBarcodeChange($postData = null, $nonOffset = false)
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $response = array();
        
        $store = $postData['store'];
        $brand = $postData['brand'] ? $postData['brand'] : '';
        $division = $postData['division'] ? $postData['division'] : '';
        $sub_division = $postData['sub_division'] ? $postData['sub_division'] : '';
        $dept = $postData['dept'] ? $postData['dept'] : '';
        $sub_dept = $postData['sub_dept'] ? $postData['sub_dept'] : '';
        $draw = !$nonOffset ? $postData['draw'] : '';
        $start = !$nonOffset ? $postData['start'] : '';
        $rowperpage = !$nonOffset ? $postData['length'] : ''; // Rows display per page
        $columnIndex = !$nonOffset ? $postData['order'][0]['column'] : ''; // Column index
        $columnName = !$nonOffset ? $postData['columns'][$columnIndex]['data'] : $postData['columnName']; // Column name
        $columnSortOrder = !$nonOffset ? $postData['order'][0]['dir'] : $postData['columnSortOrder']; // asc or desc
        $searchValue = !$nonOffset ? $postData['search']['value'] : $postData['searchValue']; // Search value


        $query = "SELECT b.trans_no, a.branch_id, a.article_code, a.article_number, a.brand, a.category_code, a.DIVISION, a.SUB_DIVISION, a.DEPT, a.SUB_DEPT,  a.article_name, a.varian_option1, a.varian_option2, b.old_barcode, b.new_barcode, b.effective_date FROM v_full_list_master_item a left join t_codebar_change b on a.article_number = b.article_number WHERE a.branch_id = '".$store."' and old_barcode != new_barcode and b.`status` = '1' and trans_no is not null and date_format(DATE_ADD(effective_date, INTERVAL 0 DAY),'%Y.%m.%d') between date_format(DATE_ADD(curdate(), INTERVAL 0 DAY),'%Y.%m.%d') and date_format(DATE_ADD(curdate(), INTERVAL 1 DAY),'%Y.%m.%d') ";
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " and (
            trans_no like '%" . $searchValue . "%' 
            or a.article_code like '%" . $searchValue . "%' 
            or a.article_number like '%" . $searchValue . "%' 
            or old_barcode like'%" . $searchValue . "%' 
            or new_barcode like'%" . $searchValue . "%' 
            or a.category_code like'%" . $searchValue . "%' 
            or a.brand like'%" . $searchValue . "%' 
            or aa.rticle_name like'%" . $searchValue . "%'            
            ) ";
        }
        $whereClause = "";
        if($division != ''){
            $whereClause .= " and a.DIVISION ='".$division."' ";
        }
        if($sub_division != ''){
            $whereClause .= " and a.SUB_DIVISION ='".$sub_division."' ";
        }
        if($dept != ''){
            $whereClause .= " and a.DEPT ='".$dept."' ";
        }
        if($sub_dept != ''){
            $whereClause .= " and a.SUB_DEPT ='".$sub_dept."' ";
        }
        if($brand != ''){
            $whereClause .= " and a.brand ='".$brand."' ";
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