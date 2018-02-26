<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Evaluasi_renja extends CI_controller
{
	var $CI = NULL;
	public $triwulan = array(
								"1" => array(
												"nama" => "Triwulan 1",
												"awal" => 1,
												"akhir" => 3,
												"romawi" => "I"
											),
								"2" => array(
												"nama" => "Triwulan 2",
												"awal" => 4,
												"akhir" => 6,
												"romawi" => "II"
											),
								"3" => array(
												"nama" => "Triwulan 3",
												"awal" => 7,
												"akhir" => 9,
												"romawi" => "III"
											),
								"4" => array(
												"nama" => "Triwulan 4",
												"awal" => 10,
												"akhir" => 12,
												"romawi" => "IV"
											),
							);

	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->database();
        $this->load->model(array('m_urusan', 'm_bidang', 'm_program', 'm_kegiatan','m_evaluasi_renja','m_skpd', 'm_settings'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		// $this->auth->restrict();
		// $id_skpd = $this->session->userdata('id_skpd');

		// $data['skpd'] = $this->m_skpd->get_one_skpd(array("id_skpd" => $id_skpd));

		// $this->template->load('template','evaluasi/renja/evaluasi_renja_view', $data);
		$this->preview_renja();
	}

	function cru_evaluasi_renja(){
		$this->auth->restrict_ajax_login();

    $data['source'] = 'Renja';
    $data['source_id'] = 'id_indikator_prog_keg';
    $data['source_id_prog_keg'] = 'id_renja';

		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$det_tahun = $this->m_evaluasi_renja->get_tahun_now($tahun);

		$data['kolom_now'] = array(
									"nominal" => "nominal_".$det_tahun->id,
									"target" => "target_".$det_tahun->id,
								);

		$id_renja = $this->input->post("idr");
		$id_indikator = $this->input->post("idi");
		$tw = $this->input->post("tw");

    ### GET DATA FOR EDIT ###
		$evaluasi_detail = $this->m_evaluasi_renja->get_one_evaluasi_renja($id_indikator, $tahun, $tw);
    $data['evaluasi_detail'] = $evaluasi_detail;
		if (!empty($evaluasi_detail)) {
			$data['ket_revisi'] = $this->m_evaluasi_renja->get_revisi_evaluasi_renja($evaluasi_detail->id_evaluasi_renja);
		}

		$data['tahun_terakhir'] = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}
		$data['tahun_sebelum'] = $tahun_sebelum;

		$data['indikator_renstra'] = $this->m_evaluasi_renja->get_indikator_renstra($id_indikator);
		$data['renstra'] = $this->m_evaluasi_renja->get_renstra($id_renja);

		$indikator_cik = $this->m_evaluasi_renja->get_indikator_cik($id_indikator);
		$cik = $this->m_evaluasi_renja->get_cik($id_renja);
		$nominal_pengurang = 0;
		$real_pengurang = 0;
		for ($i=1; $i <= 4; $i++) {
			if ($i > $tw) {
				$tw_cik[$i] = array(
								"nominal" => 0,
								"target" => 0,
							);
				continue;
			}
			$realisasi = "realisasi_".$this->triwulan[$i]["akhir"];
			$real = "real_".$this->triwulan[$i]["akhir"];

			$nominal = $cik->$realisasi - $nominal_pengurang;
			$real_persen = @$indikator_cik->$real - $real_pengurang;
			$nominal_pengurang = $cik->$realisasi;
			$real_pengurang = @$indikator_cik->$real;
			$tw_cik[$i] = array(
								"nominal" => $nominal,
								"target" => $real_persen,
							);
		}
		$realisasi = "realisasi_".$this->triwulan[$tw]["akhir"];
		$real = "real_".$this->triwulan[$tw]["akhir"];

		$nominal = $cik->$realisasi;
		$real_persen = (float)@$indikator_cik->$real;
		$tw_cik["ak"] = array(
							"nominal" => $nominal,
							"target" => $real_persen,
						);

		$data['cik'] = $tw_cik;

		$data['skpd'] = $this->m_skpd->get_one_skpd(array("id_skpd" => $id_skpd));
		$data['tahun'] = $tahun;
		$data['id_indikator'] = $id_indikator;
		$data['tw'] = $tw;
		$data['data'] = $this->m_evaluasi_renja->get_one_renja($id_renja);
		$data['periode'] = $this->triwulan[$tw]['nama'];
		$last_year = $this->m_evaluasi_renja->get_last_evaluasi_renja($id_indikator, $tahun);
		$data['realisasi_capaian_tahun_lalu'] = array(
														"target" => (!empty($last_year))?$last_year->realisasi_kinerja_k:0,
														"nominal" => (!empty($last_year))?$last_year->realisasi_kinerja_rp:0
													);

    $data['status_5t'] = (!empty($data['renstra']->nama_prog_or_keg) && !empty($data['indikator_renstra']->indikator))?1:0;
    $data['status_1t'] = 1;
    $data['status_r'] = (!empty($cik->nama_prog_or_keg) && !empty($indikator_cik->indikator))?1:0;

		$html = $this->load->view("evaluasi/renja/cru_evaluasi_renja", $data, TRUE);
		echo json_encode(array("html" => $html));
	}

  function cru_evaluasi_cik(){
    $this->auth->restrict_ajax_login();

    $data['source'] = 'CIK';
    $data['source_id'] = 'id_indikator_prog_keg_cik';
    $data['source_id_prog_keg'] = 'id_cik';

		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$det_tahun = $this->m_evaluasi_renja->get_tahun_now($tahun);

		$data['kolom_now'] = array(
									"nominal" => "nominal_".$det_tahun->id,
									"target" => "target_".$det_tahun->id,
								);

		$id_cik = $this->input->post("idr");
		$id_indikator = $this->input->post("idi");
		$tw = $this->input->post("tw");

    ### GET DATA FOR EDIT ###
		$evaluasi_detail = $this->m_evaluasi_renja->get_one_evaluasi_renja_cik($id_indikator, $tahun, $tw);
		$data['evaluasi_detail'] = $evaluasi_detail;
		if (!empty($evaluasi_detail)) {
			$data['ket_revisi'] = $this->m_evaluasi_renja->get_revisi_evaluasi_renja($evaluasi_detail->id_evaluasi_renja);
		}

		$data['tahun_terakhir'] = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}
		$data['tahun_sebelum'] = $tahun_sebelum;

		$data['indikator_renstra'] = $this->m_evaluasi_renja->get_indikator_renstra_cik($id_indikator);
		$data['renstra'] = $this->m_evaluasi_renja->get_renstra_cik($id_cik);

		$indikator_cik = $this->m_evaluasi_renja->get_indikator_cik_itself($id_indikator);
		$cik = $this->m_evaluasi_renja->get_cik_itself($id_cik);
		$nominal_pengurang = 0;
		$real_pengurang = 0;
		for ($i=1; $i <= $tw; $i++) {
			if ($i > $tw) {
				$tw_cik[$i] = array(
								"nominal" => 0,
								"target" => 0,
							);
				continue;
			}
			$realisasi = "realisasi_".$this->triwulan[$i]["akhir"];
			$real = "real_".$this->triwulan[$i]["akhir"];

			$nominal = $cik->$realisasi - $nominal_pengurang;
			$real_persen = @$indikator_cik->$real - $real_pengurang;
			$nominal_pengurang = $cik->$realisasi;
			$real_pengurang = @$indikator_cik->$real;
			$tw_cik[$i] = array(
								"nominal" => $nominal,
								"target" => $real_persen,
							);
		}
		$realisasi = "realisasi_".$this->triwulan[$tw]["akhir"];
		$real = "real_".$this->triwulan[$tw]["akhir"];

		$nominal = $cik->$realisasi;
		$real_persen = (float)@$indikator_cik->$real;
		$tw_cik["ak"] = array(
							"nominal" => $nominal,
							"target" => $real_persen,
						);

		$data['cik'] = $tw_cik;

		$data['skpd'] = $this->m_skpd->get_one_skpd(array("id_skpd" => $id_skpd));
		$data['tahun'] = $tahun;
		$data['id_indikator'] = $id_indikator;
		$data['tw'] = $tw;
		$data['data'] = $this->m_evaluasi_renja->get_one_cik($id_cik);
		$data['periode'] = $this->triwulan[$tw]['nama'];
		$last_year = $this->m_evaluasi_renja->get_last_evaluasi_renja_cik($id_indikator, $tahun);
		$data['realisasi_capaian_tahun_lalu'] = array(
														"target" => (!empty($last_year))?$last_year->realisasi_kinerja_k:0,
														"nominal" => (!empty($last_year))?$last_year->realisasi_kinerja_rp:0
													);

    $check_renja = $this->m_evaluasi_renja->check_renja_cik($id_indikator);
    $data['status_5t'] = (!empty($data['renstra']->nama_prog_or_keg) && !empty($data['indikator_renstra']->indikator))?1:0;
    $data['status_1t'] = (!empty($check_renja->nama_prog_or_keg) && !empty($check_renja->indikator))?1:0;
    $data['status_r'] = (!empty($cik->nama_prog_or_keg) && !empty($indikator_cik->indikator))?1:0;

		$html = $this->load->view("evaluasi/renja/cru_evaluasi_renja", $data, TRUE);
		echo json_encode(array("html" => $html));
  }

	function get_evaluasi_renja($pusat=NULL){
		$this->auth->restrict_ajax_login();

		$data['pusat'] = FALSE;
		if ($pusat==='PUSAT') {
			$data['pusat'] = TRUE;
		}

		$id_evaluasi_renja = $this->input->post("idev");
		$data['data'] = $this->m_evaluasi_renja->get_one_evaluasi_detail($id_evaluasi_renja);
    // echo $this->db->last_query();

		$id_skpd = $data['data']['evaluasirenja']->id_skpd;
		$tahun = $data['data']['evaluasirenja']->tahun;
		$tw = $this->input->post("tw");
		$det_tahun = $this->m_evaluasi_renja->get_tahun_now($tahun);

		$data['tahun_terakhir'] = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}
		$data['tahun_sebelum'] = $tahun_sebelum;

		$data['skpd'] = $this->m_skpd->get_one_skpd(array("id_skpd" => $id_skpd));
		$data['tahun'] = $tahun;
		$data['periode'] = $this->triwulan[$tw]['nama'];
		$data['periode_er'] = $this->m_evaluasi_renja->get_periode();
		$data['triwulan'] = $tw;
		$data['ket_revisi'] = $this->m_evaluasi_renja->get_revisi_evaluasi_renja($id_evaluasi_renja);

		$html = $this->load->view("evaluasi/renja/view_det_evaluasi_renja", $data, TRUE);
		echo json_encode(array("html" => $html));
	}

	function get_table_data(){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');

		$data['renja'] = $this->m_evaluasi_renja->get_renja_all($tahun, $id_skpd);
    $data['cik'] = $this->m_evaluasi_renja->get_cik_all($tahun, $id_skpd);

		$data['periode'] = $this->m_evaluasi_renja->get_periode();

		$html = $this->load->view('evaluasi/renja/table_renja', $data, TRUE);
		echo json_encode(array("html" => $html));
	}

	function save(){
		$input = $this->input->post();
		$realisasi['k'] = $this->input->post("realisasi_k");
		$realisasi['rp'] = $this->input->post("realisasi_rp");

    $id_evaluasi_renja = $this->input->post("id_evaluasi_renja");
    $id_evaluasi_renja_prog_keg = $this->input->post("id_evaluasi_renja_prog_keg");

    $data_evaluasi_prog_keg = array(
                                    "tahun" => $input["tahun"],
                                    "triwulan" => $input["triwulan_berjalan"],
                                    "is_prog_or_keg" => $input["is_prog_or_keg"],
                                    "kd_urusan" => $input["kd_urusan"],
                                    "kd_bidang" => $input["kd_bidang"],
                                    "kd_program" => $input["kd_program"],
                                    "nama_prog_or_keg" => $input["nama_prog_or_keg"],
                                    "id_skpd" => $input["id_skpd"],
                                    "penanggung_jawab" => $input["penanggung_jawab"],
                    								"target_akhir_renstra_rp" => $input["target_akhir_renstra_rp"],
                    								"realisasi_kinerja_sebelum_rp" => $input["realisasi_kinerja_sebelum_rp"],
                    								"target_anggaran_berjalan_rp" => $input["target_anggaran_berjalan_rp"],
                    								"realisasi_kinerja_berjalan_rp" => $input["realisasi_kinerja_berjalan_rp"],
                                    "tingkat_capaian_rp" => $input["tingkat_capaian_rp"],
                                    "realisasi_kinerja_rp" => $input["realisasi_kinerja_rp"],
                                    "tingkat_capaian_total_rp" => $input["tingkat_capaian_total_rp"]
                    							);
    if (!empty($input["kd_kegiatan"])) {
      $data_evaluasi_prog_keg['kd_kegiatan'] = $input["kd_kegiatan"];
    }
    if (!empty($input["id_renja"])) {
      $data_evaluasi_prog_keg['id_renja'] = $input["id_renja"];
    }
    if (!empty($input["id_cik"])) {
      $data_evaluasi_prog_keg['id_cik'] = $input["id_cik"];
    }

		$data_evaluasi = array(
                            "indikator" => $input["indikator"],
            								"satuan" => $input["satuan"],
                            "target_indikator" => $input["target_indikator"],
            								"target_akhir_renstra_k" => $input["target_akhir_renstra_k"],
            								"realisasi_kinerja_sebelum_k" => $input["realisasi_kinerja_sebelum_k"],
            								"target_anggaran_berjalan_k" => $input["target_anggaran_berjalan_k"],
            								"realisasi_kinerja_berjalan_k" => $input["realisasi_kinerja_berjalan_k"],
                            "tingkat_capaian_k" => $input["tingkat_capaian_k"],
                            "realisasi_kinerja_k" => $input["realisasi_kinerja_k"],
                            "tingkat_capaian_total_k" => $input["tingkat_capaian_total_k"],
                            "status_5t" => $input["status_5t"],
                            "status_1t" => $input["status_1t"],
                            "status_r" => $input["status_r"],
            							);
    if(!empty($input["id_indikator_prog_keg"])){
      ### Renja ###
      $data_evaluasi["id_indikator_prog_keg"] = $input["id_indikator_prog_keg"];
    }elseif(!empty($input["id_indikator_prog_keg_cik"])){
      ### CIK ###
      $data_evaluasi["id_indikator_prog_keg_cik"] = $input["id_indikator_prog_keg_cik"];
    }

		$result = $this->m_evaluasi_renja->save($data_evaluasi_prog_keg, $realisasi, $data_evaluasi, $id_evaluasi_renja, $id_evaluasi_renja_prog_keg);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Evaluasi renja berhasil simpan.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_veri(){
		$this->auth->restrict_ajax_login();

		$html = $this->load->view('evaluasi/renja/view_kirim_verifikasi', NULL, TRUE);
		echo json_encode(array("html" => $html));
	}

	function get_table_veri(){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');

		$data['veri'] = $this->m_evaluasi_renja->get_data_need_veri($id_skpd, $tahun);

		$html = $this->load->view('evaluasi/renja/table_veri', $data, TRUE);
		echo json_encode(array("html" => $html));
	}

	function send_veri(){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$triwulan = $this->input->post("triwulan");
		$status = $this->input->post("status");

		$this->m_evaluasi_renja->kirim_veri($id_skpd, $tahun, $triwulan, $status);

		echo json_encode(array("success" => 1));
	}

	### Verifikasi ###
	function veri(){
		$this->auth->restrict();

		$tahun = $this->session->userdata('t_anggaran_aktif');
		$data['tahun'] = $tahun;
		$data['veri_data'] = $this->m_evaluasi_renja->get_skpd_veri_data($tahun);

		$this->template->load('template','evaluasi/renja/veri/veri_view', $data);
	}

	function veri_skpd($id_skpd=NULL, $triwulan=NULL, $tahun=NULL){
		$this->auth->restrict();
		if (empty($id_skpd) || empty($triwulan) || empty($tahun)) {
			redirect("evaluasi_renja/veri");
		}

		$data['triwulan'] = $triwulan;
		$data['tahun'] = $tahun;
		$data['skpd'] = $this->m_skpd->get_one_skpd(array("id_skpd" => $id_skpd));

		$this->template->load('template','evaluasi/renja/veri/veri_skpd', $data);
	}

	function get_table_veri_skpd(){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->input->post('id_skpd');
		$tahun = $this->input->post('tahun');
		$triwulan = $this->input->post("triwulan");

		$data['triwulan'] = $triwulan;
		$data['tahun'] = $tahun;
		$data['tahun_terakhir'] = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}
		$data['tahun_sebelum'] = $tahun_sebelum;

		$data['evaluasi_renja'] = $this->m_evaluasi_renja->get_table_veri_skpd($tahun, $id_skpd, $triwulan);
		$data['periode'] = $this->m_evaluasi_renja->get_periode();

		$html = $this->load->view('evaluasi/renja/veri/table_renja', $data, TRUE);
		echo json_encode(array("html" => $html));
	}

	function veri_form(){
		$this->auth->restrict_ajax_login();

		$id_evaluasi_renja = $this->input->post("idev");
		$tw = $this->input->post("tw");
		$data['data'] = $this->m_evaluasi_renja->get_one_evaluasi_detail($id_evaluasi_renja);

		$id_skpd = $data['data']['evaluasirenja']->id_skpd;
		$tahun = $data['data']['evaluasirenja']->tahun;
		$det_tahun = $this->m_evaluasi_renja->get_tahun_now($tahun);

		$data['tahun_terakhir'] = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}
		$data['tahun_sebelum'] = $tahun_sebelum;

		$data['kolom_now'] = array(
									"nominal" => "nominal_".$det_tahun->id,
									"target" => "target_".$det_tahun->id,
								);

		$data['skpd'] = $this->m_skpd->get_one_skpd(array("id_skpd" => $id_skpd));
		$data['tahun'] = $tahun;
		$data['periode'] = $this->triwulan[$tw]['nama'];
		$data['triwulan'] = $tw;
		$data['id_evaluasi_renja'] = $id_evaluasi_renja;

		$html = $this->load->view("evaluasi/renja/veri/veri_form", $data, TRUE);
		echo json_encode(array("html" => $html));
	}

	function save_veri(){
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		$result = $this->m_evaluasi_renja->update_status($veri, $id, $ket);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Evaluasi renja berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	### Preview && Cetak Verifikasi ###
	private function cetak_evaluasi_renja_func($id_skpd, $triwulan, $tahun, $css_header_tw='', $css_table=''){
		$data['triwulan'] = $triwulan;
		$data['tahun'] = $tahun;
		$data['id_skpd'] = $id_skpd;
		$data['skpd'] = $this->m_skpd->get_one_skpd(array("id_skpd" => $id_skpd));

		$data['triwulan'] = $triwulan;
		$data['tahun'] = $tahun;
		$data['tahun_terakhir'] = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}
		$data['tahun_sebelum'] = $tahun_sebelum;

		$data['evaluasi'] = $this->m_evaluasi_renja->get_evaluasi_renja_urusan_bidang($tahun, $triwulan, $id_skpd);
		$data['periode'] = $this->m_evaluasi_renja->get_periode();

		$data['css_header_tw'] = $css_header_tw;
		$data['css_table'] = $css_table;
		$data['evaluasi'] = $this->load->view('evaluasi/renja/cetak/table_renja', $data, TRUE);
		return $data;
	}

	private function preview_list_func($id_skpd, $tahun, $type=1, $pusat=NULL){
		if ($type==1) {
			$data['title'] = "Cetak";
			$data['link'] = "cetak_evaluasi_renja";
			$data['class'] = "box_preview_cetak";
		}elseif ($type==2) {
			$data['title'] = "Preview";
			$data['link'] = "preview_evaluasi_renja";
			$data['class'] = "box_preview_preview";
      if (!is_null($pusat)) {
        $data['class'] = "box_preview_cetak";
      }
		}else{
			$data['title'] = "Export";
			$data['link'] = "export_evaluasi_renja";
			$data['class'] = "box_preview_cetak";
		}
		$data['preview_data'] = $this->m_evaluasi_renja->get_preview($id_skpd, $tahun);
    $data['pusat'] = $pusat;

		$html = $this->load->view('evaluasi/renja/preview_list', $data, TRUE);
		echo json_encode(array("html" => $html));
	}

	function preview_list($type=1){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');

		$this->preview_list_func($id_skpd, $tahun, $type);
	}

	function preview_evaluasi_renja($tw=NULL, $skpd_var=NULL){
		if (empty($tw)) {
			return FALSE;
		}

		$this->auth->restrict();
    if (!empty($skpd_var)) {
      $id_skpd = $skpd_var;
    }else{
      $id_skpd = $this->session->userdata('id_skpd');
    }
		$tahun = $this->session->userdata('t_anggaran_aktif');

		$data = $this->cetak_evaluasi_renja_func($id_skpd, $tw, $tahun, "class='header_tw'", 'class="table-common tablesorter" style="width:99%"');
    $data['skpd_var'] = $skpd_var;
		$this->template->load('template', 'evaluasi/renja/preview_evaluasi_renja',$data);
	}

	function cetak_evaluasi_renja($tw=NULL, $skpd_var=NULL){
		if (empty($tw)) {
			return FALSE;
		}

		$this->auth->restrict();
    if (!empty($skpd_var)) {
      $id_skpd = $skpd_var;
    }else{
      $id_skpd = $this->session->userdata('id_skpd');
    }
		$tahun = $this->session->userdata('t_anggaran_aktif');

		$skpd = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$data = $this->cetak_evaluasi_renja_func($id_skpd, $tw, $tahun, "class='header_tw'", 'class="full_width collapse" border="1" style="font-size: 8px;"');
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Evaluasi Renja '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s"), 1);
		$filename='Renstra '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s") .'.pdf';

		$html = $this->template->load('template_cetak', 'evaluasi/renja/cetak/cetak', $data, true);
		pdf_create($html, $filename, "A4", "Landscape", FALSE);
	}

	function export_evaluasi_renja($triwulan=NULL, $skpd_var=NULL){
		if (empty($triwulan)) {
			return FALSE;
		}
		ini_set('memory_limit','-1');

		$this->auth->restrict();
    if (!empty($skpd_var)) {
      $id_skpd = $skpd_var;
    }else{
      $id_skpd = $this->session->userdata('id_skpd');
    }
		$skpd = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$tahun = $this->session->userdata('t_anggaran_aktif');
		$triwulan = $triwulan;

		$tahun_terakhir = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}

		$periode = $this->m_evaluasi_renja->get_periode();

		// Do export
		$this->load->library('Export_excel');
		$this->export_excel->create_header(array(
													"Evaluasi Renja ".$skpd->nama_skpd ." Triwulan ". $this->triwulan[$triwulan]["romawi"]
												)
											);

		$this->export_excel->create_header(array(
													"No",
													"KODE",
													NULL,
													NULL,
													NULL,
													"Urusan/Bidang Urusan Pemerintahan Daerah dan Program / Kegiatan",
													"Indikator Kinerja Program (Outcome) / Kegiatan(Output)",
													"Target Renstra SKPD Pada Tahun ". $tahun_terakhir,
													NULL,
								          "Realisasi Capaian Kinerja Renstra SKPD s./d. Renja SKPD Tahun Lalu (". $tahun_sebelum .")",
													NULL,
								          "Target Kinerja & Anggaran Renja SKPD Tahun Berjalan Yang Dievaluasi ". $tahun,
													NULL,
								          "Realisasi Kinerja Pada Triwulan",
													NULL,
													NULL,
													NULL,
													NULL,
								          NULL,
													NULL,
								          NULL,
								          "Realisasi Capaian Kinerja dan Anggaran Renja KSPD Yang Dievaluasi (". $tahun .")",
													NULL,
								          "Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (". $tahun .")",
													NULL,
								          "Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (". $tahun .")",
													NULL,
								          "Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun ". $tahun ." (%)",
													NULL,
								          "Unit SKPD Penanggung Jawab",
								          "Ket",
                          NULL,
								          NULL,
												)
											);
		$this->export_excel->create_header(array(
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
								          NULL,
													NULL,
								          NULL,
													NULL,
													"I",
													NULL,
													"II",
													NULL,
								          "III",
													NULL,
								          "IV",
													NULL,
								          NULL,
													NULL,
								          NULL,
													NULL,
								          NULL,
													NULL,
								          NULL,
													NULL,
								          NULL,
								          NULL,
                          NULL,
								          NULL,
												)
											);
		$this->export_excel->create_header(array(
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"",
													"Rp,",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
                          NULL,
                          "5t",
                          "1t",
                          "R",
												)
											);

		$this->export_excel->merge_cell('A1','AG1');
		$this->export_excel->merge_cell('A2','A4');
		$this->export_excel->merge_cell('B2','E4');
		$this->export_excel->merge_cell('F2','F4');
		$this->export_excel->merge_cell('G2','G4');
		$this->export_excel->merge_cell('H2','I3');
		$this->export_excel->merge_cell('J2','K3');
		$this->export_excel->merge_cell('L2','M3');
		$this->export_excel->merge_cell('N2','U3');
		$this->export_excel->merge_cell('N3','O3');
		$this->export_excel->merge_cell('P3','Q3');
		$this->export_excel->merge_cell('R3','S3');
		$this->export_excel->merge_cell('T3','U3');
		$this->export_excel->merge_cell('V2','W3');
		$this->export_excel->merge_cell('X2','Y3');
		$this->export_excel->merge_cell('Z2','AA3');
		$this->export_excel->merge_cell('AB2','AC3');
		$this->export_excel->merge_cell('AD2','AD4');
		$this->export_excel->merge_cell('AE2','AG3');

		$evaluasi = $this->m_evaluasi_renja->get_evaluasi_renja_urusan_bidang($tahun, $triwulan, $id_skpd);

		$no=0;
		$tot_tingkat_capaian_k = 0;
		$tot_tingkat_capaian_rp = 0;
		$tot_tingkat_capaian_total_k = 0;
		$tot_tingkat_capaian_total_rp = 0;
		$tot_count_k = 0;
		$tot_count_rp = 0;
		foreach ($evaluasi['kode_urusan'] as $row_urusan) {
			$this->export_excel->set_row(array(
																				NULL,
																				$row_urusan->kd_urusan,
																				NULL,
																				NULL,
																				NULL,
																				$row_urusan->urusan,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																				NULL
																));

			if (!empty($evaluasi['kode_bidang'][$row_urusan->kd_urusan])) {
				foreach ($evaluasi['kode_bidang'][$row_urusan->kd_urusan] as $row_bidang) {
					// Program
					$evaluasi_renja = $this->m_evaluasi_renja->cetak_evaluasi_each_triwulan($tahun, $id_skpd, $triwulan, $row_urusan->kd_urusan, $row_bidang->kd_bidang);

					$var_tingkat_capaian_k[1] = 0;
					$var_tingkat_capaian_rp[1] = 0;
					$var_tingkat_capaian_total_k[1] = 0;
					$var_tingkat_capaian_total_rp[1] = 0;
					$count_k[1] = 0;
					$count_rp[1] = 0;

					$var_tingkat_capaian_k[2] = 0;
					$var_tingkat_capaian_rp[2] = 0;
					$var_tingkat_capaian_total_k[2] = 0;
					$var_tingkat_capaian_total_rp[2] = 0;
					$count_k[2] = 0;
					$count_rp[2] = 0;

					$this->export_excel->set_row(array(
																						NULL,
																						$row_bidang->kd_urusan,
																						$row_bidang->kd_bidang,
																						NULL,
																						NULL,
																						$row_bidang->bidang,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL,
																						NULL
																		));

					foreach ($evaluasi_renja['evaluasi_renja_prog_keg'] as $key => $row) {
				    if (!empty($evaluasi_renja['evaluasi_renja'][$row->id])) {
				    	$row_indikator = $evaluasi_renja['evaluasi_renja'][$row->id][0];
				      $no++;

							$var_tingkat_capaian_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_k;
				      $var_tingkat_capaian_rp[$row->is_prog_or_keg] += $row->tingkat_capaian_rp;
				      $var_tingkat_capaian_total_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_total_k;
				      $var_tingkat_capaian_total_rp[$row->is_prog_or_keg] += $row->tingkat_capaian_total_rp;
				      $count_k[$row->is_prog_or_keg]++;
				      $count_rp[$row->is_prog_or_keg]++;

							$this->export_excel->set_row(array(
																									$no,
																									$row->kd_urusan,
																									$row->kd_bidang,
																									$row->kd_program,
																									$row->kd_kegiatan,
																									$row->nama_prog_or_keg,
																									$row_indikator->indikator,
																									$row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan,
																									FORMATTING::currency($row->target_akhir_renstra_rp),
																									$row_indikator->realisasi_kinerja_sebelum_k,
																									FORMATTING::currency($row->realisasi_kinerja_sebelum_rp),
																									$row_indikator->target_anggaran_berjalan_k,
																									FORMATTING::currency($row->target_anggaran_berjalan_rp),
																									@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][1]->realisasi_k,
																									FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][1]->realisasi_rp),
																									@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][2]->realisasi_k,
																									FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][2]->realisasi_rp),
																									@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][3]->realisasi_k,
																									FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][3]->realisasi_rp),
																									@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][4]->realisasi_k,
																									FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][4]->realisasi_rp),
																									$row_indikator->realisasi_kinerja_berjalan_k,
																					        FORMATTING::currency($row->realisasi_kinerja_berjalan_rp),
																					        $row_indikator->tingkat_capaian_k,
																					        FORMATTING::currency($row->tingkat_capaian_rp),
																					        $row_indikator->realisasi_kinerja_k,
																					        FORMATTING::currency($row->realisasi_kinerja_rp),
																					        $row_indikator->tingkat_capaian_total_k,
																					        $row->tingkat_capaian_total_rp,
																					        $row->penanggung_jawab,
			                                            $row_indikator->status_5t,
			                                            $row_indikator->status_1t,
			                                            $row_indikator->status_r
																					));

							if ($evaluasi_renja['jumlah_evaluasi_renja'][$row->id] > 1) {
								for ($i=1; $i < $evaluasi_renja['jumlah_evaluasi_renja'][$row->id]; $i++) {
									$row_indikator = $evaluasi_renja['evaluasi_renja'][$row->id][$i];

									$var_tingkat_capaian_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_k;
					        $var_tingkat_capaian_total_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_total_k;
					        $count_k[$row->is_prog_or_keg]++;

									$this->export_excel->set_row(array(
																											NULL,
																											NULL,
																											NULL,
																											NULL,
																											NULL,
																											NULL,
																											$row_indikator->indikator,
																											$row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan,
																											NULL,
																											$row_indikator->realisasi_kinerja_sebelum_k,
																											NULL,
																											$row_indikator->target_anggaran_berjalan_k,
																											NULL,
																											@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][1]->realisasi_k,
																											NULL,
																											@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][2]->realisasi_k,
																											NULL,
																											@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][3]->realisasi_k,
																											NULL,
																											@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][4]->realisasi_k,
																											NULL,
																											$row_indikator->realisasi_kinerja_berjalan_k,
																							        NULL,
																							        $row_indikator->tingkat_capaian_k,
																							        NULL,
																							        $row_indikator->realisasi_kinerja_k,
																							        NULL,
																							        $row_indikator->tingkat_capaian_total_k,
																							        NULL,
																							        NULL,
			                                                $row_indikator->status_5t,
			                                                $row_indikator->status_1t,
			                                                $row_indikator->status_r
																							));
								}

								// Merge cel if indikator more than 1
								$row_merge = $this->export_excel->get_last_row() - $evaluasi_renja['jumlah_evaluasi_renja'][$row->id] + 1;
								$this->export_excel->merge_cell('A'.$row_merge,'A'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('B'.$row_merge,'B'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('C'.$row_merge,'C'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('D'.$row_merge,'D'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('E'.$row_merge,'E'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('F'.$row_merge,'F'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('I'.$row_merge,'I'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('K'.$row_merge,'K'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('M'.$row_merge,'M'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('O'.$row_merge,'O'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('Q'.$row_merge,'Q'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('S'.$row_merge,'S'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('U'.$row_merge,'U'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('W'.$row_merge,'W'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('Y'.$row_merge,'Y'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('AA'.$row_merge,'AA'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('AC'.$row_merge,'AC'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('AD'.$row_merge,'AD'.$this->export_excel->get_last_row());
							}
						}

						if (empty($evaluasi_renja['evaluasi_renja_prog_keg'][$key+1]) || (!empty($evaluasi_renja['evaluasi_renja_prog_keg'][$key+1]) && $evaluasi_renja['evaluasi_renja_prog_keg'][$key+1]->is_prog_or_keg==1)) {
							$this->export_excel->set_row(array(
																									"Rata-rata Capaian Kinerja (%)",
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																					        NULL,
																					        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_k[2], $count_k[2]),
																					        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_rp[2], $count_rp[2]),
																					        NULL,
																					        NULL,
																					        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_k[2], $count_k[2]),
																					        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_rp[2], $count_rp[2]),
																					        NULL,
																					        NULL,
			                                            NULL,
																					        NULL,
																					));
							$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
							$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

							$this->export_excel->set_row(array(
																									"Predikat Kinerja",
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																									NULL,
																					        NULL,
																					        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_k[2], $count_k[2]),
																					        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_rp[2], $count_rp[2]),
																					        NULL,
																					        NULL,
																					        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_k[2], $count_k[2]),
																					        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_rp[2], $count_rp[2]),
																					        NULL,
																					        NULL,
			                                            NULL,
																					        NULL,
																					));
							$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
							$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					    $var_tingkat_capaian_k[2] = 0;
					    $var_tingkat_capaian_rp[2] = 0;
					    $var_tingkat_capaian_total_k[2] = 0;
					    $var_tingkat_capaian_total_rp[2] = 0;
					    $count_k[2] = 0;
					    $count_rp[2] = 0;
					  }
					}

					$this->export_excel->set_row(array(
																							"Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program Dalam Bidang Urusan (%)",
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
			                                        NULL,
			                                        NULL,
																			));
					$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
					$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$this->export_excel->set_row(array(
																							"Predikat Kinerja Dari Seluruh Program Dalam Bidang Urusan (%)",
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
			                                        NULL,
			                                        NULL,
																			));
					$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
					$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$tot_tingkat_capaian_k += $var_tingkat_capaian_k[1];
					$tot_tingkat_capaian_rp += $var_tingkat_capaian_rp[1];
					$tot_tingkat_capaian_total_k += $var_tingkat_capaian_total_k[1];
					$tot_tingkat_capaian_total_rp += $var_tingkat_capaian_total_rp[1];
					$tot_count_k += $count_k[1];
					$tot_count_rp += $count_rp[1];
				}
			}
		}

		$this->export_excel->set_row(array(
																		"Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program (%)",
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_k, $tot_count_k),
																		$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_rp, $tot_count_rp),
																		NULL,
																		NULL,
																		$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_total_k, $tot_count_k),
																		$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_total_rp, $tot_count_rp),
																		NULL,
																		NULL,
																		NULL,
																		NULL,
														));
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row().':AG'.$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CFCFCF'))));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$this->export_excel->set_row(array(
																		"Predikat Kinerja Dari Seluruh Program",
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		NULL,
																		$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_k, $tot_count_k),
																		$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_rp, $tot_count_rp),
																		NULL,
																		NULL,
																		$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_total_k, $tot_count_k),
																		$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_total_rp, $tot_count_rp),
																		NULL,
																		NULL,
																		NULL,
																		NULL,
														));
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row().':AG'.$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CFCFCF'))));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$this->export_excel->getActiveSheet()->getStyle("A1:AG".$this->export_excel->get_last_row())->applyFromArray($this->export_excel->default_border);

		$this->export_excel->filename = "Evaluasi Renja ".$skpd->nama_skpd ." Triwulan ". $this->triwulan[$triwulan]["romawi"] ." ";

		$this->export_excel->set_readonly();
		$this->export_excel->execute();
	}

	### Pusat ###
	function pusat(){
		$this->auth->restrict();
    $data['skpd'] = $this->m_evaluasi_renja->get_all_skpd_in_evaluasi_renja();

		$this->template->load('template','evaluasi/renja/pusat/evaluasi_renja_view', $data);
	}

	function pusat_get_table_data(){
		$this->auth->restrict_ajax_login();

		$tahun = $this->session->userdata('t_anggaran_aktif');
		$id_skpd = $this->input->post("id_skpd");

    $data['renja'] = $this->m_evaluasi_renja->get_renja_all($tahun, $id_skpd);
    $data['cik'] = $this->m_evaluasi_renja->get_cik_all($tahun, $id_skpd);

    // Di Null kan agar action tidak muncul
    $temp = new stdClass;
    $temp->active = 0;
    $periode[1] = $temp;
    $periode[2] = $temp;
    $periode[3] = $temp;
    $periode[4] = $temp;
		$data['periode'] = $periode;

		$html = $this->load->view('evaluasi/renja/table_renja', $data, TRUE);
		echo json_encode(array("html" => $html));
	}

  function pusat_preview_list($type=1){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->session->userdata('t_anggaran_aktif');

		$this->preview_list_func($id_skpd, $tahun, $type, true);
	}

	function preview_renja($triwulan=NULL){
		$this->auth->restrict();
		$data['triwulan'] = $triwulan;
		$data['ta'] = $this->session->userdata('t_anggaran_aktif');
		if ($this->m_settings->get_id_tahun() == 1) {
			$data['ta_min'] = 'Kondisi Awal Renstra SKPD';
		}else{
			$data['ta_min'] = $data['ta']-1;
		}
		// $this->template->load('template', 'cik/preview_cik',$data);
		$this->template->load('template','evaluasi/renja/renja_ng/preview_ev', $data);
	}

	function get_data_evaluasi_renja(){
		$id_triwulan = $this->input->post('id_triwulan');
		$data['id_triwulan'] = $id_triwulan;
		$data['ta'] = $this->session->userdata('t_anggaran_aktif');
		$data['id_skpd'] = $this->session->userdata('id_skpd');
		$data['urusan'] = $this->m_evaluasi_renja->get_data_renja_urusan($data['ta'], $data['id_skpd']);
		echo $this->load->view('evaluasi/renja/renja_ng/isi_ev', $data, TRUE);
	}
}
