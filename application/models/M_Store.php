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
            
            
            $query = "SELECT date_format( trans_date, '%Y.%m.%d' ) periode, count( DISTINCT ( tsth.trans_no )) tot_trx, sum( tstd.qty ) tot_qty, format(round( sum( net_price ),0),0) AS gross, format(round( sum( net_price / 1.11 ),0),0) AS net FROM t_sales_trans_hdr tsth INNER JOIN t_sales_trans_dtl tstd ON tsth.trans_no = tstd.trans_no WHERE trans_status = '1' AND date_format( trans_date, '%Y.%m.%d' ) BETWEEN ? AND ? AND tstd.category_code NOT IN ( 'RSOTMKVC01' ) AND tstd.barcode NOT IN ( '9000110400005', '9000125600001','9000119000008' ) AND substring( tsth.trans_no, 9, 1 ) NOT IN ('4','5')";
            //$sqlData = $dbStore->query($query, array(date("Y.m.d",strtotime("-1 days")), date("Y.m.d",strtotime("-1 days"))));
            $sqlData = $dbStore->query($query, array(date("Y.m.d"), date("Y.m.d")));
            return $sqlData->result();
        }

        function get_sales_today_all($store, $source)
        {
            $where = "";
            $allfloor = "";

            if($store == 'R002'){
                $dbStore = $this->load->database('storeR002', TRUE);
                $allfloor = " AND substring( tsth.trans_no, 9, 1 ) in ('0','1')";
            }
            else if($store == 'V001') {
                $dbStore = $this->load->database('storeV001', TRUE);
                $allfloor = " AND substring( tsth.trans_no, 9, 1 ) in ('0')";
            }
            else if($store == 'R001') {
                $dbStore = $this->load->database('storeR001', TRUE);
                $allfloor = " AND substring( tsth.trans_no, 9, 1 ) in ('1','2')";
            }
            
            if($source == "GF"){
                $where.=" AND substring( tsth.trans_no, 9, 1 ) = '0'";
            }else if($source == "FL1"){
                $where.=" AND substring( tsth.trans_no, 9, 1 ) = '1'";
            }else if($source == "FL2"){
                $where.=" AND substring( tsth.trans_no, 9, 1 ) = '2'";
            }else if($source == "ALLFL"){
                $where.= $allfloor;
            }else if($source == "RD"){
                $where.=" AND left(tstd.category_code,2) = 'RD'";
            }else if($source == "RS"){
                $where.=" AND left(tstd.category_code,2) = 'RS'";
            }else if($source == "BAZAAR"){
                $where.=" AND substring( tsth.trans_no, 9, 1 ) = '3'";
            }
            
            $query = "SELECT date_format( trans_date, '%Y.%m.%d' ) periode, count( DISTINCT ( tsth.trans_no )) tot_trx, sum( tstd.qty ) tot_qty, format(round( sum( net_price ),0),0) AS gross, format(round( sum( net_price / 1.11 ),0),0) AS net FROM t_sales_trans_hdr tsth INNER JOIN t_sales_trans_dtl tstd ON tsth.trans_no = tstd.trans_no WHERE trans_status = '1' AND date_format( trans_date, '%Y.%m.%d' ) BETWEEN ? AND ? AND tstd.category_code NOT IN ( 'RSOTMKVC01' ) AND tstd.barcode NOT IN ( '9000110400005', '9000125600001','9000119000008' ) $where AND substring( tsth.trans_no, 9, 1 ) NOT IN ('4','5')";

            $sqlData = $dbStore->query($query, array(date("Y.m.d"), date("Y.m.d")));
            return $sqlData->result();
        }


    }