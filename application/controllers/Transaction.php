<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
        $this->load->library('session');
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

    public function import_page($store)
    {
        extract(populateform());
        $data['title']          = 'Rambla | Sales Transaction';
        $data['username']       = $this->input->cookie('cookie_invent_user');

        $data['store'] = $store;

        // CEK PRIVILAGE
        $data['site'] = $this->db->query("SELECT a.branch_id, b.branch_name from m_user_site a
        inner join m_branches b
        on a.branch_id = b.branch_id
        where a.flagactv ='1'
        and username ='" . $data['username'] . "' and a.branch_id ='" . $store . "'")->row();

        if (!$data['site']) {
            die("<script language='JavaScript'>alert('Akses dilarang!!!'); document.location='" . base_url() . "Transaction/upload_sales'</script>");
        }

        $this->load->view('template_member/header', $data);
        $this->load->view('template_member/navbar', $data);
        $this->load->view('template_member/sidebar', $data);
        $this->load->view('transaction/import_page', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function import_process()
    {
        extract(populateform());
        $unlink_file        = $_SERVER['DOCUMENT_ROOT'] . "/report-service/assets/excel/" . $namafile;
        $file_path          = base_url() . '/assets/excel/' . $namafile;
        $data['username']   = $this->input->cookie('cookie_invent_user');
        $data['status']     = 0;
        $data['message']    = "";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $_ENV['APICENTRALDEV'] . 'ops/pos/sales/upload',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('file' => new CURLFILE($file_path), 'user_id' => $data['username'], 'branch_id' => $store),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $_ENV['APIAUTHVALUE']
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $message = "";

        $hasil = json_decode($response);
        if ($hasil) {
            if ($hasil->status == "error") {
                foreach ($hasil->errors as $row) {
                    $message .= $row . " \n";
                }
                // unlink($file_path);
                $data['status']     = 0;
                $data['message']    = $message;
                // $this->session->set_flashdata('failed-upload', $message);
                // redirect(base_url() . "Transaction/import_page/".$store);
            } else if ($hasil->status == "success") {
                $message = "Data berhasil di upload!";
                $data['status']     = 1;
                $data['message']    = $message;
                // $this->session->set_flashdata('success-upload', $message);
                // redirect(base_url() . "Transaction/upload_sales");
            }
        } else {
            $message = "Upload gagal, silakan diulang kembali!";
            // unlink($file_path);
            $data['status']     = 0;
            $data['message']    = $message;
            // $this->session->set_flashdata('failed-upload', $message);
            // redirect(base_url() . "Transaction/import_page/".$store);
        }
        unlink($unlink_file);
        echo json_encode($data);
    }

    public function update_transaksi()
    {
        extract(populateform());

        $data = array();
        $status_err = array();
        $message_err = "";

        // Mulai transaksi database
        try {
            foreach ($selectedRows as $row) {
                $no_ref = $row["no_ref"];
                $username   = $this->input->cookie('cookie_invent_user');
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $_ENV['APICENTRALDEV'] . 'ops/pos/sales/upload/update',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('no_ref' => $no_ref, 'user_id' => $username, 'branch_id' => $store, 'type' => $status),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $_ENV['APIAUTHVALUE']
                    ),
                ));

                $response = curl_exec($curl);
                $hasil = json_decode($response);
                if ($hasil) {
                    if ($hasil->status == "error") {
                        $err_code       = 0;
                        $message        = "No Ref " . $no_ref . " gagal di update!";
                    } else if ($hasil->status == "success") {
                        $err_code       = 1;
                        $message        = "No Ref " . $no_ref . " berhasil di update!";
                    }
                    array_push($status_err, $err_code);
                    $message_err .= $message . " \n";
                } else {
                    throw new Exception('Curl error: ' . curl_error($curl));
                }
                curl_close($curl);  // Tutup cURL
            }
            if (in_array(0, $status_err)) {
                $data["status"] = 0;
                $data["message"] = $message_err;
            } else {
                $data["status"] = 1;
                $data["message"] = "Data berhasil di update!";
            }
            echo json_encode($data);
        } catch (Exception $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    public function submit_transaksi()
    {
        extract(populateform());

        $data = array();
        $status_err = array();
        $message_err = "";

        // Mulai transaksi database
        try {
            foreach ($selectedRows as $row) {
                $no_ref = $row["no_ref"];
                $username   = $this->input->cookie('cookie_invent_user');
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $_ENV['APICENTRALDEV'] . 'ops/pos/sales/upload/submit',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('no_ref' => $no_ref, 'user_id' => $username, 'branch_id' => $store),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $_ENV['APIAUTHVALUE']
                    ),
                ));

                $response = curl_exec($curl);
                $hasil = json_decode($response);
                if ($hasil) {
                    if ($hasil->status == "error") {
                        $err_code       = 0;
                        $message        = "No Ref " . $no_ref . " gagal di approve!";
                    } else if ($hasil->status == "success") {
                        $err_code       = 1;
                        $message        = "No Ref " . $no_ref . " berhasil di approve!";
                    }
                    array_push($status_err, $err_code);
                    $message_err .= $message . " \n";
                } else {
                    throw new Exception('Curl error: ' . curl_error($curl));
                }
                curl_close($curl);  // Tutup cURL
            }
            if (in_array(0, $status_err)) {
                $data["status"] = 0;
                $data["message"] = $message_err;
            } else {
                $data["status"] = 1;
                $data["message"] = "Data berhasil di approve!";
            }
            echo json_encode($data);
        } catch (Exception $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    public function upload_sales()
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
        $this->load->view('transaction/upload_sales', $data);
        $this->load->view('template_member/footer', $data);
    }

    public function list_sales_history()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getSalesHistory($postData);
        echo json_encode($data);
    }

    public function list_sales_today()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getSalesToday($postData);
        echo json_encode($data);
    }

    public function hapus_transaksi()
    {
        extract(populateform());
        try {
            foreach ($selectedRows as $row) {
                $no_ref = $row["no_ref"];
                $this->M_Sales->hapusSalesUpload($no_ref, $store);
            }
            $data["status"] = 1;
            $data["message"] = "Data berhasil di hapus!";
            echo json_encode($data);
        } catch (Exception $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    public function list_detail_sales_upload()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getDetailSalesUpload($postData);
        echo json_encode($data);
    }

    public function list_header_sales_upload()
    {
        $postData = $this->input->post();
        try {
            $data = $this->M_Sales->getHeaderSalesUpload($postData);
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Terjadi kesalahan saat memuat data: " . $e->getMessage()]);
        }
    }

    public function list_paid_today()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getPaidToday($postData);
        echo json_encode($data);
    }

    public function list_sales_detail_today()
    {
        $postData = $this->input->post();
        $data = $this->M_Sales->getSalesDetailToday($postData);
        echo json_encode($data);
    }

    public function cek_central()
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $postData = $this->input->post();
        // CEK EXIST RECORD
        $data1 = $dbCentral->query("SELECT * FROM t_sales_trans_hdr a
        inner join t_sales_trans_dtl b
        on a.trans_no = b.trans_no
        and substring(a.trans_no,7,2) = '" . $postData['store_code'] . "' and a.trans_no ='" . $postData['trans_no'] . "'")->row();

        // CEK JUMLAH RECORD
        $data2 = $dbCentral->query("SELECT * FROM (
        SELECT a.trans_no, count(b.barcode) as jml_record, sum(b.qty) as tot_qty, sum(net_price) as net_price FROM t_sales_trans_hdr a
        inner join t_sales_trans_dtl b
        on a.trans_no = b.trans_no
        and substring(a.trans_no,7,2) = '" . $postData['store_code'] . "' and a.trans_no ='" . $postData['trans_no'] . "'
        GROUP BY a.trans_no) a WHERE (jml_record = '" . $postData['jml_record'] . "' and tot_qty = '" . $postData['tot_qty'] . "' and net_price = '" . $postData['net_price'] . "')")->row();

        if ($data1 && $data2) {
            $data['hasil'] = 1;
        } else if ($data1 && !$data2) {
            $data['hasil'] = 2;
        } else {
            $data['hasil'] = 0;
        }


        echo json_encode($data);
    }

    public function insert_sales()
    {
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $postData = $this->input->post();
        // $data['hasil'] = $postData['trans_no'];
        $kode = substr($postData['trans_no'], 6, 2);
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

            // CEK METHOD
            if ($postData['method'] == "sync") {
                // CEK BOLEH DI DELETE NGGA
                // CEK HEADER CENTRAL
                $get_header = $dbCentral->query("SELECT * FROM t_sales_trans_hdr where trans_no = '" . $postData['trans_no'] . "'")->row();

                if ($get_header->flag_central == '0') {
                    // DELETE DATA CENTRAL
                    $dbCentral->query("DELETE FROM t_sales_trans_hdr where trans_no ='" . $postData['trans_no'] . "'");
                    $dbCentral->query("DELETE FROM t_sales_trans_dtl where trans_no ='" . $postData['trans_no'] . "'");
                    $dbCentral->query("DELETE FROM t_paid where trans_no ='" . $postData['trans_no'] . "'");
                    $dbCentral->query("DELETE FROM report_service.r_sales where trans_no ='" . $postData['trans_no'] . "'");
                } else {
                    $dbCentral->trans_rollback();
                    $data['status']     = 0;
                    $data['message']    = "Sales sudah settlement, tidak bisa di ubah!";
                    echo json_encode($data);
                    exit;
                }
            }

            $get_header_toko = $dbStore->query("SELECT * FROM dbserver_history.t_sales_trans_hdr where trans_no = '" . $postData['trans_no'] . "'")->row();

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
            $delivery_number = $get_header_toko->delivery_number;
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
            '" . $trans_no . "',
            '" . $trans_date . "',
            '" . $trans_time . "',
            '" . $cashier_id . "',
            '" . $member_id . "',
            '" . $total_qty . "',
            '" . $total_net . "',
            '" . $total_discount . "',
            '" . $total_fee . "',
            '" . $total_tax . "',
            '" . $total_amount . "',
            '" . $paid_amount . "',
            '" . $change_amount . "',
            '" . $rounding . "',
            '" . $add_point . "',
            '" . $spend_point . "',
            '" . $balance_point . "',
            '" . $delivery_type . "',
            '" . $notes . "',
            '" . $gift_msg . "',
            '" . $trans_status . "',
            '" . $upload_status . "',
            '" . $delivery_number . "',
            '" . $trx_source . "',
            '" . $shift . "',
            '" . $flag_return . "',
            '" . $no_ref . "'
            )");

            // CEK DTAIL TOKO
            $get_detail_toko = $dbStore->query("SELECT * FROM dbserver_history.t_sales_trans_dtl where trans_no = '" . $postData['trans_no'] . "'")->result();

            foreach ($get_detail_toko as $row) {
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
                    '" . $trans_no_dtl . "',
                    '" . $seq_dtl . "',
                    '" . $barcode_dtl . "',
                    '" . $article_code_dtl . "',
                    '" . $article_name_dtl . "',
                    '" . $supplier_pcode_dtl . "',
                    '" . $supplier_pname_dtl . "',
                    '" . $brand_dtl . "',
                    '" . $category_code_dtl . "',
                    '" . $option1_dtl . "',
                    '" . $varian_option1_dtl . "',
                    '" . $option2_dtl . "',
                    '" . $varian_option2_dtl . "',
                    '" . $option3_dtl . "',
                    '" . $varian_option3_dtl . "',
                    '" . $qty_dtl . "',
                    '" . $current_price_dtl . "',
                    '" . $price_dtl . "',
                    '" . $disc_pct_dtl . "',
                    '" . $disc_amt_dtl . "',
                    '" . $moredisc_pct_dtl . "',
                    '" . $moredisc_amt_dtl . "',
                    '" . $extradisc_pct_dtl . "',
                    '" . $extradisc_amt_dtl . "',
                    '" . $fee_dtl . "',
                    '" . $margin_code_dtl . "',
                    '" . $margin_number_dtl . "',
                    '" . $flag_flexi_dtl . "',
                    '" . $type_flex_dtl . "',
                    '" . $flag_tier_dtl . "',
                    '" . $type_tier_dtl . "',
                    '" . $flag_void_dtl . "',
                    '" . $promo_id_dtl . "',
                    '" . $net_price_dtl . "',
                    '" . $tax_dtl . "',
                    '" . $berat_dtl . "',
                    '" . $flag_tax_dtl . "',
                    '" . $sa_no_dtl . "',
                    '" . $tag_1_dtl . "',
                    '" . $tag_2_dtl . "',
                    '" . $tag_3_dtl . "',
                    '" . $tag_4_dtl . "',
                    '" . $tag_5_dtl . "',
                    '" . $margin_level_dtl . "',
                    '" . $sku_code_dtl . "',
                    '" . $article_number_dtl . "'
                )");
            }

            // CEK PAID TOKO
            $get_paid_toko = $dbStore->query("SELECT * FROM dbserver_history.t_paid where trans_no = '" . $postData['trans_no'] . "'")->result();

            foreach ($get_paid_toko as $row) {
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
                    '" . $trans_no_dtl . "',
                    '" . $seq_dtl . "',
                    '" . $mop_code_dtl . "',
                    '" . $card_number_dtl . "',
                    '" . $card_name_dtl . "',
                    '" . $paid_amount_dtl . "'
                )");
            }

            // INSERT REPORTING SALES
            $dbCentral->query("INSERT INTO report_service.r_sales (periode, DIVISION, SUB_DIVISION, category_code, DEPT, SUB_DEPT, brand_code, brand_name, barcode, article_name, varian_option1, varian_option2, price, vendor_code, vendor_name,
            margin, tot_qty, disc_pct, total_disc_amt, moredisc_pct, total_moredisc_amt, gross, net_bf, net_af, gross_after_margin, tag_5, vendor_type, fee, trans_no, no_ref,
            source_data, branch_id, article_code, tot_berat, trans_status)
            select periode, DIVISION, SUB_DIVISION, category_code, DEPT, SUB_DEPT, brand_code, brand_name, barcode, article_name, varian_option1, varian_option2, price, vendor_code, vendor_name,
            margin, tot_qty, disc_pct, total_disc_amt, moredisc_pct, total_moredisc_amt, gross, net_bf, net_af, gross_after_margin, tag_5, vendor_type, fee, trans_no, no_ref,
            source_data, branch_id, article_code, tot_berat, trans_status from v_laporan_penjualan_perartikel_all
            where trans_no = '" . $postData['trans_no'] . "'");

            $dbCentral->trans_complete();
            // Check the transaction status
            if ($dbCentral->trans_status() === FALSE) {
                // Transaction failed, so rollback
                $data['status']     = 0;
                $data['message']    = "Transaction failed, rolling back.";
                //throw new Exception('Transaction failed, rolling back.');
            } else {
                $dbCentral->query("call insert_log_data('t_sales_trans_hdr','" . $postData['trans_no'] . "','" . $data['username'] . "','')");
                $dbCentral->query("call insert_log_data('t_sales_trans_dtl','" . $postData['trans_no'] . "','" . $data['username'] . "','')");
                $dbCentral->query("call insert_log_data('t_paid','" . $postData['trans_no'] . "','" . $data['username'] . "','')");
                $data['status'] = 1;
                // echo "Transaction successful!";
            }
        } catch (Exception $e) {
            // In case of error, rollback
            $dbCentral->trans_rollback();
            $data['status']     = 0;
            $data['message']    = $e->getMessage();
            // echo "Error: " . $e->getMessage();
        }

        echo json_encode($data);
    }
}
