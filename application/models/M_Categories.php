<?php

    class M_Categories extends CI_Model
    {
        function __construct()
        {
            parent::__construct();
        }
 
        function get_category($username)
        {
            $user = $this->db->query("SELECT * FROM m_user_category where username ='".$username."'")->row();
            return $user->category;
        }

    }