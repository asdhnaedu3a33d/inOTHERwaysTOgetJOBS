<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @brief Controller Class untuk fungsi-fungsi menu admin
 * 
 * Controller Class admin berisi fungsi-fugsi untuk yang ada di menu admin
 * 
 * 
 * @author Putu Praba Santika
 */
class Under_construction extends CI_Controller
{  
	public function __construct()
	{                                
	    
	    parent::__construct();
	    $this->load->helper(array('form','url', 'text_helper','date'));       
	    $this->load->database();
	    $this->load->library(array('Pagination','image_lib'));	    
	    $this->load->model('m_umum'); 
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$uc = $this->m_umum->get_under_construction();
		if ($uc == 1) {
			redirect('authenticate/login');
		}
		$this->load->view('under_construction');
	}
}