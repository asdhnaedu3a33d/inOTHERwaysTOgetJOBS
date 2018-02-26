<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kendali extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_musrenbang','m_lov','m_template_cetak','m_desa','m_skpd','m_kendali','m_template_cetak','m_rka',
		                         'm_urusan', 'm_bidang','m_program', 'm_kegiatan','m_kendali_belanja'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	public function kendali_renja()
	{
		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$ta = $this->m_settings->get_tahun_anggaran();
		$data['program'] =  $this->m_kendali->get_program_rka($id_skpd,$tahun);
		// print_r($this->db->last_query());
		$data['jendela_kontrol'] = $this->m_rka->count_jendela_kontrol($id_skpd,$ta);
		$this->template->load('template','kendali_renja/view_kendali_renja',$data);
	}

	function kirim_kendali_renja(){
		$this->auth->restrict();
		$data['skpd'] = $this->session->userdata("id_skpd");
		$this->load->view('kendali_renja/kirim_kendali_renja', $data);
	}

	function do_kirim_kendali_renja(){
		$this->auth->restrict();
		$id = $this->input->post('skpd');
		$ta = $this->m_settings->get_tahun_anggaran();
		$result = $this->m_rka->kirim_kendali_renja($id,$ta);
		//echo $this->db->last_query();
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kendali Renja berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kendali Renja gagal dikirim, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

		function get_kegiatan_skpd(){
		$id_skpd = $this->session->userdata("id_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$data['jendela_kontrol'] = $this->m_rka->count_jendela_kontrol($id_skpd,$ta);

		$id			= $this->input->post('id');
		//echo $id_renstra;

		$data['id']	= $id;
		$data['kegiatan'] = $this->m_rka->get_all_kegiatan($id, $id_skpd, $ta);

		$this->load->view("rka/view_kegiatan", $data);
	}

	function cru_kendali_renja(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id 	= $this->input->post('id');
		$tahun	= $this->m_settings->get_tahun_anggaran();

		$data['id_kendali'] = $id;
		$data['kendali'] = $this->m_kendali->get_kendali_renja($id,$tahun);
		//echo $this->db->last_query();

		$this->load->view("kendali_renja/cru_kendali_renja", $data);
	}

	function preview_history_renja(){
		//$this->output->enable_profiler(true);
    	$id = $this->input->post("id");
		$tahun	= $this->m_settings->get_tahun_anggaran();
    	$data['result'] = $this->m_kendali->get_history($id,$tahun);
		//echo $this->db->last_query();
    	$this->load->view('kendali_renja/history',$data);
    }

	function save_kendali_renja(){
		$this->auth->restrict();
		$id = $this->input->post('id');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$kesesuaian    = $this->input->post("kesesuaian");
		$hasil_kendali = $this->input->post("hasil_kendali");
		$tindak_lanjut = $this->input->post("tindak_lanjut");
		$hasil_tl	   = $this->input->post("hasil_tl");

		$data = $this->global_function->clean_array($data);
		$result = $this->m_kendali->add_kendali_renja($data,$id);



		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kendali Renja berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kendali Renja gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}


		public function kendali_belanja()
		{
			$id_skpd = $this->session->userdata('id_skpd');
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$data['program'] = $this->m_kendali->get_program_dpa($id_skpd,$tahun);
			$this->template->load('template','kendali_belanja/view_kendali_belanja',$data);
			// $this->template->load('template','kendali_belanja/view_kendali_belanja_detail',$data);
		}



		public function edit_kinerja_triwulan(){
			$this->auth->restrict();
			$id_dpa_prog_keg = $this->input->post('id_dpa_prog_keg');
			$id_triwulan = $this->input->post('id_triwulan');

			// $sql= "
			// 	SELECT b.* FROM (SELECT id AS id_dpa_prog_keg_triwulan,id_dpa_prog_keg FROM tx_dpa_prog_keg_triwulan
			// 	WHERE id_dpa_prog_keg = '$id_dpa_prog_keg' AND id_triwulan='$id_triwulan') a
			// 	LEFT JOIN tx_dpa_prog_keg_triwulan_detail b
			// 	ON a.id_dpa_prog_keg_triwulan = b.id_dpa_prog_keg_triwulan";
			$sql = "
				select * from tx_dpa_rencana_aksi where id_dpa_prog_keg = '".$id_dpa_prog_keg."' and bulan = '".$id_triwulan."'";
			$data_triwulan = $this->db->query($sql);


			$data['kinerja_triwulan'] = $data_triwulan;
			$data['id_dpa_prog_keg_triwulan'] = $id_dpa_prog_keg;
			// $data['id_dpa_prog_keg_triwulan'] = @$this->db->query("SELECT id FROM `tx_dpa_prog_keg_triwulan` WHERE id_dpa_prog_keg = '$id_dpa_prog_keg' AND id_triwulan = '$id_triwulan'")->row()->id;
			// $data['revisi'] = $this->m_kendali_belanja->show_revisi($data['id_dpa_prog_keg_triwulan']);
			// //var_dump($id_dpa_prog_keg,$id_triwulan);
			//
			// if(isset($data['id_dpa_prog_keg_triwulan'])){
			// 	$mp_filefiles				= $this->get_file(explode( ',', $this->db->query("select file from tx_dpa_prog_keg_triwulan where id ='".$data['id_dpa_prog_keg_triwulan'] ."'")->row()->file), TRUE);
			// 	$data['mp_jmlfile']			= $mp_filefiles->num_rows();
			// 	$data['mp_filefiles']		= $mp_filefiles->result();
			// }
			// else{
			// 	$data['mp_jmlfile']			= 0;
			// 	$data['mp_filefiles']		= 0;
			// }
			$this->load->view("kendali_belanja/kinerja_triwulan",$data);

		}

		public function add_kinerja_triwulan(){
			$this->auth->restrict();
			$id_dpa_prog_keg = $this->input->post('id_dpa_prog_keg');
			$id_triwulan = $this->input->post('id_triwulan');

			$sql= "
				SELECT b.* FROM (SELECT id AS id_dpa_prog_keg_triwulan,id_dpa_prog_keg FROM tx_dpa_prog_keg_triwulan
				WHERE id_dpa_prog_keg = '$id_dpa_prog_keg' AND id_triwulan='$id_triwulan') a
				LEFT JOIN tx_dpa_prog_keg_triwulan_detail b
				ON a.id_dpa_prog_keg_triwulan = b.id_dpa_prog_keg_triwulan";
			$data_triwulan = $this->db->query($sql);


			$data['kinerja_triwulan'] = $data_triwulan;

			$result_1 = $this->db->query("SELECT id FROM `tx_dpa_prog_keg_triwulan` WHERE id_dpa_prog_keg = '$id_dpa_prog_keg' AND id_triwulan = '$id_triwulan'");
			//$data['id_dpa_prog_keg_triwulan'] = @->row()->id;

			if($result_1->num_rows()==0){
				for ($i=1; $i < 13; $i++) {
					$this->db->query("insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id_dpa_prog_keg."','".$i."',0)");
				}
			}

			$data['id_dpa_prog_keg_triwulan'] = $this->db->query("SELECT id FROM `tx_dpa_prog_keg_triwulan` WHERE id_dpa_prog_keg = '$id_dpa_prog_keg' AND id_triwulan = '$id_triwulan'")->row()->id;

			//if no id_dpa_prog_keg_triwulan, berarti anggaran belum diinputkan ke dpa, create row triwulan

			$data['revisi'] = $this->m_kendali_belanja->show_revisi($data['id_dpa_prog_keg_triwulan']);
			//var_dump($id_dpa_prog_keg,$id_triwulan);

			if(isset($data['id_dpa_prog_keg_triwulan'])){
				$mp_filefiles				= $this->get_file(explode( ',', $this->db->query("select file from tx_dpa_prog_keg_triwulan where id ='".$data['id_dpa_prog_keg_triwulan'] ."'")->row()->file), TRUE);
				$data['mp_jmlfile']			= $mp_filefiles->num_rows();
				$data['mp_filefiles']		= $mp_filefiles->result();
			}
			else{
				$data['mp_jmlfile']			= 0;
				$data['mp_filefiles']		= 0;
			}

			$this->load->view("kendali_belanja/add_kinerja_triwulan",$data);

		}

		public function save_kinerja_triwulan(){
			$this->auth->restrict();
			$id_dpa_prog_keg_triwulan = $this->input->post('id_dpa_prog_keg_triwulan');

			$data = $this->input->post();

			$id = $this->input->post("id_kinerja_triwulan");
			$catatan = $this->input->post("catatan");
			$keterangan = $this->input->post("keterangan");
			$capaian = $this->input->post("capaian");

			foreach ($capaian as $key => $value) {
				$this->db->query('UPDATE tx_dpa_rencana_aksi SET capaian = "'.$value.'" WHERE id = "'.$id[$key].'"');
			}
			//$clean = array('id_kinerja_triwulan', 'catatan', 'keterangan', 'satuan_target','capaian');
			//$data = $this->global_function->clean_array($data, $clean);


			//Persiapan folder berdasarkan unit
			$dir_file_upload='file_upload/kendali_kinerja_triwulan';
			if (!file_exists($dir_file_upload)) {
			    mkdir($dir_file_upload, 0766, true);
			}
			//UPLOAD
			$this->load->library('upload');
			$config = array();
			$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
			$config['upload_path'] = $directory;
			$config['allowed_types'] = 'jpeg|jpg|png|pdf|xls|doc|docx|xlsx';
			$config['max_size'] = '2048';
			$config['overwrite'] = FALSE;

			$id_userfile 	= $this->input->post("id_userfile");
			$name_file 	= $this->input->post("name_file");
			$ket_file	= $this->input->post("ket_file");
		    $files = $_FILES;
		    $cpt = $this->input->post("upload_length");
			//var_dump($files);
		    $hapus	= $this->input->post("hapus_file");
		    $name_file_arr = array();
		    $id_file_arr = array();

		    for($i=1; $i<=$cpt; $i++)
		    {
		    	if (empty($files['userfile']['name'][$i]) && empty($id_userfile[$i])) {
		    		continue;
		    	}elseif (empty($files['userfile']['name'][$i]) && !empty($id_userfile[$i])) {
		    		$update_var = array('name'=> $name_file[$i],'ket'=>$ket_file[$i]);
		    		$this->update_file($id_userfile[$i], $update_var);
		    		continue;
		    	}

		    	//$file_name=date("Ymd_His");

		        $_FILES['userfile']['name']= $files['userfile']['name'][$i];
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
					$id_file_arr[] = $this->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);
					$name_file_arr[] = $file;
				} else {
					// Error Occured in one of the uploads
					if (empty($id_dpa_prog_keg_triwulan) || (!empty($_FILES['userfile']['name']) && !empty($id_dpa_prog_keg_triwulan))) {
						foreach ($id_file_arr as $value) {
							$this->delete_file($value);
						}
						foreach ($name_file_arr as $value) {
							unlink($directory.$value);
						}
						$error_upload	= "Draft Usulan gagal disimpan, terdapat kesalahan pada upload file atau file upload tidak sesuai dengan ketentuan.";
						$this->session->set_userdata('msg_typ','err');
		            	$this->session->set_userdata('msg', $error_upload);
						//var_dump($file);
	                    //redirect('home');
					}
				}
			}

			$hasil_kinerja_triwulan = $this->db->query("select * from tx_dpa_prog_keg_triwulan where id='$id_dpa_prog_keg_triwulan'")->row();
			if(empty($hasil_kinerja_triwulan)) {
				$hasil_kinerja_triwulan = new stdClass();
			}

			if (!empty($hasil_kinerja_triwulan->file)) {
	    		$id_file_arr_old = explode(",", $hasil_kinerja_triwulan->file);
	    		if (!empty($hapus)) {
	    			foreach ($hapus as $row) {
						$key = array_search($row, $id_file_arr_old);
						unset($id_file_arr_old[$key]);

				    	$var_hapus = $this->get_one_file($row);
				    	//echo $this->db->last_query();
				    	unlink($directory.'/'.$var_hapus->file);
				    	$this->delete_file($row);
				    }
	    		}
			    foreach ($id_file_arr_old as $value) {
			    	$id_file_arr[] = $value;
			    }
		    }

		    if (!empty($id_file_arr)) {
		    	$data_post['file'] = implode(",", $id_file_arr);
		    }


			$result = $this->m_kendali_belanja->kinerja_triwulan($id,$id_dpa_prog_keg_triwulan,$catatan,$keterangan,$capaian);



			//var_dump($data_post);
			//update
			$sql = "update tx_dpa_prog_keg_triwulan set `file` = '".$data_post['file']."' where id='$id_dpa_prog_keg_triwulan'";
			$this->db->query($sql);


			if ($result) {
				$msg = array('success' => '1', 'msg' => 'Program berhasil dibuat.');
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
				echo json_encode($msg);
			}
		}

		public function save_add_kinerja_triwulan(){
			$this->auth->restrict();
			$id_dpa_prog_keg_triwulan = $this->input->post('id_dpa_prog_keg_triwulan');

			$data = $this->input->post();

			$id = $this->input->post("id_kinerja_triwulan");
			$catatan = $this->input->post("catatan");
			$keterangan = $this->input->post("keterangan");
			$capaian = $this->input->post("capaian");

			//var_dump($data);
			//var_dump($catatan);
			//var_dump($data);
			$result = $this->m_kendali_belanja->kinerja_triwulan($id,$id_dpa_prog_keg_triwulan,$catatan,$keterangan,$capaian);
			if ($result) {
				$msg = array('success' => '1', 'msg' => 'Program berhasil dibuat.');
				//echo json_encode($msg);
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Program berhasil dibuat');
				//var_dump($file);
				//redirect('kendali/kendali_belanja');
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
				//echo json_encode($msg);
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
				//redirect('kendali/kendali_belanja');
			}
			echo json_encode($msg);
		}



		function add_file($file, $name, $ket, $location){
			$this->auth->restrict();
			$this->db->set('file', $file);
			$this->db->set('name', $name);
			$this->db->set('ket', $ket);
			$this->db->set('location', $location);
			$this->db->insert('t_upload_file_kinerja_triwulan');
			return $this->db->insert_id();
		}

		function update_file($id, $data){
			$this->auth->restrict();
			$this->db->where('id', $id);
			$result = $this->db->update('t_upload_file_kinerja_triwulan', $data);
			return $result;
		}

		function delete_file($id){
			$this->auth->restrict();
			$this->db->where("id", $id);
			$result = $this->db->delete('t_upload_file_kinerja_triwulan');
			return $result;
		}

		function get_file($id = array(), $only = FALSE){
			$this->db->where_in("id", $id);
			$this->db->from('t_upload_file_kinerja_triwulan');
			$result = $this->db->get();
			if ($only) {
				return $result;
			}else{
				return $result->result();
			}
		}

		function get_one_file($id){
			$this->db->where("id", $id);
			$this->db->from('t_upload_file_kinerja_triwulan');
			$result = $this->db->get();
			return $result->row();
		}

//-----------------------------------------------FUNGSI CETAK--------------------------------------------------
		private function cetak_kendali_renja_func($id_skpd)
		{
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$data['kendali_type'] = "KENDALI RENJA";
			//$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			//$header = $this->m_template_cetak->get_value("GAMBAR");
			//$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			//$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";

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
					 FROM tx_rka_prog_keg a
					 LEFT JOIN t_renja_prog_keg b ON a.`kd_urusan`=b.`kd_urusan`
								  AND a.`kd_bidang`=b.`kd_bidang`
								  AND a.`kd_program`=b.`kd_program`
								  AND a.`kd_kegiatan`=b.`kd_kegiatan`
								  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
					 WHERE a.is_prog_or_keg=1
					 GROUP BY a.`id`) AS pro
				INNER JOIN
					(SELECT a.`id`, a.`id_skpd`,a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`parent`,
							a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn
					 FROM tx_rka_prog_keg a
					 LEFT JOIN t_renja_prog_keg b ON a.`kd_urusan`=b.`kd_urusan`
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
			//$data1['program'] = $this->m_kendali->get_program_rka_4_cetak($id_skpd,$tahun);
			$data['tahun'] = $tahun;
			$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			$data['kendali'] = $this->load->view('kendali_renja/cetak/isi_kendali_renja', $data1, TRUE);
			return $data;

		}

		function do_cetak_kendali_renja($id_skpd=NULL){
			ini_set('memory_limit','-1');
			if(empty($id_skpd)) {
				$id_skpd = $this->session->userdata('id_skpd');
			}

			$data = $this->cetak_kendali_renja_func($id_skpd);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Kendali_Renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'kendali_renja/cetak/cetak', $data, TRUE);

			$filename = 'Kendali_Renja'. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
		}

		function preview_kendali_renja(){
			$this->auth->restrict();
			$id_skpd = $this->session->userdata('id_skpd');

			$skpd = $this->m_kendali->get_one_kendali_renja($id_skpd, TRUE);
			if (!empty($skpd)) {
				$data = $this->cetak_kendali_renja_func($id_skpd);
				$this->template->load('template', 'kendali_renja/preview_kendali_renja', $data);
			}else{
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data kendali renja tidak tersedia.');
				redirect('home');
			}
		}

		private function cetak_kendali_belanja_func($id_skpd)
		{
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$data['kendali_type'] = "KENDALI BELANJA";
			//$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			//$header = $this->m_template_cetak->get_value("GAMBAR");
			//$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			//$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";

			$data1['urusan'] = $this->db->query("
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
			$data['tahun'] = $tahun;
			$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			$data['kendali'] = $this->load->view('kendali_belanja/cetak/isi_kendali_belanja', $data1, TRUE);
			return $data;

		}

		function do_cetak_kendali_belanja()
		{
			ini_set('memory_limit','-1');
			if(empty($id_skpd)) {
				$id_skpd = $this->session->userdata('id_skpd');
			}

			$data = $this->cetak_kendali_belanja_func($id_skpd);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Kendali_Belanja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'kendali_belanja/cetak/cetak', $data, TRUE);

			$filename = 'kendali_belanja'. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
		}
		function preview_kendali_belanja(){
			$this->auth->restrict();
			$id_skpd = $this->session->userdata('id_skpd');

			$skpd = $this->m_kendali->get_one_kendali_belanja($id_skpd, TRUE);
			if (!empty($skpd)) {
				$data = $this->cetak_kendali_belanja_func($id_skpd);
				$this->template->load('template', 'kendali_belanja/preview_kendali_belanja', $data);
			}else{
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data kendali belanja tidak tersedia.');
				redirect('home');
			}
		}
