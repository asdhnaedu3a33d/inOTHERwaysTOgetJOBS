<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_schedule extends CI_Model
{
	var $table_schedule = 't_schedule';
	var $primary_schedule = 'id';

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id','date_start','date_end','title','information');
		$sql = "
			SELECT * FROM t_schedule
				WHERE (title LIKE '%".$search['value']."%' 
				OR information LIKE '%".$search['value']."%')
			order by ".$order_arr[$order["column"]]." ".$order["dir"]."
            limit $start,$length
		";

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_schedule);
		
		$this->db->like("title", $search['value']);
		$this->db->or_like("information", $search['value']);
				
		$result = $this->db->count_all_results();
		return $result;
	}

	function get_schedule_by_id($id)
	{
		$sql = "
				SELECT *
				FROM t_schedule
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

	function simpan_schedule($data_schedule)			
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();
		

		//$data_schedule->date_cru		= Formatting::get_datetime();
		//$data_schedule->user_cru		= $this->session->userdata('username');
		
		$this->db->set($data_schedule);
    	$this->db->insert('t_schedule');

		$this->db->trans_complete();
		return $this->db->trans_status();		
	}

    function update_schedule($data,$id,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }

    function delete_schedule($id){
   	    $this->db->trans_strict(FALSE);
		$this->db->trans_start();
	    
		$this->db->where('id',$id);
        $this->db->delete('t_schedule');

		
		$this->db->trans_complete();

		return $this->db->trans_status();
    }
}