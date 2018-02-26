<?php

class Home extends CI_Controller {

    public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('alat');
		$this->load->model(array('m_umum', 'guest/m_home', 'm_bidang', 'm_evaluasi_renja'));
		if (!empty($this->session->userdata("db_aktif"))) {
        	$this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$tahun = date_format(date_create(), 'Y');
		$bulan = (int)date_format(date_create(), 'm');
		$data['triwulan'] = ceil($bulan/3);

		$data['active_menu'] = "dashboard";
		$data['slider']=$this->m_umum->get_slider();
		$schedule = $this->m_home->get_schedule_all();
		$data['schedule'] = json_encode($schedule);
		$data['berita'] = $this->m_home->get_berita_all();
		$data['files'] = $this->m_home->get_file4publik();

    $skpd_arr = $this->m_home->get_skpd_files();
    $data['skpd_files'] = NULL;
    if (!empty($skpd_arr)) {
      $data['skpd_arr'] = json_encode($skpd_arr);
      $data['skpd_files'] = $this->m_home->get_file4publik($skpd_arr[rand(0, (count($skpd_arr)-1))]['id_skpd']);
    }

		$data['perbandingan_prokeg'] = $this->m_home->perbandingan_prokeg($tahun);
		$data['perbandingan_jml_prokeg'] = $this->m_home->perbandingan_jml_prokeg($tahun);
		//$data['list_perbandingan'] = $this->m_home->list_perbandingan($tahun);

		$bidang_urusan = $this->m_bidang->get_bidang_array(NULL, TRUE);
		$data['bidang_urusan'] = json_encode($bidang_urusan);

		$anggaran = $this->m_home->get_angaran($tahun);
		$realisasi = $this->m_home->get_realisasi($tahun);
		$realisasi_perubahan = $this->m_home->get_realisasi_perubahan($tahun);

		$data['anggaran'] = json_encode(array(0,
												(!empty($anggaran->nominal_1))?$anggaran->nominal_1:0,
												(!empty($anggaran->nominal_1))?$anggaran->nominal_1:0,
												(!empty($anggaran->nominal_1))?$anggaran->nominal_1:0,
												(!empty($anggaran->nominal_2))?$anggaran->nominal_2:0,
												(!empty($anggaran->nominal_2))?$anggaran->nominal_2:0,
												(!empty($anggaran->nominal_2))?$anggaran->nominal_2:0,
												(!empty($anggaran->nominal_3))?$anggaran->nominal_3:0,
												(!empty($anggaran->nominal_3))?$anggaran->nominal_3:0,
												(!empty($anggaran->nominal_3))?$anggaran->nominal_3:0,
												(!empty($anggaran->nominal_4))?$anggaran->nominal_4:0,
												(!empty($anggaran->nominal_4))?$anggaran->nominal_4:0,
												(!empty($anggaran->nominal_4))?$anggaran->nominal_4:0
											)
										);
		$arr_r = array();
		$arr_r[] = 0;
		for($i=1; $i<=12; $i++){
			$nama_var = "realisasi_".$i;
			if(!empty((float)$realisasi_perubahan->$nama_var)){
				$realisasi_temp = $realisasi_perubahan->$nama_var;
			}elseif(!empty((float)$realisasi->$nama_var)){
				$realisasi_temp = $realisasi->$nama_var;
			}else{
				$realisasi_temp = 0;
			}
			$arr_r[] = $realisasi_temp;
		}
		$data['realisasi'] = json_encode($arr_r);

		$data['tahun']=$tahun;
		$this->template->load('guest/template', 'guest/home', $data);
	}

	function get_det_schedule(){
		$id = $this->input->post("id");
		$schedule = $this->m_home->get_schedule_one($id);
		echo json_encode($schedule);
	}

	function get_det_news(){
		$id = $this->input->post("id");
		$berita = $this->m_home->get_berita_one($id);
		$return = array('title' => $berita->title, 'content'=>"<p><i>Tanggal : ".date_format(date_create($berita->date_cru), 'd/m/Y')."</i><BR><BR>".$berita->content."</p>");
		echo json_encode($return);
	}

