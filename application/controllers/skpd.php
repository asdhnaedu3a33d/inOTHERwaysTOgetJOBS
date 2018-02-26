<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Skpd extends CI_Controller
{
    var $CI = NULL;
  	public function __construct(){
        $this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_musrenbang','m_lov','m_template_cetak','m_desa','m_skpd','m_kecamatan', 'm_urusan','m_bidang','m_program','m_kegiatan'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
  }

  function index(){
    	$this->auth->restrict();
      $data['url_add_data'] = site_url('skpd/edit_data');
      $data['url_load_data'] = site_url('skpd/load_data');
      $data['url_delete_data'] = site_url('skpd/delete_data');
      $data['url_edit_data'] = site_url('skpd/edit_data');
      $data['url_save_data'] = site_url('skpd/save_data');
      $data['url_show_gallery'] = site_url('skpd/show_gallery');

      $data['url_summary_biaya'] = site_url('skpd/get_summary_biaya');

    	$this->template->load('template','skpd/skpd',$data);
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
          'kd_urusan'			  => $this->input->post('kd_urusan'),
        	'kd_bidang'	 		=> $this->input->post('kd_bidang'),
        	'kd_program'	 	=> $this->input->post('kd_program'),
        	'kd_kegiatan'		=> $this->input->post('kd_kegiatan'),
        	'jenis_pekerjaan'	=> $this->input->post('jenis_pekerjaan'),
        	'volume'			=> $this->input->post('volume'),
        	'lokasi'			=> $this->input->post('lokasi'),
          'satuan'			=> $this->input->post('satuan'),
        	'jumlah_dana'		=> $this->input->post('jumlah_dana'),
        	'id_skpd'			=> $this->input->post('id_skpd'),
          'id_asal_usulan' => $this->input->post('id_asal_usulan')==''? '3' : $this->input->post('id_asal_usulan'),
  				'alasan_keputusan' =>$this->input->post('alasan_keputusan'),
        	'id_keputusan'   => $this->input->post('id_keputusan'),
          'id_status_usulan' => '3'

      );
      echo var_dump($data_post);

  		if(strpos($call_from, 'skpd/edit_data') != FALSE) {
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
    			//echo $this->db->last_query();
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

      redirect('skpd');
  		//var_dump($cekbank);
  		//print_r ($id_cek);
    }

    function load_data(){
        $search = $this->input->post("search");
				$start = $this->input->post("start");
				$length = $this->input->post("length");
				$order = $this->input->post("order");

				$renstra = $this->m_musrenbang->get_data_table_skpd($search, $start, $length, $order["0"]);
				$alldata = $this->m_musrenbang->count_data_table_skpd($search, $start, $length, $order["0"]);

				$data = array();
				$no=0;
				foreach ($renstra as $row) {
  					$no++;
  					$data[] = array(
    						$no,
                $row->kode_kegiatan,
                $row->nama_program_kegiatan,
                $row->jenis_pekerjaan,
                $row->volume_satuan,
                $row->jumlah_dana,
                ($row->nama_desa.' - '.$row->nama_kec),
                $row->status_keputusan,
    						'<a href="javascript:void(0)" onclick="edit_musrenbangcam('. $row->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Usulan"/>
    						<a href="javascript:void(0)" onclick="delete_musrenbangcam('. $row->id_musrenbang .')" class="icon2-delete" title="Hapus Usulan"/>
                <a href="javascript:void(0)" onclick="show_gallery('. $row->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>'
    						);
        }
    		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
    		echo json_encode($json);
    }

    function edit_data($id_musrenbang=NULL){
        $this->auth->restrict();

        $data['url_save_data'] = site_url('skpd/save_data');
        $kd_urusan_edit=NULL;
        $kd_bidang_edit=NULL;
        $kd_program_edit=NULL;
        $kd_kegiatan_edit=NULL;
        $id_skpd_edit=NULL;

				$data['isEdit'] = FALSE;
        $data['combo_skpd']         = $this->m_musrenbang->create_lov_skpd('');
        $data['combo_keputusan']    = $this->m_lov->create_lov('table_keputusan','id_keputusan','nama','');
        if (!empty($id_musrenbang)) {
            $data_ = array('id_musrenbang'=>$id_musrenbang);
            $result = $this->m_musrenbang->get_data_with_rincian($id_musrenbang,'table_musrenbang');
						if (empty($result)) {
  							$this->session->set_userdata('msg_typ','err');
  							$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
  							redirect('musrenbangcam');
						}

            $data['id_musrenbang']		= $result->id_musrenbang;
		    		// $data['urusan'] = $result->kd_urusan;
		    		// $data['bidang'] = $result->kd_bidang;
		    		// $data['program'] = $result->kd_program;
		    		// $data['kegiatan'] = $result->kd_kegiatan;
            $kd_urusan_edit=$result->kd_urusan;
            $kd_bidang_edit=$result->kd_bidang;
            $kd_program_edit=$result->kd_program;
            $kd_kegiatan_edit=$result->kd_kegiatan;

		    		$data['nm_urusan'] = $result->nm_urusan;
		    		$data['nm_bidang'] = $result->nm_bidang;
		    		$data['nm_program'] = $result->ket_program;
		    		$data['nm_kegiatan'] = $result->ket_kegiatan;

		    		$data['jenis_pekerjaan']	= $result->jenis_pekerjaan;
            $data['lokasi']				= $result->lokasi;
		    		$data['volume']				= $result->volume;
		    		$data['satuan']				= $result->satuan;
		    		$data['jumlah_dana']		= $result->jumlah_dana;

            $data['id_asal_usulan']= $result->id_asal_usulan;
            $data['alasan_keputusan']= $result->alasan_keputusan;

            $data['isEdit']				= TRUE;
            //$data['combo_skpd']         = $this->m_musrenbang->create_lov_skpd($result->id_skpd);
            $id_skpd_edit = $result->id_skpd;
						$data['combo_keputusan']    = $this->m_lov->create_lov('table_keputusan','id_keputusan','nama',$result->id_keputusan);
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
        foreach ($this->m_program->get_prog($kd_urusan_edit,$kd_bidang_edit,$kd_program_edit) as $row) {
          $kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
        }

        $id_skpd = array("" => "");
        foreach ($this->m_skpd->get_skpd_chosen() as $row) {
            $id_skpd[$row->id] = $row->id .". ". $row->label;
        }

        $data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Kode Urusan" class="common chosen-select" id="kd_urusan"');
        $data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Kode Bidang" class="common chosen-select" id="kd_bidang"');
        $data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Kode Program " class="common chosen-select" id="kd_program"');
        $data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kode Kegiatan" class="common chosen-select" id="kd_kegiatan"');
        $data['id_skpd'] = form_dropdown('id_skpd', $id_skpd, $id_skpd_edit, 'data-placeholder="Pilih SKPD" class="common chosen-select" id="id_skpd"');

        //var_dump($data);

    	$this->template->load('template','skpd/skpd_view', $data);
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

      			redirect('musrenbangdes');
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

    function formatRupiah($rupiah){
        return "Rp".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
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
    ##         Cetak Rekapitulasi SKPD        ##
    ## -------------------------------------- ##
    private function cetak_rekap_func($tahun,$id_skpd){
        $data['header_type'] = "Rekapitulasi SKPD";
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        //$header = $this->m_template_cetak->get_value("GAMBAR");
        //$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
        //$desa_detail = $this->m_desa->get_one_desa(array('id_desa' => $id_desa));
        $skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
        //$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd)."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>No Telp. ".$skpd_detail->telp_skpd."<p>";
		$data['logo'] = "";
		$data['header'] = "<h4>Rekapitulasi SKPD ".$skpd_detail->nama_skpd."</h4>";

        $id_keputusan1 = 1;
        $id_keputusan2 = 2;
        $id_keputusan3 = 3;
        $data1['rekap_skpd1'] = $this->m_musrenbang->get_rekap_skpd_cetak($id_skpd,$tahun,$id_keputusan1);
        $data1['rekap_skpd2'] = $this->m_musrenbang->get_rekap_skpd_cetak($id_skpd,$tahun,$id_keputusan2);
        $data1['rekap_skpd3'] = $this->m_musrenbang->get_rekap_skpd_cetak($id_skpd,$tahun,$id_keputusan3);
        $data['rekapitulasi1'] = $this->load->view('skpd/cetak/isi_rekap_skpd1', $data1, TRUE);
        $data['rekapitulasi2'] = $this->load->view('skpd/cetak/isi_rekap_skpd2', $data1, TRUE);
        $data['rekapitulasi3'] = $this->load->view('skpd/cetak/isi_rekap_skpd3', $data1, TRUE);
        return $data;
    }

    function do_cetak_rekap_skpd($id_skpd=NULL){
        ini_set('memory_limit', '-1');

        $this->auth->restrict();
        if (empty($id_skpd)) {
            $tahun = $this->session->userdata('t_anggaran_aktif');
            $id_skpd = $this->session->userdata('id_skpd');
        }

        $skpd = $this->m_musrenbang->get_one_rekap_skpd($id_skpd,TRUE);
        if (!empty($skpd)) {
            $data = $this->cetak_rekap_func($tahun,$id_skpd);
            $html = $this->template->load('template_cetak', 'skpd/cetak/cetak', $data, true);
            $filename='Rekapitulasi-SKPD '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s") .'.pdf';
        }else{
            $html = "<center>Data Tidak Tersedia . . .</center>";
            $filename='Rekapitulasi-SKPD '. date("d-m-Y_H-i-s") .'.pdf';
        }
        //echo $html;
        pdf_create($html, $filename, "A4", "Landscape", FALSE);
    }

    ## --------------------- ##
    ## Preview Musrenbangcam ##
    ## --------------------- ##
    function preview_rekap_skpd(){
        $this->auth->restrict();
        $tahun = $this->session->userdata('t_anggaran_aktif');
        $id_skpd = $this->session->userdata('id_skpd');

        $skpd = $this->m_musrenbang->get_one_musrenbangcam($id_skpd,TRUE);
        if (!empty($skpd)) {
            $data = $this->cetak_rekap_func($tahun,$id_skpd);
            $this->template->load('template', 'skpd/cetak/preview_cetak', $data);
        }else{
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data rekapitulasi SKPD tidak tersedia.');
            redirect('home');
        }
    }

    function get_summary_biaya(){
      $arr = array(
        'total_biaya' => $this->m_musrenbang->get_summary_biaya_skpd()
      );

      echo json_encode($arr);
    }
}
