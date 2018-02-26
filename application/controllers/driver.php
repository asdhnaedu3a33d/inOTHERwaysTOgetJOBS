<?php

class Driver extends CI_Controller {

    public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
		$this->load->helper('url');
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}
    
    
    public function session(){
        echo "<pre>";
        var_dump($_SESSION);
        //var_dump($this->session->userdata('active_menu'));
        echo "</pre>";
    }


}
?>