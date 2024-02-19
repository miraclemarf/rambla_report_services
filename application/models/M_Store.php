<?php

    class M_Store extends CI_Model
    {
        function __construct()
        {
            parent::__construct();
        }
 
        function get_sales_today($store)
        {
            if($store == 'R002')
                $dbStore = $this->load->database('storeR002', TRUE);
            else if($store == 'V001')
                $dbStore = $this->load->database('storeV001', TRUE);
            else
                $dbStore = $this->load->database('storeR001', TRUE);
            

            
            $query = "SELECT date_format( trans_date, '%Y.%m.%d' ) periode, count( DISTINCT ( tsth.trans_no )) tot_trx, sum( tstd.qty ) tot_qty, format(round( sum( net_price ),0),0) AS gross, format(round( sum( net_price / 1.11 ),0),0) AS net FROM t_sales_trans_hdr tsth INNER JOIN t_sales_trans_dtl tstd ON tsth.trans_no = tstd.trans_no WHERE trans_status = '1' AND date_format( trans_date, '%Y.%m.%d' ) BETWEEN ? AND ? AND tstd.category_code NOT IN ( 'RSOTMKVC01' ) AND tsth.trans_status = '1' AND tstd.barcode NOT IN ( '9000110400005', '9000125600001' ) AND substring( tsth.trans_no, 9, 1 ) NOT IN ('3','4','5')";
            //$sqlData = $dbStore->query($query, array(date("Y.m.d",strtotime("-1 days")), date("Y.m.d",strtotime("-1 days"))));
            $sqlData = $dbStore->query($query, array(date("Y.m.d"), date("Y.m.d")));
            return $sqlData->result();

        }
        function get_top10_brand($store){
            if($store == 'R002')
                $dbStore = $this->load->database('storeR002', TRUE);
            else if($store == 'V001')
                $dbStore = $this->load->database('storeV001', TRUE);
            else
                $dbStore = $this->load->database('storeR001', TRUE);
        

        
            $query = "SELECT date_format( trans_date, '%Y.%m.%d' ) periode, tstd.brand, mb.brand_name, count( DISTINCT ( tsth.trans_no )) tot_trx, sum( tstd.qty ) tot_qty, format( round( sum( net_price ), 0 ), 0 ) AS gross, format( round( sum( net_price / 1.11 ), 0 ), 0 ) AS net FROM t_sales_trans_hdr tsth INNER JOIN t_sales_trans_dtl tstd ON tsth.trans_no = tstd.trans_no LEFT JOIN m_brand mb on tstd.brand = mb.brand_code WHERE trans_status = '1' AND date_format( trans_date, '%Y.%m.%d' ) BETWEEN ? AND ? AND tstd.category_code NOT IN ( 'RSOTMKVC01' ) AND tsth.trans_status = '1' AND tstd.barcode NOT IN ( '9000110400005', '9000125600001' ) AND substring( tsth.trans_no, 9, 1 ) NOT IN ( '3', '4', '5' ) GROUP BY tstd.brand ORDER BY round( sum( net_price ), 0 ) DESC LIMIT 10";
            //$sqlData = $dbStore->query($query, array(date("Y.m.d",strtotime("-1 days")), date("Y.m.d",strtotime("-1 days"))));
            $sqlData = $dbStore->query($query, array(date("Y.m.d"), date("Y.m.d")));
            return $sqlData->result();
        }

        function get_top10_article($store){
            if($store == 'R002')
                $dbStore = $this->load->database('storeR002', TRUE);
            else if($store == 'V001')
                $dbStore = $this->load->database('storeV001', TRUE);
            else
                $dbStore = $this->load->database('storeR001', TRUE);     

        
            $query = "SELECT date_format( trans_date, '%Y.%m.%d' ) periode, tstd.article_code, tstd.article_name, count( DISTINCT ( tsth.trans_no )) tot_trx, sum( tstd.qty ) tot_qty, format( round( sum( net_price ), 0 ), 0 ) AS gross, format( round( sum( net_price / 1.11 ), 0 ), 0 ) AS net FROM t_sales_trans_hdr tsth INNER JOIN t_sales_trans_dtl tstd ON tsth.trans_no = tstd.trans_no WHERE trans_status = '1' AND date_format( trans_date, '%Y.%m.%d' ) BETWEEN ? AND ? AND tstd.category_code NOT IN ( 'RSOTMKVC01' ) AND tsth.trans_status = '1' AND tstd.barcode NOT IN ( '9000110400005', '9000125600001' ) AND substring( tsth.trans_no, 9, 1 ) NOT IN ( '3', '4', '5' ) GROUP BY tstd.article_code ORDER BY round( sum( net_price ), 0 ) DESC LIMIT 10";
            //$sqlData = $dbStore->query($query, array(date("Y.m.d",strtotime("-1 days")), date("Y.m.d",strtotime("-1 days"))));
            $sqlData = $dbStore->query($query, array(date("Y.m.d"), date("Y.m.d")));
            return $sqlData->result();
        }

    }