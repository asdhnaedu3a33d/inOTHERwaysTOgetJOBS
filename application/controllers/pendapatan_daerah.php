<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendapatan_daerah extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
		$this->load->helper(array('form','url', 'text_helper','date'));
		$this->load->database();
		//$this->load->model('m_musrenbang','',TRUE);
		$this->load->model(array('m_pendapatan', 'm_jenis_pendapatan', 'm_asal_pendapatan','m_pendapatan_new'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
    	//$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		$data['url_add_data'] = site_url('pendapatan_daerah/edit_data');
		$data['url_delete_data'] = site_url('pendapatan_daerah/delete_data');
		$data['url_save_data'] = site_url('pendapatan_daerah/save_data');
		$data['url_edit_data'] = site_url('pendapatan_daerah/edit_data');

        $data['tahun'] = $this->session->userdata('t_anggaran_aktif');

		$data['data_table_pendaptan'] = $this->m_pendapatan_new->generate_table_pendapatan($data['tahun']);
		$this->template->load('template','pendapatan_daerah/view_pendapatan', $data);
	}



	function save_data(){
	    $this->auth->restrict();
	    //action save cekbank di table t_cmusrenbangdes
	    $id	= $this->input->post('id');
		$id_jenis_pendapatan = $this->input->post('id_jenis_pendapatan');
		$tahun = $this->input->post('tahun');

	    $call_from			= $this->input->post('call_from');
	    $data_post = array(
			'tahun' => $tahun,
			'id_jenis_pendapatan' => $id_jenis_pendapatan,
			'realisasi_n_3'	 	=> $this->input->post('realisasi_n_3'),
			'realisasi_n_2'		=> $this->input->post('realisasi_n_2'),
			'realisasi_n_1'	=> $this->input->post('realisasi_n_1'),
			'anggaran'			=> $this->input->post('anggaran'),
			'proyeksi'			=> $this->input->post('proyeksi')
    	);

		if(strpos($call_from, 'pendapatan_daerah/edit_data') != FALSE) {
			$call_from = '';
		}


		$ret = TRUE;
		//update
		//var_dump($id);
		if($id!=''){
			$ret = $this->db->update('t_pendapatan_new', $data_post, array('id' => $id));
		}else{
			$ret = $this->db->insert('t_pendapatan_new', $data_post);
		}


		if ($ret === FALSE){
	        $this->session->set_userdata('msg_typ','err');
	        $this->session->set_userdata('msg', 'Data pendapatan Gagal disimpan');
		} else {
	        $this->session->set_userdata('msg_typ','ok');
	        $this->session->set_userdata('msg', 'Data pendapatan Berhasil disimpan');
		}


    	redirect('pendapatan_daerah');
		//var_dump($cekbank);
		//print_r ($id_cek);
    }

	function edit_data($id_jenis_pendapatan=NULL){
		$this->auth->restrict();

		$data['url_save_data'] = site_url('pendapatan_daerah/save_data');

		$data['isEdit'] = FALSE;
		$tahun = $this->session->userdata('t_anggaran_aktif');

		if (!empty($id_jenis_pendapatan)) {

			$result = $this->m_pendapatan_new->get_pendapatan_row($tahun,$id_jenis_pendapatan);

			// var_dump($result);
			$data['id']		= $result->id;
			$data['id_jenis_pendapatan'] = $result->id_jenis_pendapatan;
			$data['tahun'] = $result->tahun;

			$data['kode'] = $result->kode;
			$data['nama'] = $result->nama;
			$data['realisasi_n_3'] = $result->realisasi_n_3;
			$data['realisasi_n_2'] = $result->realisasi_n_2;
			$data['realisasi_n_1'] = $result->realisasi_n_1;
			$data['anggaran'] = $result->anggaran;
			$data['proyeksi'] = $result->proyeksi;

			$data['isEdit']				= TRUE;
		}

		//var_dump($data);
		$this->template->load('template','pendapatan_daerah/pendapatan', $data);
	}


		function delete_data(){
        $id = $this->input->post('id');
        $data_post = array('id_pendapatan'=>$id);
        $result = $this->m_pendapatan->hard_delete($data_post,'table_pendapatan');
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




    function autocomplete_jenispendapatan(){
        $req = $this->input->post('term');
        $result = $this->m_pendapatan->get_value_autocomplete_jenis_pendapatan($req);
        echo json_encode($result);
    }

    function autocomplete_asalpendapatan(){
        $kd_urusan = $this->input->post('kd_urusan');
        $req = $this->input->post('term');
        $result = $this->m_musrenbang->get_value_autocomplete_asal_pendapatan($req, $kd_urusan);
        echo json_encode($result);
    }

}
