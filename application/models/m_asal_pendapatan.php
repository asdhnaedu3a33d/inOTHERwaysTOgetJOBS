<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_asal_pendapatan extends CI_Model 
{
       
    public function __construct()
    {
        parent::__construct();           
    }
    
    var $table_asal_pendapatan = 'm_asal_pendapatan';    

	function get_value_autocomplete_asal_pendapatan($search, $kd_jenis){
		$this->db->select('kd_asal AS id, asal_pendapatan AS label');
		$this->db->where('kd_jenis', $kd_jenis);
		$this->db->where('(kd_asal LIKE \'%'. $search .'%\' OR asal_pendapatan LIKE \'%'. $search .'%\')');    		 
		$result = $this->db->get($this->table_asal_pendapatan);
		return $result->result();
	}

	function get_asal_pendapatan($kd_jenis=NULL){
		$this->db->select('kd_asal AS id, asal_pendapatan AS nama');
		if (!empty($kd_jenis)) {
			$this->db->where('kd_jenis', $kd_jenis);
		}		
		$result = $this->db->get($this->table_asal_pendapatan);
		return $result->result();	
	}
}
?>