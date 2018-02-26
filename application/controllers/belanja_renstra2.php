<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class belanja_renstra extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_belanja_renstra'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		//$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		$this->template->load('template','renstra/belanja_renstra/belanja_renstra_view',$data);
	}

	function load_data()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$order_arr = array();
		$belanja = $this->m_belanja_renstra->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_belanja_renstra->count_data_table($search, $start, $length, $order["0"]);
		//var_dump($belanja);
		$data = array();
		$no=0;
		foreach ($belanja as $row){
			$no++;
			$data[] = array(
				$no,
				$row->kode_program,
				$row->kode_kegiatan,
				$row->kode_belanja,
				$row->uraian,
				'<a href="javascript:void(0)" onclick="edit_belanja('. $row->id_belanja_renstra .')" class="icon2-page_white_edit" title="Edit Data Belanja Langsung"/>
				<a href="javascript:void(0)" onclick="delete_belanja('. $row->id_belanja_renstra .')" class="icon2-delete" title="Hapus Data Belanja Langsung"/>'
			);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}



 	function save()
	{
		$this->auth->restrict();
		$id_belanja	= $this->input->post('id_belanja');
		$call_from	= $this->input->post('call_from');
		
		$data_post = array(
				'$tahun_input' => $this->session->userdata('t_anggaran_aktif');
				'kode_urusan' =>  $this->input->post('kd_urusan');
				'kode_bidang' => $this->input->post('kd_bidang');
				'kode_program' => $this->input->post('kd_prog');
				'kode_kegiatan' => $this->input->post('kd_keg');
				'kode_jenis_belanja' => $this->input->post('kd_jenis_belanja');
				'kode_kategori_belanja' => $this->input->post('kd_kategori_belanja');
				'kode_subkategori_belanja' => $this->input->post('kd_subkategori_belanja');
				'kode_belanja' => $this->input->post('kd_belanja');
				'uraian' => $this->input->post('uraian');
				'volume' => $this->input->post('volume');
				'satuan' => $this->input->post('satuan');
				'nominal' => $this->input->post('nominal');
				'id_skpd' => $this->input->post('id_skpd');
				);

		if(strpos($call_from, 'belanja_renstra/edit_data') != FALSE) {
			$call_from = '';
		}
		//cek apakah sudah ada di db
		$cekbelanja= $this->m_belanja_renstra->get_data_belanja($id_belanja);

        if(empty($cekbelanja)) {
			//$cekusulan = new stdClass();
			$id_belanja = '';
		}
		
		$ret = TRUE;
		if(empty($id_belanja)) {
			//insert
			$data_post['created_by'] = $this->session->userdata('nama');
            $data_post['created_date'] = $date." ".$time;
			$ret = $this->m_belanja_renstra->insert($data_post);
			//echo $this->db->last_query();
		} else {
			//update
			$data_post['changed_by'] = $this->session->userdata('nama');
            $data_post['changed_date'] = $date." ".$time;
			$ret = $this->m_belanja->update_belanja($data_post, $id_belanja, 'table_belanja', 'primary_belanja');
			//echo $this->db->last_query();
		}

		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Belanja Renstra gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Belanja Renstra Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('belanja_renstra');
	}



	function edit_data($id_belanja=NULL)
	{
		$this->auth->restrict();
		$data['url_save_data'] = site_url('belanja_renstra/save');

		$data['isEdit'] = FALSE;
		
		$this->template->load('template','renstra/belanja_renstra/create',$data);

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
