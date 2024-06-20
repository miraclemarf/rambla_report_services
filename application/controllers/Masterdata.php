<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masterdata extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('models', '', TRUE);
        $this->load->model('M_Division');
        $this->ceklogin();
    }
    public function get_user_brand()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where = "";
        $where .= "AND brand in (
            select distinct brand from m_user_brand 
            where username = '" . $data['username'] . "'
        )";

        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['hasil']          = $this->Models->showdata("SELECT DISTINCT brand, brand_name from v_user_login_brand
        where 1=1 $where order by brand asc");
        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->brand . "'>" . $row->brand_name . " (" . $row->brand . ")</option>";
        }
    }

    public function get_list_barcode()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where = "";
        $where .= "AND brand in (
            select distinct brand from m_user_brand 
            where username = '" . $data['username'] . "'
        )";

        $data['hasil']          = $this->Models->showdata("SELECT barcode from v_list_barcode 
        where 1=1 $where");
        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->barcode . "'>" . $row->barcode . "</option>";
        }
    }

    public function get_list_prmotype()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $data['hasil']          = $this->Models->showdata("SELECT promo_type, promo_name from t_promo_type
        where isaktif = '1'");
        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->promo_type . "'>" . $row->promo_type . " (" . $row->promo_name . ")</option>";
        }
    }

    public function get_store()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $cek_user_branch        = $this->db->query("SELECT * FROM m_user_site where username ='" . $data['username'] . "' and flagactv = '1'")->row();

        if ($cek_user_branch) {
            $data['hasil']      = $this->Models->showdata("SELECT a.branch_id, b.branch_name from m_user_site a
            inner join m_branches b
            on a.branch_id = b.branch_id
            where a.flagactv ='1'
            and username ='" . $data['username'] . "'");
        } else {
            $data['hasil']      = $this->Models->showdata("SELECT branch_id, branch_name from m_branches");
        }

        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->branch_id . "'>" . $row->branch_name . " (" . $row->branch_id . ")</option>";
        }
    }

    public function get_role()
    {
        extract(populateform());
        $data['hasil']      = $this->Models->showdata("SELECT * from m_role
        where role_fl = '1'");
        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->role_id . "'>" . $row->role_name . "</option>";
        }
    }

    public function get_list_division()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $where = "";
        $where .= "AND brand_code in (
            select distinct brand from m_user_brand 
            where username = '" . $data['username'] . "'
        )";

        $cek_site = $this->db->query("SELECT * from m_user_site where username ='" . $data['username'] . "' and flagactv = '1' limit 1")->row();
        if ($cek_site) {
            $data['hasil']          = $this->M_Division->get_division_filter($data['username'], $store);
        } else {
            $data['hasil']          = $this->Models->showdata("SELECT DISTINCT DIVISION, KODE_DIVISION from m_kategori_list WHERE category_code in (
                select category_code from m_vendor_category
                WHERE 1=1 $where and isactive = '1'
            )");
        }

        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->DIVISION . "'>" . $row->DIVISION . " (" . $row->KODE_DIVISION . ")</option>";
        }
    }

    public function get_list_sub_division()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $and = "";
        $where = "";
        $where .= "AND brand_code in (
            select distinct brand from m_user_brand 
            where username = '" . $data['username'] . "'
        )";
        if ($division) {
            $and .= "AND DIVISION = '" . $division . "'";
        }
        $data['hasil']          = $this->Models->showdata("SELECT DISTINCT KODE_SUB_DIVISION, SUB_DIVISION from m_kategori_list
        where DIVISION in 
        (
        SELECT DISTINCT DIVISION from m_kategori_list WHERE category_code in (
            select category_code from m_vendor_category
            WHERE 1=1 $where and isactive = '1')
        ) $and");

        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->SUB_DIVISION . "'>" . $row->SUB_DIVISION . " (" . $row->KODE_SUB_DIVISION . ")</option>";
        }
    }

    public function get_list_dept()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $and = "";
        $where = "";
        $where .= "AND brand_code in (
            select distinct brand from m_user_brand 
            where username = '" . $data['username'] . "'
        )";
        if ($sub_division) {
            $and .= "AND SUB_DIVISION = '" . $sub_division . "'";
        }
        $data['hasil']          = $this->Models->showdata("SELECT DISTINCT DEPT, KODE_DEPT from m_kategori_list where SUB_DIVISION in 
        (
            SELECT DISTINCT SUB_DIVISION from m_kategori_list
            where DIVISION in 
            (
            SELECT DISTINCT DIVISION from m_kategori_list WHERE category_code in (
                select category_code from m_vendor_category
                WHERE 1=1 $where and isactive = '1')
        ))$and");

        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->DEPT . "'>" . $row->DEPT . " (" . $row->KODE_DEPT . ")</option>";
        }
    }

    public function get_list_sub_dept()
    {
        extract(populateform());
        $data['username']       = $this->input->cookie('cookie_invent_user');
        $and = "";
        $where = "";
        $where .= "AND brand_code in (
            select distinct brand from m_user_brand 
            where username = '" . $data['username'] . "'
        )";

        if ($dept) {
            $and .= "AND DEPT = '" . $dept . "'";
        }

        $data['hasil']          = $this->Models->showdata("SELECT DISTINCT KODE_SUB_DEPT, SUB_DEPT from m_kategori_list where DEPT in (
        SELECT DISTINCT DEPT from m_kategori_list where DIVISION in 
        (
            SELECT DISTINCT DIVISION from m_kategori_list WHERE category_code in (
                select category_code from m_vendor_category
                WHERE 1=1 $where and isactive = '1')
            )
        )$and");

        echo "<option value=''>-- Pilih Data --</option>";
        foreach ($data['hasil'] as $row) {
            echo "<option value='" . $row->SUB_DEPT . "'>" . $row->SUB_DEPT . " (" . $row->KODE_SUB_DEPT . ")</option>";
        }
    }
}
