<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class belanja_renstra extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->model(array('m_belanja_renstra', 'm_groups', 'm_musrenbang','m_urusan', 'm_bidang', 'm_program','m_kegiatan', 'm_jenis_belanja', 'm_kategori_belanja','m_subkategori_belanja','m_kode_belanja'));
        //$this->load->model(array('m_renstra_trx', 'm_skpd', 'm_template_cetak'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){

		$this->auth->restrict();

        // $data['url_add_data'] = site_url('belanja_renstra/edit_data');
        // $data['url_load_data'] = site_url('belanja_renstra/load_data');
        // $data['url_delete_data'] = site_url('belanja_renstra/delete_data');
        // $data['url_edit_data'] = site_url('belanja_renstra/edit_data');
        // $data['url_save_data'] = site_url('belanja_renstra/save_data');

		//$this->template->load('template','renstra/belanja_renstra/belanja_renstra_view',$data);
		//$this->template->load('templatenyah','renstra/belanja_renstra/belanja_renstra_view_copy');
		//echo anchor('belanja_renstra/belanja_renstra_view_copy', 'title="My News"', array('target' => '_blank', 'class' => 'new_window'));
		$this->load->view('renstra/belanja_renstra/belanja_renstra_view_copy');

	}

	## --------------------------------------------- ##
	## Tambah, Edit, Delete View Renstra setiap SKPD ##
	## --------------------------------------------- ##

	function load_data()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$order_arr = array();
		$belanja = $this->m_belanja_renstra->get_data_table($search, $start, $length, $order["0"]);
		//echo $this->db->last_query();
		$alldata = $this->m_belanja_renstra->count_data_table($search);
		//var_dump($belanja);

		$data = array();
		$no=0;
		foreach ($belanja as $row){
			$no++;
			$data[] = array(
				$no,
				$row->kode_belanja,
				$row->belanja,
				$row->uraian,
				$row->nominal,
				'<a href="javascript:void(0)" onclick="edit_belanja('. $row->id_belanja_renstra .')" class="icon2-page_white_edit" title="Edit Data Belanja Renstra"/>
				<a href="javascript:void(0)" onclick="delete_belanja('. $row->id_belanja_renstra .')" class="icon2-delete" title="Hapus Data Belanja Renstra"/>'
			);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function edit_data($id=NULL)
	{
		$this->auth->restrict();
		$data['url_save_data'] = site_url('belanja_renstra/save');

		$data['isEdit'] = FALSE;

		 if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_belanja_renstra->get_data_with_rincian($id);
			//echo $this->db->last_query();
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data belanja tidak ditemukan.');
				redirect('belanja_renstra');
			}

            $data['id_belanja_renstra']	= $result->id_belanja_renstra;
            $data['kode_urusan']	= $result->kode_urusan;
    		$data['kode_bidang'] 	= $result->kode_bidang;
    		$data['kode_program'] 	= $result->kode_program;
    		$data['kode_kegiatan'] 	= $result->kode_kegiatan;
    		$data['kode_jenis_belanja'] = $result->kode_jenis_belanja;
			$data['kode_kategori_belanja'] = $result->kode_kategori_belanja;
			$data['kode_subkategori_belanja'] = $result->kode_subkategori_belanja;
			$data['kode_belanja'] = $result->kode_belanja;
			$data['uraian'] = $result->uraian;
    		$data['volume'] = $result->volume;
			$data['satuan'] = $result->satuan;
			$data['nominal'] = $result->nominal;
			$urusan = $this->m_urusan->get_urusan_by_kode($result->kode_urusan);
			$ftr =  'kd_Urusan = '. $result->kode_urusan . ' AND Kd_Bidang = '. $result->kode_bidang;
			$bidang = $this->m_bidang->get_one_bidang($ftr);
			$program = $this->m_program->get_prog_one($result->kode_urusan,$result->kode_bidang,$result->kode_program);
			$kegiatan = $this->m_kegiatan->get_keg_one($result->kode_urusan,$result->kode_bidang,$result->kode_program,$result->kode_kegiatan);
			$jenis_belanja = $this->m_jenis_belanja->get_jenis_belanja_one($result->kode_jenis_belanja);
			$kategori_belanja = $this->m_kategori_belanja->get_kategori_belanja_one($result->kode_jenis_belanja,$result->kode_kategori_belanja);
			$subkategori_belanja = $this->m_subkategori_belanja->get_subkategori_belanja_one($result->kode_jenis_belanja,$result->kode_kategori_belanja,$result->kode_subkategori_belanja);
			$belanja = $this->m_kode_belanja->get_kode_belanja_one($result->kode_jenis_belanja,$result->kode_kategori_belanja,$result->kode_subkategori_belanja,$result->kode_belanja);

//echo $result->kode_kategori_belanja;
			//echo $ftr;
			//echo $this->db->last_query();

			$data['nm_urusan']	= $urusan->nama;
			$data['nm_bidang']	= $bidang->nama;
			$data['nm_program']	= $program->nama;
			$data['nm_kegiatan']	= $kegiatan->nama;
			$data['nm_jenis_belanja']= $jenis_belanja->nama;
			$data['nm_kategori_belanja']= $kategori_belanja->nama;
			$data['nm_subkategori_belanja']= $subkategori_belanja->nama;
			$data['nm_belanja']= $belanja->nama;


			$data['isEdit']				= TRUE;
		}


		$this->template->load('template','renstra/belanja_renstra/create',$data);

	}

	function save()
	{
		$date=date("Y-m-d");
        $time=date("H:i:s");
		$this->auth->restrict();
		$id_belanja	= $this->input->post('id_belanja_renstra');
		//$call_from	= $this->input->post('call_from');

		$data_post = array(
				'tahun' => $this->session->userdata('t_anggaran_aktif'),
				'kode_urusan' =>  $this->input->post('kd_urusan'),
				'kode_bidang' => $this->input->post('kd_bidang'),
				'kode_program' => $this->input->post('kd_prog'),
				'kode_kegiatan' => $this->input->post('kd_keg'),
				//'kode_jenis_belanja' => $this->input->post('kd_jenis_belanja'),
				//'kode_kategori_belanja' => $this->input->post('kd_kategori_belanja'),
				//'kode_subkategori_belanja' => $this->input->post('kd_subkategori_belanja'),
				//'kode_belanja' => $this->input->post('kd_belanja'),
				//'uraian' => $this->input->post('uraian'),
				//'volume' => $this->input->post('volume'),
				//'satuan' => $this->input->post('satuan'),
				//'nominal' => $this->input->post('nominal'),
				'id_skpd' => $this->input->post('id_skpd'),
				);

		$n = count($this->input->post('index'));

		//$nrow = $this->m_belanja_renstra->cekid();

		//$nrow = $nrow + 1;
	$nrow = 1;

		//for ($i=0; $i <= $n-1 ; $i++) {
	$datata[] = array('kode_jenis_belanja' => $this->input->post('jnsbelanja',true));
	//var_dump($datata);
	print_r($datata);

			$data_post_detil = array(
				'kode_jenis_belanja' => $this->input->post('kd_jenis_belanja',true),
				'kode_kategori_belanja' => $this->input->post('kd_kategori_belanja',true),
				'kode_subkategori_belanja' => $this->input->post('subkatbelanja',true),
				'kode_belanja' => $this->input->post('kd_belanja',true),
				'uraian' => $this->input->post('uraian',true),
				'volume' => $this->input->post('volume',true),
				'satuan' => $this->input->post('satuan',true),
				'nominal' => $this->input->post('nominal',true),
				'id_belanja_renstra' => $nrow
				);
		//}
//print_r($data_post_detil);
			exit();
		//var_dump($data_post_detil);
		//log_message('debug', var_dump($data_post_detil));

		//if(strpos($call_from, 'belanja_renstra/edit_data') != FALSE) {
		//	$call_from = '';
		//}
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
			$this->m_belanja_renstra->insert_detil($datata);
//			for ($j=0; $j <= $n-1 ; $j++) {
//				$this->m_belanja_renstra->insert_detil($data_post_detil[$j]);
//			}
			//echo $datata;
			echo $this->db->last_query();
		} else {
			//update
			$data_post['changed_by'] = $this->session->userdata('nama');
            $data_post['changed_date'] = $date." ".$time;

			$ret = $this->m_belanja_renstra->update($id_belanja,$data_post, 'table', 'primary_groups');
			//echo $this->db->last_query();
		}

		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Belanja Renstra gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Belanja Renstra Berhasil disimpan');
		}

		//if(!empty($call_from))
		//	redirect($call_from);

        redirect('belanja_renstra');
	}







    function edit_data2($id=NULL){
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        $data['url_save_data'] = site_url('usulanbansos/save_data');

		$data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_usulan_bansos->get_data_with_rincian($id);
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data usulan tidak ditemukan.');
				redirect('usulanbansos');
			}

            $data['id_usulan_bansos']	= $result->id_usulan_bansos;
            $data['id_groups']	= $result->id_groups;
    		$data['id_skpd'] 	= $result->id_SKPD;
    		$data['id_kec'] 	= $result->id_kecamatan;
    		$data['id_desa'] 	= $result->id_desa;
    		$data['jenis_pekerjaan'] = $result->jenis_pekerjaan;

			$data['nama_group'] = $result->nama_group;
    		$data['nama_kec'] = $result->nama_kec;
    		$data['nama_skpd'] = $result->nama_skpd;
    		$data['nama_desa'] = $result->nama_desa;
			$data['nama_dewan'] = $result->nama_dewan;
    		$data['id_pilihanrenja'] = $result->id_pilihanrenja;

    		$data['volume'] = $result->volume;
    		$data['satuan'] = $result->satuan;
    		$data['lokasi'] = $result->lokasi;
    		$data['catatan'] = $result->catatan;
    		$data['jumlah_dana'] = $result->jumlah_dana;
			$data['id_sumberdana'] = $result->id_sumberdana;
			$data['id_jenishibah'] = $result->id_jenishibah;

			$data['isEdit']				= TRUE;
			//$mp_filefiles				= $this->m_usulan_bansos->get_file(explode( ',', $result->file), TRUE);
			//$data['mp_jmlfile']			= $mp_filefiles->num_rows();
			//$data['mp_filefiles']		= $mp_filefiles->result();

		}
        //var_dump($data);
    	$this->template->load('template','usulanbansos/create', $data);
    }



	function delete_data(){
		$this->auth->restrict_ajax_login();

		$id = $this->input->post("id");
		$id_group = $this->session->userdata('id_group');
		$result = $this->m_belanja_renstra->delete($id, $id_group);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data belanja renstra berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data usulan gagal dihapus, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}
	}

}
