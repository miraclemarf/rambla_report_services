<?php

class M_Categories extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_category($username)
    {
        $user = $this->db->query("SELECT * FROM m_user_category where username ='" . $username . "'")->row();
        return $user->category;
    }

    function get_sub_division($username)
    {
        $user = $this->db->query("SELECT * FROM m_user_sub_division where username ='" . $username . "' and flagactv ='1'")->result();

        $data = array();

        foreach ($user as $row) {
            $data[] = $row->kode_sub_division;
        }
        return $data;
    }
}
