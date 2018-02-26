<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Berita extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));        
        $this->load->database();
        $this->load->model('m_berita','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		$this->auth->restrict();
		$data['url_delete_data'] = site_url('berita/delete_berita');
		$this->template->load('template','berita/berita_view',$data);
	}

	function load_berita()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		
		$data_file = $this->m_berita->get_data_table($search, $start, $length, $order["0"]);		
		$alldata = $this->m_berita->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;


		foreach ($data_file as $row) {
			$no++;
			$data[] = array(
							$no, 
							$row->title,
                            $row->content,			
							'<a href="javascript:void(0)" onclick="edit_berita('. $row->id .')" class="icon2-page_white_edit" title="Edit File Publik"/>
							<a href="javascript:void(0)" onclick="delete_berita('. $row->id .')" class="icon2-delete" title="Hapus File Publik"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		
        echo json_encode($json);
	}

	function cru_berita()
	{
		$this->template->load('template','berita/cru_berita');
	}

	function save_berita() 
	{
		$this->auth->restrict();
	        //action save cekbank di table t_cekbank
			$id 		 	= $this->input->post('id');
			$call_from		= $this->input->post('call_from');
			$title			= $this->input->post('title');
			$content 		= $this->input->post('content');

			if(strpos($call_from, 'berita/cru_berita') != FALSE) {
				$call_from = '';
			}
			//cek apakah cekbank tsb ada
			$data_berita = $this->m_berita->get_berita_by_id($id);
			if(empty($data_berita)) {
				//cek bank baru
				$data_berita = new stdClass();
				$id = '';
			}

			//all
			$data_berita->id			= $id;
			$data_berita->title			= $title;
			$data_berita->content		= $content;

			$ret = TRUE;
			if(empty($id)) {
				//insert
				$ret = $this->m_berita->simpan_berita($data_berita);
				//echo $this->db->last_query();
			} else {
				//update
				$$ret = $this->m_berita->update_berita($data_berita, $id,'table_berita', 'primary_berita');
				//echo $this->db->last_query();
			}
			if ($ret === FALSE){
	            $this->session->set_userdata('msg_typ','err');
	            $this->session->set_userdata('msg', 'Data Berita gagal disimpan');						  
			} else {
	            $this->session->set_userdata('msg_typ','ok');
	            $this->session->set_userdata('msg', 'Data Berita berhasil disimpan');
			}

			if(!empty($call_from))
				redirect($call_from);

	        redirect('berita');		
    }

    function edit_berita($id = NULL)
	{
        $this->auth->restrict();
        $data['url_save_data'] = site_url('berita/save_berita');

        $data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_berita->get_data_with_rincian($id,'table_berita');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data File Publik tidak ditemukan.');
				redirect('berita');
			}
			
            $data['id']					= $result->id;
            $data['title']				= $result->title;
            $data['content']			= $result->content;
            $data['isEdit']				= TRUE;
			    		
		}
        $this->template->load('template','berita/cru_berita', $data);
        
	}

	function delete_berita() 
	{  
        $id = $this->input->post('id');
        
        $result = $this->m_berita->delete_berita($id);
        if ($result) {
			$msg = array('success' => '1', 'msg' => 'Berita berhasil dihapus.');
			echo json_encode($msg);			
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Berita gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);			
		}
	}
}