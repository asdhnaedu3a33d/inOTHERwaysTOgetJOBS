<?php

class Report extends CI_Controller {

    public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
		$this->load->helper('url');
		$this->load->model(array('guest/m_report', 'm_skpd', 'm_renstra_trx', 'm_rpjmd_trx', 'm_bulan',
									'm_renja_trx','m_t_anggaran_aktif','m_rka','m_desa', 'm_dpa',
									'm_musrenbang','m_kecamatan','m_cik','m_kendali','m_usulanpro_trx',
									'm_renja_trx_perubahan','m_rka_perubahan','m_dpa_perubahan',
									'm_cik_perubahan'));
		if (!empty($this->session->userdata("db_aktif"))) {
        	$this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		$report=$this->input->get("report");

		$data['active_menu'] = "report";
		$jumlah = $this->m_report->get_jumlah_kegiatan_per_skpd();
		$skpd_array=array();
		$jum_array=array();
		foreach ($jumlah as $row) {
			$skpd_array[] = $row->nama;
			$jum_array[] = $row->jumlah;
		}
		$data['jumlah'] = json_encode($jum_array);
		$data['skpd'] = json_encode($skpd_array);
		$data['shortcut'] = $this->get_shortcutlist();

		switch ($report) {
			
			case 'evaluasi_rkpd';			
				$data['title'] = 'Evaluasi RKPD';
				$data['konten'] = $this->get_evaluasi_rkpd();
				$data['konten'] = $this->get_t_anggaran_evaluasi_rkpd();
				break;

			case 'evaluasi_renja';			
				$data['title'] = 'Evaluasi Renja';
				$data['konten'] = $this->get_evaluasi_renja();
				$data['konten'] = $this->get_t_anggaran_evaluasi_renja();
				break;
				
			case 'kendali_belanja':
				$data['title'] = 'Kendali Belanja';
				$data['konten'] = $this->get_kendali_belanja();
				$data['konten'] = $this->get_t_anggaran_kendali_belanja();
				break;
				
			case 'cik':
				$data['title'] = 'CIK';
				$data['konten'] = $this->get_cik();
				$data['konten'] = $this->get_t_anggaran_cik();
				$data['konten'] = $this->get_bulan_cik();
				break;
				
			case 'cik_perubahan':
				$data['title'] = 'CIK Perubahan';
				$data['konten'] = $this->get_cik_perubahan();
				$data['konten'] = $this->get_t_anggaran_cik_perubahan();
				$data['konten'] = $this->get_bulan_cik_perubahan();
				break;
			
			case 'temuwirasa':
				$data['title'] = 'Rekapitulasi Temuwirasa';
				$data['konten'] = $this->get_t_anggaran_temuwirasa();
				break;
			
			case 'pokir':
				$data['title'] = 'Rekapitulasi Pokir DPR';
				$data['konten'] = $this->get_t_anggaran_pokir();
				break;
				
			case 'rekap_skpd':
				$data['title'] = 'Rekapitulasi SKPD';
				$data['konten'] = $this->get_rekap_skpd();
				$data['konten'] = $this->get_ta_rekap_skpd();
				break;

			case 'rekap_kecamatan':
				$data['title'] = 'Rekapitulasi Kecamatan';
				$data['konten'] = $this->get_rekap_kecamatan();
				$data['konten'] = $this->get_ta_rekap_kecamatan();
				break;

			case 'musrenbangdes':
				$data['title'] = 'Rekapitulasi Desa';
				$data['konten'] = $this->get_musrenbangdes();
				$data['konten'] = $this->get_t_anggaran_musrenbangdes();
				break;
			
			case 'dpa':
				$data['title'] = 'DPA';
				$data['konten'] = $this->get_dpa();
				$data['konten'] = $this->get_t_anggaran_dpa();
				break;
				
			case 'dpa_perubahan':
				$data['title'] = 'DPA Perubahan';
				$data['konten'] = $this->get_dpa_perubahan();
				$data['konten'] = $this->get_t_anggaran_dpa_perubahan();
				break;
				
			case 'rka':
				$data['title'] = 'RKA';
				$data['konten'] = $this->get_rka();
				$data['konten'] = $this->get_t_anggaran_rka();
				break;
			
			case 'rka_perubahan':
				$data['title'] = 'RKA Perubahan';
				$data['konten'] = $this->get_rka_perubahan();
				$data['konten'] = $this->get_t_anggaran_rka_perubahan();
				break;

			case 'renja':
				$data['title'] = 'Renja';
				$data['konten'] = $this->get_renja();
				$data['konten'] = $this->get_t_anggaran_renja();
				break;
			
			case 'renja_perubahan':
				$data['title'] = 'Renja Perubahan';
				$data['konten'] = $this->get_renja_perubahan();
				$data['konten'] = $this->get_t_anggaran_renja_perubahan();
				break;
			
			case 'rkpd':
				$data['title'] = 'RKPD';
				$data['konten'] = $this->get_t_anggaran_rkpd();
				//$data['konten'] = $this->get_rkpd();
				break;

			case 'rpjmd':
				$data['title'] = 'RPJMD';
				$data['konten'] = $this->get_rpjmd();
				break;

			case 'renstra':	
				$data['title'] = 'Renstra';
				$data['konten'] = $this->get_renstra();
				break;

		}
		$this->template->load('guest/template', 'guest/report', $data);
	}	

