<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_skpd extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
    parent::__construct();
    $this->load->helper(array('form','url', 'text_helper','date'));
    $this->load->database();
    $this->load->model(array('m_musrenbang','m_lov','m_template_cetak','m_skpd'));
    // $this->load->model('m_musrenbang','',TRUE);
	// $this->load->model('m_lov','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

    function index(){
        $this->auth->restrict();

        $data['url_add_data'] = site_url('forum_skpd/edit_data');
        $data['url_load_data'] = site_url('forum_skpd/load_data');
        $data['url_delete_data'] = site_url('forum_skpd/delete_data');
        $data['url_edit_data'] = site_url('forum_skpd/edit_data');
        $data['url_save_data'] = site_url('forum_skpd/save_data');
        $data['url_data_list_musrenbangcam'] = site_url('forum_skpd/show_list_musrembangcam');

        $data['url_terima_usulan_musrenbang'] = site_url('forum_skpd/terima_usulan_musrenbang');
        $data['url_tolak_usulan_musrenbang'] = site_url('forum_skpd/tolak_usulan_musrenbang');
        $data['url_valid_usulan_musrenbang'] = site_url('forum_skpd/valid_usulan_musrenbang');
        $data['url_load_keterangan'] = site_url('forum_skpd/load_keterangan');
        $data['url_show_gallery'] = site_url('forum_skpd/show_gallery');
        $this->template->load('template','forum_skpd/forum_skpd',$data);
	}

	function save_data(){
    $date=date("Y-m-d");
    $time=date("H:i:s");
    $this->auth->restrict();
    //action save cekbank di table t_cmusrenbangdes
    $id_musrenbang 	= $this->input->post('id_musrenbang');
    $call_from			= $this->input->post('call_from');
    $data_post = array(
        'tahun'             => $this->session->userdata('t_anggaran_aktif'),
    		'jenis_pekerjaan'	=> $this->input->post('jenis_pekerjaan'),
    		'volume'			=> $this->input->post('volume'),
    		'lokasi'			=> $this->input->post('lokasi'),
        'satuan'			=> $this->input->post('satuan'),
    		'jumlah_dana'		=> $this->input->post('jumlah_dana'),
    		'id_skpd'			=> $this->input->post('id_skpd'),
				'id_kecamatan' => $this->input->post('id_kecamatan')=='' ? $this->session->userdata('id_kecamatan') : $this->input->post('id_kecamatan'),
				'id_keputusan'   => $this->input->post('id_keputusan'),
				'id_asal_usulan' => $this->input->post('id_asal_usulan')==''? '5' : $this->input->post('id_asal_usulan'),
				'alasan_prioritas' =>$this->input->post('alasan_prioritas')
    );

		if(strpos($call_from, 'forum_skpd/edit_data') != FALSE) {
			$call_from = '';
		}

		$cekmusrenbang = $this->m_musrenbang->get_data(array('id_musrenbang'=>$id_musrenbang),'table_musrenbang');
		if($cekmusrenbang === empty($cekmusrenbang)) {
			$cekmusrenbang = new stdClass();
			$id_musrenbang = '';
		}

		$ret = TRUE;
		if(empty($id_musrenbang)) {
			//insert
      $data_post['created_by'] = $this->session->userdata('id_user');
      $data_post['created_date'] = $date." ".$time;
			$ret = $this->m_musrenbang->insert($data_post,'table_musrenbang');
			//echo $this->db->last_query();
		} else {
			//update
      $data_post['changed_by'] = $this->session->userdata('id_user');
      $data_post['changed_date'] = $date." ".$time;
			$ret = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');
			echo $this->db->last_query();
		}
		if ($ret === FALSE){
      $this->session->set_userdata('msg_typ','err');
      $this->session->set_userdata('msg', 'Data musrenbang Gagal disimpan');
		} else {
      $this->session->set_userdata('msg_typ','ok');
      $this->session->set_userdata('msg', 'Data musrenbang Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

    redirect('forum_skpd');
		//var_dump($cekbank);
		//print_r ($id_cek);
  }

  function load_data(){
    $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$renstra = $this->m_musrenbang->get_data_table_cam($search, $start, $length, $order["0"]);
		$alldata = $this->m_musrenbang->count_data_table_cam($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;
		foreach ($renstra as $row) {
			$no++;
			$data[] = array(
				'no'                    => $no,
				'kode_kegiatan'         => $row->kode_kegiatan,
				'nama_program_kegiatan' => $row->nama_program_kegiatan,
				'total_jumlah_dana'     => $this->formatRupiah($row->total_jumlah_dana),
				'nama_skpd'             => $row->nama_skpd,
				'id_skpd' 				=> $row->id_skpd
				);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
    }

    function edit_data($id_musrenbang=NULL){
        $this->auth->restrict();

        $data['url_save_data'] = site_url('forum_skpd/save_data');

				$data['isEdit'] = FALSE;
        $data['combo_skpd']         = $this->m_musrenbang->create_lov_skpd('');
				$data['combo_keputusan']    = $this->m_lov->create_lov('table_keputusan','id_keputusan','nama','');
        if (!empty($id_musrenbang)) {
            $data_ = array('id_musrenbang'=>$id_musrenbang);
            $result = $this->m_musrenbang->get_data_with_rincian($id_musrenbang,'table_musrenbang');
						if (empty($result)) {
							$this->session->set_userdata('msg_typ','err');
							$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
							redirect('forum_skpd');
						}

            $data['id_musrenbang']		= $result->id_musrenbang;

		    		$data['jenis_pekerjaan']	= $result->jenis_pekerjaan;
            $data['lokasi']				= $result->lokasi;
		    		$data['volume']				= $result->volume;
		    		$data['satuan']				= $result->satuan;
		    		$data['jumlah_dana']		= $result->jumlah_dana;
						$data['id_asal_usulan']= $result->id_asal_usulan;
						$data['alasan_prioritas']= $result->alasan_prioritas;
						$data['id_kecamatan'] = $result->id_kecamatan;
            $data['isEdit']				= TRUE;
            $data['combo_skpd']         = $this->m_musrenbang->create_lov_skpd($result->id_skpd);
						$data['combo_keputusan']    = $this->m_lov->create_lov('table_keputusan','id_keputusan','nama',$result->id_keputusan);

					}

        //var_dump($data);


    	$this->template->load('template','forum_skpd/forum_skpd_view', $data);
    }

    function delete_data() {
    	if(!$this->auth->restrict_ajax_login()) return;
        $date=date("Y-m-d");
        $time=date("H:i:s");
		//$idu = $this->session->userdata('id_unit');
		//$idsu  = $this->session->userdata('id_subunit');
		//$ta  = $this->m_settings->get_tahun_anggaran();
		//$cdsu= $this->session->userdata('kode_subunit');

		$id_musrenbang = $this->input->post('id_musrenbang');

		//cek apakah musrembang itu ada

		$musrenbang = $this->m_musrenbang->get_data(array('id_musrenbang'=>$id_musrenbang),'table_musrenbang');

		if(empty($musrenbang)) {
			$this->session->set_userdata('msg_typ','err');
    	$this->session->set_userdata('msg', 'Musrembang yang dipilih tidak ada');

			redirect('forum_skpd');
		}

		//hapus musrembangdes
    $data_ = array(
      'flag_delete' => '1',
      'changed_date' => $date." ".$time,
      'changed_by' => $this->session->userdata('id_user')
    );
		$result = $this->m_musrenbang->delete($id_musrenbang,$data_,'table_musrenbang','primary_musrenbang');
		if($result) {
			$response['errno'] = 0;
			$response['message'] = 'Musrembang berhasil dihapus';
		} else {
			$response['errno'] = 1;
			$response['message'] = 'Musrembang gagal dihapus';
		}

		echo json_encode($response);
    }

    function autocomplete_kdurusan(){
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_urusan($req);
    	echo json_encode($result);
    }

    function autocomplete_kdbidang(){
    	$kd_urusan = $this->input->post('kd_urusan');
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_bidang($req, $kd_urusan);
    	echo json_encode($result);
    }

    function autocomplete_kdprog(){
    	$kd_urusan = $this->input->post('kd_urusan');
    	$kd_bidang = $this->input->post('kd_bidang');
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_prog($req, $kd_urusan, $kd_bidang);
    	echo json_encode($result);
    }

    function autocomplete_keg(){
    	$kd_urusan 	= $this->input->post('kd_urusan');
    	$kd_bidang 	= $this->input->post('kd_bidang');
    	$kd_prog 	= $this->input->post('kd_prog');
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_keg($req, $kd_urusan, $kd_bidang, $kd_prog);
    	echo json_encode($result);
    }

    function formatRupiah($rupiah)
    {
        return "Rp".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
    }

		function show_list_musrembangcam(){
        $kode_kegiatan = $this->input->post('kode_kegiatan');
        $id_skpd = $this->input->post('id_skpd');


        $results = $this->m_musrenbang->get_list_musrenbangcam($kode_kegiatan,$id_skpd);
        $data = "";
        foreach ($results as $result) {
          //  $action = '<a href="javascript:void(0)" onclick="edit_forum_skpd('. $result->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Usulan Musrenbang"/>';
            $action = '<a href="javascript:void(0)" onclick="terima_usulan_musrenbangcam('. $result->id_musrenbang .')" class="icon2-accept" title="Terima Usulan Musrenbang"/>';
            $action .= '<a href="javascript:void(0)" onclick="tolak_usulan_musrenbangcam('. $result->id_musrenbang .')" class="icon2-cancel" title="Tolak Usulan Musrenbang"/>';
            $action .= '<a href="javascript:void(0)" onclick="valid_usulan_musrenbangcam('. $result->id_musrenbang .')" class="icon2-report_go" title="Sahkan Usulan Musrenbang"/>';
            $action .= '<a href="javascript:void(0)" onclick="show_gallery('. $result->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>';
            $data .= '<tr>';
            $data .= '<td>';
            $data .= $result->jenis_pekerjaan;
            $data .= '</td><td>';
            $data .= $result->volume;
            $data .= ' ';
            $data .= $result->satuan;
            $data .= '</td><td>';
            $data .= $this->formatRupiah($result->jumlah_dana);
            $data .= '</td><td>';
            $data .= $result->lokasi;
            $data .= '</td><td>';
            $data .= $result->nama_desa;
            $data .= ' - ';
            $data .= $result->nama_kec;
            $data .= '</td><td>';
            $data .= $result->asal_usulan;
            $data .= '</td><td>';
            $data .= $result->status_usulan;
            $data .= '</td><td>';
						$data .= $result->status_keputusan;
						$data .= '</td><td>';
            $data .= $action;
            $data .= '</td>';
            $data .= '</tr>';
        }
        //echo $data;
        echo $data;
    }

		function terima_usulan_musrenbang(){
        $id_musrenbang = $this->input->post('id_musrenbang');
        $data_post = array(
            'id_keputusan' => '2',
						'id_status_usulan' => '4'
            );
        $result = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');

        if($result===FALSE){
            $arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
        }else{
            $arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
        }
        echo json_encode($arr);
    }

		function valid_usulan_musrenbang(){
        $id_musrenbang = $this->input->post('id_musrenbang');
        $data_post = array(
            'id_status_usulan' => '5'
            );
        $result = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');

        if($result===FALSE){
            $arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
        }else{
            $arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
        }
        echo json_encode($arr);
    }

    function load_keterangan(){
        $data['id_musrenbang'] = $this->input->post('id_musrenbang');
        $this->load->view('forum_skpd/keterangan_penolakan_usulan',$data);
    }

	function add_keterangan(){
		$id_musrenbang = $this->input->post('id_musrenbang');
		$keterangan = $this->input->post('keterangan');
		//var_dump($id_musrenbang);
		//var_dump($keterangan);

		$data_post = array(
			'id_keputusan' => '3',
			'alasan_keputusan' => $keterangan,
			'id_status_usulan' => '4'
		);
		$result = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');
		if($result===FALSE){
				$arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
		}else{
				$arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
		}
		echo json_encode($arr);
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

    ## -------------------------------------- ##
    ##             Cetak Forum SKPD           ##
    ## -------------------------------------- ##
    private function cetak_forum_skpd_func($tahun,$id_skpd){
        $data['title_type'] = "FORUM SKPD";
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $header = $this->m_template_cetak->get_value("GAMBAR");     
        $data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
        $skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
        $data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd)."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>No Telp. ".$skpd_detail->telp_skpd."<p>";

        $data1['data_forum'] = $this->m_musrenbang->get_forum_skpd_cetak($id_skpd,$tahun);
        $data['forum_skpd'] = $this->load->view('forum_skpd/cetak/isi_forum', $data1, TRUE);
        return $data;
    }

    function do_cetak_forum_skpd($id_skpd=NULL){
        ini_set('memory_limit', '-1');
        
        $this->auth->restrict();
        if (empty($id_skpd)) {
            $tahun = $this->session->userdata('t_anggaran_aktif');
            $id_skpd = $this->session->userdata('id_skpd');
        }

        $skpd = $this->m_musrenbang->get_one_forum_skpd($id_skpd,TRUE);
        if (!empty($skpd)) {
            $data = $this->cetak_forum_skpd_func($tahun,$id_skpd);
            $html = $this->template->load('template_cetak', 'forum_skpd/cetak/cetak', $data, true);
            $filename='Fourm_SKPD '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s") .'.pdf';
        }else{
            $html = "<center>Data Tidak Tersedia . . .</center>";
            $filename='Forum_SKPD '. date("d-m-Y_H-i-s") .'.pdf';    
        }
        //echo $html;              
        pdf_create($html, $filename, "A4", "Landscape", FALSE);
    }

}
