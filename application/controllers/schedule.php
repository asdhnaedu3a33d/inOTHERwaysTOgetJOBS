<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Schedule extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));        
        $this->load->database();
        $this->load->model('m_schedule','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		$this->auth->restrict();
		$data['url_delete_data'] = site_url('schedule/delete_schedule');
		$this->template->load('template','schedule/schedule_view',$data);
	}

	function load_schedule()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		
		$data_file = $this->m_schedule->get_data_table($search, $start, $length, $order["0"]);		
		$alldata = $this->m_schedule->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;


		foreach ($data_file as $row) {
			$no++;
			$data[] = array(
							$no, 
							$row->date_start,
                            $row->date_end,
                            $row->title,
                            $row->information,			
							'<a href="javascript:void(0)" onclick="edit_schedule('. $row->id .')" class="icon2-page_white_edit" title="Edit Time Schedule"/>
							<a href="javascript:void(0)" onclick="delete_schedule('. $row->id .')" class="icon2-delete" title="Hapus Time Schedule"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		
        echo json_encode($json);
	}

	function cru_schedule()
	{
		$this->template->load('template','schedule/cru_schedule');
	}

	function save_schedule() 
	{
		$this->auth->restrict();
	        //action save cekbank di table t_cekbank
			$id 		 	= $this->input->post('id');
			$date_start		= $this->input->post('date_start');
			$date_end		= $this->input->post('date_end');
			$title		 	= $this->input->post('title');
			$information	= $this->input->post('information');

			if(strpos($call_from, 'schedule/cru_schedule') != FALSE) {
				$call_from = '';
			}
			//cek apakah cekbank tsb ada
			$data_schedule = $this->m_schedule->get_schedule_by_id($id);
			if(empty($data_schedule)) {
				//cek bank baru
				$data_schedule = new stdClass();
				$id = '';
			}

			//all
			$data_schedule->id			= $id;
			$data_schedule->date_start	= $date_start;
			$data_schedule->date_end	= $date_end;
			$data_schedule->title		= $title;
			$data_schedule->information = $information;

			$ret = TRUE;
			if(empty($id)) {
				//insert
				$ret = $this->m_schedule->simpan_schedule($data_schedule);
				//echo $this->db->last_query();
			} else {
				//update
				$$ret = $this->m_schedule->update_schedule($data_schedule, $id,'table_schedule', 'primary_schedule');
				//echo $this->db->last_query();
			}
			if ($ret === FALSE){
	            $this->session->set_userdata('msg_typ','err');
	            $this->session->set_userdata('msg', 'Data Time Schedule gagal disimpan');						  
			} else {
	            $this->session->set_userdata('msg_typ','ok');
	            $this->session->set_userdata('msg', 'Data Time Schedule berhasil disimpan');
			}

			if(!empty($call_from))
				redirect($call_from);

	        redirect('schedule');		
    }

    function edit_schedule($id = NULL)
	{
        $this->auth->restrict();
        $data['url_save_data'] = site_url('schedule/save_schedule');

        $data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_schedule->get_data_with_rincian($id,'table_schedule');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data Time Schedule tidak ditemukan.');
				redirect('schedule');
			}
			
            $data['id']					= $result->id;
            $data['date_start']			= $result->date_start;
            $data['date_end']			= $result->date_end;
            $data['title']				= $result->title;
            $data['information']		= $result->information;
            $data['isEdit']				= TRUE;
			    		
		}
        $this->template->load('template','schedule/cru_schedule', $data);
        
	}

	function delete_schedule() 
	{  
        $id = $this->input->post('id');
        
        $result = $this->m_schedule->delete_schedule($id);
        if ($result) {
			$msg = array('success' => '1', 'msg' => 'Time Schedule berhasil dihapus.');
			echo json_encode($msg);			
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Time Schedule gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);			
		}
	}
}