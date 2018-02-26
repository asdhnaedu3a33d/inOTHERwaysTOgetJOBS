<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_berita extends CI_Model
{
	var $table_berita = 't_berita';
	var $primary_berita = 'id';

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id','title','content');
		$sql = "
			SELECT * FROM t_berita
				WHERE (title LIKE '%".$search['value']."%' 
				OR content LIKE '%".$search['value']."%')
			order by ".$order_arr[$order["column"]]." ".$order["dir"]."
            limit $start,$length
		";

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_berita);
		
		$this->db->like("title", $search['value']);
		$this->db->or_like("content", $search['value']);
				
		$result = $this->db->count_all_results();
		return $result;
	}

	function get_berita_by_id($id)
	{
		$sql = "
				SELECT *
				FROM t_berita
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

	function simpan_berita($data_berita)			
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();
		

		$data_berita->date_cru		= Formatting::get_datetime();
		$data_berita->user_cru		= $this->session->userdata('username');
		
		$this->db->set($data_berita);
    	$this->db->insert('t_berita');

		$this->db->trans_complete();
		return $this->db->trans_status();		
	}

    function update_berita($data,$id,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }

    function delete_berita($id){
   	    $this->db->trans_strict(FALSE);
		$this->db->trans_start();
	    
		$this->db->where('id',$id);
        $this->db->delete('t_berita');

		
		$this->db->trans_complete();

		return $this->db->trans_status();
    }
}