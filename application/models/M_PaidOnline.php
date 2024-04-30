<?php 

class M_PaidOnline extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getPaidOnline($postData = null)
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
        
        $store = $postData['store'] ? $postData['store'] : '';
        $date = $postData['params3'] ? $postData['params3'] : '';
        $deltype = $postData['deltype'] ? $postData['deltype'] : '';
        $paytype = $postData['paytype'] ? $postData['paytype'] : '';
        
        
        $query = "SELECT distinct a.trans_date, CASE WHEN ( substr( a.trans_no, 7, 2 ) = '01' ) THEN 'R001' WHEN ( substr( a.trans_no, 7, 2 ) = '02' ) THEN 'R002' WHEN ( substr( a.trans_no, 7, 2 ) = '03' ) THEN 'V001' END  AS branch_id, a.trans_no, a.no_ref, a.delivery_type, a.delivery_number, tp.seq, tp.mop_code, CASE left(tp.mop_code,2) when 'VA' THEN 'Virtual Account' WHEN 'VC' THEN 'Voucher' WHEN 'PP' THEN 'Point' WHEN 'CC' THEN 'Credit Card' WHEN 'CP' THEN 'Coupon' ELSE description end mop_name, card_name, tp.paid_amount FROM t_sales_trans_hdr a LEFT JOIN t_paid tp on tp.trans_no = a.trans_no LEFT JOIN m_mop mm on mm.mop_code = tp.mop_code where a.trans_status = '1' and substr( a.trans_no, 9, 1 )  = '5' ";
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (emp_name like '%" . $searchValue . "%' or email like '%" . $searchValue . "%' or city like'%" . $searchValue . "%' ) ";
        }

        $whereClause = "";
        if($store != ''){
            $store = $store == "V001" ? "03" : substr($store, -2);
            $whereClause .= " and substr( a.trans_no, 7, 2 ) ='".$store."' ";
        }
        if($date != ''){
            if (strpos($date, '-') !== false) {
                $tgl = explode("-", $date);
                $fromdate = date("Y-m-d", strtotime($tgl[0]));
                $todate = date("Y-m-d", strtotime($tgl[1]));
            }
            $whereClause .= " AND DATE_FORMAT(a.trans_date,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }
        if($deltype != ''){
            $whereClause .= " and a.delivery_type ='".$deltype."' ";
        }
        if($paytype != ''){
            if($paytype != 'VA' || $paytype != 'VC' || $paytype != 'PP' || $paytype != 'CC' || $paytype != 'CP'){
                
                $whereClause .= " and mm.description ='".$paytype."' ";
            }
            else{
                $whereClause .= " and left(tp.mop_code,2) ='".$paytype."' ";
            }
        }

        $orderBy = "order by a.trans_date desc ";

        ## Total number of records without filtering
        //$dbCentral->select('count(*) as allcount');
        //$records = $dbCentral->query($query)->result();
        ## Total number of record with filtering
        //$dbCentral->select('count(*) as allcount');
        // if ($whereClause != ''){
        // //$records = $this->dbCentral->query($query)->result();
        //     $totalRecordwithFilter = $dbCentral->query($query.$whereClause)->num_rows();
        // }c
        
        
        $totalRecords = $dbCentral->query($query.$whereClause)->num_rows();

        ## Fetch records
        //$dbCentral->select('*');
        if ($searchQuery != ''){
            $dbCentral->where($searchQuery);
        }
        $totalRecordwithFilter = $dbCentral->query($query.$whereClause)->num_rows();
        // $dbCentral->order_by($columnName, $columnSortOrder);
        $limitStart = ' LIMIT ' . $rowperpage . ' OFFSET ' . $start;
        $records = $dbCentral->query($query.$whereClause.$orderBy.$limitStart)->result();

        //var_dump($query.$whereClause.$limitStart);
        $data = array();
        foreach ($records as $record) {

            $data[] = array(
                "trans_date" => $record->trans_date,
                "branch_id" => $record->branch_id,
                "trans_no" => $record->trans_no,
                "no_ref" => $record->no_ref,
                "delivery_type" => $record->delivery_type,
                "delivery_number" => $record->delivery_number,
                "seq" => $record->seq,
                "mop_code" => $record->mop_code,
                "mop_name" => $record->mop_name,
                "card_name" => $record->card_name,
                "paid_amount" => $record->paid_amount
            );
        }

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