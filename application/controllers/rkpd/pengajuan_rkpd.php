<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_rkpd extends CI_Controller
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
	
        $data['url_add_data'] = site_url('rkpd/pengajuan_rkpd/add_data');
        $data['url_load_data'] = site_url('rkpd/pengajuan_rkpd/load_data');
        $data['url_save_data'] = site_url('rkpd/pengajuan_rkpd/save_data');
        
		$this->template->load('template','rkpd/pengajuan_rkpd',$data);
	}
    
    function save_data(){
        $date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
        //action save cekbank di table t_musrenbang
        $id_musrenbang 	= $this->input->post('id_musrenbang');
        $id_renstra 	= $this->input->post('id_renstra');
        $call_from			= $this->input->post('call_from');
        
        
        if(strpos($call_from, 'rkpd/pengajuan_rkpd/add_data') != FALSE) {
			$call_from = '';
		}
        
        //check data pada t_musrenbang
        //status belum terpakai di rkpd = 0
        
        $data_ = array(
            'id_musrenbang'     => $id_musrenbang,
            'id_status_usulan'  => '0'
        );
        
        $data_musrenbang = $this->m_rkpd->get_data($data_,'table_musrenbang');
        
        if($data_musrenbang){
            //data musrenbang belum diusulkan ke rkpd
            //TODO : Kenapa kok ada nama kegiatan lagi kan udah mengacu renstra?????
            
            //set status menjadi 1
            
            $id_rkpd = TRUE;
            $id_history_rkpd = TRUE;
            
            //start transaction
            $this->db->trans_start();
            //insert data usulan ke t_rkpd
            
            //get data skpd
            $skpd = $this->m_rkpd->get_data(array('id_skpd'=>$data_musrenbang->id_skpd),'table_skpd');
            
            //get data renstra
            $data_musrenbang_detail = $this->m_rkpd->get_data_with_rincian($id_musrenbang,'table_musrenbang');
            
            $data_usulan_rkpd = array(
                'tahun'              => $data_musrenbang->tahun,
                'id_renstra'            => $data_musrenbang->id_renstra,
                'id_skpd'               => $data_musrenbang->id_skpd,
                'id_bid_koor'           => $skpd->id_bidkoor,
                'kd_urusan'             => $data_musrenbang->kd_urusan,
                'kd_bidang'             => $data_musrenbang->kd_bidang,
                'kd_program'            => $data_musrenbang->kd_program,
                'kd_kegiatan'           => $data_musrenbang->kd_kegiatan,
                'nm_urusan'             => $data_musrenbang_detail->nm_urusan,
                'nm_bidang'             => $data_musrenbang_detail->nm_bidang,
                'ket_program'            => $data_musrenbang_detail->ket_program,
                'ket_kegiatan'           => $data_musrenbang_detail->ket_kegiatan,
                'jenis_pekerjaan'       => $data_musrenbang_detail->jenis_pekerjaan,
                'jumlah_dana'           => $data_musrenbang_detail->jumlah_dana,
                'lokasi'                => $data_musrenbang->lokasi,
                'volume'                => $data_musrenbang->volume,
                'id_prioritas'          => $this->input->post('prioritas'),
                'id_sumberdana'         => $this->input->post('sumberdana'),
                'id_status_verifikasi'  => '1' 
            );
            $id_rkpd = $this->m_rkpd->insert($data_usulan_rkpd,'table_rkpd');
            
            //insert status ke t_history_rkpd
            $data_history_rkpd = array(
                'id_rkpd'       => $id_rkpd,
                'keterangan'    => 'Data musrenbang diusulkan menjadi rancangan RKPD',
                'created_by'    => $this->session->userdata('id_user'),
                'created_date'  => $date." ".$time                
            );
            
            $id_history_rkpd = $this->m_rkpd->insert($data_history_rkpd,'table_history_rkpd');
            //update ke t_rkpd
            $data__ = array(
                'id_history_rkpd'   => $id_history_rkpd
            );
            $this->m_rkpd->update($id_rkpd,$data__,'table_rkpd','primary_rkpd');
            //update status di musrenbang
            $this->m_rkpd->update($id_musrenbang,array('id_status_usulan'=>'1'),'table_musrenbang','primary_musrenbang');
            $this->db->trans_complete();
            
            
            //suksess kah?
            
            if($id_rkpd===FALSE){
                $this->session->set_userdata('msg_typ','err');
                $this->session->set_userdata('msg', 'Data Usulan Rancangan RKPD Gagal disimpan');
            }else{  
                $this->session->set_userdata('msg_typ','ok');
                $this->session->set_userdata('msg', 'Data Usulan Rancangan RKPD Berhasil disimpan');
            }
            
        }else{
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data sudah pernah diajukan menjadi rancangan RKPD!');
        }

		if(!empty($call_from))
			redirect($call_from);

        redirect('rkpd/pengajuan_rkpd');
        
		//var_dump($cekbank);
		//print_r ($id_cek);
    }
    
    function load_data(){
        //$order_arr = array('id_musrenbang','kd_urusan','kd_bidang','kd_program','kd_kegiatan','jenis_kegiatan');
        
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$renstra = $this->m_rkpd->get_data_table_musrenbang($search, $start, $length, $order["0"]);		
		$alldata = $this->m_rkpd->count_data_table_musrenbang($search, $start, $length, $order["0"]);		
		
		$data = array();
		$no=0;
		foreach ($renstra as $row) {
			$no++;
			$data[] = array(
							$no, 
							$row->kode_kegiatan,
                            $row->nama_program_kegiatan,
                            $row->jenis_pekerjaan,
                            $row->nama_skpd,
							'<a href="javascript:void(0)" onclick="add_usulan_rkpd('. $row->id_musrenbang .')" class="icon2-page_white_edit" title="Ajukan menjadi usulan RKPD"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
        //echo json_encode($order);
        
    }
    
    function add_data($id_musrenbang=NULL){
        $this->auth->restrict();
        $data['url_save_data'] = site_url('rkpd/pengajuan_rkpd/save_data');

		$data['isEdit'] = FALSE;
        if (!empty($id_musrenbang)) {
            $data_ = array('id_musrenbang'=>$id_musrenbang);
            $result = $this->m_rkpd->get_data_with_rincian($id_musrenbang,'table_musrenbang');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
				redirect('rkpd/pengajuan_rkpd');
			}
			
            $data['id_musrenbang']		= $result->id_musrenbang;
            
    		$data['urusan'] = $result->kd_urusan;
    		$data['bidang'] = $result->kd_bidang;
    		$data['program'] = $result->kd_program;
    		$data['kegiatan'] = $result->kd_kegiatan;
    		
    		$data['nm_urusan'] = $result->nm_urusan;
    		$data['nm_bidang'] = $result->nm_bidang;
    		$data['nm_program'] = $result->ket_program;
    		$data['nm_kegiatan'] = $result->ket_kegiatan;
    
    		$data['jenis_pekerjaan']	= $result->jenis_pekerjaan;
    		$data['volume']				= $result->volume;
    		$data['satuan']				= $result->satuan;
    		$data['jumlah_dana']		= $result->jumlah_dana;
            $data['lokasi']             = $result->lokasi;
            $data['id_renstra']         = $result->id_renstra;
            $data['isEdit']				= TRUE;
            $data['combo_prioritas']    = $this->m_lov->create_lov('table_prioritas','id_prioritas','nama','');
            $data['combo_sumberdana']   = $this->m_lov->create_lov('table_sumberdana','id_sumberdana','nama','');
		}
        
        //var_dump($data);
        

    	$this->template->load('template','rkpd/add_pengajuan_rkpd', $data);
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

}