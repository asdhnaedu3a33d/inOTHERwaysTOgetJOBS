<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_urusan extends CI_Model 
{
       
    public function __construct()
    {
        parent::__construct();           
    }

    var $table_urusan = 'm_urusan';    

	function get_value_autocomplete_kd_urusan($search){
		$this->db->select('Kd_Urusan AS id, Nm_Urusan AS label');
		$this->db->like('Kd_Urusan', $search);
		$this->db->or_like('Nm_Urusan', $search);
		$result = $this->db->get($this->table_urusan);
		return $result->result();
	}

	function get_urusan(){
		$this->db->select('Kd_Urusan AS id, Nm_Urusan AS nama');		
		$result = $this->db->get($this->table_urusan);
		return $result->result();
	}
	function get_urusan_by_kode($kd_urusan){
		$this->db->select('Kd_Urusan AS id, Nm_Urusan AS nama');		
		$this->db->where('Kd_Urusan', $kd_urusan);
		$result = $this->db->get($this->table_urusan);
		return $result->row();
	}

	
}
?>