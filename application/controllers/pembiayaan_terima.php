<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembiayaan_terima extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_pembiayaan_terima'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		//$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		$data['url_add_data'] = site_url('pembiayaan_terima/edit_data');
		$data['url_load_data'] = site_url('pembiayaan_terima/load_data');
		$data['url_delete_data'] = site_url('pembiayaan_terima/delete_data');
		$data['url_edit_data'] = site_url('pembiayaan_terima/edit_data');
	$this->template->load('template','keg_belanja/pembiayaan/view_pembiayaan_terima',$data);
	}

	function load_data()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$terima = $this->m_pembiayaan->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_pembiayaan->count_data_table($search, $start, $length, $order["0"]);
		//var_dump($belanja);
		$data = array();
		$no=0;
		foreach ($terima as $row){
			$no++;
			$data[] = array(
				$no,
				$row->jenis_pembiayaan_terima,
				$row->nominal_th1,
				$row->nominal_th2,
				$row->nominal_th3,
				$row->nominal_th4,
				$row->nominal_th5,
				'<a href="javascript:void(0)" onclick="edit_belanja('. $row->id_pembiayaan_terima .')" class="icon2-page_white_edit" title="Edit Data"/>
				<a href="javascript:void(0)" onclick="delete_belanja('. $row->id_pembiayaan_terima .')" class="icon2-delete" title="Hapus Data"/>'
			);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function cru_pembiayaan_terima()
	{
			$this->template->load('template','keg_belanja/pembiayaan/cru_pembiayaan_terima');
	}

 	function save_data()
	{
		$this->auth->restrict();
		$tahun_input = $this->session->userdata('t_anggaran_aktif');
		$id_pembiayaan_terima		 	= $this->input->post('id_pembiayaan_terima');
		$call_from					= $this->input->post('call_from');
		$jenis_belanja 	= $this->input->post('jenis_pembiayaan_terima');
		$nominal_th1				= $this->input->post('nominal_th1');
		$nominal_th2	 			= $this->input->post('nominal_th2');
		$nominal_th3	 			= $this->input->post('nominal_th3');
		$nominal_th4				= $this->input->post('nominal_th4');
		$nominal_th5				= $this->input->post('nominal_th5');


		if(strpos($call_from, 'pembiayaan/cru_pembiayaan_terima') != FALSE) {
			$call_from = '';
		}

		$pem_terima = $this->m_pembiayaan->get_pembiayaan_terima_by_id($id_pembiayaan_terima);
		if(empty($pem_terima)) {
			//cek bank baru
			$pem_terima = new stdClass();
			$id_pembiayaan_terima = '';
		}

		$pem_terima->id_pembiayaan_terima				= $id_pembiayaan_terima;
		$pem_terima->jenis_pembiayaan_terima 			= $jenis_pembiayaan_terima;
		$pem_terima->nominal_th1			= $nominal_th1;
		$pem_terima->nominal_th2	 		= $nominal_th2;
		$pem_terima->nominal_th3	 		= $nominal_th3;
		$pem_terima->nominal_th4			= $nominal_th4;
		$pem_terima->nominal_th5			= $nominal_th5;
		$pem_terima->tahun_input 			= $tahun_input;
		$ret = TRUE;
		if(empty($id_pembiayaan_terima)) {
			//insert
			$ret = $this->m_pembiayaan->simpan_pembiayaan_terima($pem_terima);
			//echo $this->db->last_query();
		} else {
			//update
			$ret = $this->m_pembiayaan->update_pembiayaan_terima($pem_terima, $id_pembiayaan_terima, 'table_pembiayaan_terima', 'primary_pembiayaan_terima');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('pembiayaan_terima');
	}

	function edit_data($id_pembiayaan=NULL)
	{
		$this->auth->restrict();
		$data['url_save_data'] = site_url('pembiayaan_terima/save_data');

		$data['isEdit'] = FALSE;
		if (!empty($id_pembiayaan_terima)) {
				$data_ = array('id_pembiayaan_terima'=>$id_pembiayaan_terima);
				$result = $this->m_pembiayaan->get_data_with_rincian($id_pembiayaan_terima,'table_pembiayaan_terima');
				if (empty($result))	{
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data tidak ditemukan.');
					redirect('pembiayaan_terima');
				}

		$data['id_pembiayaan_terima']		= $result->id_pembiayaan_terima;
		$data['jenis_pembiayaan_terima'] = $result->jenis_pembiayaan_terima;
		$data['nominal_th1'] 				= $result->nominal_th1;
		$data['nominal_th2'] 				= $result->nominal_th2;
		$data['nominal_th3'] 				= $result->nominal_th3;
		$data['nominal_th4'] 				= $result->nominal_th4;
		$data['nominal_th5'] 				= $result->nominal_th5;
		$data['isEdit']						= TRUE;
		}
		$this->template->load('template','keg_belanja/pembiayaan/cru_pembiayaan_terima',$data);

	}

	function delete_data()
	{
		$id = $this->input->post('id');
        $pem_terima = array('id_pembiayaan_terima'=>$id);
        $result = $this->m_pembiayaan->hard_delete($pem_terima,'table_pembiayaan_terima');
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
