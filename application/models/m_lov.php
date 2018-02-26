<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_lov extends CI_Model {

    var $table_prioritas = 'm_prioritas';
    var $table_sumberdana = 'm_sumber_dana';
    var $table_keputusan = 'm_keputusan';
    var $table_status_rkpd = 'm_status_rkpd';



    function get_status_indikator(){
      $query = $this->db->query("SELECT * FROM m_status_indikator ORDER BY kode_status_indikator ASC");
      return $query->result();
    }

    function get_kategori_indikator(){
      $query = $this->db->query("SELECT * FROM m_kategori_indikator ORDER BY kode_kategori_indikator ASC");
      return $query->result();
    }


    function get_list_lov($kode_app, $exclude_items = NULL) {

        $sql = "
            SELECT kode_value, nama_value
            FROM m_lov
            WHERE kode_app={$kode_app} AND active = 1
			ORDER BY sort ASC
        ";

        $query = $this->db->query($sql);
        if($query) {
        	$lists = $query->result();

        	//cek jika ada yang di exclude, remove item
        	if(!is_null($exclude_items) && is_array($exclude_items)) {
			    foreach($lists as $key=>$val) {
			        if(in_array($val->kode_value, $exclude_items)) {
			            unset($lists[$key]);
			        }
			    }
        	}

            return $lists;
        }

        return NULL;
    }

    function create_lov($table,$value,$display_value,$where_val){
		$query = $this->db->get($this->$table);
        $data = '<option value="">--- Pilih Data ---</option>';
        foreach ($query->result() as $row)
        {
            if($where_val===$row->$value){
                $data .= "<option value='".$row->$value."' selected='selected'>".$row->$display_value."</option>";
            }else{
                $data .= "<option value='".$row->$value."'>".$row->$display_value."</option>";
            }
        }
        return $data;
	}

  function get_all_sumber_dana(){
    $query = $this->db->query("SELECT * FROM m_sumber_dana ORDER BY id ASC");
    return $query->result();
  }

}
?>
