<?php

class M_Stock extends CI_Model
{

    private $redis_key_list = 'v_full_s_item_stok_cache';

    public function __construct()
    {
        parent::__construct();
        $this->load->database('dbcentral', TRUE);
        $this->load->library('redislib');
    }

    public function getListStock($postData = null)
    {
        // Check if cached data exists
        if ($this->redislib->exists($this->redis_key_list)) {
            $data = json_decode($this->redislib->get($this->redis_key_list), true);

            // Use array_filter with dynamic conditions
            $filteredData = array_filter($data, function ($item) use ($postData) {
                foreach ($postData as $key => $value) {
                    if (isset($item[$key]) && $item[$key] !== $value) {
                        return false;
                    }
                }
                return true;
            });
            // UNTUK REINDEX ARRAY DARI 0
            return array_values($filteredData);
        }
        return $this->setListStock();
    }


    public function setListStock()
    {
        $dbCentral = $this->load->database('dbcentral', TRUE);
        $dbCentral->select([
            '*'
        ]);
        $dbCentral->from('v_full_s_item_stok');

        $query = $dbCentral->get();
        $result = $query->result_array();

        // Store the entire list in Redis cache
        $this->redislib->set($this->redis_key_list, json_encode($result));

        return $result;
    }
}
