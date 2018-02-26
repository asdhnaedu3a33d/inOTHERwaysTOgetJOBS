<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_template_cetak extends CI_Model {		
    var $table = "m_template_cetak";

    function get_value($kode){
        $this->db->from($this->table);
        $this->db->where('kode_setting', $kode);
        $result = $this->db->get();
        $result = $result->row();
        return $result->template;
    }    
}
?>