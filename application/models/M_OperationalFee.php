<?php

class M_OperationalFee extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getOperationalFee($postData = null)
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
        // $deltype = $postData['deltype'] ? $postData['deltype'] : '';
        // $paytype = $postData['paytype'] ? $postData['paytype'] : '';
        $kode = "";

        if ($store != '') {
            if ($store == "R001") {
                $kode = "01";
            } else if ($store == "R002") {
                $kode = "02";
            } else if ($store == "V001") {
                $kode = "03";
            } else if ($store == "S002") {
                $kode = "04";
            } else if ($store == "S003") {
                $kode = "05";
            } else if ($store == "V002") {
                $kode = "06";
            } else if ($store == "V003") {
                $kode = "07";
            }
        }

        $whereClause = "";

        if ($date != '') {
            if (strpos($date, '-') !== false) {
                $tgl = explode("-", $date);
                $fromdate = date("Y-m-d", strtotime($tgl[0]));
                $todate = date("Y-m-d", strtotime($tgl[1]));
            }
            $whereClause .= " AND DATE_FORMAT(trans_date,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }



        $query = "SELECT row_number() over() No, sales.vendor_code, vendor_name, brand_code, 
        sum(net_bf_floor)net_floor, sum(net_bf_bazzar)nett_bazzar, 
        sum(gross_bf_floor)gross_floor, sum(gross_bf_bazzar)gross_bazzar,
        ifnull(ops_fee,0)ops_fee, 
        ifnull(sum((gross_bf_floor*ops_fee)/100),0)TotalOpsFee
        from (
            select date_format(trans_date , '%Y.%m') bulan, 
            round(sum(td.net_prc),0) net_af_floor, 0 net_af_bazzar,
            round(sum(td.net_all),0) net_bf_floor, 0 net_bf_bazzar,    
            round(sum(td.gross_BF),0) gross_bf_floor, 0 gross_bf_bazzar,
            td.brand, mim.vendor_code
            from t_sales_trans_hdr th inner join 
            (select *, case when flag_tax in(1) then net_price / 1.11				
                          else net_price
                      end as net_prc, 	
                          
                  case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price / 1.11	
                        when '1' then (net_price + (fee * -1))/ 1.11
                        when '2' then (net_price - fee)/ 1.11
                    end		
                    else 		
                      case when flag_tax in(1)  then net_price / 1.11	
                          else net_price
                      end	
                    end as net_all,
                    case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price	
                        when '1' then net_price + (fee * -1)
                        when '2' then net_price - fee
                    end		
                    else net_price 
                    end as gross_BF
                    from t_sales_trans_dtl
            ) td on th.trans_no = td.trans_no left join m_codebar mc on td.barcode = mc.barcode
            left join m_item_master mim on mc.article_number = mim.article_number and mim.branch_id = (CASE
            WHEN substring(th.trans_no,7,2) = '01' THEN 'R001'
            WHEN substring(th.trans_no,7,2) = '02' THEN 'R002'
            WHEN substring(th.trans_no,7,2) = '03' THEN 'V001' 
            WHEN substring(th.trans_no,7,2) = '04' THEN 'S002'
            WHEN substring(th.trans_no,7,2) = '05' THEN 'S003'
            WHEN substring(th.trans_no,7,2) = '06' THEN 'V002'
            WHEN substring(th.trans_no,7,2) = '07' THEN 'V003'
            END)
            where trans_status in ('1') and td.category_code != 'RSOTMKVC01' $whereClause 
            and substring(th.trans_no,7,2) = '$kode'
            and substring(th.trans_no,9,1) in ('0','1','2','5') 
            group by bulan, td.brand, mim.vendor_code		
            union all 
            select date_format(trans_date , '%Y.%m') bulan, 
            0 net_af_floor, round(sum(td.net_prc),0) net_af_bazzar, 
            0 net_bf_floor, round(sum(td.net_all),0) net_bf_bazzar, 
            0 gross_bf_floor, round(sum(td.gross_BF),0) gross_bf_bazzar,
            td.brand, mim.vendor_code
            from t_sales_trans_hdr th inner join 
            (select *, case when flag_tax in(1) then net_price / 1.11				
                          else net_price
                      end as net_prc, 	
                          
                  case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price / 1.11	
                        when '1' then (net_price + (fee * -1))/ 1.11
                        when '2' then (net_price - fee)/ 1.11
                    end		
                    else 		
                      case when flag_tax in(1)  then net_price / 1.11	
                          else net_price
                      end	
                    end as net_all,
                    case flag_flexi when 1 then 		
                    case type_flex when '0' then net_price	
                        when '1' then net_price + (fee * -1)
                        when '2' then net_price - fee
                    end		
                    else net_price 
                    end as gross_BF
                    from t_sales_trans_dtl
            ) td on th.trans_no = td.trans_no left join m_codebar mc on td.barcode = mc.barcode
            left join m_item_master mim on mc.article_number = mim.article_number and mim.branch_id = (CASE
            WHEN substring(th.trans_no,7,2) = '01' THEN 'R001'
            WHEN substring(th.trans_no,7,2) = '02' THEN 'R002'
            WHEN substring(th.trans_no,7,2) = '03' THEN 'V001' 
            WHEN substring(th.trans_no,7,2) = '04' THEN 'S002'
            WHEN substring(th.trans_no,7,2) = '05' THEN 'S003'
            WHEN substring(th.trans_no,7,2) = '06' THEN 'V002'
            WHEN substring(th.trans_no,7,2) = '07' THEN 'V003'
            END)
            where trans_status in ('1') and td.category_code != 'RSOTMKVC01' $whereClause    
            and substring(th.trans_no,7,2) = '$kode' 
            and substring(th.trans_no,9,1) in ('3') 
            group by bulan, td.brand, mim.vendor_code    
        )sales left join (
            SELECT mc.vendor_code, brand_code, vendor_name, ops_fee
            FROM m_margin_code mc left join m_vendor mv on mc.vendor_code = mv.vendor_code
            where mc.branch_id = '" . $store . "'
        ) Margin on sales.brand = Margin.brand_code and sales.vendor_code = Margin.vendor_code  
        group by sales.vendor_code, vendor_name, brand_code, ops_fee
        ";
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (vendor_code like '%" . $searchValue . "%' or vendor_name like '%" . $searchValue . "%' or brand_code like'%" . $searchValue . "%' ) ";
        }


        $orderBy = "";

        $totalRecords = $dbCentral->query($query)->num_rows();

        ## Fetch records
        //$dbCentral->select('*');
        if ($searchQuery != '') {
            $dbCentral->where($searchQuery);
        }
        $totalRecordwithFilter = $dbCentral->query($query)->num_rows();
        // $dbCentral->order_by($columnName, $columnSortOrder);
        $limitStart = ' LIMIT ' . $rowperpage . ' OFFSET ' . $start;
        $records = $dbCentral->query($query . $orderBy . $limitStart)->result();

        //var_dump($query.$whereClause.$limitStart);
        $data = array();
        foreach ($records as $record) {

            $data[] = array(
                "vendor_code" => $record->vendor_code,
                "vendor_name" => $record->vendor_name,
                "brand_code" => $record->brand_code,
                "net_floor" => $record->net_floor,
                "nett_bazzar" => $record->nett_bazzar,
                "gross_floor" => $record->gross_floor,
                "gross_bazzar" => $record->gross_bazzar,
                "ops_fee" => $record->ops_fee,
                "TotalOpsFee" => $record->TotalOpsFee
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
