<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_bidang extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    var $table_bidang = 'm_bidang';

	function get_value_autocomplete_kd_bidang($search, $kd_urusan){
		$this->db->select('Kd_Bidang AS id, Nm_Bidang AS label');
		$this->db->where('Kd_Urusan', $kd_urusan);
		$this->db->where('(Kd_Bidang LIKE \'%'. $search .'%\' OR Nm_Bidang LIKE \'%'. $search .'%\')');

		$result = $this->db->get($this->table_bidang);

		return $result->result();
	}

	function get_bidang($kd_urusan=NULL, $kode_urusan=FALSE){
		$this->db->select('Kd_Bidang AS id, Nm_Bidang AS nama');
		if ($kode_urusan) {
			$this->db->select('Kd_Urusan AS id_urusan');
		}

		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		$result = $this->db->get($this->table_bidang);
		return $result->result();
	}
	function get_bidang_new($kd_urusan=NULL, $kode_urusan=FALSE){
		$this->db->select('id AS id, Nm_Bidang AS nama');
		if ($kode_urusan) {
			$this->db->select('Kd_Urusan AS id_urusan');
		}

		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		$result = $this->db->get($this->table_bidang);
		return $result->result();
	}

  function get_bidang_dee($kd_urusan=NULL){
		$this->db->select('Kd_Bidang AS id, Nm_Bidang AS nama');

		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		$result = $this->db->get($this->table_bidang);
		return $result->result();
	}

	function get_bidang_array($kd_urusan=NULL, $kode_urusan=FALSE){
		$this->db->select('Kd_Bidang AS id, Nm_Bidang AS nama');
		if ($kode_urusan) {
			$this->db->select('Kd_Urusan AS id_urusan');
		}

		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		$result = $this->db->get($this->table_bidang);
		return $result->result_array();
	}

	function get_one_bidang($where){
		$this->db->select('Kd_Bidang AS id, Kd_Urusan AS id_urusan, Nm_Bidang AS nama');
		$this->db->where($where);

		$result = $this->db->get($this->table_bidang);
		return $result->row();
	}


}
?>
