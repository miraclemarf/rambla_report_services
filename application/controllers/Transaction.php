<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Store');
        $this->load->model('M_Sales');
        $this->ceklogin();
    }

    public function push_sales()
    {
        extract(populateform());
        $data['title']          = 'Rambla | Sales Transaction';
        $data['username']       = $this->input->cookie('cookie_invent_user');

        $data['site'] = $this->db->query("SELECT a.branch_id, b.branch_name from m_user_site a
        inner join m_branches b
        on a.branch_id = b.branch_id
        where a.flagactv ='1'
        and username ='" . $data['username'] . "'")->result();

        if ($data['site']) {
            $data['storename'] = $data['site'][0]->branch_name;
            $store = !$this->input->post('storeid') ? $data['site'][0]->branch_id : $this->input->post('storeid');
        } else {
            $data['storename'] = 'Rambla Kelapa Gading';
            $store = !$this->input->post('storeid') ? 'R001' : $this->input->post('storeid');
        }

        $data['storeid']        =  $store ? $store : 'R001';

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('transaction/push_sales', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function list_sales_history()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getSalesHistory($postData);
        echo json_encode($data);
    }

    public function cek_central()
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $postData = $this->input->post();
        $data['hasil'] = $dbCentral->query("SELECT * FROM t_sales_trans_hdr a
        inner join t_sales_trans_dtl b
        on a.trans_no = b.trans_no
        and substring(a.trans_no,7,2) = '".$postData['store_code']."' and a.trans_no ='".$postData['trans_no']."'")->row();
        echo json_encode($data);
    }

    public function insert_sales()
    {
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $postData = $this->input->post();
        // $data['hasil'] = $postData['trans_no'];
        $kode = substr($postData['trans_no'],6,2);
        $store = "";
        if ($kode == "01") {
            $store = "R001";
            $dbStore = $this->load->database('storeR001', TRUE);
        } else if ($kode == "02") {
            $store = "R002";
            $dbStore = $this->load->database('storeR002', TRUE);
        } else if ($kode == "03") {
            $store = "V001";
            $dbStore = $this->load->database('storeV001', TRUE);
        } else if ($kode == "04") {
            $store = "S002";
            $dbStore = $this->load->database('storeS002', TRUE);
        } else if ($kode == "05") {
            $store = "S003";
            $dbStore = $this->load->database('storeS003', TRUE);
        } else if ($kode == "06") {
            $store = "V002";
            $dbStore = $this->load->database('storeV002', TRUE);
        } else if ($kode == "07") {
            $store = "V003";
            $dbStore = $this->load->database('storeV003', TRUE);
        }

        $dbCentral->trans_start();
        try {
            // CEK HEADER CENTRAL
            $get_header_central = $dbStore->query("SELECT * FROM t_sales_trans_hdr where trans_no = '".$postData['trans_no']."'")->row();

            if(!$get_header_central){
                $get_header_toko = $dbStore->query("SELECT * FROM dbserver_history.t_sales_trans_hdr where trans_no = '".$postData['trans_no']."'")->row();

                // INSERT HEADER
                $trans_no       = $get_header_toko->trans_no;
                $trans_date     = $get_header_toko->trans_date;
                $trans_time     = $get_header_toko->trans_time;
                $cashier_id     = $get_header_toko->cashier_id;
                $member_id      = $get_header_toko->member_id;
                $total_qty      = $get_header_toko->total_qty;
                $total_net      = $get_header_toko->total_net;
                $total_discount = $get_header_toko->total_discount;
                $total_fee      = $get_header_toko->total_fee;
                $total_tax      = $get_header_toko->total_tax;
                $total_amount   = $get_header_toko->total_amount;
                $paid_amount    = $get_header_toko->paid_amount;
                $change_amount  = $get_header_toko->change_amount;
                $rounding       = $get_header_toko->rounding;
                $add_point      = $get_header_toko->add_point;
                $spend_point    = $get_header_toko->spend_point;
                $balance_point  = $get_header_toko->balance_point;
                $delivery_type  = $get_header_toko->delivery_type;
                $notes          = $get_header_toko->notes;
                $gift_msg       = $get_header_toko->gift_msg;
                $trans_status   = $get_header_toko->trans_status;
                $upload_status  = $get_header_toko->upload_status;
                $delivery_number= $get_header_toko->delivery_number;
                $trx_source     = $get_header_toko->trx_source;
                $shift          = $get_header_toko->shift;
                $flag_return    = $get_header_toko->flag_return;
                $no_ref         = $get_header_toko->no_ref;

                $dbCentral->query("INSERT INTO t_sales_trans_hdr (
                trans_no,
                trans_date,
                trans_time,
                cashier_id,
                member_id,
                total_qty,
                total_net,
                total_discount,
                total_fee,
                total_tax,
                total_amount,
                paid_amount,
                change_amount,
                rounding,
                add_point,
                spend_point,
                balance_point,
                delivery_type,
                notes,
                gift_msg,
                trans_status,
                upload_status, 
                delivery_number,
                trx_source,
                shift,
                flag_return,
                no_ref) VALUES (
                '".$trans_no."',
                '".$trans_date."',
                '".$trans_time."',
                '".$cashier_id."',
                '".$member_id."',
                '".$total_qty."',
                '".$total_net."',
                '".$total_discount."',
                '".$total_fee."',
                '".$total_tax."',
                '".$total_amount."',
                '".$paid_amount."',
                '".$change_amount."',
                '".$rounding."',
                '".$add_point."',
                '".$spend_point."',
                '".$balance_point."',
                '".$delivery_type."',
                '".$notes."',
                '".$gift_msg."',
                '".$trans_status."',
                '".$upload_status."',
                '".$delivery_number."',
                '".$trx_source."',
                '".$shift."',
                '".$flag_return."',
                '".$no_ref."'
                )");
                
                // CEK DTAIL TOKO
                $get_detail_toko = $dbStore->query("SELECT * FROM dbserver_history.t_sales_trans_dtl where trans_no = '".$postData['trans_no']."'")->result();

                foreach($get_detail_toko as $row){
                    $trans_no_dtl           = $row->trans_no;
                    $seq_dtl                = $row->seq;
                    $barcode_dtl            = $row->barcode;
                    $article_code_dtl       = $row->article_code;
                    $article_name_dtl       = $row->article_name;
                    $supplier_pcode_dtl     = $row->supplier_pcode;
                    $supplier_pname_dtl     = $row->supplier_pname;
                    $brand_dtl              = $row->brand;
                    $category_code_dtl      = $row->category_code;
                    $option1_dtl            = $row->option1;
                    $varian_option1_dtl     = $row->varian_option1;
                    $option2_dtl            = $row->option2;
                    $varian_option2_dtl     = $row->varian_option2;
                    $option3_dtl            = $row->option3;
                    $varian_option3_dtl     = $row->varian_option3;
                    $qty_dtl                = $row->qty;
                    $current_price_dtl      = $row->current_price;
                    $price_dtl              = $row->price;
                    $disc_pct_dtl           = $row->disc_pct;
                    $disc_amt_dtl           = $row->disc_amt;
                    $moredisc_pct_dtl       = $row->moredisc_pct;
                    $moredisc_amt_dtl       = $row->moredisc_amt;
                    $extradisc_pct_dtl      = $row->extradisc_pct;
                    $extradisc_amt_dtl      = $row->extradisc_amt;
                    $fee_dtl                = $row->fee;
                    $margin_code_dtl        = $row->margin_code;
                    $margin_number_dtl      = $row->margin_number;
                    $flag_flexi_dtl         = $row->flag_flexi;
                    $type_flex_dtl          = $row->type_flex;
                    $flag_tier_dtl          = $row->flag_tier;
                    $type_tier_dtl          = $row->type_tier;
                    $flag_void_dtl          = $row->flag_void;
                    $promo_id_dtl           = $row->promo_id;
                    $net_price_dtl          = $row->net_price;
                    $tax_dtl                = $row->tax;
                    $berat_dtl              = $row->berat;
                    $flag_tax_dtl           = $row->flag_tax;
                    $sa_no_dtl              = $row->sa_no;
                    $tag_1_dtl              = $row->tag_1;
                    $tag_2_dtl              = $row->tag_2;
                    $tag_3_dtl              = $row->tag_3;
                    $tag_4_dtl              = $row->tag_4;
                    $tag_5_dtl              = $row->tag_5;
                    $margin_level_dtl       = $row->margin_level;
                    $sku_code_dtl           = $row->sku_code;
                    $article_number_dtl     = $row->article_number;

                    $dbCentral->query("INSERT INTO t_sales_trans_dtl (
                        trans_no,
                        seq,
                        barcode,
                        article_code,
                        article_name,
                        supplier_pcode,
                        supplier_pname,
                        brand,
                        category_code,
                        option1,
                        varian_option1,
                        option2,
                        varian_option2,
                        option3,
                        varian_option3,
                        qty,
                        current_price,
                        price,
                        disc_pct,
                        disc_amt,
                        moredisc_pct,
                        moredisc_amt,
                        extradisc_pct,
                        extradisc_amt,
                        fee,
                        margin_code,
                        margin_number,
                        flag_flexi,
                        type_flex,
                        flag_tier,
                        type_tier,
                        flag_void,
                        promo_id,
                        net_price,
                        tax,
                        berat,
                        flag_tax,
                        sa_no,
                        tag_1,
                        tag_2,
                        tag_3,
                        tag_4,
                        tag_5,
                        margin_level,
                        sku_code,
                        article_number
                        ) VALUES (
                        '".$trans_no_dtl."',
                        '".$seq_dtl."',
                        '".$barcode_dtl."',
                        '".$article_code_dtl."',
                        '".$article_name_dtl."',
                        '".$supplier_pcode_dtl."',
                        '".$supplier_pname_dtl."',
                        '".$brand_dtl."',
                        '".$category_code_dtl."',
                        '".$option1_dtl."',
                        '".$varian_option1_dtl."',
                        '".$option2_dtl."',
                        '".$varian_option2_dtl."',
                        '".$option3_dtl."',
                        '".$varian_option3_dtl."',
                        '".$qty_dtl."',
                        '".$current_price_dtl."',
                        '".$price_dtl."',
                        '".$disc_pct_dtl."',
                        '".$disc_amt_dtl."',
                        '".$moredisc_pct_dtl."',
                        '".$moredisc_amt_dtl."',
                        '".$extradisc_pct_dtl."',
                        '".$extradisc_amt_dtl."',
                        '".$fee_dtl."',
                        '".$margin_code_dtl."',
                        '".$margin_number_dtl."',
                        '".$flag_flexi_dtl."',
                        '".$type_flex_dtl."',
                        '".$flag_tier_dtl."',
                        '".$type_tier_dtl."',
                        '".$flag_void_dtl."',
                        '".$promo_id_dtl."',
                        '".$net_price_dtl."',
                        '".$tax_dtl."',
                        '".$berat_dtl."',
                        '".$flag_tax_dtl."',
                        '".$sa_no_dtl."',
                        '".$tag_1_dtl."',
                        '".$tag_2_dtl."',
                        '".$tag_3_dtl."',
                        '".$tag_4_dtl."',
                        '".$tag_5_dtl."',
                        '".$margin_level_dtl."',
                        '".$sku_code_dtl."',
                        '".$article_number_dtl."'
                    )");
                }

                // CEK PAID TOKO
                $get_paid_toko = $dbStore->query("SELECT * FROM dbserver_history.t_paid where trans_no = '".$postData['trans_no']."'")->result();

                foreach($get_paid_toko as $row){
                    $trans_no_dtl           = $row->trans_no;
                    $seq_dtl                = $row->seq;
                    $mop_code_dtl           = $row->mop_code;
                    $card_number_dtl        = $row->card_number;
                    $card_name_dtl          = $row->card_name;
                    $paid_amount_dtl        = $row->paid_amount;

                    $dbCentral->query("INSERT INTO t_paid (
                        trans_no,
                        seq,
                        mop_code,
                        card_number,
                        card_name,
                        paid_amount) VALUES (
                        '".$trans_no_dtl."',
                        '".$seq_dtl."',
                        '".$mop_code_dtl."',
                        '".$card_number_dtl."',
                        '".$card_name_dtl."',
                        '".$paid_amount_dtl."'
                    )");
                }

                // INSERT REPORTING SALES
                $dbCentral->query("INSERT INTO report_service.r_sales (periode, DIVISION, SUB_DIVISION, category_code, DEPT, SUB_DEPT, brand_code, brand_name, barcode, article_name, varian_option1, varian_option2, price, vendor_code, vendor_name,
                margin, tot_qty, disc_pct, total_disc_amt, moredisc_pct, total_moredisc_amt, gross, net_bf, net_af, gross_after_margin, tag_5, vendor_type, fee, trans_no, no_ref,
                source_data, branch_id, article_code, tot_berat, trans_status)
                select periode, DIVISION, SUB_DIVISION, category_code, DEPT, SUB_DEPT, brand_code, brand_name, barcode, article_name, varian_option1, varian_option2, price, vendor_code, vendor_name,
                margin, tot_qty, disc_pct, total_disc_amt, moredisc_pct, total_moredisc_amt, gross, net_bf, net_af, gross_after_margin, tag_5, vendor_type, fee, trans_no, no_ref,
                source_data, branch_id, article_code, tot_berat, trans_status from v_laporan_penjualan_perartikel_all
                where trans_no = '".$postData['trans_no']."'");

            }
            $dbCentral->trans_complete();
            // Check the transaction status
            if ($dbCentral->trans_status() === FALSE) {
                // Transaction failed, so rollback
                $data['status'] = 0;
                //throw new Exception('Transaction failed, rolling back.');
            } else {
                $dbCentral->query("call insert_log_data('t_sales_trans_hdr','".$postData['trans_no']."','".$data['username']."','')");
                $dbCentral->query("call insert_log_data('t_sales_trans_dtl','".$postData['trans_no']."','".$data['username']."','')");
                $dbCentral->query("call insert_log_data('t_paid','".$postData['trans_no']."','".$data['username']."','')");
                $data['status'] = 1;
                // echo "Transaction successful!";
            }
        } catch (Exception $e) {
            // In case of error, rollback
            $dbCentral->trans_rollback();
            $data['status'] = 0;
            // echo "Error: " . $e->getMessage();
        }

        echo json_encode($data);
    }
}
