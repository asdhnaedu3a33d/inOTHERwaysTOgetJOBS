<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kategori_belanja extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    var $table = 'm_kategori_belanja';

	function get_value_autocomplete_kd_kategori_belanja($search, $kd_jenis_belanja){
		$this->db->select('Kd_kategori_belanja AS id, kategori_belanja AS label');
		$this->db->where('kd_jenis_belanja', $kd_jenis_belanja);
		$this->db->where('(Kd_kategori_belanja LIKE \'%'. $search .'%\' OR kategori_belanja LIKE \'%'. $search .'%\')');
		$result = $this->db->get($this->table);
		return $result->result();
	}

	function get_kategori_belanja_one($kd_jenis=NULL, $kd_kategori=null){
		$this->db->select('Kd_kategori_belanja AS id, kategori_belanja AS nama');
		if (!empty($kd_jenis)) {
			$this->db->where('kd_jenis_belanja',$kd_jenis);
		}

		if (!empty($kd_kategori)) {
			$this->db->where('Kd_kategori_belanja', $kd_kategori);
		}
		$result = $this->db->get($this->table);
		return $result->row();
	}

  function get_kategori_belanja($kd_jenis=NULL, $kode_jenis=FALSE){
    $this->db->select('Kd_kategori_belanja AS id, kategori_belanja AS nama');
    if ($kode_jenis) {
      $this->db->where('kd_jenis_belanja AS id_jenis');
    }

    if (!empty($kd_jenis)) {
      $this->db->where('kd_jenis_belanja', $kd_jenis);
    }
    $result = $this->db->get($this->table);
    return $result->result();
  }
}
?>
