<?php

class M_Division extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_division($username, $store)
    {
        $filter = "";
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $username . "' and branch_id ='" . $store . "'")->row();

        if ($cek_user_site->RD == "1" and $cek_user_site->RS == "0") {
            $filter = "AND DIVISION ='Department store'";
        } else if ($cek_user_site->RD == "0" and $cek_user_site->RS == "1") {
            $filter = "AND DIVISION ='Supermarket'";
        } else if ($cek_user_site->RD == "1" and $cek_user_site->RS == "1") {
            $filter = "AND DIVISION in ('Department store','Supermarket')";
        }
        return $filter;
    }

    function get_division_filter($username, $store)
    {
        $data = "";
        $cek_user_site = $this->db->query("SELECT * from m_user_site where username ='" . $username . "' and branch_id ='" . $store . "'")->row();
        if ($cek_user_site->RD == "1" and $cek_user_site->RS == "0") {
            $data          = $this->Models->showdata("SELECT DISTINCT DIVISION, KODE_DIVISION from m_kategori_list WHERE DIVISION ='Department store'");
        } else if ($cek_user_site->RD == "0" and $cek_user_site->RS == "1") {
            $data          = $this->Models->showdata("SELECT DISTINCT DIVISION, KODE_DIVISION from m_kategori_list WHERE DIVISION ='Supermarket'");
        } else if ($cek_user_site->RD == "1" and $cek_user_site->RS == "1") {
            $data          = $this->Models->showdata("SELECT DISTINCT DIVISION, KODE_DIVISION from m_kategori_list WHERE DIVISION in ('Department store','Supermarket')");
        }
        return $data;
    }
}
