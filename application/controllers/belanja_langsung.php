<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Belanja_langsung extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_belanja'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		//$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		$data['url_add_data'] = site_url('belanja_langsung/edit_data');
		$data['url_load_data'] = site_url('belanja_langsung/load_data');
		$data['url_delete_data'] = site_url('belanja_langsung/delete_data');
		$data['url_edit_data'] = site_url('belanja_langsung/edit_data');
	$this->template->load('template','keg_belanja/belanja_langsung/view_belanja_langsung',$data);
	}

	function load_data()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$belanja = $this->m_belanja->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_belanja->count_data_table($search, $start, $length, $order["0"]);
		//var_dump($belanja);
		$data = array();
		$no=0;
		foreach ($belanja as $row){
			$no++;
			$data[] = array(
				$no,
				$row->jenis_belanja,
				$row->nominal_th1,
				$row->nominal_th2,
				$row->nominal_th3,
				$row->nominal_th4,
				$row->nominal_th5,
				'<a href="javascript:void(0)" onclick="edit_belanja('. $row->id_belanja .')" class="icon2-page_white_edit" title="Edit Data Belanja Langsung"/>
				<a href="javascript:void(0)" onclick="delete_belanja('. $row->id_belanja .')" class="icon2-delete" title="Hapus Data Belanja Langsung"/>'
			);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function cru_belanja()
	{
			$this->template->load('template','keg_belanja/belanja_langsung/cru_belanja');
	}

 	function save_data()
	{
		$this->auth->restrict();
		$tahun_input = $this->session->userdata('t_anggaran_aktif');
		$id_belanja		 	= $this->input->post('id_belanja');
		$call_from					= $this->input->post('call_from');
		$jenis_belanja 	= $this->input->post('jenis_belanja');
		$nominal_th1				= $this->input->post('nominal_th1');
		$nominal_th2	 			= $this->input->post('nominal_th2');
		$nominal_th3	 			= $this->input->post('nominal_th3');
		$nominal_th4				= $this->input->post('nominal_th4');
		$nominal_th5				= $this->input->post('nominal_th5');


		if(strpos($call_from, 'belanja_langsung/cru_belanja') != FALSE) {
			$call_from = '';
		}

		$data_belanja = $this->m_belanja->get_belanja_by_id($id_belanja);
		if(empty($data_belanja)) {
			//cek bank baru
			$data_belanja = new stdClass();
			$id_belanja = '';
		}

		$data_belanja->id_belanja				= $id_belanja;
		$data_belanja->jenis_belanja 			= $jenis_belanja;
		$data_belanja->nominal_th1			= $nominal_th1;
		$data_belanja->nominal_th2	 		= $nominal_th2;
		$data_belanja->nominal_th3	 		= $nominal_th3;
		$data_belanja->nominal_th4			= $nominal_th4;
		$data_belanja->nominal_th5			= $nominal_th5;
		$data_belanja->tahun_input 			= $tahun_input;
		$ret = TRUE;
		if(empty($id_belanja)) {
			//insert
			$ret = $this->m_belanja->simpan_belanja($data_belanja);
			//echo $this->db->last_query();
		} else {
			//update
			$ret = $this->m_belanja->update_belanja($data_belanja, $id_belanja, 'table_belanja', 'primary_belanja');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Belanja Langsung gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Belanja Langsung Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('belanja_langsung');
	}

	function edit_data($id_belanja=NULL)
	{
		$this->auth->restrict();
		$data['url_save_data'] = site_url('belanja_langsung/save_data');

		$data['isEdit'] = FALSE;
		if (!empty($id_belanja)) {
				$data_ = array('id_belanja'=>$id_belanja);
				$result = $this->m_belanja->get_data_with_rincian($id_belanja,'table_belanja');
				if (empty($result))	{
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data tidak ditemukan.');
					redirect('belanja_langsung');
				}

		$data['id_belanja']		= $result->id_belanja;
		$data['jenis_belanja'] = $result->jenis_belanja;
		$data['nominal_th1'] 				= $result->nominal_th1;
		$data['nominal_th2'] 				= $result->nominal_th2;
		$data['nominal_th3'] 				= $result->nominal_th3;
		$data['nominal_th4'] 				= $result->nominal_th4;
		$data['nominal_th5'] 				= $result->nominal_th5;
		$data['isEdit']						= TRUE;
		}
		$this->template->load('template','keg_belanja/belanja_langsung/cru_belanja',$data);

	}

	function delete_data()
	{
		$id = $this->input->post('id');
        $data_post = array('id_belanja'=>$id);
        $result = $this->m_belanja->hard_delete($data_post,'table_belanja');
        if($result){
            $arr = array(
                'success' => 1,
                'result' => $result
            );
        }
        else{
            $arr = array(
                'success' => 0,
                'result' => $result
            );
        }
        echo json_encode($arr);
	}
}