//===================================================================================================================
	//proses verifikasi kendali renja
	function veri_view_renja(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_kendali->get_all_renja_veri();
		$this->template->load('template','kendali_renja/verifikasi/view_all', $data);
	}

	function veri_renja($id_skpd){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_kendali->get_data_renja($id_skpd);
		$data['id_skpd'] = $id_skpd;
		$this->template->load('template','kendali_renja/verifikasi/view', $data);
	}

	function do_veri_renja(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$rka = $this->m_rka->get_one_rka_veri($id);
		$data['rka'] = $rka;

		$this->load->view('kendali_renja/verifikasi/veri', $data);
	}

	function save_veri_renja(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_rka->approved_renja($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_rka->not_approved_renja($id, $ket);
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
		$this->auth->restrict();
		$data['id'] = $this->input->post('id');
		$this->load->view('kendali_renja/verifikasi/disapprove_renja', $data);
	}

	function do_disapprove_renja(){
		$this->auth->restrict_ajax_login();
		$ta = $this->m_settings->get_tahun_anggaran();
		$id = $this->input->post('id');
		$result = $this->m_rka->disapprove_renja($id, $ta);
		echo json_encode(array('success' => '1', 'msg' => 'Kendali Renja telah ditolak.', 'href' => site_url('kendali/veri_view_renja')));
	}

	//proses verifikasi kendali belanja
	function veri_view_belanja(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_kendali->get_all_belanja_veri();
		$this->template->load('template','kendali_belanja/verifikasi/view_all', $data);
	}

	function veri_belanja($id_skpd){
		$this->auth->restrict();

		$data['belanjas'] = $this->m_kendali->get_data_belanja($id_skpd);
		$data['id_skpd'] = $id_skpd;
		$this->template->load('template','kendali_belanja/verifikasi/view', $data);
	}

	function do_veri_belanja(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$action = $this->input->post('action');

		$data['renja'] = $this->m_rka->get_one_renja_veri($id);
		$renja = $data['renja'];
		$data['indikator'] = $this->m_rka->get_indikator_prog_keg($renja->id, TRUE, TRUE);
		if ($action=="pro") {
			$data['program'] = TRUE;
		}else{
			$data['program'] = FALSE;
		}

		$this->load->view('kendali_belanja/verifikasi/veri', $data);
	}

	function save_veri_belanja(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_rka->approved_renja($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_rka->not_approved_renja($id, $ket);
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
		$this->auth->restrict();
		$data['id'] = $this->input->post('id');
		$this->load->view('kendali_renja/verifikasi/disapprove_renja', $data);
	}

	function do_disapprove_belanja(){
		$this->auth->restrict_ajax_login();

		$id = $this->input->post('id');
		$result = $this->m_kendali->disapprove_belanja($id);
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

		$this->m_kendali_belanja->kirim_veri($id_skpd, $ta);

		redirect("kendali/kendali_belanja");
	}

	function veri_view()
	{
		$this->auth->restrict();

		$data['kendali'] = $this->m_kendali_belanja->get_all_veri_list();
		$this->template->load('template','kendali_belanja/verifikasi_kendali/view_all', $data);
	}

	function veri($id=NULL)
	{
		if (!empty($id)) {
			$data['kendali'] = $this->m_kendali_belanja->get_veri_list($id);
			$this->template->load('template','kendali_belanja/verifikasi_kendali/view_veri', $data);
		}else{
			redirect("kendali/veri_view");
		}
	}

	function show_view()
	{
		$id = $this->input->post('id');
		$veri = $this->m_kendali_belanja->get_detail_for_veri($id);
		$data['veri'] = $veri;

		$data['kriteria'] = $this->m_kendali->get_indikator_prog_keg_dpa($veri->id_dpa_prog_keg, TRUE, TRUE);
		$data['output'] = $this->m_kendali_belanja->get_dpa_detail($veri->id_tx_dpa_prog_keg_triwulan);
		$data['revisi'] = $this->m_kendali_belanja->show_revisi($veri->id_tx_dpa_prog_keg_triwulan);

		$this->load->view('kendali_belanja/verifikasi_kendali/veri', $data);
	}

	function save_veri()
	{
		$this->auth->restrict();
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_kendali_belanja->approved_veri_kendali($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_kendali_belanja->not_approved_veri_kendali($id, $ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kendali belanja berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kendali belanja gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function show_revisi()
	{
		$id = $this->input->post("id");
		$data['revisi'] = $this->m_kendali_belanja->show_revisi($id);

		$this->load->view('kendali_belanja/show_revisi', $data);
	}

	function get_kendali_langsung_bulanan(){
		$id = $this->input->post('idk');
		$is_prog_or_keg = $this->input->post('is_prog_or_keg');

		$data['id_dpa_prog_keg'] = $id;
		$data['is_prog_or_keg'] = $is_prog_or_keg;
		$this->load->view('kendali_belanja/view_kendali_belanja_detail', $data);
	}
}
?>
