<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_bulan extends CI_Model 
{
       
    public function __construct()
    {
        parent::__construct();           
    }

    var $table_bulan = 'm_bulan';    

	function get_value_autocomplete_kd_bulan($search){
		$this->db->select('id_bulan AS id, nm_bulan AS label');
		$this->db->like('id_bulan', $search);
		$this->db->or_like('nm_bulan', $search);
		$result = $this->db->get($this->table_bulan);
		return $result->result();
	}

	function get_bulan(){
		$this->db->select('id_bulan AS id, nm_bulan AS nama');		
		$result = $this->db->get($this->table_bulan);
		return $result->result();
	}
	
	function get_data_dropdown_bulan($where = array(), $all=FALSE, $first_null=FALSE){		
		$this->db->from($this->table_bulan);		

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}			
		}

		$result = $this->db->get();
		$data = $result->result();
		
		$result= array();
		
		if ($first_null) {
			$result[''] = "~~ Pilih Bulan ~~";
		}

		foreach ($data as $row) {
			$result[$row->id_bulan] = $row->nm_bulan;
		}
		return $result;
	}
}
?>