	private function get_renstra(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/renstra_frm', $data, TRUE);
	}

	function get_data_renstra(){
		$id_skpd = $this->input->post("skpd");
		$proses = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);
		
		if (empty($proses->veri2)) {
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data renstra akhir belum tersedia.
						</div>
					</div>
				</div>
			';
		}else{
			$data['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd);
			$data2['renstra'] = $this->load->view('renstra/cetak/program_kegiatan', $data, TRUE);
			$this->load->view('guest/report/renstra_view', $data2);
		}		
	}

	private function get_rpjmd(){		
		$temp['class_table']='class="table-common"';		
		$temp['misi'] = $this->m_rpjmd_trx->get_misi_rpjmd_4_cetak_final();
		$temp['bidang_urusans'] = $this->m_rpjmd_trx->get_all_bidang_urusan_4_cetak_final();
		$data['rpjmd'] = $this->load->view("rpjmd/cetak/cetak_bidang_urusan", $temp, TRUE);
		return $this->load->view('guest/report/rpjmd_view', $data, TRUE);
	}

	private function get_renja(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/renja_frm', $data, TRUE);
	}

	private function get_t_anggaran_renja(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/renja_frm', $data, TRUE);
	}

	function get_data_renja(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
		$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd,$ta);
		
		if (empty($proses->veri2)) {
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data renja belum tersedia.
						</div>
					</div>
				</div>
			';
		}else{
			$data['ta']	= $ta;
			$data['program'] = $this->m_renja_trx->get_program_skpd_4_cetak($id_skpd,$ta);
			$data2['renja'] = $this->load->view('renja/cetak/program_kegiatan', $data, TRUE);
			$this->load->view('guest/report/renja_view', $data2);
		} 			
	}
	
	private function get_renja_perubahan(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/renja_perubahan_frm', $data, TRUE);
	}

	private function get_t_anggaran_renja_perubahan(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/renja_perubahan_frm', $data, TRUE);
	}

	function get_data_renja_perubahan(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
		$proses = $this->m_renja_trx_perubahan->count_jendela_kontrol($id_skpd,$ta);
		
		if (empty($proses->veri2)) {
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data Renja Perubahan belum tersedia.
						</div>
					</div>
				</div>
			';
		}else{
			$data['ta']	= $ta;
			$data['id_skpd']=$id_skpd;
			//$data['program'] = $this->m_renja_trx_perubahan->get_program_skpd_4_cetak($id_skpd,$ta);
			$data['urusan'] = $this->m_renja_trx_perubahan->get_urusan_skpd($ta,$id_skpd);
			$data2['renja'] = $this->load->view('renja_perubahan/cetak/program_kegiatan', $data, TRUE);
			$this->load->view('guest/report/renja_perubahan_view', $data2);
		} 			
	}
	
	private function get_t_anggaran_rkpd(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rkpd_frm', $data, TRUE);
	}
	
	function get_rkpd(){
		$ta = $this->input->post("ta");
		$temp['ta'] = $ta;
		$data['rkpd'] = $this->load->view("rkpd/cetak/cetak_rkpd", $temp, TRUE);
		//var_dump($data['rkpd']);
		$this->load->view('guest/report/rkpd_view', $data);
	}
	
	private function get_rka(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rka_frm', $data, TRUE);
	}

	private function get_t_anggaran_rka(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rka_frm', $data, TRUE);
	}

	function get_data_rka(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
			$data['ta']	= $ta;
			$data['id_skpd'] = $id_skpd;
			$data['urusan'] = $this->m_rka->get_urusan_skpd_4_cetak($id_skpd,$ta);
			$data2['rka'] = $this->load->view('rka/cetak/program_kegiatan_preview', $data, TRUE);
			$this->load->view('guest/report/rka_view', $data2);			
	}
	
	private function get_rka_perubahan(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rka_perubahan_frm', $data, TRUE);
	}

	private function get_t_anggaran_rka_perubahan(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rka_perubahan_frm', $data, TRUE);
	}

	function get_data_rka_perubahan(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
			$data['ta']	= $ta;
			$data['id_skpd'] = $id_skpd;
			//$data['program'] = $this->m_rka_perubahan->get_program_skpd_4_cetak($id_skpd,$ta);
			$data['urusan'] = $this->m_rka_perubahan->get_urusan_skpd_4_cetak($id_skpd,$ta);
			$data2['rka'] = $this->load->view('rka_perubahan/cetak/program_kegiatan_preview', $data, TRUE);
			$this->load->view('guest/report/rka_perubahan_view', $data2);			
	}

	private function get_musrenbangdes(){
		$all_desa = $this->m_desa->get_data_dropdown_desa(NULL);
		$data['dd_desa'] = form_dropdown('dd_desa', $all_desa, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/musrenbangdes_frm', $data, TRUE);
	}

	private function get_t_anggaran_musrenbangdes(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/musrenbangdes_frm', $data, TRUE);
	}

	function get_data_musrenbangdes()
	{
		$id_desa = $this->input->post("id_desa");
		$ta = $this->input->post("ta");
		$data['ta']	= $ta;
		$data['musrenbangdes'] = $this->m_musrenbang->get_musrenbangdes_cetak($id_desa,$ta);
		if(empty($data['musrenbangdes'])){
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data Musrenbangdes belum tersedia.
						</div>
					</div>
				</div>
			';
		}
		else{
			$data2['musrenbang'] = $this->load->view('musrenbang/cetak/isi_musrenbangdes', $data, TRUE);
			$this->load->view('guest/report/musrenbangdes_view', $data2);
		}
	}

	private function get_rekap_kecamatan(){
		$all_kecamatan = $this->m_kecamatan->get_data_dropdown_kecamatan(NULL);
		$data['dd_kecamatan'] = form_dropdown('dd_kecamatan', $all_kecamatan, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rekap_kec_frm', $data, TRUE);
	}

	private function get_ta_rekap_kecamatan(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rekap_kec_frm', $data, TRUE);
	}

	function get_data_rekap_kec()
	{
		$id_kec = $this->input->post("id_kec");
		$ta = $this->input->post("ta");
		$data['ta']	= $ta;
		$data['rekap_kecamatan'] = $this->m_musrenbang->get_rekap_kecamatan_cetak($id_kec,$ta);
		if(empty($data['rekap_kecamatan'])){
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data Rekapitulasi Kecamatan belum tersedia.
						</div>
					</div>
				</div>
			';
		}
		else{
			$data2['musrenbang'] = $this->load->view('musrenbang/cetak/isi_rekap_kecamatan', $data, TRUE);
			$this->load->view('guest/report/rekap_kecamatan_view', $data2);
		}
	}

	private function get_rekap_skpd(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rekap_skpd_frm', $data, TRUE);
	}

	private function get_ta_rekap_skpd(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rekap_skpd_frm', $data, TRUE);
	}

	function get_data_rekap_skpd()
	{
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$id_keputusan1 = 1;
     	$id_keputusan2 = 2;
        $id_keputusan3 = 3;
        $data['rekap_skpd1'] = $this->m_musrenbang->get_rekap_skpd_cetak($id_skpd,$tahun,$id_keputusan1);
        $data['rekap_skpd2'] = $this->m_musrenbang->get_rekap_skpd_cetak($id_skpd,$tahun,$id_keputusan2);
        $data['rekap_skpd3'] = $this->m_musrenbang->get_rekap_skpd_cetak($id_skpd,$tahun,$id_keputusan3); 
		if(empty($data['rekap_skpd1']) && empty($data['rekap_skpd2']) && empty($data['rekap_skpd3'])){
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data Rekapitulasi SKPD belum tersedia.
						</div>
					</div>
				</div>
			';
		}
		else{
			if(!empty($data['rekap_skpd1'])){
				$data2['rekapitulasi1'] = $this->load->view('skpd/cetak/isi_rekap_skpd1', $data, TRUE);
			}
			if(!empty($data['rekap_skpd2'])){
	        	$data2['rekapitulasi2'] = $this->load->view('skpd/cetak/isi_rekap_skpd2', $data, TRUE);
			}
			if(!empty($data['rekap_skpd3'])){
		        $data2['rekapitulasi3'] = $this->load->view('skpd/cetak/isi_rekap_skpd3', $data, TRUE);
		    }
			$this->load->view('guest/report/rekap_skpd_view', $data2);
		}
	}
	
	private function get_dpa(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/dpa_frm', $data, TRUE);
	}

	private function get_t_anggaran_dpa(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/dpa_frm', $data, TRUE);
	}

	function get_data_dpa(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
			$data['ta']	= $ta;
			$data['program'] = $this->m_dpa->get_program_skpd_4_cetak($id_skpd,$dpa);
			$data2['dpa'] = $this->load->view('dpa/cetak/program_kegiatan', $data, TRUE);
			$this->load->view('guest/report/dpa_view', $data2);			
	}
	
	private function get_dpa_perubahan(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/dpa_perubahan_frm', $data, TRUE);
	}

	private function get_t_anggaran_dpa_perubahan(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/dpa_perubahan_frm', $data, TRUE);
	}

	function get_data_dpa_perubahan(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
			$data['ta']	= $ta;
			$data['id_skpd'] = $id_skpd;
			//$data['program'] = $this->m_dpa_perubahan->get_program_skpd_4_cetak($id_skpd,$dpa);
			$data['urusan'] = $this->m_dpa_perubahan->get_urusan_skpd_4_cetak($id_skpd,$ta);
			$data2['dpa'] = $this->load->view('dpa_perubahan/cetak/program_kegiatan_preview', $data, TRUE);
			$this->load->view('guest/report/dpa_perubahan_view', $data2);			
	}
	
	private function get_cik(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/cik_frm', $data, TRUE);
	}

	private function get_t_anggaran_cik(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/cik_frm', $data, TRUE);
	}
	
	private function get_bulan_cik(){
		$all_bulan = $this->m_bulan->get_data_dropdown_bulan(NULL);
		$data['t_bulan'] = form_dropdown('t_bulan', $all_bulan, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/cik_frm', $data, TRUE);
	}

	function get_data_cik(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
		$bulan = $this->input->post("bulan");
			$data['ta']	= $ta;
			$data['bulan'] = $bulan;
			$data['tahun'] = $ta;
			$data['id_skpd'] = $id_skpd;
			$tot_prog = $this->m_cik->sum_capaian_program($id_skpd,$bulan,$ta);
			$count_prog = $this->m_cik->count_program($id_skpd,$bulan,$ta);
			$tot_keg = $this->m_cik->sum_capaian_kegiatan($id_skpd,$bulan,$ta);
			$count_keg = $this->m_cik->count_kegiatan($id_skpd,$bulan,$ta);
			$data['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
			$data['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
			$data['urusan'] = $this->db->query("
					SELECT pro.*,
					SUM(keg.realisasi_".$bulan.") AS realisasi,
					SUM(keg.rencana) AS rencana,
					pro.capaian_".$bulan." AS capaian,
					u.Nm_Urusan AS nama_urusan
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					LEFT JOIN m_urusan AS u
					ON pro.kd_urusan = u.Kd_Urusan
					WHERE
						keg.id_skpd =".$id_skpd."
					  AND keg.tahun = ".$ta."
					  GROUP BY keg.kd_urusan
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC;
				")->result();
			$data2['cik'] = $this->load->view('cik/cetak/isi_preview_cik', $data, TRUE);
			$this->load->view('guest/report/cik_view', $data2);			
	}
	
	private function get_cik_perubahan(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/cik_perubahan_frm', $data, TRUE);
	}

	private function get_t_anggaran_cik_perubahan(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/cik_perubahan_frm', $data, TRUE);
	}
	
	private function get_bulan_cik_perubahan(){
		$all_bulan = $this->m_bulan->get_data_dropdown_bulan(NULL);
		$data['t_bulan'] = form_dropdown('t_bulan', $all_bulan, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/cik_perubahan_frm', $data, TRUE);
	}

	function get_data_cik_perubahan(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
		$bulan = $this->input->post("bulan");
			$data['ta']	= $ta;
			$data['bulan'] = $bulan;
			$data['tahun'] = $ta;
			$data['id_skpd'] = $id_skpd;
			$tot_prog = $this->m_cik_perubahan->sum_capaian_program($id_skpd,$bulan,$ta);
			$count_prog = $this->m_cik_perubahan->count_program($id_skpd,$bulan,$ta);
			$tot_keg = $this->m_cik_perubahan->sum_capaian_kegiatan($id_skpd,$bulan,$ta);
			$count_keg = $this->m_cik_perubahan->count_kegiatan($id_skpd,$bulan,$ta);
			$data['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
			$data['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
			$data['urusan'] = $this->db->query("
					SELECT pro.*,
					SUM(keg.realisasi_".$bulan.") AS realisasi,
					SUM(keg.rencana) AS rencana,
					pro.capaian_".$bulan." AS capaian,
					u.Nm_Urusan AS nama_urusan
					  FROM
						(SELECT * FROM tx_cik_prog_keg_perubahan WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg_perubahan WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					LEFT JOIN m_urusan AS u
					ON pro.kd_urusan = u.Kd_Urusan
					WHERE
						keg.id_skpd =".$id_skpd."
					  AND keg.tahun = ".$ta."
					  GROUP BY keg.kd_urusan
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC;
				")->result();
			$data2['cik'] = $this->load->view('cik_perubahan/cetak/isi_report_cik', $data, TRUE);
			$this->load->view('guest/report/cik_perubahan_view', $data2);			
	}
	
	private function get_kendali_belanja(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/kendali_belanja_frm', $data, TRUE);
	}

	private function get_t_anggaran_kendali_belanja(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/kendali_belanja_frm', $data, TRUE);
	}
	
	function get_data_kendali_belanja(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
			//$data['program'] = $this->m_kendali->get_program_dpa_4_cetak($id_skpd,$ta);
		$data['urusan'] = $this->db->query("
				SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
				SELECT	pro.*,
						SUM(keg.nominal_1) AS sum_nominal_1,
						SUM(keg.nominal_2) AS sum_nominal_2,
						SUM(keg.nominal_3) AS sum_nominal_3,
						SUM(keg.nominal_4) AS sum_nominal_4
					FROM
						(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=1) AS pro
					INNER JOIN
						(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					WHERE
						keg.id_skpd=".$id_skpd."
						AND keg.tahun= ".$ta."
					GROUP BY keg.kd_urusan
					ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
				)t
				LEFT JOIN m_urusan AS u
				ON t.kd_urusan = u.Kd_Urusan
				")->result();
		$data['id_skpd'] = $id_skpd;
		$data['ta'] = $ta;
		$data['tahun_sekarang'] = $ta;
		$data2['kendali_belanja'] = $this->load->view('kendali_belanja/cetak/isi_kendali_belanja', $data, TRUE);
		$this->load->view('guest/report/kendali_belanja_view', $data2);			
	}
	
	private function get_evaluasi_renja(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/evaluasi_renja_frm', $data, TRUE);
	}

	private function get_t_anggaran_evaluasi_renja(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/evaluasi_renja_frm', $data, TRUE);
	}
	
	function get_data_evaluasi_renja(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
			//$data['program'] = $this->m_kendali->get_program_dpa_4_cetak($id_skpd,$ta);
			$data['ta'] = $ta;
			$data2['evaluasi_renja'] = $this->load->view('evaluasi/cetak/isi_evaluasi_renja', $data, TRUE);
			$this->load->view('guest/report/evaluasi_renja_view', $data2);			
	}
	
	private function get_evaluasi_rkpd(){
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL);
		$data['dd_skpd'] = form_dropdown('dd_skpd', $all_skpd, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/evaluasi_rkpd_frm', $data, TRUE);
	}

	private function get_t_anggaran_evaluasi_rkpd(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/evaluasi_rkpd_frm', $data, TRUE);
	}
	
	function get_data_evaluasi_rkpd(){
		$id_skpd = $this->input->post("skpd");
		$ta = $this->input->post("ta");
			//$data['program'] = $this->m_kendali->get_program_dpa_4_cetak($id_skpd,$ta);
			$data['ta'] = $ta;
			$data2['evaluasi_rkpd'] = $this->load->view('evaluasi/cetak/isi_evaluasi_rkpd', $data, TRUE);
			$this->load->view('guest/report/evaluasi_rkpd_view', $data2);
	}
	
	private function get_shortcutlist(){
		$options = array(
                  '1'  => 'Perencanaan 5 Tahun',
                  '2'  => 'Perencanaan Tahunan',
                  '3'  => 'Laporan Penganggaran',
				  '4'  => 'Laporan Usulan',
				  '5'  => 'Laporan Kendali',
				  '6'  => 'Laporan Evaluasi',
                );
		$data['laporan'] = form_dropdown('shortcut',$options, NULL, 'class="" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/list_frm', $data, TRUE);
		//<button id="btn-cari" class="btn" type="button">Search!</button>
	}
	
	function get_datalist(){
		$data['id_laporan'] = $this->input->post("laporan");
		//$data2['cik'] = $this->load->view('cik/cetak/isi_preview_cik', $data, TRUE);
		// $data2['laporan'] = $this->load->view('guest/report/isi_list', $data, TRUE);
		$this->load->view('guest/report/isi_list', $data);
	}
	
	private function get_t_anggaran_temuwirasa(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rekap_temuwirasa_frm', $data, TRUE);
	}
	
	function get_data_temuwirasa(){
		$ta = $this->input->post("ta");
		$data['ta']	= $ta;
		$data['usulanpro'] = $this->m_usulanpro_trx->get_temuwirasa_cetak($ta);
		$data['check'] = 0;
		if(empty($data['usulanpro'])){
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data Temuwirasa belum tersedia.
						</div>
					</div>
				</div>
			';
		}
		else{
			//$data2['usulanpro'] = $this->load->view('usulanpro/cetak/isi_usulanpro', $data, TRUE);
			//$this->load->view('guest/report/rekap_usulanpro_view', $data2);
			//$data2['usulanpro'] = $this->load->view('usulanpro/cetak/isi_usulanpro', $data, TRUE);
			$usulanpro =$this->m_usulanpro_trx->get_temuwirasa_cetak($ta);
			//var_dump($usulanpro);
			
			
			$html_data = '
			<thead>
				<tr>
					<th style="font-size:14px" >No</th>
					<th style="font-size:14px" >GROUP</th>
					<th style="font-size:14px" >SKPD TUJUAN</th>
					<th style="font-size:14px" >KECAMATAN</th>
					<th style="font-size:14px" >DESA</th>
					<th style="font-size:14px" >JENIS PEKERJAAN</th>
					<th style="font-size:14px" >VOLUME</th>
					<th style="font-size:14px" >SATUAN</th>					
					<th style="font-size:14px" >LOKASI</th>
					<th style="font-size:14px" >TANGGAL INPUT</th>
					<th style="font-size:14px" >POSISI</th>
					<th style="font-size:14px" >STATUS</th>
				</tr>				
			</thead>
			<tbody>';
			
				
				$i=0;
				foreach($usulanpro as $row){
				$i++;
				$html_data .= '
				<tr>
					<td style="font-size:12px" align="center">'.$i.'</td>
					<td style="font-size:12px" >'.$row->nama_group.'</td>
					<td style="font-size:12px" >'.$row->nama_skpd.'</td>
					<td style="font-size:11px" >'.$row->nama_kec.'</td>
					<td style="font-size:12px" >'.$row->nama_desa.'</td>
					<td style="font-size:12px" >'.$row->jenis_pekerjaan.'</td>
					<td style="font-size:12px" >'.$row->volume.'</td>
					<td style="font-size:12px" >'.$row->satuan.'</td>					
					<td style="font-size:12px" >'.$row->lokasi.'</td>
					<td style="font-size:12px" >'.$this->format_date($row->created_date).'</td>'.
					($row->status == NULL ?
					'<td style="font-size:12px" >Belum Ditentukan SKPD</td>'
					:'<td style="font-size:12px" >'.$row->status.'</td>').
					'<td style="font-size:12px" >'.$row->nama_keputusan.'</td>
				</tr>';
				}
				$html_data .='</tbody>';
				
				$data2['html_data'] = $html_data;
				//var_dump($html_data);
			$this->load->view('guest/report/rekap_usulanpro_view', $data2);
		}
	}
	
	private function get_t_anggaran_pokir(){
		$all_ta = $this->m_t_anggaran_aktif->get_data_dropdown_t_anggaran_aktif(NULL);
		$data['t_anggaran'] = form_dropdown('t_anggaran', $all_ta, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		return $this->load->view('guest/report/rekap_pokir_frm', $data, TRUE);
	}
	
	function get_data_pokir(){
		$ta = $this->input->post("ta");
		$data['ta']	= $ta;
		$data['usulanpro'] = $this->m_usulanpro_trx->get_pokir_cetak($ta);
		$data['check'] = 1;
		$check = 1;
		if(empty($data['usulanpro'])){
			echo '
				<div class="row">
					<div class="span11">
						<div class="alert">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>PERINGATAN!</strong> Data Pokir DPRD belum tersedia.
						</div>
					</div>
				</div>
			';
		}
		else{
			//$data2['usulanpro'] = $this->load->view('usulanpro/cetak/isi_usulanpro', $data, TRUE);
			$usulanpro =$this->m_usulanpro_trx->get_pokir_cetak($ta);
			//var_dump($usulanpro);
			
			
			$html_data = '
			<thead>
				<tr>
					<th style="font-size:14px" >No</th>
					<th style="font-size:14px" >GROUP</th>'.
					(($check == 1)?'<th style="font-size:14px" >NAMA DEWAN</th>':'').
					'<th style="font-size:14px" >SKPD TUJUAN</th>
					<th style="font-size:14px" >KECAMATAN</th>
					<th style="font-size:14px" >DESA</th>
					<th style="font-size:14px" >JENIS PEKERJAAN</th>
					<th style="font-size:14px" >VOLUME</th>
					<th style="font-size:14px" >SATUAN</th>					
					<th style="font-size:14px" >LOKASI</th>
					<th style="font-size:14px" >TANGGAL INPUT</th>
					<th style="font-size:14px" >POSISI</th>
					<th style="font-size:14px" >STATUS</th>
				</tr>				
			</thead>
			<tbody>';
			
				
				$i=0;
				foreach($usulanpro as $row){
				$i++;
				$html_data .= '
				<tr>
					<td style="font-size:12px" align="center">'.$i.'</td>
					<td style="font-size:12px" >'.$row->nama_group.'</td>
					'.($check == 1?'<td style="font-size:12px" >'.$row->nama_dewan.'</td>':'').
					'<td style="font-size:12px" >'.$row->nama_skpd.'</td>
					<td style="font-size:11px" >'.$row->nama_kec.'</td>
					<td style="font-size:12px" >'.$row->nama_desa.'</td>
					<td style="font-size:12px" >'.$row->jenis_pekerjaan.'</td>
					<td style="font-size:12px" >'.$row->volume.'</td>
					<td style="font-size:12px" >'.$row->satuan.'</td>					
					<td style="font-size:12px" >'.$row->lokasi.'</td>
					<td style="font-size:12px" >'.$this->format_date($row->created_date).'</td>'.
					($row->status == NULL ?
					'<td style="font-size:12px" >Belum Ditentukan SKPD</td>'
					:'<td style="font-size:12px" >'.$row->status.'</td>').
					'<td style="font-size:12px" >'.$row->nama_keputusan.'</td>
				</tr>';
				}
				$html_data .='</tbody>';
				
				$data2['html_data'] = $html_data;
				//var_dump($html_data);
			$this->load->view('guest/report/rekap_usulanpro_view', $data2);
		}
	}
	function indonesian_date ($timestamp = '', $date_format = 'l, j F Y | H:i') {
    if (trim ($timestamp) == '')
    {
    $timestamp = time ();
    }
    elseif (!ctype_digit ($timestamp))
        {
            $timestamp = strtotime ($timestamp);
        }
        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace ("/S/", "", $date_format);
        $pattern = array (
            '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
            '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
            '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
            '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
            '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
            '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
            '/April/','/June/','/July/','/August/','/September/','/October/',
            '/November/','/December/',
        );
        $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
            'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
            'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
            'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
            'Oktober','November','Desember',
        );
        $date = date ($date_format, $timestamp);
        $date = preg_replace ($pattern, $replace, $date);
        $date = "{$date}";
        return $date;
    }
    function format_date($tanggal){
        date_default_timezone_set('Asia/Makassar');
        $timestamp = time ();
        return $this->indonesian_date($tanggal,"j F Y");
    }
	
}
?>