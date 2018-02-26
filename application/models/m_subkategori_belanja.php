<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_subkategori_belanja extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    var $table = 'm_subkategori_belanja';

	function get_value_autocomplete_kd_subkategori_belanja($search, $kd_jenis_belanja, $kd_kategori_belanja){
		$this->db->select('kd_subkategori_belanja AS id, sub_kategori_belanja AS label');
		$this->db->where('kd_jenis_belanja', $kd_jenis_belanja);
		$this->db->where('kd_kategori_belanja', $kd_kategori_belanja);
		$this->db->where('(kd_subkategori_belanja LIKE \'%'. $search .'%\' OR kd_subkategori_belanja LIKE \'%'. $search .'%\')');
		$result = $this->db->get($this->table);
		return $result->result();
	}

	function get_subkategori_belanja_one($kd_jenis=NULL, $kd_kategori=NULL,$kd_subkategori = null){
		$this->db->select('kd_subkategori_belanja AS id, sub_kategori_belanja AS nama');
		if (!empty($kd_jenis)) {
			$this->db->where('kd_jenis_belanja', $kd_jenis);
		}
		if (!empty($kd_kategori)) {
			$this->db->where('kd_kategori_belanja', $kd_kategori);
		}
		if (!empty($kd_subkategori)) {
			$this->db->where('kd_subkategori_belanja', $kd_subkategori);
		}
		$result = $this->db->get($this->table);
		return $result->row();
	}

  function get_subkategori_belanja($kd_jenis=NULL, $kd_kategori=NULL){
    $this->db->select('kd_subkategori_belanja AS id, sub_kategori_belanja AS nama');
    if (!empty($kd_jenis)) {
      $this->db->where('kd_jenis_belanja', $kd_jenis);
    }
    if (!empty($kd_kategori)) {
      $this->db->where('kd_kategori_belanja', $kd_kategori);
    }
    $result = $this->db->get($this->table);
    return $result->result();
  }
}
?>
