<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_file_publik extends CI_Model
{
	var $table_file_publik = 't_file_4_publik';
	var $primary_file_publik = 'id';

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id','title','keterangan','nama_file');
		$sql = "
			SELECT * FROM t_file_4_publik
				WHERE (title LIKE '%".$search['value']."%'
				OR keterangan LIKE '%".$search['value']."%'
				OR nama_file LIKE '%".$search['value']."%')
			order by ".$order_arr[$order["column"]]." ".$order["dir"]."
            limit $start,$length
		";

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_file_publik);

		$this->db->like("title", $search['value']);
		$this->db->or_like("keterangan", $search['value']);
		$this->db->or_like("nama_file", $search['value']);

		$result = $this->db->count_all_results();
		return $result;
	}

	function get_file_publik_by_id($id)
	{
		$sql = "
				SELECT *
				FROM t_file_4_publik
				WHERE id = ?
			";

		$query = $this->db->query($sql, array($id));

		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}

		return NULL;
	}

	function get_data_with_rincian($id,$table)
	{
		$sql="
			SELECT * FROM ".$this->$table."
			WHERE id = ?
		";

		$query = $this->db->query($sql, array($id));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
	}

	function simpan_file_publik($data_file_publik)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();


		$data_file_publik->date_cru		= Formatting::get_datetime();
		$data_file_publik->user_cru		= $this->session->userdata('username');

		$this->db->set($data_file_publik);
    $this->db->insert('t_file_4_publik');
		$id = $this->db->insert_id();

		$this->db->trans_complete();
		return $id;
	}

    function update_file_publik($data,$id,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }

    function delete_file_publik($id){
   	    $this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id',$id);
        $this->db->delete('t_file_4_publik');


		$this->db->trans_complete();

		return $this->db->trans_status();
    }
}
