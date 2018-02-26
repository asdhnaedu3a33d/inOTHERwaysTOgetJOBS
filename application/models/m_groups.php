<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_groups extends CI_Model
{
    var $table = 'm_groups';

    public function __construct()
    {
        parent::__construct();
    }

    function get_group($where = array()){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		return $result->result_array();
	}

  function get_group_dee(){
    $this->db->select('id_groups AS id, nama_group AS nama');
		$result = $this->db->get($this->table);
		return $result->result();
  }
	/*
	function get_one_skpd($where = array()){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		return $result->row();
	}

	function get_data_dropdown_skpd($where = array(), $all=FALSE, $first_null=FALSE){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		$data = $result->result();

		$result= array();

		if ($first_null) {
			$result[''] = "~~ Pilih SKPD ~~";
		}

		if ($all) {
			$result['all'] = "~~ Semua SKPD ~~";
		}

		foreach ($data as $row) {
			$result[$row->id_skpd] = $row->kode_skpd .". ". $row->nama_skpd;
		}
		return $result;
	}
	*/
	function get_groups_detail($where = array()){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		return $result;
	}

	function get_groups_autocomplete($search){
		$this->db->select('id_groups AS id, nama_group AS label');
		$this->db->like('nama_group', $search);
		$result = $this->db->get($this->table);
		return $result->result();
	}
}
?>
