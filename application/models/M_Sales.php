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

        $cache_key = "getPenjualanBrand_{$start}_length_{$rowperpage}_draw_{$draw}_store_{$store}_tp_{$this_period}_lp_{$last_period}_search_" . md5($whereClause);
        $cached_data = $this->redislib->get($cache_key); // Try to fetch cached data

        if ($cached_data) {
            return json_decode($cached_data, true);
        }

        $query = "SELECT CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        CASE WHEN LP.DEPT is null then TP.DEPT else LP.DEPT end as DEPT, CONCAT(CASE WHEN LP.brand_code is null then TP.brand_code else LP.brand_code end,' - ',CASE WHEN LP.brand_name is null then TP.brand_name else LP.brand_name end) as BRAND, 
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1,'' as Margin1,
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) /LP.net_bazaar)*100,0),0) end as Growth2,'' as Margin2,
                (LP.net_floor+LP.net_bazaar) as LP_Sales3, '' as TP_Target3, (TP.net_floor+TP.net_bazaar) as TP_Sales3, '' as Achieve3,
        case when (LP.net_floor+LP.net_bazaar) IS NULL OR (TP.net_floor+TP.net_bazaar) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar) -  (LP.net_floor+LP.net_bazaar))  / (LP.net_floor+LP.net_bazaar))*100,0),0) end as Growth3,'' as Margin3
        from (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) LP
        left join 
        (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) TP on LP.brand_code = TP.brand_code and TP.DEPT = LP.DEPT  
        union 
        SELECT CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        CASE WHEN LP.DEPT is null then TP.DEPT else LP.DEPT end as DEPT, CONCAT(CASE WHEN LP.brand_code is null then TP.brand_code else LP.brand_code end,' - ',CASE WHEN LP.brand_name is null then TP.brand_name else LP.brand_name end) as BRAND, 
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1,'' as Margin1,
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) /LP.net_bazaar)*100,0),0) end as Growth2,'' as Margin2,
                (LP.net_floor+LP.net_bazaar) as LP_Sales3, '' as TP_Target3, (TP.net_floor+TP.net_bazaar) as TP_Sales3, '' as Achieve3,
        case when (LP.net_floor+LP.net_bazaar) IS NULL OR (TP.net_floor+TP.net_bazaar) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar) -  (LP.net_floor+LP.net_bazaar))  / (LP.net_floor+LP.net_bazaar))*100,0),0) end as Growth3,'' as Margin3
        from (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code
        ) LP
        right join 
        (
        SELECT branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name,sum(net_floor) as net_floor,sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION, DEPT,  brand_code, brand_name, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION, DEPT, brand_code, brand_name,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
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
                "STORE"         => $record->STORE,
                "SBU"           => $record->SBU,
                "DEPT"          => $record->DEPT,
                "BRAND"         => $record->BRAND,
                "LP_Sales1"     => $record->LP_Sales1,
                "LP_Target1"    => $record->TP_Target1,
                "TP_Sales1"     => $record->TP_Sales1,
                "Achieve1"      => $record->Achieve1,
                "Growth1"       => $record->Growth1,
                "Margin1"       => $record->Margin1,
                "LP_Sales2"     => $record->LP_Sales2,
                "LP_Target2"    => $record->TP_Target2,
                "TP_Sales2"     => $record->TP_Sales2,
                "Achieve2"      => $record->Achieve2,
                "Growth2"       => $record->Growth2,
                "Margin2"       => $record->Margin2,
                "LP_Sales3"     => $record->LP_Sales3,
                "LP_Target3"    => $record->TP_Target3,
                "TP_Sales3"     => $record->TP_Sales3,
                "Achieve3"      => $record->Achieve3,
                "Growth3"       => $record->Growth3,
                "Margin3"       => $record->Margin3,
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

        $cache_key = "getPenjualanKategori_{$start}_length_{$rowperpage}_draw_{$draw}_store_{$store}_tp_{$this_period}_lp_{$last_period}_search_" . md5($whereClause);
        $cached_data = $this->redislib->get($cache_key); // Try to fetch cached data

        if ($cached_data) {
            return json_decode($cached_data, true);
        }

        $query = "SELECT CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1,'' as Margin1,
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) /LP.net_bazaar)*100,0),0) end as Growth2,'' as Margin2,
                (LP.net_floor+LP.net_bazaar) as LP_Sales3, '' as TP_Target3, (TP.net_floor+TP.net_bazaar) as TP_Sales3, '' as Achieve3,
        case when (LP.net_floor+LP.net_bazaar) IS NULL OR (TP.net_floor+TP.net_bazaar) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar) -  (LP.net_floor+LP.net_bazaar))  / (LP.net_floor+LP.net_bazaar))*100,0),0) end as Growth3,'' as Margin3
        from (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor, sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION
        ) LP
        left join 
        (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor, sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION
        ) TP on TP.SUB_DIVISION = LP.SUB_DIVISION
        union 
        SELECT CASE WHEN LP.branch_id is null then TP.branch_id else LP.branch_id end as STORE, 
        CASE WHEN LP.SUB_DIVISION is null then  TP.SUB_DIVISION else LP.SUB_DIVISION end as SBU, 
        LP.net_floor as LP_Sales1, '' as TP_Target1, TP.net_floor as TP_Sales1, '' as Achieve1,
        case when LP.net_floor IS NULL OR TP.net_floor IS NULL THEN 0 else ifnull(round(((TP.net_floor - LP.net_floor) / LP.net_floor) *100,0),0) end as Growth1,'' as Margin1,
        LP.net_bazaar as LP_Sales2, '' as TP_Target2, TP.net_bazaar as TP_Sales2, '' as Achieve2,
        case when LP.net_bazaar IS NULL OR TP.net_bazaar IS NULL THEN 0 else ifnull(round(((TP.net_bazaar - LP.net_bazaar) /LP.net_bazaar)*100,0),0) end as Growth2,'' as Margin2,
                (LP.net_floor+LP.net_bazaar) as LP_Sales3, '' as TP_Target3, (TP.net_floor+TP.net_bazaar) as TP_Sales3, '' as Achieve3,
        case when (LP.net_floor+LP.net_bazaar) IS NULL OR (TP.net_floor+TP.net_bazaar) IS NULL THEN 0 else ifnull(round((((TP.net_floor+TP.net_bazaar) -  (LP.net_floor+LP.net_bazaar))  / (LP.net_floor+LP.net_bazaar))*100,0),0) end as Growth3,'' as Margin3
        from (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor, sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $last_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A
        GROUP BY branch_id, SUB_DIVISION
        ) LP
        right join 
        (
        SELECT branch_id, SUB_DIVISION, sum(net_floor) as net_floor, sum(net_bazaar) as net_bazaar FROM (
        select branch_id, SUB_DIVISION, 
        sum(CASE WHEN substring(trans_no, 9, 1) in ('0','1','2','5') then net_af else 0 end) net_floor,
        sum(CASE WHEN substring(trans_no, 9, 1) in ('3') then net_af else 0 end) net_bazaar,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
            WHEN substring(trans_no, 9, 1) = '5' THEN 'ONLINE'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END areatrx  from report_service.r_sales
        $this_period
        and branch_id = '" . $store . "'
        $whereClause
        GROUP BY branch_id, SUB_DIVISION,
        CASE
            WHEN substring(trans_no, 9, 1) in ('0','1','2','5') THEN 'FLOOR'
        WHEN substring(trans_no, 9, 1) = '3' THEN 'BAZAAR'
        END
        ) A GROUP BY branch_id, SUB_DIVISION
        ) TP on TP.SUB_DIVISION = LP.SUB_DIVISION
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
                "STORE"         => $record->STORE,
                "SBU"           => $record->SBU,
                "LP_Sales1"     => $record->LP_Sales1,
                "LP_Target1"    => $record->TP_Target1,
                "TP_Sales1"     => $record->TP_Sales1,
                "Achieve1"      => $record->Achieve1,
                "Growth1"       => $record->Growth1,
                "Margin1"       => $record->Margin1,
                "LP_Sales2"     => $record->LP_Sales2,
                "LP_Target2"    => $record->TP_Target2,
                "TP_Sales2"     => $record->TP_Sales2,
                "Achieve2"      => $record->Achieve2,
                "Growth2"       => $record->Growth2,
                "Margin2"       => $record->Margin2,
                "LP_Sales3"     => $record->LP_Sales3,
                "LP_Target3"    => $record->TP_Target3,
                "TP_Sales3"     => $record->TP_Sales3,
                "Achieve3"      => $record->Achieve3,
                "Growth3"       => $record->Growth3,
                "Margin3"       => $record->Margin3,
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
}
