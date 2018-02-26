<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Belanja_tak_langsung extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_belanja_tl'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		//$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		$data['url_add_data'] = site_url('belanja_tak_langsung/edit_data');
		$data['url_load_data'] = site_url('belanja_tak_langsung/load_data');
		$data['url_delete_data'] = site_url('belanja_tak_langsung/delete_data');
		$data['url_edit_data'] = site_url('belanja_tak_langsung/edit_data');
		$this->template->load('template','keg_belanja/belanja_tak_langsung/view_belanja_tak_langsung',$data);
	}

	function load_data()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$belanja = $this->m_belanja_tl->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_belanja_tl->count_data_table($search, $start, $length, $order["0"]);
		//var_dump($belanja);
		$data = array();
		$no=0;
		foreach ($belanja as $row){
			$no++;
			$data[] = array(
				$no,
				$row->jenis_belanja_tl,
				$row->nominal_th1,
				$row->nominal_th2,
				$row->nominal_th3,
				$row->nominal_th4,
				$row->nominal_th5,
				'<a href="javascript:void(0)" onclick="edit_belanja_tl('. $row->id_belanja_tl .')" class="icon2-page_white_edit" title="Edit Data Belanja Tak Langsung"/>
				<a href="javascript:void(0)" onclick="delete_belanja_tl('. $row->id_belanja_tl .')" class="icon2-delete" title="Hapus Data Belanja Tak Langsung"/>'
			);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function cru_btl()
	{
			$this->template->load('template','keg_belanja/belanja_tak_langsung/cru_btl');
	}
 	
 	function save_data()
	{
		$this->auth->restrict();
		$tahun_input = $this->session->userdata('t_anggaran_aktif');
		$id_belanja_tl		 	= $this->input->post('id_belanja_tl');
		$call_from					= $this->input->post('call_from');
		$jenis_belanja_tl 	= $this->input->post('jenis_belanja_tl');
		$nominal_th1				= $this->input->post('nominal_th1');
		$nominal_th2	 			= $this->input->post('nominal_th2');
		$nominal_th3	 			= $this->input->post('nominal_th3');
		$nominal_th4				= $this->input->post('nominal_th4');
		$nominal_th5				= $this->input->post('nominal_th5');


		if(strpos($call_from, 'belanja_tak_langsung/cru_btl') != FALSE) {
			$call_from = '';
		}

		$data_btl = $this->m_belanja_tl->get_btl_by_id($id_belanja_tl);
		if(empty($data_btl)) {
			//cek bank baru
			$data_btl = new stdClass();
			$id_belanja_tl = '';
		}

		$data_btl->id_belanja_tl				= $id_belanja_tl;
		$data_btl->jenis_belanja_tl 			= $jenis_belanja_tl;
		$data_btl->nominal_th1			= $nominal_th1;
		$data_btl->nominal_th2	 		= $nominal_th2;
		$data_btl->nominal_th3	 		= $nominal_th3;
		$data_btl->nominal_th4			= $nominal_th4;
		$data_btl->nominal_th5			= $nominal_th5;
		$data_btl->tahun_input 			= $tahun_input;
		$ret = TRUE;
		if(empty($id_belanja_tl)) {
			//insert
			$ret = $this->m_belanja_tl->simpan_belanja_tl($data_btl);
			//echo $this->db->last_query();
		} else {
			//update
			$ret = $this->m_belanja_tl->update_belanja_tl($data_btl, $id_belanja_tl, 'table_belanja_tl', 'primary_belanja_tl');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Belanja Tak Langsung gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Belanja Tak Langsung Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('belanja_tak_langsung');
	}

	function edit_data($id_belanja_tl=NULL)
	{
		$this->auth->restrict();
		$data['url_save_data'] = site_url('belanja_tak_langsung/save_data');

		$data['isEdit'] = FALSE;
		if (!empty($id_belanja_tl)) {
				$data_ = array('id_belanja_tl'=>$id_belanja_tl);
				$result = $this->m_belanja_tl->get_data_with_rincian($id_belanja_tl,'table_belanja_tl');
				if (empty($result))	{
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data tidak ditemukan.');
					redirect('belanja_tak_langsung');
				}

		$data['id_belanja_tl']		= $result->id_belanja_tl;
		$data['jenis_belanja_tl'] = $result->jenis_belanja_tl;
		$data['nominal_th1'] 				= $result->nominal_th1;
		$data['nominal_th2'] 				= $result->nominal_th2;
		$data['nominal_th3'] 				= $result->nominal_th3;
		$data['nominal_th4'] 				= $result->nominal_th4;
		$data['nominal_th5'] 				= $result->nominal_th5;
		$data['isEdit']						= TRUE;
		}
		$this->template->load('template','keg_belanja/belanja_tak_langsung/cru_btl',$data);

	}

	function delete_data()
	{
		$id = $this->input->post('id');
        $data_post = array('id_belanja_tl'=>$id);
        $result = $this->m_belanja_tl->hard_delete($data_post,'table_belanja_tl');
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
