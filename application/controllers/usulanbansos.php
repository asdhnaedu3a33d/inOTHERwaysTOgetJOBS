<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class usulanbansos extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->model(array('m_usulan_bansos','m_skpd', 'm_kecamatan', 'm_desa', 'm_groups', 'm_musrenbang','m_urusan', 'm_bidang','m_template_cetak', 'm_lov', 'm_program', 'm_kegiatan'));
        //$this->load->model(array('m_renstra_trx', 'm_skpd', 'm_template_cetak'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){

		$this->auth->restrict();

        $data['url_add_data'] = site_url('usulanbansos/edit_data');
        $data['url_load_data'] = site_url('usulanbansos/load_data');
        $data['url_delete_data'] = site_url('usulanbansos/delete_data');
        $data['url_edit_data'] = site_url('usulanbansos/edit_data');
        $data['url_save_data'] = site_url('usulanbansos/save_data');
		$data['url_show_gallery'] = site_url('usulanbansos/show_gallery');

		$this->template->load('template','usulanbansos/usulan_view',$data);
	}

	function persetujuanusulanbansos(){
		$this->auth->restrict();
		$data['url_load_data'] = site_url('usulanbansos/load_data_persetujuan');
		$data['url_edit_data'] = site_url('usulanbansos/edit_data_persetujuan');

				//
				if($this->session->userdata('id_skpd') == '1'){

					$search = $this->input->post("search");
					$start = $this->input->post("start");
					$length = $this->input->post("length");
					$order = $this->input->post("order");
					$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
					$result = $this->m_usulan_bansos->get_one_usulan_persetujuan($search, $start, $length, $order["0"], $order_arr);
//echo $this->db->last_query();
						# code...
						$mp_filefiles				= $this->m_usulan_bansos->get_file(explode( ',', $result->file_rapat), TRUE);
		//echo $this->db->last_query();
		//				echo $this->db->last_query;
						$data['mp_jmlfile']			= $mp_filefiles->num_rows();
						$data['mp_filefiles']		= $mp_filefiles->result();
						$data['keterangan_rapat']		= $result->keterangan_rapat;


	//echo $result->file_rapat;

				};
//echo $result->keterangan_rapat;
//exit();
		$this->template->load('template','usulanbansos/usulan_view_persetujuan',$data);
	}

	function evaluasiusulanbansos(){
		$this->auth->restrict();
        $data['url_load_data'] = site_url('usulanbansos/load_data_evaluasi');
        $data['url_edit_data'] = site_url('usulanbansos/edit_data_evaluasi');
        $this->template->load('template','usulanbansos/usulan_view_evaluasi',$data);
	}


	function coba(){
		$this->auth->restrict();
		$this->template->load('template','usulanbansos/usulan_view_persetujuan',$data);
	}

	function cru_persetujuan(){
		$this->auth->restrict();
		$id = $this->input->post('id');

		$this->load->view("usulanbansos/cru_persetujuan", $id);
	}
	private function cetak_func($cetak=FALSE){
			if (!$cetak) {
				$temp['class_table']='class="table-common"';
			}else{
				$temp['class_table']='class="border"';
			}
			    $search = $this->input->post("search");
			$start = $this->input->post("start");
			$length = $this->input->post("length");
			$order = $this->input->post("order");

			$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
			$usulanbansos['usulanbansos'] = $this->m_usulan_bansos->get_all_usulan_persetujuan($search, $start, $length, $order["0"], $order_arr);

			$result = $this->load->view("usulanbansos/usulan_view_persetujuan_cetak", $usulanbansos, TRUE);
			return $result;
		}

	function cetakpersetujuanusulanbansos(){
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
		$usulanbansos['usulanbansos'] = $this->m_usulan_bansos->get_all_usulan_persetujuan($search, $start, $length, $order["0"], $order_arr);
	//print_r($usulanbansos);
	//exit();
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'usulanbansos/persetujuanusulanbansos'. date("d-m-Y_H-i-s"), 1);

		$data['cetak'] = $this->cetak_func(TRUE);
		$html = $this->template->load('template_cetak', 'usulanbansos/cetak_view', $data, true);

		//print_r($html);
		//exit();

		//$html= $this->template->load('template_cetak','usulanbansos/usulan_view_persetujuan_cetak',$usulanbansos,true);
		 $filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';
		    pdf_create($html, $filename, "A4", "Landscape", FALSE);



	}


	function load_data_persetujuan(){
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$data = array();
		$alldata=0;
		if ($this->session->userdata('id_skpd')=='1') {
			# code...

			$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
			$usulanbansos = $this->m_usulan_bansos->get_all_usulan_persetujuan($search, $start, $length, $order["0"], $order_arr);
	//		console.log($usulanbansos);
	// print_r($this->db->last_query()) ;

			$alldata = $this->m_usulan_bansos->count_all_usulan_persetujuan($search);

				$no=0;
			foreach ($usulanbansos as $row) {
				$no++;
				//$preview_action = '<a href="javascript:void(0)" onclick="preview_modal('. $row->id .')" class="icon-search" title="Lihat Usulan"/>';
				$edit_action = '<a href="javascript:void(0)" onclick="edit_usulan_table('. $row->id_usulan_bansos .')" class="icon2-note" title="Konfirmasi Usulan Hibah/Bansos"/>';
				if(is_null($row->nominal_setuju)){
					$NominalAnggaran = '<input type="hidden" value="'.$row->id_usulan_bansos.'" name ="id['.$no.']"><input type="text" oninput="kup(this)" value="'.Formatting::currency($row->jumlah_dana,2).'" id="det_uraian['.$no.']" name="anggaran['.$no.']" class="common jos" />';
				}else{
					$NominalAnggaran = '<input type="hidden" value="'.$row->id_usulan_bansos.'" name ="id['.$no.']"><input type="text" oninput="kup(this)" value="'.Formatting::currency($row->nominal_setuju,2).'" id="det_uraian['.$no.']" name="anggaran['.$no.']" class="common jos" />';
				};


				if ($row->flag_masukRKPD=='0'){
					$statusRKPD = '<select class="common" name="status['.$no.']" ><option>Disetujui</option><option selected ="selected">Tidak Disetujui</option></select>';
				}else{
					$statusRKPD = '<select class="common" name="status['.$no.']" ><option selected ="selected">Disetujui</option><option>Ditolak</option></select>';
				};
				if ($row->pengusul=='3'){
					$pengusulbansos = $row->lainnya;
				}else{
					$pengusulbansos = $row->pengusulbansos;

				};

				$rekomendasi = $row->norekomendasi;



				$action =		$edit_action;
				$data[] = array(
								$no,
								$pengusulbansos,
								$row->jenis_pekerjaan,
						//		$row->nama_desa,
							//	$row->nama_kec,
							//	$row->lokasi,
								Formatting::currency($row->jumlah_dana,2),
								$row->nama_skpd,
								//$row->catatan,
								$statusRKPD ,
								$NominalAnggaran,
								$rekomendasi,
								$row->tglrekomendasi,

								);
			}
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);

    }


    function load_data_evaluasi(){
    $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
		$usulanbansos = $this->m_usulan_bansos->get_all_usulan_evaluasi($search, $start, $length, $order["0"], $order_arr);
		//echo $this->db->last_query();
		//exit();
		$alldata = $this->m_usulan_bansos->count_all_usulan_evaluasi($search);

		$data = array();
		$no=0;
		foreach ($usulanbansos as $row) {
			$no++;
			//$preview_action = '<a href="javascript:void(0)" onclick="preview_modal('. $row->id .')" class="icon-search" title="Lihat Usulan"/>';
			$edit_action = '<a href="javascript:void(0)" onclick="edit_usulan_table('. $row->id_usulan_bansos .')" class="icon2-note" title="Konfirmasi Usulan Hibah/Bansos"/>';
			$action =		$edit_action;
			$data[] = array(
							$no,
							$row->nama_group,
							$row->nama_dewan,
							$row->nama_skpd,
							$row->nama_kec,
							$row->nama_desa,
							$row->jenis_pekerjaan,
							$row->volume,
							Formatting::currency($row->jumlah_dana,2),
							$row->lokasi,
							$row->catatan,
							$action,
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
    }

	## --------------------------------------------- ##
	## Tambah, Edit, Delete View Renstra setiap SKPD ##
	## --------------------------------------------- ##

	function load_data(){
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
	$data = array();

	 $alldata=0;
		if ($this->session->userdata('id_skpd')=='6') {

			$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
			$usulanbansos = $this->m_usulan_bansos->get_all_usulan($search, $start, $length, $order["0"], $order_arr);
			// print_r($this->db->last_query()) ;
			// exit();
			$alldata = $this->m_usulan_bansos->count_all_usulan($search);


			$no=0;
			foreach ($usulanbansos as $row) {
				$no++;
				$edit_action = '<a href="javascript:void(0)" onclick="edit_usulan_table('. $row->id_usulan_bansos .')" class="icon2-page_white_edit" title="Edit Usulan"/>';
				$delete_action = '<a href="javascript:void(0)" onclick="delete_usulan_table('. $row->id_usulan_bansos .')" class="icon2-delete" title="Hapus Usulan"/>';
				$galery = '<a href="javascript:void(0)" onclick="show_gallery('. $row->id_usulan_bansos .')" class="icon-search" title="Lihat Gambar"/>';

				$action =		$edit_action.
								$delete_action.
								$galery;
				$data[] = array(
								$no,
								$row->nama_group,
								$row->nama_skpd,
								$row->nama_kec,
								$row->nama_desa,
								$row->jenis_pekerjaan,
								$row->volume,
								Formatting::currency($row->jumlah_dana,2),
								$row->lokasi,
								$row->catatan,
								$action,
								);
			}
			# code...
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);

    }

    function edit_data($id=NULL){
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        $data['url_save_data'] = site_url('usulanbansos/save_data');
				$kd_urusan_edit = NULL;
				$kd_bidang_edit = NULL;
				$id_groups_edit = NULL;
				$id_skpd_edit = NULL;
				$id_kec_edit = NULL;
				$id_desa_edit = NULL;
		$data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_usulan_bansos->get_data_with_rincian($id);
		//echo $this->db->last_query();
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
			$data['pengusul'] = $result->pengusul;
			$data['alamat_pengusul'] = $result->alamat_pengusul;

			$data['lainnya'] = $result->lainnya;

			$data['isEdit']				= TRUE;
			$mp_filefiles				= $this->m_usulan_bansos->get_file(explode( ',', $result->file), TRUE);

			$data['mp_jmlfile']			= $mp_filefiles->num_rows();
			$data['mp_filefiles']		= $mp_filefiles->result();
			$data['kode_urusan']	= $result->kode_urusan;
			$data['kode_bidang'] 	= $result->kode_bidang;


			if (!empty($result->kode_urusan)){
				$urusan = $this->m_urusan->get_urusan_by_kode($result->kode_urusan);
				$ftr =  'kd_Urusan = '. $result->kode_urusan . ' AND Kd_Bidang = '. $result->kode_bidang;
				$bidang = $this->m_bidang->get_one_bidang($ftr);
				$data['nm_urusan']	= $urusan->nama;
				$data['nm_bidang']	= $bidang->nama;

				$kd_urusan_edit	= $result->kode_urusan;
				$kd_bidang_edit = $result->kode_bidang;
			}

			$data['satuan_edit'] = $result->satuan;
			$id_groups_edit = $result->id_groups;;
			$id_skpd_edit = $result->id_SKPD;
			$id_kec_edit = $result->id_kecamatan;
			$id_desa_edit = $result->id_desa;
		}

		$id_groups = array("" => "");
		foreach ($this->m_groups->get_group_dee() as $row) {
				$id_groups[$row->id] = $row->nama;
		}
		$id_skpd = array("" => "");
		foreach ($this->m_skpd->get_skpd_chosen() as $row) {
				$id_skpd[$row->id] = $row->label;
		}

		$id_kec = array("" => "");
		foreach ($this->m_kecamatan->get_kec_dee() as $row) {
				$id_kec[$row->id] = $row->nama;
		}
		$id_desa = array("" => "");
		foreach ($this->m_desa->get_desa_dee($id_kec_edit) as $row) {
				$id_desa[$row->id] = $row->label;
		}

		$kd_urusan = array("" => "");
		foreach ($this->m_urusan->get_urusan() as $row) {
			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
		}
		$kd_bidang = array("" => "");
		foreach ($this->m_bidang->get_bidang_dee($kd_urusan_edit) as $row) {
			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
		}
		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}


		$data['satuan'] = $satuan;
		$data['id_groups_dee'] = form_dropdown('id_groups', $id_groups, $id_groups_edit, 'data-placeholder="Pilih Fasilitator" class="common chosen-select" id="id_groups_dee"');
		$data['id_skpd_dee'] = form_dropdown('id_skpd', $id_skpd, $id_skpd_edit, 'data-placeholder="Pilih SKPD Evaluator" class="common chosen-select" id="id_skpd_dee"');
		$data['id_kec_dee'] = form_dropdown('id_kec', $id_kec, $id_kec_edit, 'data-placeholder="Pilih Kecamatan Sasaran" class="common chosen-select" id="id_kec_dee"');
		$data['id_desa_dee'] = form_dropdown('id_desa', $id_desa, $id_desa_edit, 'data-placeholder="Pilih Desa Sasaran" class="common chosen-select" id="id_desa_dee"');
		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');

        //var_dump($data);
    	$this->template->load('template','usulanbansos/create', $data);
    }

    function edit_data_persetujuan($id=NULL){
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        $data['url_save_data'] = site_url('usulanbansos/save_data');

		$data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_usulan_bansos->get_data_with_rincian($id);
		//echo $this->db->last_query();
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
			$data['pengusul'] = $result->pengusul;
			$data['id_keputusan'] = $result->id_keputusan;
			$data['alasan'] = $result->alasan;
			$data['nominal_setuju'] = $result->nominal_setuju;

			$data['lainnya'] = $result->lainnya;

			$data['isEdit']				= TRUE;
			$mp_filefiles				= $this->m_usulan_bansos->get_file(explode( ',', $result->file), TRUE);

			$data['mp_jmlfile_view']			= $mp_filefiles->num_rows();
			$data['mp_filefiles_view']		= $mp_filefiles->result();

		}
        //var_dump($data);
    	$this->template->load('template','usulanbansos/create_persetujuan', $data);
    }
	function edit_data_evaluasi($id=NULL){
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        $data['url_save_data'] = site_url('usulanbansos/save_data');

				$kd_urusan_edit = NULL;
				$kd_bidang_edit = NULL;
				$kd_program_edit = NULL;
				$kd_kegiatan_edit = NULL;
		$data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_usulan_bansos->get_data_with_rincian($id);
		//	echo $this->db->last_query();
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
				$data['satuan_edit'] = $result->satuan;
    		$data['lokasi'] = $result->lokasi;
    		$data['catatan'] = $result->catatan;
    		$data['jumlah_dana'] = $result->jumlah_dana;
			$data['id_sumberdana'] = $result->id_sumberdana;
			$data['id_jenishibah'] = $result->id_jenishibah;
			$data['pengusul'] = $result->pengusul;

			$data['lainnya'] = $result->lainnya;
			$data['norekomendasi'] = $result->norekomendasi;
			$data['keteranganrekomendasi'] = $result->keterangan_rekomendasi;

			$tglrekom = explode('-', $result->tglrekomendasi);
			$dayyy = explode(' ', $tglrekom[2]);
			$tglrekomen = $dayyy[0]."/".$tglrekom[1]."/".$tglrekom[0];
			$data['tglrekomendasi'] = $tglrekomen;
			//$data['file_rekomendasi'] = $result->filerekomendasi;

			$data['isEdit']				= TRUE;
			$mp_filefilesreko				= $this->m_usulan_bansos->get_file(explode( ',', $result->filerekomendasi), TRUE);

			$data['mp_jmlfile']			= $mp_filefilesreko->num_rows();
			$data['mp_filefiles']		= $mp_filefilesreko->result();

			$mp_filefiles				= $this->m_usulan_bansos->get_file(explode( ',', $result->file), TRUE);

			$data['mp_jmlfile_view']			= $mp_filefiles->num_rows();
			$data['mp_filefiles_view']		= $mp_filefiles->result();

			$kd_urusan_edit = $result->kode_urusan;
			$kd_bidang_edit = $result->kode_bidang;
		}


				$kd_urusan = array("" => "");
				foreach ($this->m_urusan->get_urusan() as $row) {
					$kd_urusan[$row->id] = $row->id .". ". $row->nama;
				}

				$kd_bidang = array("" => "");
				foreach ($this->m_bidang->get_bidang($kd_urusan_edit) as $row) {
					$kd_bidang[$row->id] = $row->id .". ". $row->nama;
				}

				$kd_program = array("" => "");
				foreach ($this->m_program->get_prog($kd_urusan_edit,$kd_bidang_edit) as $row) {
					$kd_program[$row->id] = $row->id .". ". $row->nama;
				}

				$kd_kegiatan = array("" => "");
				foreach ($this->m_kegiatan->get_keg($kd_urusan_edit, $kd_bidang_edit, $kd_program_edit) as $row) {
					$kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
				}

				$satuan = array("" => "~~ Pilih Satuan ~~");
				foreach ($this->m_lov->get_list_lov(1) as $row) {
					$satuan[$row->kode_value]=$row->nama_value;
				}


				$data['satuan'] = $satuan;
				if (!empty($kd_urusan_edit)) {
					$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
					$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
					$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
					$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');
				}

        //var_dump($data);
    	$this->template->load('template','usulanbansos/create_evaluasi', $data);
    }

    function save(){

		$date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
		$id = $this->input->post('id_usulanbansos');
		 //action save
        $call_from			= $this->input->post('call_from');

        $data_post = array(
            'tahun'             => $this->session->userdata('t_anggaran_aktif'),
            'id_groups'			=> $this->input->post('id_groups'),
    		'nama_dewan'	 	=> $this->input->post('nama_dewan'),
    		'id_skpd'	 		=> $this->input->post('id_skpd'),
    		'id_kecamatan'		=> $this->input->post('id_kec'),
    		'id_desa'			=> $this->input->post('id_desa'),
    		'jenis_pekerjaan'	=> $this->input->post('jenis_pekerjaan'),
    		'volume'			=> $this->input->post('volume'),
    		'satuan'			=> $this->input->post('satuan'),
    		'lokasi'			=> $this->input->post('lokasi'),
            'catatan'			=> $this->input->post('catatan'),
            'jumlah_dana'		=> $this->input->post('jumlah_dana'),
            'id_jenishibah'		=> $this->input->post('jenis_hibah'),
    		'id_pilihanrenja'	=> $this->input->post('pilihan_renja'),
    		'id_sumberdana'		=> $this->input->post('sumber_dana'),
    		'pengusul'			=> $this->input->post('pengusul'),
    		'lainnya'		=> $this->input->post('nama_pengusul'),
    		'fasilitator'			=> $this->input->post('id_groups'),
				'kode_urusan' =>  $this->input->post('kd_urusan'),
				'kode_bidang' => $this->input->post('kd_bidang'),
				'alamat_pengusul' => $this->input->post('alamat_pengusul'),



        );



		if(strpos($call_from, 'usulanbansos/edit_data') != FALSE) {
			$call_from = '';
		}

		$cekusulan = $this->m_usulan_bansos->get_data(array('id_usulan_bansos'=>$id),'table_musrenbang');

        if(empty($cekusulan)) {
			//$cekusulan = new stdClass();
			$id = '';
		}



		//Persiapan folder berdasarkan unit
		$dir_file_upload='file_upload/4';
		if (!file_exists($dir_file_upload)) {
		    mkdir($dir_file_upload, 0766, true);
		}
		//UPLOAD
		$this->load->library('upload');
		$config = array();
		$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'jpeg|jpg|png|pdf';
		$config['max_size'] = '1024';
		$config['overwrite'] = FALSE;

		$id_userfile 	= $this->input->post("id_userfile");
		$name_file 	= $this->input->post("name_file");
		$ket_file	= $this->input->post("ket_file");
	    $files = $_FILES;
	    $cpt = $this->input->post("upload_length");
	    	 //  	   print_r($name_file);
	    $hapus	= $this->input->post("hapus_file");
	    $name_file_arr = array();
	    $id_file_arr = array();
	    //print_r($files);
	    for($i=1; $i<=$cpt; $i++)
	    {
	    	if (empty($files['userfile']['name'][$i]) && empty($id_userfile[$i])) {
	    		continue;
	    	}elseif (empty($files['userfile']['name'][$i]) && !empty($id_userfile[$i])) {
	    		$update_var = array('name'=> $name_file[$i],'ket'=>$ket_file[$i]);
	    		$this->m_usulan_bansos->update_file($id_userfile[$i], $update_var);
	    		continue;
	    	}

	    	$file_name="hibahbansos_".date("Ymd_His");
	    //	print_r($file_name);
	        $_FILES['userfile']['name']= $file_name."_".$files['userfile']['name'][$i];
	        $_FILES['userfile']['type']= $files['userfile']['type'][$i];
	        $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
	        $_FILES['userfile']['error']= $files['userfile']['error'][$i];
	        $_FILES['userfile']['size']= $files['userfile']['size'][$i];

		    $this->upload->initialize($config);
		    $file = $this->upload->do_upload();
            //var_dump($this->upload->display_errors('<p>', '</p>'));
            //var_dump($this->upload->data());
		    if ($file) {
		    	$file = $this->upload->data();
				$file = $file['file_name'];
				if (!empty($id_userfile[$i])) {
					$hapus[] = 	$id_userfile[$i];
				}
				$id_file_arr[] = $this->m_usulan_bansos->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);
				$name_file_arr[] = $file;

			} else {
				// Error Occured in one of the uploads
				if (empty($id) || (!empty($_FILES['userfile']['name']) && !empty($id))) {
					foreach ($id_file_arr as $value) {
						$this->m_usulan_bansos->delete_file($value);
					}
					foreach ($name_file_arr as $value) {
						unlink($directory.$value);
					}
					$error_upload	= "Draft Usulan gagal disimpan, terdapat kesalahan pada upload file atau file upload tidak sesuai dengan ketentuan.";
					$this->session->set_userdata('msg_typ','err');
	            	$this->session->set_userdata('msg', $error_upload);
					//var_dump($file);

				}
			}
		}

		if (!empty($cekusulan->file)) {
    		$id_file_arr_old = explode(",", $cekusulan->file);
    		if (!empty($hapus)) {
    			foreach ($hapus as $row) {
					$key = array_search($row, $id_file_arr_old);
					unset($id_file_arr_old[$key]);

			    	$var_hapus = $this->m->get_one_file($row);
			    	unlink(dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$var_hapus->location);
			    	$this->m_usulan_bansos->delete_file($row);
			    }
    		}
		    foreach ($id_file_arr_old as $value) {
		    	$id_file_arr[] = $value;
		    }
	    }


	    if (!empty($id_file_arr)) {
	    	$tes=implode(",", $id_file_arr);
	    	//echo $tes;

	    	//$cekusulan->file = $tes;
	    }

	//	echo $cekusulan->file;

		$ret = TRUE;
		if ($this->input->post('sumber_dana')=='1') {
			$data_post['ishibah'] = "1";
		}else {
			$data_post['ishibah'] = "0";
		}

		//exit();
		if(empty($id)) {
			//insert
            $data_post['created_by'] = $this->session->userdata('nama');
            $data_post['created_date'] = $date." ".$time;
			$data_post['file'] = $tes;
			$data_post['id_keputusan'] = "0";
			$data_post['SKPD_setuju'] = "1";

			$ret = $this->m_usulan_bansos->insert($data_post,'table_musrenbang');
			//print_r($this->db->last_query());

		} else {
			//update
            $data_post['changed_by'] = $this->session->userdata('nama');
            $data_post['changed_date'] = $date." ".$time;
			$ret = $this->m_usulan_bansos->update($id,$data_post,'table_musrenbang','primary_musrenbang');
			//echo $this->db->last_query();
			// print_r($this->db->last_query());
			// exit;
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Usulan Gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Usulan Berhasil disimpan');
		}




		//var_dump($cekbank);
		//print_r ($id_cek);
		redirect('usulanbansos');
    }


    function savepersetujuan(){
		$date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
		//$id = $this->input->post('id_usulanbansos');



		//Persiapan folder berdasarkan unit
		$dir_file_upload='file_upload/4';
		if (!file_exists($dir_file_upload)) {
		    mkdir($dir_file_upload, 0766, true);
		}
		//UPLOAD
		$this->load->library('upload');
		$config = array();
		$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'jpeg|jpg|png|pdf';
		$config['max_size'] = '1024';
		$config['overwrite'] = FALSE;

		$id_userfile 	= $this->input->post("id_userfile");
		$name_file 	= $this->input->post("name_file");
		//
		$ket_file	= $this->input->post("ket_file");
	    $files = $_FILES;
			//print_r($files);
	    $cpt = $this->input->post("upload_length");

	    $hapus	= $this->input->post("hapus_file");
	    $name_file_arr = array();
	    $id_file_arr = array();

	    for($i=1; $i<=$cpt; $i++)
	    {
	    	if (empty($files['userfile']['name'][$i]) && empty($id_userfile[$i])) {
	    		continue;
	    	}elseif (empty($files['userfile']['name'][$i]) && !empty($id_userfile[$i])) {
	    		$update_var = array('name'=> $name_file[$i],'ket'=>$ket_file[$i]);
	    		$this->m_usulan_bansos->update_file($id_userfile[$i], $update_var);
	    		continue;
	    	}

	    	$file_name="persetujuanhibahbansos".date("Ymd_His");
	    	//print_r($file_name);
	        $_FILES['userfile']['name']= $file_name."_".$files['userfile']['name'][$i];
	        $_FILES['userfile']['type']= $files['userfile']['type'][$i];
	        $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
	        $_FILES['userfile']['error']= $files['userfile']['error'][$i];
	        $_FILES['userfile']['size']= $files['userfile']['size'][$i];

		    $this->upload->initialize($config);
		    $file = $this->upload->do_upload();
            //var_dump($this->upload->display_errors('<p>', '</p>'));
            //var_dump($this->upload->data());

		    if ($file) {
			    	$file = $this->upload->data();
						$file = $file['file_name'];
					if (!empty($id_userfile[$i])) {
						$hapus[] = 	$id_userfile[$i];
					}
					$id_file_arr[] = $this->m_usulan_bansos->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);
					$name_file_arr[] = $file;
				} else {
					// Error Occured in one of the uploads
					if (empty($id) || (!empty($_FILES['userfile']['name']) && !empty($id))) {
						foreach ($id_file_arr as $value) {
							$this->m_usulan_bansos->delete_file($value);
						}
						foreach ($name_file_arr as $value) {
							unlink($directory.$value);
						}
						$error_upload	= "Draft Usulan gagal disimpan, terdapat kesalahan pada upload file atau file upload tidak sesuai dengan ketentuan.";
						$this->session->set_userdata('msg_typ','err');
		            	$this->session->set_userdata('msg', $error_upload);
						//var_dump($file);
	                    redirect('home');
					}
				}
		}

		if (!empty($cekusulan->file)) {
    		$id_file_arr_old = explode(",", $cekusulan->file);
    		if (!empty($hapus)) {
    			foreach ($hapus as $row) {
					$key = array_search($row, $id_file_arr_old);
					unset($id_file_arr_old[$key]);

			    	$var_hapus = $this->m->get_one_file($row);
			    	unlink(dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$var_hapus->location);
			    	$this->m_usulan_bansos->delete_file($row);
			    }
    		}
		    foreach ($id_file_arr_old as $value) {
		    	$id_file_arr[] = $value;
		    }
	    }

			$tes='';
	    if (!empty($id_file_arr)) {
	    	$tes=implode(",", $id_file_arr);
	    	//echo $tes;

	    	//$cekusulan->file = $tes;
	    }


		 //action save
       // $call_from			= $this->input->post('call_from');
        $datata= array('flag_masukRKPD'   => $this->input->post('status',true),
        	              'keterangan_rapat' => $this->input->post('keterangan'),
        	              'nominal_setuju'   => $this->input->post('anggaran',true),
        			      'id'   => $this->input->post('id',true),
        			);

       $max=sizeof($datata['flag_masukRKPD']);
       for ($i =1; $i<= $max;$i++){

       		$id = $datata['id'][$i];
       		$masukRKPD = $datata['flag_masukRKPD'][$i];
       		$keteranganRapat = $datata['keterangan_rapat'];
       		$nominalsetuju = $datata['nominal_setuju'][$i];

			$datapost=array('flag_masukRKPD' => $masukRKPD,
        	              'keterangan_rapat' => $keteranganRapat,
        	              'nominal_setuju'   => str_replace(',','.',str_replace('.','', $nominalsetuju)),
        				  			'file_rapat' 		 => $tes,
        				  			'changed_by'		 => $this->session->userdata('nama'),
        				  			'changed_date'	 => $date." ".$time,
        			);
			//print_r($datapost);
			$ret = TRUE;
			$ret = $this->m_usulan_bansos->update($id,$datapost,'table_musrenbang','primary_musrenbang');
				//echo $this->db->last_query();
			if ($ret === FALSE){
	            $this->session->set_userdata('msg_typ','err');
	            $this->session->set_userdata('msg', 'Data Usulan Gagal disimpan');
			} else {
	            $this->session->set_userdata('msg_typ','ok');
	            $this->session->set_userdata('msg', 'Data Usulan Berhasil disimpan');
			}

		};
		//exit();
		if(!empty($call_from))
			redirect($call_from);

        redirect('usulanbansos/persetujuanusulanbansos');
		//var_dump($cekbank);
		//print_r ($id_cek);
    }

	function save_persetujuan(){
		$date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
		$id = $this->input->post('id_usulanbansos');

		//upload
		//Persiapan folder berdasarkan unit
		$dir_file_upload='file_upload/persetujuanhibahbansos';
		if (!file_exists($dir_file_upload)) {
		    mkdir($dir_file_upload, 0766, true);
		}
		//UPLOAD
		$this->load->library('upload');
		$config = array();
		$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'jpeg|jpg|png|pdf';
		$config['max_size'] = '1024';
		$config['overwrite'] = FALSE;

		$id_userfile 	= $this->input->post("id_userfile");
		$name_file 	= $this->input->post("name_file");
		$ket_file	= $this->input->post("ket_file");
	    $files = $_FILES;
	    $cpt = $this->input->post("upload_length");
	  //  print_r($id_userfile);
	    $hapus	= $this->input->post("hapus_file");
	    $name_file_arr = array();
	    $id_file_arr = array();

	    for($i=1; $i<=$cpt; $i++)
	    {
	    	if (empty($files['userfile']['name'][$i]) && empty($id_userfile[$i])) {
	    		continue;
	    	}elseif (empty($files['userfile']['name'][$i]) && !empty($id_userfile[$i])) {
	    		$update_var = array('name'=> $name_file[$i],'ket'=>$ket_file[$i]);
	    		$this->m_usulan_bansos->update_file($id_userfile[$i], $update_var);
	    		continue;
	    	}

	    	$file_name="persetujuanhibahbansos_".date("Ymd_His");

	        $_FILES['userfile']['name']= $file_name."_".$files['userfile']['name'][$i];
	        $_FILES['userfile']['type']= $files['userfile']['type'][$i];
	        $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
	        $_FILES['userfile']['error']= $files['userfile']['error'][$i];
	        $_FILES['userfile']['size']= $files['userfile']['size'][$i];

		    $this->upload->initialize($config);
		    $file = $this->upload->do_upload();
		    if ($file) {
		    	$file = $this->upload->data();
				$file = $file['file_name'];
				if (!empty($id_userfile[$i])) {
					$hapus[] = 	$id_userfile[$i];
				}
				$id_file_arr[] = $this->m_usulan_bansos->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);
				$name_file_arr[] = $file;

			} else {
				// Error Occured in one of the uploads
				if (empty($id) || (!empty($_FILES['userfile']['name']) && !empty($id))) {
					foreach ($id_file_arr as $value) {
						$this->m_usulan_bansos->delete_file($value);
					}
					foreach ($name_file_arr as $value) {
						unlink($directory.$value);
					}
					$error_upload	= "Draft Usulan gagal disimpan, terdapat kesalahan pada upload file atau file upload tidak sesuai dengan ketentuan.";
					$this->session->set_userdata('msg_typ','err');
	            	$this->session->set_userdata('msg', $error_upload);
					//var_dump($file);
                    redirect('home');
				}
			}
		}

		if (!empty($cekusulan->file)) {
    		$id_file_arr_old = explode(",", $cekusulan->file);
    		if (!empty($hapus)) {
    			foreach ($hapus as $row) {
					$key = array_search($row, $id_file_arr_old);
					unset($id_file_arr_old[$key]);

			    	$var_hapus = $this->m->get_one_file($row);
			    	unlink(dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$var_hapus->location);
			    	$this->m_usulan_bansos->delete_file($row);
			    }
    		}
		    foreach ($id_file_arr_old as $value) {
		    	$id_file_arr[] = $value;
		    }
	    }
			$tes='';
	    if (!empty($id_file_arr)) {
	    	$tes=implode(",", $id_file_arr);
	    }

		 //action save
        $call_from			= $this->input->post('call_from');
        $data_post = array(
            'id_keputusan' 		=> $this->input->post('status'),
    		'nominal_setuju'	=> $this->input->post('nominaldisetujui'),
    		'alasan'			=> $this->input->post('keterangan'),
        );

		if(strpos($call_from, 'usulanbansos/edit_data') != FALSE) {
			$call_from = '';
		}

		$cekusulan = $this->m_usulan_bansos->get_data(array('id_usulan_bansos'=>$id),'table_musrenbang');

        if(empty($cekusulan)) {
			//$cekusulan = new stdClass();
			$id = '';
		}

		$ret = TRUE;

		if(empty($id)) {
			//insert
            $data_post['created_by'] = $this->session->userdata('nama');
            $data_post['created_date'] = $date." ".$time;
			$data_post['file'] = $tes;
			$data_post['id_keputusan'] = "1";

			$ret = $this->m_usulan_bansos->insert($data_post,'table_musrenbang');
		//	echo $this->db->last_query();
		} else {
			//update
            $data_post['changed_by'] = $this->session->userdata('nama');
            $data_post['changed_date'] = $date." ".$time;
			$ret = $this->m_usulan_bansos->update($id,$data_post,'table_musrenbang','primary_musrenbang');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Usulan Gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Usulan Berhasil disimpan');
		}

        //var_dump($cekmusrenbang);

		if(!empty($call_from))
			redirect($call_from);

        redirect('usulanbansos/persetujuanusulanbansos');
		//var_dump($cekbank);
		//print_r ($id_cek);
    }

    function save_evaluasi(){
		$date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
		$id = $this->input->post('id_usulanbansos');
		$tglrekom = $this->input->post('tglrekomendasi');
		$tglrekomen = explode("/", $tglrekom);
		$tglrekomendasi = $tglrekomen[2]."/".$tglrekomen[1]."/".$tglrekomen[0];

		 //action save
        $call_from			= $this->input->post('call_from');
        $data_post = array(
    		'norekomendasi'			=> $this->input->post('norekomendasi'),
				'keterangan_rekomendasi'			=> $this->input->post('keteranganrekomendasi'),
				'tglrekomendasi' => $tglrekomendasi
        );

		if(strpos($call_from, 'usulanbansos/edit_data') != FALSE) {
			$call_from = '';
		}

		$cekusulan = $this->m_usulan_bansos->get_data(array('id_usulan_bansos'=>$id),'table_musrenbang');

        if(empty($cekusulan)) {
			//$cekusulan = new stdClass();
			$id = '';
		}



		//Persiapan folder berdasarkan unit
		$dir_file_upload='file_upload/4';
		if (!file_exists($dir_file_upload)) {
		    mkdir($dir_file_upload, 0766, true);
		}
		//UPLOAD
		$this->load->library('upload');
		$config = array();
		$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'jpeg|jpg|png|pdf';
		$config['max_size'] = '1024';
		$config['overwrite'] = FALSE;

		$id_userfile 	= $this->input->post("id_userfile");
		$name_file 	= $this->input->post("name_file");
		$ket_file	= $this->input->post("ket_file");
	    $files = $_FILES;
	    $cpt = $this->input->post("upload_length");
	   // print_r($id_userfile);
	    $hapus	= $this->input->post("hapus_file");
	    $name_file_arr = array();
	    $id_file_arr = array();

	    for($i=1; $i<=$cpt; $i++)
	    {
	    	if (empty($files['userfile']['name'][$i]) && empty($id_userfile[$i])) {
	    		continue;
	    	}elseif (empty($files['userfile']['name'][$i]) && !empty($id_userfile[$i])) {
	    		$update_var = array('name'=> $name_file[$i],'ket'=>$ket_file[$i]);
	    		$this->m_usulan_bansos->update_file($id_userfile[$i], $update_var);
	    		continue;
	    	}

	    	$file_name="evaluasihibahbansos_".date("Ymd_His");

	        $_FILES['userfile']['name']= $file_name."_".$files['userfile']['name'][$i];
	        $_FILES['userfile']['type']= $files['userfile']['type'][$i];
	        $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
	        $_FILES['userfile']['error']= $files['userfile']['error'][$i];
	        $_FILES['userfile']['size']= $files['userfile']['size'][$i];

		    $this->upload->initialize($config);
		    $file = $this->upload->do_upload();
            //var_dump($this->upload->display_errors('<p>', '</p>'));
          //  var_dump($this->upload->data());
		    //print_r(var_dump($this->upload->display_errors('<p>', '</p>'));	);
		    //exit();
		    if ($file) {
		    	$file = $this->upload->data();
				$file = $file['file_name'];
				if (!empty($id_userfile[$i])) {
					$hapus[] = 	$id_userfile[$i];
				}
				$id_file_arr[] = $this->m_usulan_bansos->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);

				//echo $this->db->last_query();
				$name_file_arr[] = $file;

			} else {
				// Error Occured in one of the uploads
				if (empty($id) || (!empty($_FILES['userfile']['name']) && !empty($id))) {
					foreach ($id_file_arr as $value) {
						$this->m_usulan_bansos->delete_file($value);
					}
					foreach ($name_file_arr as $value) {
						unlink($directory.$value);
					}
					$error_upload	= "Draft Usulan gagal disimpan, terdapat kesalahan pada upload file atau file upload tidak sesuai dengan ketentuan.";
					$this->session->set_userdata('msg_typ','err');
	            	$this->session->set_userdata('msg', $error_upload);
					//var_dump($file);
                    redirect('home');
				}
			}
		}

		if (!empty($cekusulan->filerekomendasi)) {
    		$id_file_arr_old = explode(",", $cekusulan->filerekomendasi);
    		if (!empty($hapus)) {
    			foreach ($hapus as $row) {
					$key = array_search($row, $id_file_arr_old);
					unset($id_file_arr_old[$key]);

			    	$var_hapus = $this->m->get_one_file($row);
			    	unlink(dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$var_hapus->location);
			    	$this->m_usulan_bansos->delete_file($row);
			    }
    		}
		    foreach ($id_file_arr_old as $value) {
		    	$id_file_arr[] = $value;
		    }
	    }


	    if (!empty($id_file_arr)) {
	    	$tes=implode(",", $id_file_arr);
	    	//print_r($tes);

	    	//$cekusulan->file = $tes;
	    }

	//	echo $cekusulan->file;
//exit();
		$ret = TRUE;

		if(empty($id)) {
			//insert
            $data_post['created_by'] = $this->session->userdata('nama');
            $data_post['created_date'] = $date." ".$time;
			$data_post['filerek'] = $tes;
			$data_post['id_keputusan'] = "0";

			//$ret = $this->m_usulan_bansos->insert($data_post,'table_musrenbang');
		//	echo $this->db->last_query();
		} else {
			//update
            $data_post['changed_by'] = $this->session->userdata('nama');
            $data_post['changed_date'] = $date." ".$time;
			$data_post['filerekomendasi'] = $tes;

			$ret = $this->m_usulan_bansos->update($id,$data_post,'table_musrenbang','primary_musrenbang');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Usulan Gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Usulan Berhasil disimpan');
		}

        //var_dump($cekmusrenbang);

		if(!empty($call_from))
			redirect($call_from);

       redirect('usulanbansos/evaluasiusulanbansos');
		//var_dump($cekbank);

    }

	function delete_data(){
		$this->auth->restrict_ajax_login();

		$id = $this->input->post("id");
		$id_group = $this->session->userdata('id_group');
		$result = $this->m_usulan_bansos->delete($id, $id_group);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data usulan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data usulan gagal dihapus, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}
	}

}
