<?php

    class M_Categories extends CI_Model
    {
        function __construct()
        {
            parent::__construct();
        }
 
        function get_category($username)
        {
            if($username == "TESSA"){
                $data = "AND left(category_code,4) = 'RDKC'";
                return $data;
            }
            if($username == "ADITYA"){
                $data = "AND category_code in (select DISTINCT CATEGORY_CODE from m_kategori_list where SUB_DIVISION in 
                (
                'Pria',
                'Wanita',
                'Anak',
                'Rumah tangga',
                'Koper',
                'Gaya Hidup Cute',
                'Olah Raga',
                'Bayi'
                )
                )";
                return $data;
            }
            if($username == "ROSITAH"){
                $data = "AND left(category_code,4) in ('RDGH','RDWA','RDAN','RDBY')";
                return $data;
            }

            if($username == "HERMIEN" or $username == "HENDRIK" or $username == "SENJAYA" or $username == "KIYATONO"){
                $data = "AND left(category_code,2) in ('RS')";
                return $data;
            }

        }

    }