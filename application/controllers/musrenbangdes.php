<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Musrenbangdes extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_musrenbang','m_lov','m_template_cetak','m_desa','m_skpd'));
    //     $this->load->model('m_musrenbang','',TRUE);
				// $this->load->model('m_lov','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

    function index()
	{
		$this->auth->restrict();

        $data['url_add_data'] = site_url('musrenbangdes/edit_data');
        $data['url_load_data'] = site_url('musrenbangdes/load_data');
        $data['url_delete_data'] = site_url('musrenbangdes/delete_data');
        $data['url_edit_data'] = site_url('musrenbangdes/edit_data');
        $data['url_save_data'] = site_url('musrenbangdes/save_data');
        $data['url_show_gallery'] = site_url('musrenbangdes/show_gallery');

		$data['url_summary_biaya'] = site_url('musrenbangdes/get_summary_biaya');
		$this->template->load('template','musrenbang/musrenbangdes',$data);

	}

    function save_data(){
        $date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
		$id_desa      = $this->session->userdata('id_desa');
		$id_kecamatan = $this->session->userdata('id_kecamatan');
        //action save cekbank di table t_musrenbangdes
        $id_musrenbang 	= $this->input->post('id_musrenbang');
        $call_from			= $this->input->post('call_from');
        $data_post = array(
			'tahun'             => $this->session->userdata('t_anggaran_aktif'),
			'jenis_pekerjaan'	=> $this->input->post('jenis_pekerjaan'),
			'volume'			=> $this->input->post('volume'),
			'satuan'			=> $this->input->post('satuan'),
			'jumlah_dana'		=> $this->input->post('jumlah_dana'),
			'lokasi'            => $this->input->post('lokasi'),
			'id_asal_usulan'    => '1',
			'id_desa'			=> $this->session->userdata('id_desa'),
			'id_kecamatan'		=> $this->session->userdata('id_kecamatan'),
			'id_status_usulan' => '1'
			);

		if(strpos($call_from, 'musrenbangdes/edit_data') != FALSE) {
			$call_from = '';
		}

		$cekmusrenbang = $this->m_musrenbang->get_data(array('id_musrenbang'=>$id_musrenbang),'table_musrenbang');

        if(empty($cekmusrenbang)) {
			$cekmusrenbang = new stdClass();
			$id_musrenbang = '';
		}
		//Persiapan folder berdasarkan unit
		$dir_file_upload='file_upload/'.$id_kecamatan;
		if (!file_exists($dir_file_upload)) {
		    mkdir($dir_file_upload, 0766, true);
		}
		//UPLOAD
		$this->load->library('upload');
		$config = array();
		$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size'] = '1024';
		$config['overwrite'] = FALSE;

		$id_userfile 	= $this->input->post("id_userfile");
		$name_file 	= $this->input->post("name_file");
		$ket_file	= $this->input->post("ket_file");
	    $files = $_FILES;
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
	    		$this->m_musrenbang->update_file($id_userfile[$i], $update_var);
	    		continue;
	    	}

	    	$file_name=$id_kecamatan."_".$id_desa."_".date("Ymd_His");

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
				$id_file_arr[] = $this->m_musrenbang->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);
				$name_file_arr[] = $file;
			} else {
				// Error Occured in one of the uploads
				if (empty($id_musrenbang) || (!empty($_FILES['userfile']['name']) && !empty($id_musrenbang))) {
					foreach ($id_file_arr as $value) {
						$this->m_musrenbang->delete_file($value);
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
		if (!empty($cekmusrenbang->file)) {
    		$id_file_arr_old = explode(",", $cekmusrenbang->file);
    		if (!empty($hapus)) {
    			foreach ($hapus as $row) {
					$key = array_search($row, $id_file_arr_old);
					unset($id_file_arr_old[$key]);

			    	$var_hapus = $this->m_musrenbang->get_one_file($row);
			    	echo $this->db->last_query();
			    	unlink($directory.'/'.$var_hapus->file);
			    	$this->m_musrenbang->delete_file($row);
			    }
    		}
		    foreach ($id_file_arr_old as $value) {
		    	$id_file_arr[] = $value;
		    }
	    }

	    if (!empty($id_file_arr)) {
	    	$data_post['file'] = implode(",", $id_file_arr);
	    }

		$ret = TRUE;

		if(empty($id_musrenbang)) {
			//insert
            $data_post['created_by'] = $this->session->userdata('id_user');
            $data_post['created_date'] = $date." ".$time;
			$ret = $this->m_musrenbang->insert($data_post,'table_musrenbang');
			echo $this->db->last_query();
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

        //var_dump($cekmusrenbang);

		if(!empty($call_from))
			redirect($call_from);

        redirect('musrenbangdes');
		//var_dump($cekbank);
		//print_r ($id_cek);
    }

    function load_data(){
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$renstra = $this->m_musrenbang->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_musrenbang->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;
		foreach ($renstra as $row) {
			$no++;
			$data[] = array(
							$no,
              $row->jenis_pekerjaan,
              $row->volume,
              $row->satuan,
              $row->jumlah_dana,
              $row->lokasi,
							'<a href="javascript:void(0)" onclick="edit_musrenbangdes('. $row->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Data Musrenbangdes"/>
							<a href="javascript:void(0)" onclick="delete_musrenbangdes('. $row->id_musrenbang .')" class="icon2-delete" title="Hapus Data Musrenbangdes"/>
                            <a href="javascript:void(0)" onclick="show_gallery('. $row->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
    }

    function edit_data($id_musrenbang=NULL){
		//$this->output->enable_profiler(TRUE);
    $this->auth->restrict();
    $data['url_save_data'] = site_url('musrenbangdes/save_data');

		$data['isEdit'] = FALSE;
    if (!empty($id_musrenbang)) {
        $data_ = array('id_musrenbang'=>$id_musrenbang);
        $result = $this->m_musrenbang->get_data_with_rincian($id_musrenbang,'table_musrenbang');
				if (empty($result)) {
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
					redirect('musrenbangdes');
				}

        $data['id_musrenbang']		= $result->id_musrenbang;
    		$data['urusan'] = $result->kd_urusan;
    		$data['bidang'] = $result->kd_bidang;
    		$data['program'] = $result->kd_program;
    		$data['kegiatan'] = $result->kd_kegiatan;


    		$data['jenis_pekerjaan']	= $result->jenis_pekerjaan;
    		$data['volume']				= $result->volume;
    		$data['satuan']				= $result->satuan;
    		$data['jumlah_dana']		= $result->jumlah_dana;
        $data['lokasi']             = $result->lokasi;
        $data['id_asal_usulan']     = '1';
        $data['isEdit']				= TRUE;
				$mp_filefiles				= $this->m_musrenbang->get_file(explode( ',', $result->file), TRUE);
				$data['mp_jmlfile']			= $mp_filefiles->num_rows();
				$data['mp_filefiles']		= $mp_filefiles->result();
			}
        //var_dump($data);
    	$this->template->load('template','musrenbang/musrenbangdes_view', $data);
    }

    function delete_data(){
        $id = $this->input->post('id');
        $data_post = array('id_musrenbang'=>$id);
        $result = $this->m_musrenbang->hard_delete($data_post,'table_musrenbang');
        if($result){
            $arr = array(
                'success' => 1,
                'result' => $result
            );
        }
        else{
            $arr = array(
                'success' => 0,
                'result' => $result
            );
        }
        echo json_encode($arr);
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
		$fmt = new NumberFormatter( 'de_DE', NumberFormatter::CURRENCY );
		return $fmt->formatCurrency($rupiah,"IDR");
        //return number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
		//return money_format('%.2n', 123.45);
    }

    ## -------------------------------------- ##
	##           Cetak Musrenbangdes          ##
	## -------------------------------------- ##
    private function cetak_desa_func($id_desa,$tahun,$id_skpd){
		$data['musrenbang_type'] = "";
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		//$header = $this->m_template_cetak->get_value("GAMBAR");
		//$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$desa_detail = $this->m_desa->get_one_desa(array('id_desa' => $id_desa));
		$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		//$data['header'] = "<p>". strtoupper($desa_detail->nama_desa)." , ". strtoupper($skpd_detail->nama_skpd)."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$desa_detail->alamat."<BR>Kode Post ".$desa_detail->kodepost_desa."<p>";
		$data['header'] = "MUSRENBANGDES ".strtoupper($desa_detail->nama_desa);
		$data['logo'] = "";
		$data1['musrenbangdes'] = $this->m_musrenbang->get_musrenbangdes_cetak($id_desa,$tahun);
		$data['musrenbang'] = $this->load->view('musrenbang/cetak/isi_musrenbangdes', $data1, TRUE);
		return $data;
	}

	function do_cetak_musrenbangdes($id_desa=NULL){
		ini_set('memory_limit', '-1');

		$this->auth->restrict();
		if (empty($id_desa)) {
			$id_desa = $this->session->userdata('id_desa');
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$id_skpd = $this->session->userdata('id_skpd');
		}

		$desa = $this->m_musrenbang->get_one_musrenbangdes($id_desa,TRUE);
		if (!empty($desa)) {
			$data = $this->cetak_desa_func($id_desa,$tahun,$id_skpd);
			$html = $this->template->load('template_cetak', 'musrenbang/cetak/cetak', $data, true);
			$filename='Musrenbangdes '. $desa->nama_desa ." ". date("d-m-Y_H-i-s") .'.pdf';
		}else{
			$html = "<center>Data Tidak Tersedia . . .</center>";
			$filename='Musrenbangdes '. date("d-m-Y_H-i-s") .'.pdf';
		}
		//echo $html;
	    pdf_create($html, $filename, "A4", "Landscape", FALSE);
	}

	## ----------------------- ##
	## Preview Musrenbangdes   ##
	## ----------------------- ##
	function preview_musrenbangdes(){
		$this->auth->restrict();
		$id_desa = $this->session->userdata('id_desa');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$id_skpd = $this->session->userdata('id_skpd');
		$desa = $this->m_musrenbang->get_one_musrenbangdes($id_desa,TRUE);
		if (!empty($desa)) {
			$data = $this->cetak_desa_func($id_desa,$tahun,$id_skpd);
			$this->template->load('template', 'musrenbang/cetak/preview_cetak', $data);
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'Data musrenbangdes tidak tersedia.');
			redirect('home');
		}
	}

	function get_summary_biaya(){
		$arr = array(
			'total_biaya' => $this->m_musrenbang->get_summary_biaya()
		);

		echo json_encode($arr);
	}

}
