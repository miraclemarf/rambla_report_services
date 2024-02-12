<?php
class Models extends CI_Model
{

	function save($table, $data)
	{
		$save = $this->db->insert($table, $data);
		if ($save) {
			return true;
		} else {
			return false;
		}
	}

	function fields($tbl)
	{
		return $this->db->list_fields($tbl);
	}

	function save_array($table, $data)
	{
		$save = $this->db->insert_batch($table, $data);
		if ($save) {
			return true;
		} else {
			return false;
		}
	}

	function check_data($condition, $table)
	{
		$this->db->where($condition);
		$query = $this->db->get($table);
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function showdata($sql)
	{
		$results = array();
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$results = $query->result();
		}
		return $results;
	}

	function showdata_monthly($sql)
	{
		$query = $this->db->query($sql)->result_array();
		if ($query) {
			return $query;
		} else {
			return false;
		}
	}

	function showsingle($sql)
	{
		$query = $this->db->query($sql)->row();
		if ($query) {
			return $query;
		} else {
			return false;
		}
	}

	function deletepermanent($kondisi, $table)
	{
		$this->db->where($kondisi);
		$this->db->delete($table);
	}

	function update($kondisi, $table, $data)
	{
		$this->db->where($kondisi);
		$this->db->update($table, $data);
	}

	function queryhandle($query)
	{
		$this->db->query($query);
	}

	
}