	function get_det_file(){
		$id = $this->input->post("id");
		$file = $this->m_home->get_file4publik_one($id);
		$return = array('title' => $file->title, 'content'=>"<p><i>Tanggal : ".date_format(date_create($file->date_cru), 'd/m/Y')."</i><BR><BR>".$file->keterangan."<BR><a target=\"_blank\" class=\"btn btn-small\" href=\"". site_url($file->nama_file) ."\"><i class=\"icon-download-alt\"></i> Unduh</a></p>");
		echo json_encode($return);
	}

	function news(){
		$this->load->view("guest/news");
	}

	function get_news(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$order_arr = array('id','title','date_cru','');
		$result = $this->m_home->get_all_berita($search, $start, $length, $order["0"], $order_arr);
		$alldata = $this->m_home->count_all_berita($search);

		$data = array();
		$no=0;
		foreach ($result as $row) {
			$no++;

			$data[] = array(
							$no,
							$row->title,
							date_format(date_create($row->date_cru), 'd/m/Y'),
							'<a href="javascript:void(0)" idN="'. $row->id .'" class="icon-search detNews" title="Lihat"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function files(){
		$this->load->view("guest/files");
	}

	function get_files(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$order_arr = array('id','title','date_cru','');
		$result = $this->m_home->get_all_files($search, $start, $length, $order["0"], $order_arr);
		$alldata = $this->m_home->count_all_files($search);

		$data = array();
		$no=0;
		foreach ($result as $row) {
			$no++;

			$data[] = array(
							$no,
							$row->title,
							date_format(date_create($row->date_cru), 'd/m/Y'),
							'<a href="javascript:void(0)" idF="'. $row->id .'" class="icon-search bdet-file" title="Lihat"/><a class="icon-download" title="Unduh" target="_blank" href="'. site_url($row->nama_file) .'"></a>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function circle_kendali(){
		$triwulan = $this->input->post("triwulan");
		$kd_bidang = $this->input->post("kd_bidang");
		$kd_urusan = $this->input->post("kd_urusan");

		$tahun = date_format(date_create(), 'Y');

		$bidang = $this->m_bidang->get_one_bidang(array('Kd_Urusan' => $kd_urusan, 'Kd_Bidang' => $kd_bidang));
		$result = $this->m_home->get_value_circle_kendali($triwulan, $tahun, $kd_urusan, $kd_bidang);
		echo json_encode(array('title' => $bidang->nama, 'kendali1' => (!empty($result->kendali1))?$result->kendali1:0, 'kendali2' => (!empty($result->kendali2))?$result->kendali2:0, 'kendali3' => (!empty($result->kendali3))?$result->kendali3:0, 'kendali4' => (!empty($result->kendali4))?$result->kendali4:0));
	}

	function kendali(){
		$tw = array(
					1 => "Triwulan 1",
					2 => "Triwulan 2",
					3 => "Triwulan 3",
					4 => "Triwulan 4"
				);
		$data['dd_tw'] = form_dropdown('dd_tw', $tw, NULL, 'class="span2 m-wrap" id="triwulan"');
		$data['dd_bidang_urusan'] = $this->m_bidang->get_bidang(NULL, TRUE);
		$this->load->view("guest/kendali", $data);
	}

	function kinerja(){
		$data['dd_bidang_urusan'] = $this->m_bidang->get_bidang(NULL, TRUE);
		$this->load->view("guest/kinerja", $data);
	}

	function perbandingan(){
		$data['dd_bidang_urusan'] = $this->m_bidang->get_bidang(NULL, TRUE);
		$this->load->view("guest/perbandingan", $data);
	}

	function get_kinerja(){
		$kd_bidang = $this->input->post("kd_bidang");
		$kd_urusan = $this->input->post("kd_urusan");

    $bidang = $this->m_bidang->get_one_bidang(array('Kd_Urusan' => $kd_urusan, 'Kd_Bidang' => $kd_bidang));

    $tahun = date_format(date_create(), 'Y');
    $tw = 1;

    $max = $this->db->query("SELECT max(tahun) AS max FROM t_evaluasi_renja_prog_keg WHERE kd_urusan=? AND kd_bidang=? AND is_prog_or_keg=2;", array($kd_urusan, $kd_bidang))
                    ->row();

    if (!empty($max->max)) {
      $tahun = $max->max;
      $max = $this->db->query("SELECT max(triwulan) AS max FROM t_evaluasi_renja_prog_keg WHERE kd_urusan=? AND kd_bidang=? AND is_prog_or_keg=2 AND tahun=?;", array($kd_urusan, $kd_bidang, $max->max))
                      ->row();

      if (!empty($max)) {
        $tw = $max->max;
      }
    }

    $data = array(
					'title' => "Predikat Kinerja Kegiatan per Program pada Bidang ". $bidang->nama ." Tahun ".$tahun,
					'input' => array(0, 0, 0, 0, 0),
					'output' => array(0, 0, 0, 0, 0)
				);
    $input_data = $this->db->query("SELECT COUNT(*) AS tot, SUM(tingkat_capaian_rp) AS sum FROM t_evaluasi_renja_prog_keg WHERE kd_urusan=? AND kd_bidang=? AND is_prog_or_keg=2 AND triwulan=? AND tahun=? GROUP BY kd_urusan, kd_bidang, kd_program;", array($kd_urusan, $kd_bidang, $tw, $tahun))
                            ->result();
    $output_data = $this->db->query("SELECT COUNT(*) AS tot, SUM(t_evaluasi_renja.tingkat_capaian_k) AS sum FROM t_evaluasi_renja INNER JOIN t_evaluasi_renja_prog_keg ON t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id WHERE kd_urusan=? AND kd_bidang=? AND is_prog_or_keg=2 AND triwulan=? AND tahun=? GROUP BY kd_urusan, kd_bidang, kd_program;", array($kd_urusan, $kd_bidang, $tw, $tahun))
                            ->result();

    foreach ($input_data as $key => $value) {
      $temp = $this->m_evaluasi_renja->predikat_capaian_lap($value->sum, $value->tot);
      switch ($temp) {
        case "SR":
          $data['input'][0]++;
          break;
        case "R":
          $data['input'][1]++;
          break;
        case "S":
          $data['input'][2]++;
          break;
        case "T":
          $data['input'][3]++;
          break;
        case "ST":
          $data['input'][4]++;
          break;
      }
    }

    foreach ($output_data as $key => $value) {
      $temp = $this->m_evaluasi_renja->predikat_capaian_lap($value->sum, $value->tot);
      switch ($temp) {
        case "SR":
          $data['output'][0]++;
          break;
        case "R":
          $data['output'][1]++;
          break;
        case "S":
          $data['output'][2]++;
          break;
        case "T":
          $data['output'][3]++;
          break;
        case "ST":
          $data['output'][4]++;
          break;
      }
    }

		echo json_encode($data);
	}

	function get_perbandingan(){
		$tahun = date_format(date_create(), 'Y');

		$kd_urusan = $this->input->post("kd_urusan");
		$kd_bidang = $this->input->post("kd_bidang");

		$result = $this->m_home->get_perbandingan_grafik_per_bid($tahun, $kd_urusan, $kd_bidang);

		if (empty($result->nama)) {
			$status = 0;
		}else{
			$status = 1;
		}

		$data = array(
					'status' => $status,
					'title' => $result->nama,
					'pro_rkpd' => $result->pro_rkpd,
					'pro_apbd' => $result->pro_apbd,
					'pro_rkpd_apbd' => $result->pro_rkpd_apbd,
					'keg_rkpd' => $result->keg_rkpd,
					'keg_apbd' => $result->keg_apbd,
					'keg_rkpd_apbd' => $result->keg_rkpd_apbd
				);
		echo json_encode($data);
	}
}
?>
