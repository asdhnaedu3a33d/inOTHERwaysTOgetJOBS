<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_pembiayaan_terima extends CI_Model
{
	var $table_pembiayaan_terima = 't_pembiayaan_terima';
	var $primary_pembiayaan_terima = 'id_pembiayaan_terima';

	function hard_delete($data,$table){
        return $this->db->delete($this->$table, $data);
    }

  function get_pembiayaan_terima_by_id($id_belanja_tl)
	{
		$sql = "
				SELECT *
				FROM t_pembiayaan_terima
				WHERE id_pembiayaan_terima = ?
			";

		$query = $this->db->query($sql, array($id_pembiayaan_terima));
		var_dump($this->query);
		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}

		return NULL;
	}
	function get_data_with_rincian($id_pembiayaan_terima,$table)
	{
		$sql="
			SELECT * FROM ".$this->$table."
			WHERE id_pembiayaan_terima = ?
		";

		$query = $this->db->query($sql, array($id_pembiayaan_terima));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
	}

	function update_pembiayaan_terima($data,$id,$table,$primary) {
			$this->db->where($this->$primary,$id);
			return $this->db->update($this->$table,$data);
	}

	function simpan_pembiayaan_terima($pem_terima)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->set($pem_terima);
    	$this->db->insert('t_pembiayaan_terima');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_data_table($search, $start, $length, $order){
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$order_arr = array('id_pembiayaan_terima');
		$sql = "
				SELECT * FROM t_pembiayaan_terima
				WHERE jenis_pembiayaan_terima LIKE '%".$search['value']."%'
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
				SELECT * FROM t_pembiayaan_terima
				WHERE jenis_pembiayaan_terimaLIKE '%".$search['value']."%'
					AND tahun_input = ".$tahun."
        ";
		return $this->db->query($sql)->num_rows();
    }
}
