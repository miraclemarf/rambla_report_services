<?php

    class M_Gold extends CI_Model
    {
        function __construct()
        {
            parent::__construct();
        }
 
        function get_data()
        {
            $dbGold = $this->load->database('gold', TRUE);
            $query = 'SELECT * FROM "KDS_STARMSTITEM" FETCH FIRST 10 ROWS ONLY';

            $sqlData = $dbGold->query($query);
            return $sqlData->result_array();
        }


    }