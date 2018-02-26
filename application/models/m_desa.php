<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_desa extends CI_Model
{
	var $table = 'm_desa';
    public function __construct()
    {
        parent::__construct();
    }

	function get_desa_autocomplete($search, $id_kec){
		$this->db->select('id_desa AS id, nama_desa AS label');
		$this->db->where('id_kec', $id_kec);
		$this->db->like('nama_desa', $search);
		$result = $this->db->get($this->table);
		return $result->result();
	}

	function get_one_desa($where = array()){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		return $result->row();
	}

	function get_data_dropdown_desa($where = array(), $all=FALSE, $first_null=FALSE){
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
			$result[''] = "~~ Pilih Desa ~~";
		}

		foreach ($data as $row) {
			$result[$row->id_desa] = $row->nama_desa;
		}
		return $result;
	}

	function get_desa_dee($id_kec=NULL){
		$this->db->select('id_desa AS id, nama_desa AS label');
		if (!empty($id_kec)) {
			$this->db->where('id_kec', $id_kec);
		}
		$result = $this->db->get($this->table);
		return $result->result();
	}
}
?>
