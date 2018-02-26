<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Setting extends CI_Controller{
        //setting tahun aktif
    
    var $CI = NULL;
    public function __construct()
    {                                
        $this->CI =& get_instance(); 
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));    
        $this->load->model(array('m_settings', 'm_logic', 'm_umum', 'm_lov'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
        
        
    }
	function change_ta(){
        $ta=$this->input->post('ta');
        $this->session->set_userdata('t_anggaran_aktif', $ta);        
        echo "success";
    }
    
}
?>