<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kendali_skpd extends CI_Model
{
	var $table_kendali_skpd = 't_kendali_skpd';
	var $primary_kendali_skpd = 'id_kendali_skpd';

	function get_data($data,$table){
        $this->db->where($data);
        $query = $this->db->get($this->$table);
        return $query->row();
    }

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id_kendali_skpd','kd_urusan','kd_bidang','kd_program','kd_kegiatan');
		$sql="
			SELECT * FROM ".$this->table_kendali_skpd."
			WHERE kd_urusan LIKE '%".$search['value']."%' 
            OR kd_bidang LIKE '%".$search['value']."%' 
            OR kd_program LIKE '%".$search['value']."%' 
            OR kd_kegiatan LIKE '%".$search['value']."%'
		";
		$this->db->limit($length, $start);
		$this->db->order_by($order_arr[$order["column"]], $order["dir"]);

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_kendali_skpd);
		
		$this->db->like("kd_urusan", $search['value']);
		$this->db->or_like("kd_bidang", $search['value']);
		$this->db->or_like("kd_program", $search['value']);
		$this->db->or_like("kd_kegiatan", $search['value']);
				
		$result = $this->db->count_all_results();
		return $result;
	}

}