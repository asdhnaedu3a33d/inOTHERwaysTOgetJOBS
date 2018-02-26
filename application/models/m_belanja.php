<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_belanja extends CI_Model
{
	var $table_belanja_tl = 't_belanja';
	var $primary_belanja_tl = 'id_belanja';

	function hard_delete($data,$table){
        return $this->db->delete($this->$table, $data);
    }

  	function get_belanja_by_id($id_belanja)
	{
		$sql = "
				SELECT *
				FROM t_belanja
				WHERE id_belanja = ?
			";

		$query = $this->db->query($sql, array($id_belanja));
		var_dump($this->query);
		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}

		return NULL;
	}
	function get_data_with_rincian($id_belanja,$table)
	{
		$sql="
			SELECT * FROM ".$this->$table."
			WHERE id_belanja = ?
		";

		$query = $this->db->query($sql, array($id_belanja));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
	}

	function update_belanja($data,$id,$table,$primary) {
			$this->db->where($this->$primary,$id);
			return $this->db->update($this->$table,$data);
	}

	function simpan_belanja($data_btl)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->set($data_btl);
    	$this->db->insert('t_belanja');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_data_table($search, $start, $length, $order){
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$order_arr = array('id_belanja');
		$sql = "
				SELECT * FROM t_belanja
				WHERE jenis_belanja LIKE '%".$search['value']."%'
					AND tahun_input = ".$tahun."
					ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
					LIMIT $start,$length
		";
		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order){
				$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql="
				SELECT * FROM t_belanja
				WHERE jenis_belanja LIKE '%".$search['value']."%'
					AND tahun_input = ".$tahun."
        ";
		return $this->db->query($sql)->num_rows();
    }
}
