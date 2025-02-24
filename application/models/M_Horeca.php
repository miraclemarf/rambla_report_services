<?php

class M_Horeca extends CI_Model
{

    private $redis_key_list = 'happyfresh_sales_cache';

    public function __construct()
    {
        parent::__construct();
        $this->load->database('dbcentral', TRUE);
        $this->load->library('redislib');
    }

    public function getHappyFreshSales()
    {
        // Check if cached data exists
        if ($this->redislib->exists($this->redis_key_list)) {
            return json_decode($this->redislib->get($this->redis_key_list), true);
        }
        return $this->setRedisHappyFreshSales();
    }


    public function setRedisHappyFreshSales()
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $dbCentral->select([
            'vs.trans_no',
            'periode',
            'th.trans_time',
            'DIVISION',
            'SUB_DIVISION',
            'DEPT',
            'SUB_DEPT',
            'vs.article_code',
            'barcode',
            'brand_code',
            'brand_name',
            'article_name',
            'price',
            'ms.member_name',
            'tot_qty',
            'tot_berat',
            'disc_pct',
            'total_disc_amt',
            'moredisc_pct',
            'total_moredisc_amt',
            'net_af as "net"',
            'gross'
        ]);
        $dbCentral->from('v_laporan_penjualan_perartikel_all vs');
        $dbCentral->join('t_sales_trans_hdr th', 'vs.trans_no = th.trans_no');
        $dbCentral->join('l_member_master_starry mg', 'mg.member_id = th.member_id', 'left');
        $dbCentral->join('l_member_master_special ms', 'ms.member_id = th.member_id', 'left');

        $dbCentral->where('vs.branch_id', 'V001');
        $dbCentral->where('COALESCE(th.member_id, "") != ""');
        $dbCentral->where('COALESCE(mg.member_name, "") = ""');
        $dbCentral->like('ms.member_name', 'Happy Fresh', 'after');
        //$dbCentral->where(' date_format(th.trans_date, "%Y.%m") = date_format(NOW(), "%Y.%m") ');

        $query = $dbCentral->get();
        $result = $query->result_array();

        // Store the entire list in Redis cache
        $this->redislib->set($this->redis_key_list, json_encode($result));

        return $result;
    }
}
