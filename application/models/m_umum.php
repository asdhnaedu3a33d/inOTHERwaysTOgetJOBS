<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /**
 * @brief Model Class untuk mengakomodasi penerimaan
 *
 * Model Class untuk mengambil data dari database yang sering digunakan berulang
 *
 */
class M_umum extends CI_Model{    
    function get_under_construction(){
        $this->db->from('under_construction');        
        $hasil=$this->db->get();
        $hasil = $hasil->row();
        return $hasil->website_running;
    }
	
	function get_slider() {
		$sql = "
	 		SELECT *
	 		FROM t_slider a
	 		WHERE aktif = 1
	 		";

    	$query = $this->db->query($sql);
    	if($query->num_rows() > 0) {
	    	return $query->result();
    	}
    	return FALSE;
	}
	
}
