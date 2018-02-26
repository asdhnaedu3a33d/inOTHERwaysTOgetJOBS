<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class M_t_anggaran_aktif extends CI_Model
	{
		var $table = 'm_tahun_anggaran';

		public function __construct()
		{
			parent::__construct();
		}

		function get_data_dropdown_t_anggaran_aktif($where = array(), $all=FALSE, $first_null=FALSE)
		{		
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
				$result[''] = "~~ Pilih Tahun ~~";
			}	

			foreach ($data as $row) {
				$result[$row->tahun_anggaran] = $row->tahun_anggaran;
			}
			return $result;
		}

	}
?>