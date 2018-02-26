<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kendali_perubahan extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_musrenbang','m_lov','m_template_cetak','m_desa','m_skpd','m_kendali_perubahan','m_template_cetak','m_rka_perubahan',
		                         'm_urusan', 'm_bidang','m_program', 'm_kegiatan','m_kendali_belanja','m_kendali_belanja_perubahan'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	public function kendali_renja()
	{
		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$ta = $this->m_settings->get_tahun_anggaran();
		$data['program'] =  $this->m_kendali_perubahan->get_program_rka($id_skpd,$tahun);
		$data['jendela_kontrol'] = $this->m_rka_perubahan->count_jendela_kontrol($id_skpd,$ta);
		$this->template->load('template','kendali_renja_perubahan/view_kendali_renja',$data);
	}

	function kirim_kendali_renja(){
		$data['skpd'] = $this->session->userdata("id_skpd");
		$this->load->view('kendali_renja_perubahan/kirim_kendali_renja', $data);
	}

	function do_kirim_kendali_renja(){
		$id = $this->input->post('skpd');
		$ta = $this->m_settings->get_tahun_anggaran();
		$result = $this->m_rka_perubahan->kirim_kendali_renja($id,$ta);
		//echo $this->db->last_query();
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kendali Renja Perubahan berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kendali Renja Perubahan gagal dikirim, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

		function get_kegiatan_skpd(){
		$id_skpd = $this->session->userdata("id_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$data['jendela_kontrol'] = $this->m_rka_perubahan->count_jendela_kontrol($id_skpd,$ta);

		$id			= $this->input->post('id');
		//echo $id_renstra;

		$data['id']	= $id;
		$data['kegiatan'] = $this->m_rka_perubahan->get_all_kegiatan($id, $id_skpd, $ta);

		$this->load->view("rka_perubahan/view_kegiatan", $data);
	}

	function cru_kendali_renja(){
		//$this->output->enable_profiler(true);
		$id 	= $this->input->post('id');
		$tahun	= $this->m_settings->get_tahun_anggaran();

		$data['id_kendali'] = $id;
		$data['kendali'] = $this->m_kendali_perubahan->get_kendali_renja($id,$tahun);
		//echo $this->db->last_query();

		$this->load->view("kendali_renja_perubahan/cru_kendali_renja", $data);
	}

	function preview_history_renja(){
		//$this->output->enable_profiler(true);
    	$id = $this->input->post("id");
		$tahun	= $this->m_settings->get_tahun_anggaran();
    	$data['result'] = $this->m_kendali_perubahan->get_history($id,$tahun);
		//echo $this->db->last_query();
    	$this->load->view('kendali_renja_perubahan/history',$data);
    }

	function save_kendali_renja(){
		$id = $this->input->post('id');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$kesesuaian    = $this->input->post("kesesuaian");
		$hasil_kendali = $this->input->post("hasil_kendali");
		$tindak_lanjut = $this->input->post("tindak_lanjut");
		$hasil_tl	   = $this->input->post("hasil_tl");

		$data = $this->global_function->clean_array($data);
		$result = $this->m_kendali_perubahan->add_kendali_renja($data,$id);



		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kendali Renja Perubahan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kendali Renja Perubahan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}


		public function kendali_belanja()
		{
			$id_skpd = $this->session->userdata('id_skpd');
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$data['program'] = $this->m_kendali_perubahan->get_program_dpa($id_skpd,$tahun);
			//var_dump($data['program']);
			$this->template->load('template','kendali_belanja_perubahan/view_kendali_belanja',$data);
		}



		public function edit_kinerja_triwulan(){
			$id_dpa_prog_keg = $this->input->post('id_dpa_prog_keg');
			$id_triwulan = $this->input->post('id_triwulan');

			// $sql= "
			// 	SELECT b.* FROM (SELECT id AS id_dpa_prog_keg_triwulan,id_dpa_prog_keg FROM tx_dpa_prog_keg_triwulan_perubahan
			// 	WHERE id_dpa_prog_keg = '$id_dpa_prog_keg' AND id_triwulan='$id_triwulan') a
			// 	LEFT JOIN tx_dpa_prog_keg_triwulan_detail_perubahan b
			// 	ON a.id_dpa_prog_keg_triwulan = b.id_dpa_prog_keg_triwulan";
			$sql = "
				select * from tx_dpa_rencana_aksi_perubahan where id_dpa_prog_keg = '".$id_dpa_prog_keg."' and bulan = '".$id_triwulan."'";
			$data_triwulan = $this->db->query($sql);


			$data['kinerja_triwulan'] = $data_triwulan;
			$data['id_dpa_prog_keg_triwulan'] = $id_dpa_prog_keg;
			// $data['id_dpa_prog_keg_triwulan'] = $this->db->query("SELECT id FROM `tx_dpa_prog_keg_triwulan_perubahan` WHERE id_dpa_prog_keg = '$id_dpa_prog_keg' AND id_triwulan = '$id_triwulan'")->row()->id;
			// $data['revisi'] = $this->m_kendali_belanja_perubahan->show_revisi($data['id_dpa_prog_keg_triwulan']);
			//var_dump($id_dpa_prog_keg,$id_triwulan);
			$this->load->view("kendali_belanja_perubahan/kinerja_triwulan",$data);

		}

		public function save_kinerja_triwulan(){
			$id_dpa_prog_keg_triwulan = $this->input->post('id_dpa_prog_keg_triwulan');

			$data = $this->input->post();

			$id = $this->input->post("id_kinerja_triwulan");
			$catatan = $this->input->post("catatan");
			$keterangan = $this->input->post("keterangan");
			$capaian = $this->input->post("capaian");

			foreach ($capaian as $key => $value) {
				$this->db->query('UPDATE tx_dpa_rencana_aksi_perubahan SET capaian = "'.$value.'" WHERE id = "'.$id[$key].'"');
			}
			//$clean = array('id_kinerja_triwulan', 'catatan', 'keterangan', 'satuan_target','capaian');
			//$data = $this->global_function->clean_array($data, $clean);


			$result = $this->m_kendali_belanja_perubahan->kinerja_triwulan($id,$id_dpa_prog_keg_triwulan,$catatan,$keterangan,$capaian);

			if ($result) {
				$msg = array('success' => '1', 'msg' => 'Program berhasil dibuat.');
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
				echo json_encode($msg);
			}

		}

//-----------------------------------------------FUNGSI CETAK--------------------------------------------------
		private function cetak_kendali_renja_func($id_skpd)
		{
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$data['kendali_type'] = "KENDALI RENJA";
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$header = $this->m_template_cetak->get_value("GAMBAR");
			$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";

			$data1['urusan'] = $this->db->query("
				SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
				SELECT pro.*,
					   SUM(keg.nominal) AS sum_nominal,
					   SUM(keg.nominal_thndpn) AS sum_nominal_thndpn,
					   SUM(keg.nomrenja) AS sum_nomrenja,
					   SUM(keg.nomrenja_thndpn) AS sum_nomrenja_thndpn
				FROM
					(SELECT a.`id`, a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`nama_prog_or_keg`,
							a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn, a.`id_skpd`,
							a.kesesuaian,a.hasil_kendali,a.tindak_lanjut,a.hasil_tl,a.id_status
					 FROM tx_rka_prog_keg_perubahan a
					 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
								  AND a.`kd_bidang`=b.`kd_bidang`
								  AND a.`kd_program`=b.`kd_program`
								  AND a.`kd_kegiatan`=b.`kd_kegiatan`
								  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
					 WHERE a.is_prog_or_keg=1
					 GROUP BY a.`id`) AS pro
				INNER JOIN
					(SELECT a.`id`, a.`id_skpd`,a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`parent`,
							a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn
					 FROM tx_rka_prog_keg_perubahan a
					 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
								  AND a.`kd_bidang`=b.`kd_bidang`
								  AND a.`kd_program`=b.`kd_program`
								  AND a.`kd_kegiatan`=b.`kd_kegiatan`
								  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
					 WHERE a.is_prog_or_keg=2
					 GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`) AS keg ON keg.parent=pro.id
				WHERE
					keg.id_skpd=".$id_skpd."
				AND keg.tahun= ".$tahun."
				GROUP BY pro.kd_urusan
				ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
				) t
				LEFT JOIN m_urusan AS u
				ON t.kd_urusan = u.Kd_Urusan;
			")->result();

			$data1['id_skpd'] = $id_skpd;
			$data1['ta'] = $tahun;
			//$data1['program'] = $this->m_kendali_perubahan->get_program_rka_4_cetak($id_skpd,$tahun);
			$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			$data['kendali'] = $this->load->view('kendali_renja_perubahan/cetak/isi_kendali_renja', $data1, TRUE);
			return $data;

		}

		function do_cetak_kendali_renja($id_skpd=NULL){
			ini_set('memory_limit','-1');
			if(empty($id_skpd)) {
				$id_skpd = $this->session->userdata('id_skpd');
			}

			$data = $this->cetak_kendali_renja_func($id_skpd);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Kendali_Renja Perubahan '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'kendali_renja_perubahan/cetak/cetak', $data, TRUE);

			$filename = 'Kendali_Renja Perubahan '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
		}

		function preview_kendali_renja(){
			$this->auth->restrict();
			$id_skpd = $this->session->userdata('id_skpd');

			$skpd = $this->m_kendali_perubahan->get_one_kendali_renja($id_skpd, TRUE);
			if (!empty($skpd)) {
				$data = $this->cetak_kendali_renja_func($id_skpd);
				$this->template->load('template', 'kendali_renja_perubahan/preview_kendali_renja', $data);
			}else{
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data Kendali Renja Perubahan tidak tersedia.');
				redirect('home');
			}
		}

		private function cetak_kendali_belanja_func($id_skpd)
		{
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$data['kendali_type'] = "KENDALI BELANJA";
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$header = $this->m_template_cetak->get_value("GAMBAR");
			$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";
			$data1['urusan'] = $this->db->query("
				SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
				SELECT	pro.*,
						SUM(keg.nominal_1) AS sum_nominal_1,
						SUM(keg.nominal_2) AS sum_nominal_2,
						SUM(keg.nominal_3) AS sum_nominal_3,
						SUM(keg.nominal_4) AS sum_nominal_4
					FROM
						(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=1) AS pro
					INNER JOIN
						(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					WHERE
						keg.id_skpd=".$id_skpd."
						AND keg.tahun= ".$tahun."
					GROUP BY keg.kd_urusan
					ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
				)t
				LEFT JOIN m_urusan AS u
				ON t.kd_urusan = u.Kd_Urusan
				")->result();
			$data1['id_skpd'] = $id_skpd;
			$data1['ta'] = $tahun;

			//$data1['program'] = $this->m_kendali->get_program_dpa_4_cetak($id_skpd,$tahun);
			$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			$data['kendali'] = $this->load->view('kendali_belanja_perubahan/cetak/isi_kendali_belanja', $data1, TRUE);
			return $data;

		}

		function do_cetak_kendali_belanja()
		{
			ini_set('memory_limit','-1');
			if(empty($id_skpd)) {
				$id_skpd = $this->session->userdata('id_skpd');
			}

			$data = $this->cetak_kendali_belanja_func($id_skpd);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Kendali_Belanja Perubahan'. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'kendali_belanja_perubahan/cetak/cetak', $data, TRUE);

			$filename = 'kendali_belanja_perubahan'. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
		}
		function preview_kendali_belanja(){
			$this->auth->restrict();
			$id_skpd = $this->session->userdata('id_skpd');

			$skpd = $this->m_kendali_perubahan->get_one_kendali_belanja($id_skpd, TRUE);
			if (!empty($skpd)) {
				$data = $this->cetak_kendali_belanja_func($id_skpd);
				$this->template->load('template', 'kendali_belanja_perubahan/preview_kendali_belanja', $data);
			}else{
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data kendali belanja perubahan tidak tersedia.');
				redirect('home');
			}
		}
//===================================================================================================================
	//proses verifikasi kendali renja
	function veri_view_renja(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_kendali_perubahan->get_all_renja_veri();
		$this->template->load('template','kendali_renja_perubahan/verifikasi/view_all', $data);
	}

	function veri_renja($id_skpd){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_kendali_perubahan->get_data_renja($id_skpd);
		$data['id_skpd'] = $id_skpd;
		$this->template->load('template','kendali_renja_perubahan/verifikasi/view', $data);
	}

	function do_veri_renja(){
		$id = $this->input->post('id');
		$rka = $this->m_rka_perubahan->get_one_rka_veri($id);
		$data['rka'] = $rka;

		$this->load->view('kendali_renja_perubahan/verifikasi/veri', $data);
	}

	function save_veri_renja(){
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_rka_perubahan->approved_renja($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_rka_perubahan->not_approved_renja($id, $ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Data gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function disapprove_renja(){
		$data['id'] = $this->input->post('id');
		$this->load->view('kendali_renja_perubahan/verifikasi/disapprove_renja', $data);
	}

	function do_disapprove_renja(){
		$this->auth->restrict_ajax_login();
		$ta = $this->m_settings->get_tahun_anggaran();
		$id = $this->input->post('id');
		$result = $this->m_rka_perubahan->disapprove_renja($id, $ta);
		echo json_encode(array('success' => '1', 'msg' => 'Kendali Renja Perubahan telah ditolak.', 'href' => site_url('kendali/veri_view_renja')));
	}

	//proses verifikasi kendali belanja
	function veri_view_belanja(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_kendali_perubahan->get_all_belanja_veri();
		$this->template->load('template','kendali_belanja_perubahan/verifikasi/view_all', $data);
	}

	function veri_belanja($id_skpd){
		$this->auth->restrict();

		$data['belanjas'] = $this->m_kendali_perubahan->get_data_belanja($id_skpd);
		$data['id_skpd'] = $id_skpd;
		$this->template->load('template','kendali_belanja_perubahan/verifikasi/view', $data);
	}

	function do_veri_belanja(){
		$id = $this->input->post('id');
		$action = $this->input->post('action');

		$data['renja'] = $this->m_rka_perubahan->get_one_renja_veri($id);
		$renja = $data['renja'];
		$data['indikator'] = $this->m_rka_perubahan->get_indikator_prog_keg($renja->id, TRUE, TRUE);
		if ($action=="pro") {
			$data['program'] = TRUE;
		}else{
			$data['program'] = FALSE;
		}

		$this->load->view('kendali_belanja_perubahan/verifikasi/veri', $data);
	}

	function save_veri_belanja(){
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_rka_perubahan->approved_renja($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_rka_perubahan->not_approved_renja($id, $ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function disapprove_belanja(){
		$data['id'] = $this->input->post('id');
		$this->load->view('kendali_renja/verifikasi/disapprove_renja', $data);
	}

	function do_disapprove_belanja(){
		$this->auth->restrict_ajax_login();

		$id = $this->input->post('id');
		$result = $this->m_kendali_perubahan->disapprove_belanja($id);
		echo json_encode(array('success' => '1', 'msg' => 'Kendali Renja telah ditolak.', 'href' => site_url('kendali/veri_view_renja')));
	}

	/*------------------------------------------------------------------------>
	| Verifikasi Kendali Belanja
	------------------------------*/
	function kirim_veri()
	{
		$this->auth->restrict();

		$id_skpd = $this->session->userdata('id_skpd');
		$ta = $this->m_settings->get_tahun_anggaran();

		$this->m_kendali_belanja_perubahan->kirim_veri($id_skpd, $ta);

		redirect("kendali_perubahan/kendali_belanja");
	}

	function veri_view()
	{
		$this->auth->restrict();

		$data['kendali'] = $this->m_kendali_belanja_perubahan->get_all_veri_list();
		$this->template->load('template','kendali_belanja_perubahan/verifikasi_kendali/view_all', $data);
	}

	function veri($id=NULL)
	{
		if (!empty($id)) {
			$data['kendali'] = $this->m_kendali_belanja_perubahan->get_veri_list($id);
			$this->template->load('template','kendali_belanja_perubahan/verifikasi_kendali/view_veri', $data);
		}else{
			redirect("kendali_perubahan/veri_view");
		}
	}

	function show_view()
	{
		$id = $this->input->post('id');
		$veri = $this->m_kendali_belanja_perubahan->get_detail_for_veri($id);
		$data['veri'] = $veri;

		$data['kriteria'] = $this->m_kendali_perubahan->get_indikator_prog_keg_dpa($veri->id_dpa_prog_keg, TRUE, TRUE);
		$data['output'] = $this->m_kendali_belanja_perubahan->get_dpa_detail($veri->id_tx_dpa_prog_keg_triwulan);
		$data['revisi'] = $this->m_kendali_belanja_perubahan->show_revisi($veri->id_tx_dpa_prog_keg_triwulan);

		$this->load->view('kendali_belanja_perubahan/verifikasi_kendali/veri', $data);
	}

	function save_veri()
	{
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_kendali_belanja_perubahan->approved_veri_kendali($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_kendali_belanja_perubahan->not_approved_veri_kendali($id, $ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kendali belanja perubahan berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kendali belanja perubahan gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function show_revisi()
	{
		$id = $this->input->post("id");
		$data['revisi'] = $this->m_kendali_belanja_perubahan->show_revisi($id);

		$this->load->view('kendali_belanja_perubahan/show_revisi', $data);
	}

	function get_kendali_langsung_bulanan(){
		$id = $this->input->post('idk');
		$is_prog_or_keg = $this->input->post('is_prog_or_keg');

		$data['id_dpa_prog_keg'] = $id;
		$data['is_prog_or_keg'] = $is_prog_or_keg;
		$this->load->view('kendali_belanja_perubahan/view_kendali_belanja_detail', $data);
	}
}
?>
