<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekapitulasi_kecamatan extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
	    parent::__construct();
	    $this->load->helper(array('form','url', 'text_helper','date'));
	    $this->load->database();
	    $this->load->model(array('m_musrenbang','m_lov','m_template_cetak','m_desa','m_skpd'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$this->auth->restrict();
		$data['url_load_data'] = site_url('rekapitulasi_kecamatan/load_data');
		$data['url_show_gallery'] = site_url('rekapitulasi_kecamatan/show_gallery');

		$data['url_summary_biaya'] = site_url('rekapitulasi_kecamatan/get_summary_biaya');
		$this->template->load('template','musrenbang/rekapitulasi_kecamatan_view',$data);
	}

	function load_data(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$rekap = $this->m_musrenbang->get_data_table_rekap($search, $start, $length, $order["0"]);
		$alldata = $this->m_musrenbang->count_data_table_rekap($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;
		foreach ($rekap as $row) {
			$no++;
			$data[] = array(
				$no,
				$row->jenis_pekerjaan,
				$row->volume,
				$row->satuan,
				$row->lokasi,
				$row->jumlah_dana,
				$row->nama_desa,
				$row->nama_skpd,
				$row->keputusan,
				$row->alasan_keputusan,
				'<a href="javascript:void(0)" onclick="show_gallery('. $row->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>'
				);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function show_gallery(){
        $id = $this->input->post('id_musrenbang');
        $result = $this->db->query("SELECT file FROM t_musrenbang WHERE id_musrenbang=?", array($id));
        $id_photo = $result->row();

        $this->db->where_in("id", explode(',',$id_photo->file));
        $this->db->from("t_upload_file");
        $result = $this->db->get();
        $result = $result->result();
        //print_r($result);
        $arr = array();
        $i=0;
        foreach($result as $results){
            $arr[$i]['href'] = base_url().$results->location;
            $arr[$i]['title'] = $results->name;
            $i++;
        }
        //print_r($arr);
        /*$arr = array();
        $arr[0]['href'] = '1_b.jpg';
        $arr[1]['href'] = '2_b.jpg';
        $arr[2]['href'] = '3_b.jpg';
        */
        echo json_encode($arr);
    }

    function formatRupiah($rupiah)
    {
        return "Rp".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
    }

    ## -------------------------------------- ##
	##      Cetak Rekapitulasi Kecamatan      ##
	## -------------------------------------- ##
    private function cetak_rekap_func($id_kecamatan,$tahun,$id_skpd){
		$data['musrenbang_type'] = "";
		// $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		// $header = $this->m_template_cetak->get_value("GAMBAR");
		// $data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		// $data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd)."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>No Telp ".$skpd_detail->telp_skpd."<p>";
		$data['header'] = "REKAPITULASI USULAN <br>".strtoupper($skpd_detail->nama_skpd);
		$data['logo'] = "";
		$data1['rekap_kecamatan'] = $this->m_musrenbang->get_rekap_kecamatan_cetak($id_kecamatan,$tahun);
		$data['musrenbang'] = $this->load->view('musrenbang/cetak/isi_rekap_kecamatan', $data1, TRUE);
		return $data;
	}

	function do_cetak_rekap_kecamatan($id_kecamatan=NULL){
		ini_set('memory_limit', '-1');

		$this->auth->restrict();
		if (empty($id_kecamatan)) {
			$id_kecamatan = $this->session->userdata('id_kecamatan');
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$id_skpd = $this->session->userdata('id_skpd');
		}

		$kecamatan = $this->m_musrenbang->get_one_rekap_kecamatan($id_kecamatan,TRUE);
		if (!empty($kecamatan)) {
			$data = $this->cetak_rekap_func($id_kecamatan,$tahun,$id_skpd);
			$html = $this->template->load('template_cetak', 'musrenbang/cetak/cetak', $data, true);
			$filename='Rekapitulasi_kecamatan '. $kecamatan->nama_kec ." ". date("d-m-Y_H-i-s") .'.pdf';
		}else{
			$html = "<center>Data Tidak Tersedia . . .</center>";
			$filename='Rekapitulasi_kecamatan '. date("d-m-Y_H-i-s") .'.pdf';
		}
		//echo $html;
	    pdf_create($html, $filename, "A4", "Landscape", FALSE);
	}

	## ----------------------- ##
	## Preview Rekap Kecamatan ##
	## ----------------------- ##
	function preview_rekap_kecamatan(){
		$this->auth->restrict();
		$id_kecamatan = $this->session->userdata('id_kecamatan');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$id_skpd = $this->session->userdata('id_skpd');
		$kecamatan = $this->m_musrenbang->get_one_rekap_kecamatan($id_kecamatan,TRUE);
		if (!empty($kecamatan)) {
			$data = $this->cetak_rekap_func($id_kecamatan,$tahun,$id_skpd);
			$this->template->load('template', 'musrenbang/cetak/preview_cetak', $data);
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'Data musrenbang tidak tersedia.');
			redirect('home');
		}
	}

	function get_summary_biaya(){
		$arr = array(
			'total_biaya' => $this->m_musrenbang->get_summary_biaya_penentuan_skpd()
		);

		echo json_encode($arr);
	}
}
