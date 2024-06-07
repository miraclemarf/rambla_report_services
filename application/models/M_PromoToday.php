<?php 

class M_PromoToday extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getPromoToday($postData = null)
    {        
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $response = array();        

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        
        $promotype = $postData['promotype'] ? $postData['promotype'] : '';
        $ismember = $postData['ismember'] ? $postData['ismember'] : '';
        $brand = $postData['brand'] ? $postData['brand'] : '';
        $division = $postData['division'] ? $postData['division'] : '';
        $sub_division = $postData['sub_division'] ? $postData['sub_division'] : '';
        $dept = $postData['dept'] ? $postData['dept'] : '';
        $sub_dept = $postData['sub_dept'] ? $postData['sub_dept'] : '';
        $storeId = $postData['storeId'] ? $postData['storeId'] : '';
        
        
        $query = "select b.code as barcode, mi.supplier_pname as article_name, mb.brand_name, mk.SUB_DIVISION, mk.DEPT, mk.SUB_DEPT, a.promo_id, promo_desc, tp.promo_name, date_format(start_date, '%d %M %Y') as start_date, start_time, date_format(end_date, '%d %M %Y') as end_date, end_time from t_promo_hdr a inner join t_promo_dtl b on a.promo_id = b.promo_id left JOIN m_codebar mc on b.code = mc.barcode left JOIN m_item_master mi on mc.article_number = mi.article_number LEFT JOIN m_brand mb on mb.brand_code = mi.brand LEFT JOIN m_kategori_list mk on mk.CATEGORY_CODE = mi.category_code LEFT JOIN t_promo_type tp on tp.promo_type = a.promo_type where status = 'S' and a.promo_type < 30 and aktif = '1' and a.branch_id = '".$storeId."' and mi.branch_id = '".$storeId."' ";
        ## Search 
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
        
        $totalRecords = $dbCentral->query($query.$whereClause)->num_rows();
        $totalRecordwithFilter = $dbCentral->query($query.$whereClause.$searchQuery)->num_rows();
        $limitStart = ' LIMIT ' . $rowperpage . ' OFFSET ' . $start;
        $records = $dbCentral->query($query.$whereClause.$searchQuery.$orderBy.$limitStart);

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