<?php

class M_Supermarket extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getSalesDaily($store, $date = NULL)
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $dbCentral->select([
            'DATE_FORMAT(th.trans_date, "%Y-%m-%d") AS periode',
            'th.trans_time AS Jam',
            'mkl.division AS DIVISION',
            'mkl.sub_division AS SUB_DIVISION',
            'td.CATEGORY_CODE AS CATEGORY_CODE',
            'mkl.CATEGORY AS CATEGORY',
            'mkl.dept AS DEPT',
            'mkl.sub_dept AS SUB_DEPT',
            'td.brand AS brand_code',
            'REPLACE(mb.brand_name, "\'", "") AS brand_name',
            'mim.article_code AS article_code',
            'td.barcode AS barcode',
            'REPLACE(mim.supplier_pname, " \'", "") AS article_name',
            'td.varian_option1 AS varian_option1',
            'td.varian_option2 AS varian_option2',
            'td.price AS price',
            'mg.member_name AS member_name',
            'mg.mobile_number AS member_phone',
            'mg.tier_name AS member_tier',
            'mim.vendor_code AS vendor_code',
            'mv.vendor_name AS vendor_name',
            'td.margin_number AS margin',
            'SUM(td.qty) AS tot_qty',
            'CASE WHEN (mim.tag_5 = "timbang") THEN SUM(td.berat) ELSE 0 END AS tot_berat',
            'td.disc_pct AS disc_pct',
            'SUM(td.disc_amt) AS total_disc_amt',
            'td.moredisc_pct AS moredisc_pct',
            'SUM(td.moredisc_amt) AS total_moredisc_amt',
            'ROUND(SUM(td.net_prc), 0) AS net_af',
            'ROUND(SUM(td.net_all), 0) AS net_bf',
            'CASE WHEN (td.flag_tax = "1") THEN ROUND(SUM(td.net_all * 1.11), 0) ELSE ROUND(SUM(td.net_all), 0) END AS gross',
            'CASE SUBSTR(th.trans_no, 9, 1) WHEN "3" THEN "BAZAAR" WHEN "5" THEN "ONLINE" ELSE "FLOOR" END AS source_data',
            'mim.tag_5 AS tag_5',
            'CASE LEFT(mim.vendor_code, 1) WHEN "2" THEN "Consignment" ELSE "Direct" END AS vendor_type',
            'SUM(td.fee) AS fee',
            'th.trans_no AS trans_no',
            'th.no_ref AS no_ref',
            'td.promo_id as promo_id',
            'tp.promo_desc as promo_desc',
            'CASE WHEN (SUBSTR(th.trans_no, 7, 2) = "01") THEN "R001" WHEN (SUBSTR(th.trans_no, 7, 2) = "02") THEN "R002" WHEN (SUBSTR(th.trans_no, 7, 2) = "03") THEN "V001" END AS branch_id',
            'CASE WHEN (mim.tag_5 = "timbang") THEN SUM(td.berat) ELSE SUM(td.qty) END AS "Qty Gab"',
            'CASE WHEN (COALESCE(th.member_id, "") != "") THEN "MEMBER" ELSE "NON MEMBER" END AS Member',
            'DATE_FORMAT(th.trans_date, "%M") AS "Month"',
            'DATE_FORMAT(th.trans_date, "%d-%M") AS "Date"',
            'DATE_FORMAT(th.trans_time, "%H:00") AS "Hour"',
            'CASE WHEN DAYNAME(th.trans_date) IN ("Sunday", "Saturday") THEN "WE" ELSE "WD" END AS "WD/WE"',
            'CASE WHEN (th.trans_status = "3") THEN "TRADER" ELSE "NON TRADER" END AS Trader',
            'CASE WHEN  left(td.promo_id,1) in ("B","S","") and (td.disc_pct <> 0 or td.moredisc_pct <> 0)  THEN "KEY DISCOUNT" ELSE "NON KEY DISCOUNT" end AS "Key Discount"'
        ]);

        $dbCentral->from('t_sales_trans_hdr th');
        $dbCentral->join('(SELECT *, CASE WHEN t_sales_trans_dtl.flag_tax = "1" THEN t_sales_trans_dtl.net_price / 1.11 ELSE t_sales_trans_dtl.net_price end AS net_prc, CASE t_sales_trans_dtl.flag_flexi WHEN 1 THEN ( CASE t_sales_trans_dtl.type_flex WHEN "0" THEN (t_sales_trans_dtl.net_price / 1.11) WHEN "1" THEN ( ( t_sales_trans_dtl.net_price + (t_sales_trans_dtl.fee * - (1)) ) / 1.11 ) WHEN "2" THEN ( ( t_sales_trans_dtl.net_price - t_sales_trans_dtl.fee ) / 1.11 ) end ) ELSE ( CASE WHEN t_sales_trans_dtl.flag_tax = "1" THEN (t_sales_trans_dtl.net_price / 1.11) ELSE t_sales_trans_dtl.net_price end ) end AS net_all FROM t_sales_trans_dtl) td', 'th.trans_no = td.trans_no');
        $dbCentral->join('m_codebar mc', 'td.barcode = mc.barcode', 'left');
        $dbCentral->join('m_item_master mim', 'mc.article_number = mim.article_number AND mim.branch_id = CASE WHEN (SUBSTR(th.trans_no, 7, 2) = "01") THEN "R001" WHEN (SUBSTR(th.trans_no, 7, 2) = "02") THEN "R002" WHEN (SUBSTR(th.trans_no, 7, 2) = "03") THEN "V001" END', 'left');
        $dbCentral->join('m_brand mb', 'mim.brand = mb.brand_code', 'left');
        $dbCentral->join('m_kategori_list mkl', 'td.category_code = mkl.category_code', 'left');
        $dbCentral->join('m_vendor mv', 'mim.vendor_code = mv.vendor_code', 'left');
        $dbCentral->join('l_member_master_goodie mg', 'mg.member_id = th.member_id', 'left');
        $dbCentral->join('t_promo_hdr tp', 'td.promo_id = tp.promo_id', 'left');

        $dbCentral->where('th.trans_status IN ("1", "3")');
        $dbCentral->where('td.category_code != "RSOTMKVC01"');
        $dbCentral->where('th.trans_date', date('Y-m-d', !$date ? strtotime('-1 day') : strtotime($date)));
        if ($store == '01') {
            $dbCentral->where('mkl.DIVISION', 'Supermarket');
        }
        $dbCentral->where('SUBSTR(th.trans_no, 7, 2) =', $store);
        $dbCentral->group_by([
            'th.trans_date',
            'th.trans_time',
            'mkl.division',
            'mkl.sub_division',
            'td.CATEGORY_CODE',
            'mkl.CATEGORY',
            'mkl.dept',
            'mkl.sub_dept',
            'td.brand',
            'mb.brand_name',
            'mim.article_code',
            'td.barcode',
            'mim.supplier_pname',
            'td.varian_option1',
            'td.varian_option2',
            'td.price',
            'mim.vendor_code',
            'mv.vendor_name',
            'td.margin_number',
            'td.disc_pct',
            'td.moredisc_pct',
            'SUBSTR(th.trans_no, 9, 1)',
            'th.trx_source',
            'mim.tag_5',
            'th.trans_no',
            'th.no_ref',
            'td.flag_tax',
            'CASE WHEN (substr(th.trans_no, 7, 2) = "01") THEN "R001" WHEN (substr(th.trans_no, 7, 2) = "02") THEN "R002" WHEN (substr(th.trans_no, 7, 2) = "03") THEN "V001" end'
        ]);
        $dbCentral->order_by('th.trans_date ASC, th.trans_time ASC, th.trans_no ASC');
        $query = $dbCentral->get();
        return $query->result_array();
    }

    function getHappyFreshSKU($listBarcode)
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $dbCentral->select([
            '(CASE a.branch_id WHEN "V001" THEN "20001" ELSE "10001" END) AS StoreNumber',
            'a.barcode AS Barcode',
            'a.sku_code AS SKU',
            'brand_name AS Brand',
            'article_name AS "Product Name"',
            'mk.CATEGORY AS Category',
            '(NULL) AS "Sub - Category"',
            'ROUND(a.current_price) AS "Normal Price"',
            '(CASE promo.promo_type WHEN 1 THEN ROUND(a.current_price - (a.current_price * (promo.disc_percentage / 100)), 0) WHEN 3 THEN ROUND(promo.special_price, 0) ELSE 0 END) AS "Promo Price"',
            '(CASE a.tag_5 WHEN "Timbang" THEN "KG" ELSE "Piece" END) AS "Sales Unit"',
            'GREATEST(FLOOR((a.last_stock * 90 / 100)),0) AS StoreStock',
            'a.images AS Image',
            'a.width AS "Width (cm)"',
            'a.length AS "Depth (cm)"',
            'a.height AS "Height (cm)"',
            'a.weight AS "Weight (g)"'
        ]);

        $dbCentral->from('v_hf_apps_sku a');
        $dbCentral->join('( select code, promo_type, start_date, start_time, end_date, end_time, promo_desc, disc_percentage, min_qty, special_price from t_promo_dtl e left join t_promo_hdr f on e.promo_id = f.promo_id where branch_id = "V001" and aktif = 1 and promo_type < 30 and min_qty = 1 limit 1000000 ) promo', 'a.barcode = promo.code', 'left');
        $dbCentral->join('m_kategori_list mk', 'a.category_code = mk.CATEGORY_CODE', 'left');

        $dbCentral->where('a.branch_id', 'V001');
        $dbCentral->group_start();
        $listBarcodeChunk = array_chunk($listBarcode, 50);
        foreach ($listBarcodeChunk as $barcode) {
            $dbCentral->or_where_in('a.barcode', $barcode);
        }
        $dbCentral->group_end();
        //$dbCentral->where_in('a.barcode', $listBarcode);
        // $dbCentral->where('a.last_stock >', 0);
        $dbCentral->where('(a.category_code in ("RSOTMKVC01","RSGMSTSTOS", "RDOTSIOT", "RDOTSITB") and brand_code = "NMD") is not true ');

        $dbCentral->order_by('article_name ASC');
        $query = $dbCentral->get();
        return $query->result_array();
    }

    function getRealTimeSales($store)
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $dbCentral->select('barcode as Barcode, sales');
        $dbCentral->where('branch_id', $store);
        $query = $dbCentral->get('t_sales_harian');
        return $query->result_array();
    }
    function getTrxDiscByPeriod($startDate, $endDate)
    {

        $storeV001 = $this->load->database('storeV001', TRUE);
        $storeV001->select('a.trans_no, trans_date, trans_time, cashier_id, nama_panggilan kasir,
        a.member_id, member_name, d.mobile_number,
        b.barcode, article_code, article_number, article_name, qty, price, disc_pct, disc_amt, moredisc_pct, moredisc_amt, b.net_price');

        $storeV001->from('dbserver_history.t_sales_trans_hdr a');
        $storeV001->join('dbserver_history.t_sales_trans_dtl b', 'a.trans_no = b.trans_no', 'inner');
        $storeV001->join('dbserver.m_employee c', 'a.cashier_id = c.barcode', 'left');
        $storeV001->join('dbserver.l_member_master_goodie d', 'a.member_id = d.member_id', 'left');

        $storeV001->where('a.trans_status', 1);
        $storeV001->where('(b.disc_pct <> 0 OR b.moredisc_pct <> 0)');
        $storeV001->where_in('LEFT(b.promo_id, 1)', array('B', 'S', ''));

        // $storeV001->where("DATE_FORMAT(DATE_ADD(a.trans_date, INTERVAL 0 DAY), '%Y.%m') BETWEEN '2024.06' AND '2024.06'");
        $start_timestamp = strtotime($startDate);
        $end_timestamp = strtotime($endDate);
        $storeV001->where('UNIX_TIMESTAMP(a.trans_date) >=', $start_timestamp);
        $storeV001->where('UNIX_TIMESTAMP(a.trans_date) <=', $end_timestamp);
        $storeV001->order_by('trans_date, trans_time asc');

        $query = $storeV001->get();
        return $query->result_array();
    }


}