<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_jenis_pendapatan extends CI_Model 
{
       
    public function __construct()
    {
        parent::__construct();           
    }

    var $table_jenis_pendapatan = 'm_jenis_pendapatan';    

	function get_value_autocomplete_jenis_pendapatan($search){
		$this->db->select('kd_jenis AS id, nama_jenis_pendapatan AS label');
		$this->db->like('kd_jenis', $search);
		$this->db->or_like('nama_jenis_pendapatan', $search);
		$result = $this->db->get($this->table_jenis_pendapatan);
		return $result->result();
	}

	function get_jenis_pendapatan(){
		$this->db->select('kd_jenis AS id, nama_jenis_pendapatan AS nama');		
		$result = $this->db->get($this->table_jenis_pendapatan);
		return $result->result();
	}

}
?>