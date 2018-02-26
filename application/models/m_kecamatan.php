<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kecamatan extends CI_Model
{
	var $table = 'm_kecamatan';
    public function __construct()
    {
        parent::__construct();
    }

	function get_kec_autocomplete($search){
		$this->db->select('id_kec AS id, nama_kec AS label');
		$this->db->like('nama_kec', $search);
		$result = $this->db->get($this->table);
		return $result->result();
	}

	function get_kec_dee(){
		$this->db->select('id_kec AS id, nama_kec AS nama');
		$result = $this->db->get($this->table);
		return $result->result();
	}

	function get_data_dropdown_kecamatan($where = array(), $all=FALSE, $first_null=FALSE){
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
			$result[''] = "~~ Pilih Kecamatan ~~";
		}

		foreach ($data as $row) {
			$result[$row->id_kec] = $row->nama_kec;
		}
		return $result;
	}
}
?>
