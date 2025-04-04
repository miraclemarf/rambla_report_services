<?php

class M_Sales extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('redislib');
    }

    public function getPenjualanBrand($postData = null)
    {
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        $response = array();

        $draw = $postData['draw'] ? $postData['draw'] : 0;
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $store = $postData['params8'] ? $postData['params8'] : '';
        $date = $postData['params3'] ? $postData['params3'] : '';
        $date2 = $postData['params9'] ? $postData['params9'] : '';
        $division = $postData['params4'] ? $postData['params4'] : '';
        $sub_division = $postData['params5'] ? $postData['params5'] : '';
        $dept = $postData['params6'] ? $postData['params6'] : '';
        $brand = $postData['params1'] ? $postData['params1'] : '';
        // $deltype = $postData['deltype'] ? $postData['deltype'] : '';
        // $paytype = $postData['paytype'] ? $postData['paytype'] : '';

        $whereClause = "";

        $data['username'] = $this->input->cookie('cookie_invent_user');
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $whereClause = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $whereClause = $this->M_Division->get_division($data['username'], $store);
        } else {
            // UNTUK MD
            $whereClause = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($date != '') {
            if (strpos($date, '-') !== false) {
                $tgl = explode("-", $date);
                $fromdate = date("Y-m-d", strtotime($tgl[0]));
                $todate = date("Y-m-d", strtotime($tgl[1]));
            }
            $last_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        if ($date2 != '') {
            if (strpos($date2, '-') !== false) {
                $tgl2 = explode("-", $date2);
                $fromdate2 = date("Y-m-d", strtotime($tgl2[0]));
                $todate2 = date("Y-m-d", strtotime($tgl2[1]));
            }
            $this_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate2 . "' and '" . $todate2 . "'";
        }

        if ($division != '') {
            $whereClause .= " AND DIVISION ='" . $division . "'";
        }

        if ($sub_division != '') {
            $whereClause .= " AND SUB_DIVISION ='" . $sub_division . "'";
        }

        if ($dept != '') {
            $whereClause .= " AND DEPT ='" . $dept . "'";
        }

        if ($brand != '') {
            $whereClause .= " AND brand_code ='" . $brand . "'";
        }

        $cache_key = "getPenjualanBrand_{$start}_length_{$rowperpage}_draw_{$draw}_store_{$store}_tp_{$this_period}_lp_{$last_period}_search_" . md5($whereClause) . "_is_operation_" . $cek_operation;
        $cached_data = $this->redislib->get($cache_key); // Try to fetch cached data

        if ($cached_data) {
            return json_decode($cached_data, true);
        }

        $query = "
        SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        CASE WHEN LP.DEPT is null then TP.DEPT else LP.DEPT end as DEPT, 
        CONCAT(CASE WHEN LP.brand_code is null then TP.brand_code else LP.brand_code end,' - ',CASE WHEN LP.brand_name is null then TP.brand_name else LP.brand_name end) as BRAND, 
        -- FLOOR
        LP.qty_floor as LP_Qty1, LP.net_floor as LP_Sales1, '' as TP_Target1, TP.qty_floor as TP_Qty1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.qty_bazaar as LP_Qty2, LP.net_bazaar as LP_Sales2, '' as TP_Target2,  TP.qty_bazaar as TP_Qty2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.qty_online as LP_Qty3, LP.net_online as LP_Sales3, '' as TP_Target3, TP.qty_online as TP_Qty3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.qty_floor+LP.qty_bazaar+LP.qty_online) as LP_Qty4, (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4,  (TP.qty_floor+TP.qty_bazaar+TP.qty_online) as TP_Qty4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,
        sum(qty_floor) as qty_floor,sum(qty_bazaar) as qty_bazaar, sum(qty_online) as qty_online,
        sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then tot_qty else 0 end) qty_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then tot_qty else 0 end) qty_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then tot_qty else 0 end) qty_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) LP
        left join 
        (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,
        sum(qty_floor) as qty_floor,sum(qty_bazaar) as qty_bazaar, sum(qty_online) as qty_online,
        sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then tot_qty else 0 end) qty_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then tot_qty else 0 end) qty_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then tot_qty else 0 end) qty_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) TP on LP.brand_code = TP.brand_code and TP.DEPT = LP.DEPT  
        UNION
        SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        CASE WHEN LP.DEPT is null then TP.DEPT else LP.DEPT end as DEPT, 
        CONCAT(CASE WHEN LP.brand_code is null then TP.brand_code else LP.brand_code end,' - ',CASE WHEN LP.brand_name is null then TP.brand_name else LP.brand_name end) as BRAND, 
        -- FLOOR
        LP.qty_floor as LP_Qty1, LP.net_floor as LP_Sales1, '' as TP_Target1, TP.qty_floor as TP_Qty1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.qty_bazaar as LP_Qty2, LP.net_bazaar as LP_Sales2, '' as TP_Target2,  TP.qty_bazaar as TP_Qty2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.qty_online as LP_Qty3, LP.net_online as LP_Sales3, '' as TP_Target3, TP.qty_online as TP_Qty3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.qty_floor+LP.qty_bazaar+LP.qty_online) as LP_Qty4, (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4,  (TP.qty_floor+TP.qty_bazaar+TP.qty_online) as TP_Qty4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,
        sum(qty_floor) as qty_floor,sum(qty_bazaar) as qty_bazaar, sum(qty_online) as qty_online,
        sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then tot_qty else 0 end) qty_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then tot_qty else 0 end) qty_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then tot_qty else 0 end) qty_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) LP
        right join 
        (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,
        sum(qty_floor) as qty_floor,sum(qty_bazaar) as qty_bazaar, sum(qty_online) as qty_online,
        sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then tot_qty else 0 end) qty_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then tot_qty else 0 end) qty_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then tot_qty else 0 end) qty_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) TP on LP.brand_code = TP.brand_code and TP.DEPT = LP.DEPT   
        ORDER BY SBU, DEPT
        ";

        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (SBU like '%" . $searchValue . "%' or BRAND like '%" . $searchValue . "%') ";
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
                "STORE"                 => $record->STORE,
                "SBU"                   => $record->SBU,
                "DEPT"                  => $record->DEPT,
                "BRAND"                 => $record->BRAND,
                "LP_Qty1"               => ($record->LP_Qty1) ? $record->LP_Qty1 : "",
                "LP_Sales1"             => ($record->LP_Sales1) ? "Rp " . $record->LP_Sales1 : "",
                "TP_Target1"            => ($record->TP_Target1) ? "Rp " . $record->TP_Target1 : "",
                "TP_Qty1"               => ($record->TP_Qty1) ? $record->TP_Qty1 : "",
                "TP_Sales1"             => ($record->TP_Sales1) ? "Rp " . $record->TP_Sales1 : "",
                "Achieve1"              => $record->Achieve1,
                "Growth1"               => $record->Growth1,
                "LP_Margin_Percent1"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent1 . "%",
                "LP_Margin_Value1"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value1,
                "TP_Margin_Percent1"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent1 . "%",
                "TP_Margin_Value1"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value1,
                "LP_Qty2"               => ($record->LP_Qty2) ? $record->LP_Qty2 : "",
                "LP_Sales2"             => ($record->LP_Sales2) ? "Rp " . $record->LP_Sales2 : "",
                "TP_Target2"            => ($record->TP_Target2) ? "Rp " . $record->TP_Target2 : "",
                "TP_Qty2"               => ($record->TP_Qty2) ? $record->TP_Qty2 : "",
                "TP_Sales2"             => ($record->TP_Sales2) ? "Rp " . $record->TP_Sales2 : "",
                "Achieve2"              => $record->Achieve2,
                "Growth2"               => $record->Growth2,
                "LP_Margin_Percent2"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent2 . "%",
                "LP_Margin_Value2"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value2,
                "TP_Margin_Percent2"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent2 . "%",
                "TP_Margin_Value2"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value2,
                "LP_Qty3"               => ($record->LP_Qty3) ? $record->LP_Qty3 : "",
                "LP_Sales3"             => ($record->LP_Sales3) ? "Rp " . $record->LP_Sales3 : "",
                "TP_Target3"            => ($record->TP_Target3) ? "Rp " . $record->TP_Target3 : "",
                "TP_Qty3"               => ($record->TP_Qty3) ? $record->TP_Qty3 : "",
                "TP_Sales3"             => ($record->TP_Sales3) ? "Rp " . $record->TP_Sales3 : "",
                "Achieve3"              => $record->Achieve3,
                "Growth3"               => $record->Growth3,
                "LP_Margin_Percent3"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent3 . "%",
                "LP_Margin_Value3"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value3,
                "TP_Margin_Percent3"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent3 . "%",
                "TP_Margin_Value3"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value3,
                "LP_Qty4"               => ($record->LP_Qty4) ? $record->LP_Qty4 : "",
                "LP_Sales4"             => ($record->LP_Sales4) ? "Rp " . $record->LP_Sales4 : "",
                "TP_Target4"            => ($record->TP_Target4) ? "Rp " . $record->TP_Target4 : "",
                "TP_Qty4"               => ($record->TP_Qty4) ? $record->TP_Qty4 : "",
                "TP_Sales4"             => ($record->TP_Sales4) ? "Rp " . $record->TP_Sales4 : "",
                "Achieve4"              => $record->Achieve4,
                "Growth4"               => $record->Growth4,
                "LP_Margin_Percent4"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent4 . "%",
                "LP_Margin_Value4"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value4,
                "TP_Margin_Percent4"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent4 . "%",
                "TP_Margin_Value4"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value4,
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        // Cache the result in Redis
        $this->redislib->set($cache_key, json_encode($response));

        return $response;
    }

    public function getPenjualanKategori($postData = null)
    {
        $data['username'] = $this->input->cookie('cookie_invent_user');
        $cek_operation = $this->db->query("SELECT * from m_login where username ='" . $data['username'] . "'")->row();
        $cek_operation = $cek_operation->login_type_id;

        $response = array();

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $store = $postData['params8'] ? $postData['params8'] : '';
        $date = $postData['params3'] ? $postData['params3'] : '';
        $date2 = $postData['params9'] ? $postData['params9'] : '';
        $division = $postData['params4'] ? $postData['params4'] : '';
        $sub_division = $postData['params5'] ? $postData['params5'] : '';
        // $deltype = $postData['deltype'] ? $postData['deltype'] : '';
        // $paytype = $postData['paytype'] ? $postData['paytype'] : '';

        $whereClause = "";

        $data['username'] = $this->input->cookie('cookie_invent_user');
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();

        // CEK ADA USER SITENYA NGGA
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_user_category) {
            $whereClause = $this->M_Categories->get_category($data['username']);
        } else if ($cek_user_site) {
            $whereClause = $this->M_Division->get_division($data['username'], $store);
        } else {
            // UNTUK MD
            $whereClause = "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        if ($date != '') {
            if (strpos($date, '-') !== false) {
                $tgl = explode("-", $date);
                $fromdate = date("Y-m-d", strtotime($tgl[0]));
                $todate = date("Y-m-d", strtotime($tgl[1]));
            }
            $last_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        if ($date2 != '') {
            if (strpos($date2, '-') !== false) {
                $tgl2 = explode("-", $date2);
                $fromdate2 = date("Y-m-d", strtotime($tgl2[0]));
                $todate2 = date("Y-m-d", strtotime($tgl2[1]));
            }
            $this_period = " WHERE DATE_FORMAT(periode,'%Y-%m-%d') BETWEEN '" . $fromdate2 . "' and '" . $todate2 . "'";
        }

        if ($division != '') {
            $whereClause .= " AND DIVISION ='" . $division . "'";
        }

        if ($sub_division != '') {
            $whereClause .= " AND SUB_DIVISION ='" . $sub_division . "'";
        }

        $cache_key = "getPenjualanKategori_{$start}_length_{$rowperpage}_draw_{$draw}_store_{$store}_tp_{$this_period}_lp_{$last_period}_search_" . md5($whereClause) . "_is_operation_" . $cek_operation;
        $cached_data = $this->redislib->get($cache_key); // Try to fetch cached data

        if ($cached_data) {
            return json_decode($cached_data, true);
        }

        $query = "SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        -- FLOOR
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.net_online as LP_Sales3, '' as TP_Target3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION
        ) LP
        left join 
        (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION
        ) TP on LP.SUB_DIVISION = TP.SUB_DIVISION  
        union
        SELECT 
        CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        -- FLOOR
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1, 
        ifnull(round(LP.margin_percent_floor,2),0) as LP_Margin_Percent1, ifnull(round(LP.margin_value_floor,0),0) as LP_Margin_Value1,  
        ifnull(round(TP.margin_percent_floor,2),0) as TP_Margin_Percent1, ifnull(round(TP.margin_value_floor,0),0) as TP_Margin_Value1,  
        -- ATRIUM
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) / LP.net_bazaar) *100,0),0) end as Growth2, 
        ifnull(round(LP.margin_percent_bazaar,2),0) as LP_Margin_Percent2, ifnull(round(LP.margin_value_bazaar,0),0) as LP_Margin_Value2,  
        ifnull(round(TP.margin_percent_bazaar,2),0) as TP_Margin_Percent2, ifnull(round(TP.margin_value_bazaar,0),0) as TP_Margin_Value2,  
        -- ONLINE
        LP.net_online as LP_Sales3, '' as TP_Target3, TP.net_online as TP_Sales3, '' as Achieve3,
        case when LP.net_online IS NULL OR TP.net_online IS NULL THEN 0 else ifnull(round(((TP.net_online - LP.net_online) /LP.net_online)*100,0),0) end as Growth3, 
        ifnull(round(LP.margin_percent_online,2),0) as LP_Margin_Percent3, ifnull(round(LP.margin_value_online,0),0) as LP_Margin_Value3,  
        ifnull(round(TP.margin_percent_online,2),0) as TP_Margin_Percent3, ifnull(round(TP.margin_value_online,0),0) as TP_Margin_Value3,  
        -- TOTAL
        (LP.net_floor+LP.net_bazaar+LP.net_online) as LP_Sales4, '' as TP_Target4, (TP.net_floor+TP.net_bazaar+TP.net_online) as TP_Sales4, '' as Achieve4,
        case when (LP.net_floor+LP.net_bazaar+LP.net_online) IS NULL OR (TP.net_floor+TP.net_bazaar+LP.net_online) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar+TP.net_online) -  (LP.net_floor+LP.net_bazaar+LP.net_online))  / (LP.net_floor+LP.net_bazaar+LP.net_online))*100,0),0) end as Growth4,
        (ifnull(round(LP.margin_percent_floor,2),0)+ifnull(round(LP.margin_percent_online,2),0)+ifnull(round(LP.margin_percent_bazaar,2),0)) as LP_Margin_Percent4,
        (ifnull(round(TP.margin_percent_floor,2),0)+ifnull(round(TP.margin_percent_online,2),0)+ifnull(round(TP.margin_percent_bazaar,2),0)) as TP_Margin_Percent4,
        (ifnull(round(LP.margin_value_floor,0),0)+ifnull(round(LP.margin_value_online,0),0)+ifnull(round(LP.margin_value_bazaar,0),0)) as LP_Margin_Value4,
        (ifnull(round(TP.margin_value_floor,0),0)+ifnull(round(TP.margin_value_online,0),0)+ifnull(round(TP.margin_value_bazaar,0),0)) as TP_Margin_Value4
        from (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online FROM (
        select branch_id, SUB_DIVISION, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION
        ) LP
        right join 
        (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar, sum(net_online) as net_online,
        sum(margin_value_floor) as margin_value_floor, sum(margin_value_bazaar) as margin_value_bazaar, sum(margin_value_online) as margin_value_online,
        sum(margin_percent_floor) as margin_percent_floor, sum(margin_percent_bazaar) as margin_percent_bazaar, sum(margin_percent_online) as margin_percent_online
        FROM (
        select branch_id, SUB_DIVISION,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_af else 0 end) net_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) margin_value_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) margin_value_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) margin_value_online,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2') then net_bf else 0 end) * 100 as margin_percent_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_bf else 0 end) * 100 as margin_percent_bazaar,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf*margin/100 else 0 end) / sum(CASE WHEN substring(trans_no, 9, 1) in ('5') then net_bf else 0 end) * 100 as margin_percent_online,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
            WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION
        ) TP on LP.SUB_DIVISION = TP.SUB_DIVISION  
        ORDER BY SBU";

        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (SBU like '%" . $searchValue . "%') ";
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
                "STORE"                 => $record->STORE,
                "SBU"                   => $record->SBU,
                "LP_Sales1"             => ($record->LP_Sales1) ? "Rp " . $record->LP_Sales1 : "",
                "TP_Target1"            => ($record->TP_Target1) ? "Rp " . $record->TP_Target1 : "",
                "TP_Sales1"             => ($record->TP_Sales1) ? "Rp " . $record->TP_Sales1 : "",
                "Achieve1"              => $record->Achieve1,
                "Growth1"               => $record->Growth1,
                "LP_Margin_Percent1"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent1 . "%",
                "LP_Margin_Value1"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value1,
                "TP_Margin_Percent1"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent1 . "%",
                "TP_Margin_Value1"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value1,
                "LP_Sales2"             => ($record->LP_Sales2) ? "Rp " . $record->LP_Sales2 : "",
                "TP_Target2"            => ($record->TP_Target2) ? "Rp " . $record->TP_Target2 : "",
                "TP_Sales2"             => ($record->TP_Sales2) ? "Rp " . $record->TP_Sales2 : "",
                "Achieve2"              => $record->Achieve2,
                "Growth2"               => $record->Growth2,
                "LP_Margin_Percent2"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent2 . "%",
                "LP_Margin_Value2"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value2,
                "TP_Margin_Percent2"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent2 . "%",
                "TP_Margin_Value2"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value2,
                "LP_Sales3"             => ($record->LP_Sales3) ? "Rp " . $record->LP_Sales3 : "",
                "TP_Target3"            => ($record->TP_Target3) ? "Rp " . $record->TP_Target3 : "",
                "TP_Sales3"             => ($record->TP_Sales3) ? "Rp " . $record->TP_Sales3 : "",
                "Achieve3"              => $record->Achieve3,
                "Growth3"               => $record->Growth3,
                "LP_Margin_Percent3"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent3 . "%",
                "LP_Margin_Value3"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value3,
                "TP_Margin_Percent3"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent3 . "%",
                "TP_Margin_Value3"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value3,
                "LP_Sales4"             => ($record->LP_Sales4) ? "Rp " . $record->LP_Sales4 : "",
                "TP_Target4"            => ($record->TP_Target4) ? "Rp " . $record->TP_Target4 : "",
                "TP_Sales4"             => ($record->TP_Sales4) ? "Rp " . $record->TP_Sales4 : "",
                "Achieve4"              => $record->Achieve4,
                "Growth4"               => $record->Growth4,
                "LP_Margin_Percent4"    => ($cek_operation == "1") ? "" : $record->LP_Margin_Percent4 . "%",
                "LP_Margin_Value4"      => ($cek_operation == "1") ? "" : "Rp " . $record->LP_Margin_Value4,
                "TP_Margin_Percent4"    => ($cek_operation == "1") ? "" : $record->TP_Margin_Percent4 . "%",
                "TP_Margin_Value4"      => ($cek_operation == "1") ? "" : "Rp " . $record->TP_Margin_Value4,
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        $this->redislib->set($cache_key, json_encode($response));

        return $response;
    }

    public function getSalesHistory($postData = null){

        $store = $postData['store'];
        if ($store == 'R002') {
            $dbStore = $this->load->database('storeR002', TRUE);
        } else if ($store == 'V001') {
            $dbStore = $this->load->database('storeV001', TRUE);
        } else if ($store == 'R001') {
            $dbStore = $this->load->database('storeR001', TRUE);
        } else if ($store == 'S002') {
            $dbStore = $this->load->database('storeS002', TRUE);
        } else if ($store == 'S003') {
            $dbStore = $this->load->database('storeS003', TRUE);
        } else if ($store == 'V002') {
            $dbStore = $this->load->database('storeV002', TRUE);
        } else if ($store == 'V003') {
            $dbStore = $this->load->database('storeV003', TRUE);
        }
        $response = array();

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $trans_no = $postData['params1'] ? $postData['params1'] : '';
        $kode_reg = $postData['params2'] ? $postData['params2'] : '';
        $date = $postData['params3'] ? $postData['params3'] : '';
        $trans_status = $postData['params4'] ? $postData['params4'] : '';

        $whereClause = "";

        if($trans_no != ''){
            $whereClause .= " AND a.trans_no ='".$trans_no."'";
        }

        if($kode_reg != ''){
            $whereClause .= " AND substring(a.trans_no,9,3) ='".$kode_reg."'";
        }

        if($trans_status != ''){
            $whereClause .= " AND trans_status ='".$trans_status."'";
        }

        if ($date != '') {
            if (strpos($date, '-') !== false) {
                $tgl = explode("-", $date);
                $fromdate = date("Y-m-d", strtotime($tgl[0]));
                $todate = date("Y-m-d", strtotime($tgl[1]));
            }
            $whereClause .= " AND DATE_FORMAT(trans_date,'%Y-%m-%d') BETWEEN '" . $fromdate . "' and '" . $todate . "'";
        }

        $query = "SELECT DISTINCT a.trans_no,trans_status, case 
        when trans_status = 0 then 'Hold'
        when trans_status = 1 then 'Success'
        when trans_status = 2 then 'Cancel'
        when trans_status = 3 then 'Trader'
        end as status_desc,date_format( trans_date, '%Y-%m-%d %00:%00:%00' ) AS periode, trans_time, count(b.barcode) as jml_record, cashier_id, substring(a.trans_no,9,3) as kode_register,sum(b.qty) as tot_qty, sum(berat) as tot_berat, sum(net_price) as net_price, total_amount, paid_amount from dbserver_history.t_sales_trans_hdr a
        inner join dbserver_history.t_sales_trans_dtl b
        on a.trans_no = b.trans_no
        where DATE_FORMAT(a.trans_date,'%Y-%m-%d') >= ( CURDATE() - INTERVAL 7 DAY )
        $whereClause 
        GROUP BY a.trans_no, trans_time";

        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (trans_no like '%" . $searchValue . "%' or cashier_id like '%" . $searchValue . "%' or kode_register like'%" . $searchValue . "%' ) ";
        }


        $orderBy = "";

        $totalRecords = $dbStore->query($query)->num_rows();

        ## Fetch records
        //$dbStore->select('*');
        if ($searchQuery != '') {
            $dbStore->where($searchQuery);
        }
        $totalRecordwithFilter = $dbStore->query($query)->num_rows();
        // $dbStore->order_by($columnName, $columnSortOrder);
        $limitStart = ' LIMIT ' . $rowperpage . ' OFFSET ' . $start;
        $records = $dbStore->query($query . $orderBy . $limitStart)->result();

        //var_dump($query.$whereClause.$limitStart);
        $data = array();
        foreach ($records as $record) {

            $data[] = array(
                "trans_no"      => $record->trans_no,
                "trans_status"  => $record->trans_status,
                "status_desc"   => $record->status_desc,
                "periode"       => $record->periode,
                "trans_time"    => $record->trans_time,
                "cashier_id"    => $record->cashier_id,
                "kode_register" => $record->kode_register,
                "jml_record"    => $record->jml_record,
                "tot_qty"       => $record->tot_qty,
                "tot_berat"     => $record->tot_berat,
                "net_price"     => $record->net_price,
                "total_amount"  => $record->total_amount,
                "paid_amount"   => $record->paid_amount
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
