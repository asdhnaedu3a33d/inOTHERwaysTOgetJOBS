<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_jenis_belanja extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    var $table = 'm_jenis_belanja';

	function get_value_autocomplete_kd_jenis_belanja($search){
		$this->db->select('kd_jenis_belanja AS id, jenis_belanja AS label');
		$this->db->like('kd_jenis_belanja', $search);
		$this->db->or_like('jenis_belanja', $search);
		$result = $this->db->get($this->table);
		return $result->result();
	}

	function get_jenis_belanja(){
		$this->db->select('kd_jenis_belanja AS id, jenis_belanja AS nama');
		$result = $this->db->get($this->table);
		return $result->result();
	}
  
	function get_jenis_belanja_one($kode_jenis = Null){
		$this->db->select('kd_jenis_belanja AS id, jenis_belanja AS nama');
		if (!empty($kode_jenis)) {
			$this->db->where('kd_jenis_belanja',$kode_jenis);
		}

		$result = $this->db->get($this->table);
		return $result->row();
	}
}
?>
