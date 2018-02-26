<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_skpd_renja extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model('m_rkpd','',TRUE);
        $this->load->model('m_lov','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

    function index()
	{
		$this->auth->restrict();

        $data['url_edit_data'] = site_url('rkpd/forum_skpd_renja/edit_data');
        $data['url_load_data'] = site_url('rkpd/forum_skpd_renja/load_data');
        $data['url_save_data'] = site_url('rkpd/forum_skpd_renja/save_data');
        $data['url_data_list_musrenbangcam'] = site_url('rkpd/forum_skpd_renja/show_list_musrembangcam');
        $data['url_show_form_forum_skpd']  = site_url('rkpd/forum_skpd_renja/show_form_forum_skpd');
        $data['url_terima_usulan_musrenbang'] = site_url('rkpd/forum_skpd_renja/terima_usulan_musrenbang');
        $data['url_tolak_usulan_musrenbang'] = site_url('rkpd/forum_skpd_renja/tolak_usukan_musrenbang');
        $data['url_load_keterangan'] = site_url('rkpd/forum_skpd_renja/load_keterangan');
		$this->template->load('template','rkpd/forum_skpd_renja',$data);
	}

    function save_data(){
        $date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
        //action save cekbank di table t_rkpd
        $id_musrenbang 	      = $this->input->post('id_musrenbang');
        $prioritas   = $this->input->post('prioritas');
        $sumberdana   = $this->input->post('sumberdana');
        $call_from		  = $this->input->post('call_from');


        if(strpos($call_from, 'rkpd/forum_rkpd_renja/edit_data') != FALSE) {
			$call_from = '';
		}
        $data_ = array(
            'id_musrenbang'     => $id_musrenbang,
        );

        $data_musrenbang = $this->m_rkpd->get_data($data_,'table_musrenbang');

        if($data_musrenbang){
            $data_post = array(
                'id_prioritas' => $prioritas,
                'id_sumberdana' => $sumberdana,
                'id_status_usulan' => '2'
            );
            $flag = $this->m_rkpd->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');
            if($flag===FALSE){
                $this->session->set_userdata('msg_typ','err');
                $this->session->set_userdata('msg', 'Proses penyimpanan gagal!');
            }else{
                $this->session->set_userdata('msg_typ','ok');
                $this->session->set_userdata('msg', 'Proses penyimpanan berhasil!');
            }
        }
        else{
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Usulan tidak ada!');
        }

		if(!empty($call_from))
			redirect($call_from);

        redirect('rkpd/forum_skpd_renja');
    }

    function load_data(){

        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$rkpd = $this->m_rkpd->get_data_table_renja($search, $start, $length, $order["0"]);
		$alldata = $this->m_rkpd->get_data_table_renja_count($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;
		foreach ($rkpd as $row) {
			$no++;
			$data[] = array(
					'no'                    => $no,
	        'kode_kegiatan'         => $row->kode_kegiatan,
	        'nama_program_kegiatan' => $row->nama_program_kegiatan,
	        'indikator_kinerja'     => $row->indikator_kinerja,
	        'target'                => $row->target,
	        'nama_skpd'             => $row->nama_skpd,
	        'nominal'               => $this->formatRupiah($row->nominal),
	        'id_skpd'               => $row->id_skpd
			);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
    }

    function edit_data($id_musrenbang=NULL){

        $this->auth->restrict();
        $data['url_save_data'] = site_url('rkpd/forum_skpd_renja/save_data');

				$data['isEdit'] = FALSE;
        if (!empty($id_musrenbang)) {
            $data_ = array('id_musrenbang'=>$id_musrenbang);

            $result = $this->m_rkpd->get_data_musrenbang($id_musrenbang);
						if (empty($result)) {
							$this->session->set_userdata('msg_typ','err');
							$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
							redirect('rkpd/forum_skpd_renja');
						}


            $data['id_musrenbang']		= $result->id_musrenbang;
            //$data['id_renstra']         = $result->id_renstra;
            $data['id_skpd']         = $result->id_skpd;
            //$data['id_bid_koor']         = $result->id_bid_koor;
            $data['tahun'] = $result->tahun;

            $data['urusan'] = $result->kd_urusan;
		    		$data['bidang'] = $result->kd_bidang;
		    		$data['program'] = $result->kd_program;
		    		$data['kegiatan'] = $result->kd_kegiatan;

		    		$data['nm_urusan'] = $result->nm_urusan;
		    		$data['nm_bidang'] = $result->nm_bidang;
		    		$data['nm_program'] = $result->ket_program;
		    		$data['nm_kegiatan'] = $result->ket_kegiatan;

            $data['jenis_pekerjaan'] = $result->jenis_pekerjaan;
            $data['jumlah_dana'] = $this->formatRupiah($result->jumlah_dana);
            $data['lokasi'] = $result->lokasi;

            $data['volume'] = $result->volume;
            $data['satuan'] = $result->satuan;
            $data['nama_desa'] = $result->nama_desa;
            $data['nama_kec'] = $result->nama_kec;
            $data['nama_skpd']         = $result->nama_skpd;
            //$data['nama_bid_koor']         = $result->nama_bid_koor;

           // $data['combo_status']   = $this->m_lov->create_lov('table_status_rkpd','id_status_rkpd','status_rkpd',$result->id_status_verifikasi);

            $data['isEdit']				= TRUE;

            $data['combo_prioritas']    = $this->m_lov->create_lov('table_prioritas','id_prioritas','nama',$result->id_prioritas);
            $data['combo_sumberdana']   = $this->m_lov->create_lov('table_sumberdana','id_sumberdana','nama',$result->id_sumberdana);


			}
        //var_dump($data);

    	$this->template->load('template','rkpd/edit_usulan_rkpd', $data);
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

    function show_list_musrembangcam(){
        $kode_kegiatan = $this->input->post('kode_kegiatan');
        $id_skpd = $this->input->post('id_skpd');
        $results = $this->m_rkpd->get_list_musrenbangcam($kode_kegiatan,$id_skpd);
        $data = "";
        foreach ($results as $result) {
            $action = '<a href="javascript:void(0)" onclick="show_data_musrenbangcam('. $result->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Usulan Musrenbang"/>';
            if($result->id_status_usulan=='2'){
                //munculkan action terima rkpd atau tidak
                $action .= '<a href="javascript:void(0)" onclick="terima_usulan_musrenbangcam('. $result->id_musrenbang .')" class="icon2-add" title="Terima Usulan Musrenbang"/>';
                $action .= '<a href="javascript:void(0)" onclick="tolak_usulan_musrenbangcam('. $result->id_musrenbang .')" class="icon2-delete" title="Tolak Usulan Musrenbang"/>';
            }

            $data .= '<tr>';
            $data .= '<td>';
            $data .= $result->id_prioritas;
            $data .= '</td><td>';
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
            $data .= $result->nama_asal_usulan;
            $data .= '</td><td>';
            $data .= $result->status_rkpd;
            $data .= '</td><td>';
            $data .= $action;
            $data .= '</td>';
            $data .= '</tr>';
        }
        //echo $data;
        echo $data;
    }

    function formatRupiah($rupiah)
    {
        return "Rp".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
    }

    function terima_usulan_musrenbang(){
        $id_musrenbang = $this->input->post('id_musrenbang');
        $data_post = array(
            'id_status_usulan' => '3'
            );
        $result = $this->m_rkpd->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');

        if($result===FALSE){
            $arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
        }else{
            $arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
        }
        echo json_encode($arr);
    }
     function tolak_usulan_musrenbang(){
        $id_musrenbang = $this->input->post('id_musrenbang');
        $data_post = array(
            'id_status_usulan' => '4'
            );
        $result = $this->m_rkpd->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');

        if($result===FALSE){
            $arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
        }else{
            $arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
        }
        echo json_encode($arr);
    }

    function load_keterangan(){
        $data['id_musrenbang'] = $this->input->post('id_musrenbang');
        $this->load->view('rkpd/keterangan_penolakan_usulan',$data);
    }

		function add_keterangan(){
			$id_musrenbang = $this->input->post('id_musrenbang');
			$keterangan = $this->input->post('keterangan');
			//var_dump($id_musrenbang);
			//var_dump($keterangan);

			$data_post = array(
				'id_status_usulan' => '4',
				'keterangan' => $keterangan
			);
			$result = $this->m_rkpd->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');
			if($result===FALSE){
					$arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
			}else{
					$arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
			}
			echo json_encode($arr);
		}

}
