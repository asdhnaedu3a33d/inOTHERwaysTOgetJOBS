<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_program extends CI_Model 
{
       
    public function __construct()
    {
        parent::__construct();           
    }
    
    var $table_program = 'm_program';

	function get_value_autocomplete_kd_prog($search, $kd_urusan, $kd_bidang){
		$this->db->select('Kd_Prog AS id, Ket_Program AS label');
		$this->db->where('Kd_Urusan', $kd_urusan);
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('(Kd_Prog LIKE \'%'. $search .'%\' OR Ket_Program LIKE \'%'. $search .'%\')');
		$result = $this->db->get($this->table_program);
		return $result->result();
	}	

	function get_prog($kd_urusan=NULL, $kd_bidang=NULL, $modul=NULL, $id_modul=NULL){
		$this->db->select('Kd_Prog AS id, Ket_Program AS nama');
		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		if (!empty($kd_bidang)) {
			$this->db->where('Kd_Bidang', $kd_bidang);		
		}
		// if (!empty($modul)) {
		// 	$this->db->get($this->table_program);
		// 	$result = $this->db->query("SELECT kd_prog as id, ket_program as nama
		// 	FROM m_program
		// 	WHERE kd_urusan = '".$kd_urusan."' AND kd_bidang = '".$kd_bidang."'
		// 	AND kd_prog NOT IN 
		// 	(SELECT kd_program FROM ".$modul." WHERE ".$id_modul." AND kd_urusan = '".$kd_urusan."' AND kd_bidang = '".$kd_bidang."')");
		// 	return $result->result();
		// }
		$result = $this->db->get($this->table_program);
		return $result->result();
	}
	function get_prog_one($kd_urusan=NULL, $kd_bidang=NULL,$kd_program){
		$this->db->select('Kd_Prog AS id, Ket_Program AS nama');
		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		if (!empty($kd_bidang)) {
			$this->db->where('Kd_Bidang', $kd_bidang);		
		}	
		if (!empty($kd_program)) {
			$this->db->where('Kd_Prog', $kd_program);		
		}	
		$result = $this->db->get($this->table_program);
		return $result->row();
	}


}
?>