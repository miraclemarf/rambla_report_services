<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Categories');
        $this->ceklogin();
    }

    public function index()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Dashboard Page';
        $data['username']       = $this->input->cookie('cookie_invent_user');

        $where = "";

        $data['store_name']     = "";

        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();
        if ($cek_user_category) {
            $where .= $this->M_Categories->get_category($data['username']);
        } else {
            $where .= "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        $data['site'] = $this->db->query("SELECT a.branch_id, b.branch_name from m_user_site a
        inner join m_branches b
        on a.branch_id = b.branch_id
        where a.flagactv ='1'
        and username ='" . $data['username'] . "'")->result();

        if ($data['site']) {
            $data['store_name'] = $data['site'][0]->branch_name;
            $store = !$this->input->post('storeid') ? $data['site'][0]->branch_id : $this->input->post('storeid');
        } else {
            $data['store_name'] = 'Rambla Kelapa Gading';
            $store = !$this->input->post('storeid') ? 'R001' : $this->input->post('storeid');
        }




        $data['storeid']        =  $store ? $store : 'R001';

        $data['year']           = $this->Models->showdata("SELECT DISTINCT YEAR(periode) as tahun from r_sales");

        $data['list_brand']     = $this->Models->showdata("SELECT DISTINCT brand, brand_name from m_user_brand a
        inner join m_brand b
        on a.brand = b.brand_code
        where username ='" . $data['username'] . "'");

        // $data['omset_date']      = $this->Models->showdata("SELECT * from r_sales
        // WHERE MONTH(periode) ='" . date('m') . "' and YEAR(periode) ='" . date('Y') . "' $where  GROUP BY periode order by periode");

        // $data['omset_pos']      = $this->Models->showdata("SELECT SUM(net_af) as net, date_format(periode,'%Y-%m-%d') as periode
        // from r_sales
        // WHERE MONTH(periode) ='" . date('m') . "' 
        // and YEAR(periode) ='" . date('Y') . "' 
        // $where
        // and substring(trans_no, 9, 1) != '5'
        // GROUP BY periode
        // order by periode");

        // $data['omset_apps']      = $this->Models->showdata("SELECT SUM(net_af) as net, date_format(periode,'%Y-%m-%d') as periode
        // from r_sales
        // WHERE MONTH(periode) ='" . date('m') . "' 
        // and YEAR(periode) ='" . date('Y') . "' 
        // $where
        // and substring(trans_no, 9, 1) = '5'
        // GROUP BY periode
        // order by periode");

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('dashboard/index_new', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function get_salesbyday()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');

        $where = "";
        // if(($this->input->cookie('cookie_invent_tipe') == 10) or ($this->input->cookie('cookie_invent_tipe') == 03) or ($this->input->cookie('cookie_invent_tipe') == 07) or ($this->input->cookie('cookie_invent_tipe') == 02) or ($this->input->cookie('cookie_invent_tipe') == 13)){
        //     $where.= "AND brand_code in (
        //         select distinct brand from m_user_brand 
        //         where username = '".$data['username']."'
        //     )";
        // }else if($this->input->cookie('cookie_invent_tipe') == 15){
        //     $where.= $this->M_Categories->get_category($data['username']);
        // }else{
        //     $where.= "error";
        // }
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();
        if ($cek_user_category) {
            $where .= $this->M_Categories->get_category($data['username']);
        } else {
            $where .= "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA
        $data['hasil']          = $this->Models->showdata("SELECT
        date_format(periode, '%Y.%m.%d') AS periode,
        SUM(tot_qty) AS tot_qty,
        SUM(net_af) AS net
        FROM r_sales
        WHERE (date_format(periode, '%Y.%m') = date_format(current_date(),'%Y.%m')) 
        $where
        GROUP BY date_format(periode, '%Y.%m.%d')
        ORDER BY date_format(periode, '%Y.%m.%d')");
        echo json_encode($data);
    }

    public function get_salesbymonth()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');

        $where = "";
        // if(($this->input->cookie('cookie_invent_tipe') == 10) or ($this->input->cookie('cookie_invent_tipe') == 03) or ($this->input->cookie('cookie_invent_tipe') == 07) or ($this->input->cookie('cookie_invent_tipe') == 02) or ($this->input->cookie('cookie_invent_tipe') == 13)){
        //     $where.= "AND brand_code in (
        //         select distinct brand from m_user_brand 
        //         where username = '".$data['username']."'
        //     )";
        // }else if($this->input->cookie('cookie_invent_tipe') == 15){
        //     $where.= $this->M_Categories->get_category($data['username']);
        // }else{
        //     $where.= "error";
        // }
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();
        if ($cek_user_category) {
            $where .= $this->M_Categories->get_category($data['username']);
        } else {
            $where .= "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        $data['hasil']          = $this->Models->showdata("SELECT
        date_format(periode, '%Y.%m') AS periode,
        SUM(tot_qty) AS tot_qty,
        SUM(net_af) AS net
        FROM r_sales
        WHERE date_format(periode, '%Y.%m.%d') between '2023.03.01' and date_format(current_date(),'%Y.%m.%d')
        $where
        GROUP BY date_format(periode, '%Y.%m')
        ORDER BY date_format(periode, '%Y.%m')
        ");
        echo json_encode($data);
    }

    public function get_top10_rank($store)
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where = "";

        // if(($this->input->cookie('cookie_invent_tipe') == 10) or ($this->input->cookie('cookie_invent_tipe') == 03) or ($this->input->cookie('cookie_invent_tipe') == 07) or ($this->input->cookie('cookie_invent_tipe') == 02) or ($this->input->cookie('cookie_invent_tipe') == 13)){
        //     $where.= "AND brand_code in (
        //         select distinct brand from m_user_brand 
        //         where username = '".$data['username']."'
        //     )";
        // }else if($this->input->cookie('cookie_invent_tipe') == 15){
        //     $where.= $this->M_Categories->get_category($data['username']);
        // }else{
        //     $where.= "error";
        // }
        // START CEK ADA KATEGORINYA NGGA
        $cek_user_category = $this->db->query("SELECT * FROM m_user_category where username ='" . $data['username'] . "'")->row();
        if ($cek_user_category) {
            $where .= $this->M_Categories->get_category($data['username']);
        } else {
            $where .= "AND brand_code in (
                select distinct brand from m_user_brand 
                where username = '" . $data['username'] . "'
            )";
        }
        // END CEK ADA KATEGORINYA NGGA

        $data['hasil'] = $this->Models->showdata("SELECT * from (
            select ROW_NUMBER() OVER (order by sum(net_af) desc) ranking1, date_format(periode , '%Y.%m') periode1, brand_name as brand_name1 , sum(tot_qty) tot_qty1, sum(net_af) tnet1
            from r_sales 
            where date_format(periode , '%Y.%m') = date_format(DATE_ADD(current_date(), INTERVAL -2 MONTH),'%Y.%m') 
            $where
            and branch_id = '" . $store . "'
            group by date_format(periode , '%Y.%m'), brand_name1
            order by tnet1 desc
            limit 10	
        ) a inner join (
            select ROW_NUMBER() OVER (order by sum(net_af) desc) ranking2, date_format(periode , '%Y.%m') periode2, brand_name as brand_name2, sum(tot_qty) tot_qty2, sum(net_af) tnet2
            from r_sales 
            where date_format(periode , '%Y.%m') = date_format(DATE_ADD(current_date(), INTERVAL -1 MONTH),'%Y.%m') 
            $where
            and branch_id = '" . $store . "'
            group by date_format(periode , '%Y.%m'), brand_name2
            order by tnet2 desc
            limit 10
        ) b on a.ranking1 = b. ranking2 inner join (
            select ROW_NUMBER() OVER (order by sum(net_af) desc) ranking3, date_format(periode , '%Y.%m') periode3, brand_name as brand_name3, sum(tot_qty) tot_qty3, sum(net_af) tnet3
            from r_sales 
            where date_format(periode , '%Y.%m') = date_format(DATE_ADD(current_date(), INTERVAL 0 MONTH),'%Y.%m') 
            $where
            and branch_id = '" . $store . "'
            group by date_format(periode , '%Y.%m'), brand_name3
            order by tnet3 desc
            limit 10
        ) c on a.ranking1 = c.ranking3");
        echo json_encode($data);
    }


    function fetch_data_omset()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where = "";
        $from = date('Y-m-01');
        $to = date('Y-m-d');

        if (strlen($bulan) == '1') {
            $bulan = '0' . $bulan;
        } else {
            $bulan;
        }
        if ($bulan || $tahun || $brand) {
            $from   = $tahun . '-' . $bulan . '-' . '01';
            if ($bulan == date('m')) {
                $to     = $tahun . '-' . $bulan . '-' . date('d');
            } else {
                $to     = $this->db->query("SELECT LAST_DAY('" . $from . "') as last_day")->row();
                $to     = $to->last_day;
            }

            if ($brand == "all") {
                $where = "and brand_code in 
                (
                select DISTINCT brand from m_user_brand
                where username ='" . $data['username'] . "'
                )";
            } else {
                $where = "and brand_code ='" . $brand . "'";
            }
            if ($fa == 0) {
                $where .= " and substring(trans_no, 9, 1) != '5'";
            } else if ($fa == 1) {
                $where .= " and substring(trans_no, 9, 1) = '5'";
            }
            $data = $this->db->query("SELECT IFNULL(net, 0) as net,aa.periode from (
                SELECT DATE_ADD(DATE_ADD(DATE_ADD(LAST_DAY('" . $from . "'), INTERVAL 1 DAY), INTERVAL -1 MONTH), INTERVAL help_topic_id DAY) as periode
                FROM mysql.help_topic order by help_topic_id asc limit 31
            ) aa left join (
                SELECT SUM(net_af) as net, date_format(periode,'%Y-%m-%d') as periode
                from r_sales
                WHERE MONTH(periode) ='" . $bulan . "' 
                and YEAR(periode) ='" . $tahun . "' 
                $where
                GROUP BY periode
                order by periode
            ) bb on aa.periode = bb.periode    
            where aa.periode between '" . $from . "' and '" . $to . "'")->result();
        } else {
            $data = array('Data Kosong');
        }

        echo json_encode($data);
    }
}
